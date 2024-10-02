<?php

namespace App\Jobs;

use App\Mail\OrderSigned;
use App\Services\SAPService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class GeneratePDF implements ShouldQueue
{
    use Queueable;

    private int $docEntry;

    /**
     * Create a new job instance.
     */
    public function __construct($docEntry)
    {
        $this->docEntry = $docEntry;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $order = (new SAPService)->getOdataClient()
            ->from("Orders")
            ->where('DocEntry', $this->docEntry)
            ->first();

        $bp = (new SAPService)->getOdataClient()
            ->from("BusinessPartners")
            ->find($order->CardCode);

        $salesPerson = (new SAPService)->getOdataClient()
            ->from("SalesPersons")
            ->find($order->SalesPersonCode);

        $paymentTerm = (new SAPService)->getOdataClient()
            ->from("PaymentTermsTypes")
            ->where('GroupNumber', (int) $bp->PayTermsGrpCode)
            ->first();

        $pdf = Pdf::loadView('pdf.sales-order', [
            'order' => $order,
            'bp' => $bp,
            'salesPerson' => $salesPerson,
            'paymentTerm' => $paymentTerm,
            'logo' => public_path('pastrymart-logo.png'),
            'accreditation' => public_path('accreditation.jpg'),
            'esign' => public_path('/storage/signatures/PMSO_' . $order->DocNum . '.png')
        ]);

        $pdf->save(public_path('storage/sales-orders/' . 'PMSO_' . $order->DocNum . '.pdf'));

        //Email the BP
        if ($bp->EmailAddress != null)
        {
            $bp = $bp->EmailAddress;
            Mail::to($bp)->queue(new OrderSigned($order));
        }
    }
}
