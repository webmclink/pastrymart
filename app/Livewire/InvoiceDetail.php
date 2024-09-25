<?php

namespace App\Livewire;

use App\Services\SAPService;
use Livewire\Component;

class InvoiceDetail extends Component
{
    public int $docEntry;

    public string $docNum = '';

    public string $customerName = '';

    public string $address = '';

    public array $documentLines = [];

    public string $vatSum = '';

    public string $docTotal = '';

    public string $currency = '';

    public bool $acknowledgement = false;

    public string $name = '';

    public string $email = '';

    public string $dateTime = '';

    public $sign;

    public bool $signStatus = false;

    public function mount($invoiceId)
    {
        $invoice = (new SAPService)->getOdataClient()
            ->from("Invoices")
            ->where('DocEntry', (int) $invoiceId)
            ->where(config('udf.deliver_by'), auth()->user()->sap_user_code)
            ->first();
        
        if (!$invoice)
        {
            abort(404);
        }
        // dd($invoice);
        $this->docEntry = $invoice->DocEntry;
        $this->docNum = $invoice->DocNum;
        $this->customerName = $invoice->CardName;
        $this->address = $invoice->Address;
        $this->vatSum = $invoice->VatSum;
        $this->docTotal = $invoice->DocTotal;
        $this->currency = $invoice->DocCurrency;
        $this->signStatus = ($invoice->{config('udf.signature_status')} === 'SIGNED') ? true : false;
        foreach ($invoice->DocumentLines as $documentLine) {
            $this->documentLines[] = [
                'itemNumber' => $documentLine['ItemCode'],
                'itemDescription' => $documentLine['ItemDescription'],
                'qty' => $documentLine['Quantity'],
                'price' => $documentLine['Price']
            ];
        }
    }

    protected $rules = [
        'acknowledgement' => 'accepted',
        'name' => 'required|string|max:50'
    ];

    public function store()
    {
        $this->validate();

        dd('test');
    }

    public function render()
    {
        return view('livewire.invoice-detail');
    }
}
