<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\SAPService;

class ListOrders extends Component
{
    public array $orders = [];

    public function mount()
    {
        $orders = (new SAPService)->getOdataClient()
            ->from('Orders')
            ->where(config('udf.deliver_by'), auth()->user()->sap_user_code)
            ->where(config('udf.send_for_signature'), 'Y')
            ->order('DocEntry', 'desc')
            ->get();
        
        foreach ($orders as $order) {
            $this->orders[] = [
                'docEntry' => $order->DocEntry,
                'docNum' => $order->DocNum,
                'cardName' => $order->CardName,
                'signatureStatus' => $order->{config('udf.signature_status')},
                'shipTo' => $order->Address
            ];
        }
    }

    public function render()
    {
        return view('livewire.list-orders');
    }
}
