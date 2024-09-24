<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\SAPService;

class ListInvoices extends Component
{
    public function mount()
    {
        // $invoices = (new SAPService)->getOdataClient()
        //     ->from('Invoices')
        //     ->where(config('udf.deliver_by'), auth()->user()->sap_user_code)
        //     ->where(config('udf.send_for_signature'), 'Y')
        //     ->order('DocEntry', 'desc')
        //     ->get();

        // dd($invoices);
    }

    public function render()
    {
        return view('livewire.list-invoices');
    }
}
