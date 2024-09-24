<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\SAPService;

class ListInvoices extends Component
{
    public array $invoices = [];

    public function mount()
    {
        $invoices = (new SAPService)->getOdataClient()
            ->from('Invoices')
            ->where(config('udf.deliver_by'), auth()->user()->sap_user_code)
            ->where(config('udf.send_for_signature'), 'Y')
            ->order('DocEntry', 'desc')
            ->get();
        
        foreach ($invoices as $invoice) {
            $this->invoices[] = [
                'docEntry' => $invoice->DocEntry,
                'docNum' => $invoice->DocNum,
                'cardName' => $invoice->CardName,
                'signatureStatus' => $invoice->{config('udf.signature_status')},
                'shipTo' => $invoice->Address
            ];
        }
    }

    public function render()
    {
        return view('livewire.list-invoices');
    }
}
