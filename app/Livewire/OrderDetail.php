<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Jobs\GeneratePDF;
use Illuminate\Support\Str;
use App\Services\SAPService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class OrderDetail extends Component
{
    public int $docEntry;

    public string $docNum = '';

    public string $cardCode = '';

    public string $customerName = '';

    public string $customerReference = '';

    public string $address = '';

    public array $documentLines = [];

    public string $vatSum = '';

    public string $docTotal = '';

    public string $currency = '';

    public bool $acknowledgement = false;

    public string $name = '';

    public string $email = '';

    public string $dateTime = '';

    public string $signature = '';

    public bool $signStatus = false;

    public bool $showPrices = true;

    public string $remarks = '';

    public string $paymentTerm = '';

    public function mount($orderId)
    {
        $order = (new SAPService)->getOdataClient()
            ->from("Orders")
            ->where('DocEntry', (int) $orderId)
            ->where(config('udf.deliver_by'), auth()->user()->sap_user_code)
            ->order('DocEntry', 'desc')
            ->first();

        if (!$order) {
            abort(404);
        }
        // dd($order);
        $bp = (new SAPService)->getOdataClient()
        ->from("BusinessPartners")
        ->find($order->CardCode);

        $paymentTerm = (new SAPService)->getOdataClient()
            ->from("PaymentTermsTypes")
            ->where('GroupNumber', (int) $bp->PayTermsGrpCode)
            ->first();

        $this->docEntry = $order->DocEntry;
        $this->docNum = $order->DocNum;
        $this->cardCode = $order->CardCode;
        $this->customerName = $order->CardName;
        $this->customerReference = $order->NumAtCard ?? '';

        $address = '';
        $shipToAddress = $order->AddressExtension;
        $shipToBlock = $shipToAddress['ShipToBlock'] ?? '';
        $shipToStreet = $shipToAddress['ShipToStreet'] ?? '';
        $shipToPostal = $shipToAddress['ShipToZipCode'] ?? '';
        $address .= $shipToBlock . "\n" . $shipToStreet . "\n" . 'Singapore-'. $shipToPostal;
        $this->address = $address;
        $this->vatSum = $order->VatSum;
        $this->docTotal = $order->DocTotal;
        $this->currency = $order->DocCurrency;
        $this->signStatus = ($order->{config('udf.signature_status')} === 'SIGNED') ? true : false;
        $this->name = $order->{config('udf.signed_name')} ?? '';
        $this->dateTime = $order->{config('udf.signed_date')} ?? '';
        $this->showPrices = ($order->{config('udf.show_prices')} === 'Y') ? true : false;
        $this->paymentTerm = $paymentTerm->PaymentTermsGroupName ?? '';
        $this->remarks = $order->{config('udf.order_remarks')} ?? '';
        foreach ($order->DocumentLines as $documentLine) {
            $this->documentLines[] = [
                'itemNumber' => $documentLine['ItemCode'],
                'itemDescription' => $documentLine['ItemDescription'],
                'qty' => $documentLine['Quantity'],
                'uomCode' => $documentLine['UoMCode'],
                'price' => $documentLine['Price']
            ];
        }
    }

    protected $rules = [
        'acknowledgement' => 'accepted',
        'name' => 'required|string|max:50',
        'signature' => 'required|string'
    ];

    public function store()
    {
        $this->validate();

        try {
            $fileName = "PMSO_{$this->docNum}.png";
        
            Storage::disk('public')->put("signatures/{$fileName}", base64_decode(Str::of($this->signature)->after(',')));
    
            (new SAPService)->getOdataClient()->from("Orders")
                ->whereKey((int) $this->docEntry)
                ->patch([
                    config('udf.signed_name') => $this->name,
                    config('udf.signed_date') => Carbon::now()->format('Y-m-d h:i:s'),
                    config('udf.signed_image') => asset('storage/signatures/'. $fileName),
                    config('udf.signature_status') => "SIGNED",
                    config('udf.order_remarks') => $this->remarks,
                ]);
    
            //generate the pdf and send to the customer
            GeneratePDF::dispatch($this->docEntry);
    
            return redirect()->route('order.detail', ['orderId' => $this->docEntry]);
        } catch (\Exception $e) {
            Log::error('Error updating SAP B1: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.order-detail');
    }
}
