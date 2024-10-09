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
            ->select('DocEntry', 'DocNum', 'CardName', config('udf.signature_status'), 'AddressExtension', config('udf.list_remarks'))
            ->from('Orders')
            ->where(config('udf.deliver_by'), auth()->user()->sap_user_code)
            ->where(config('udf.send_for_signature'), 'Y')
            ->order('DocEntry', 'desc')
            ->order(config('udf.signature_status'), 'asc')
            ->get();
        // dd($orders);
        foreach ($orders as $order) {
            $address = '';
            $shipToAddress = $order->AddressExtension;
            $shipToBlock = $shipToAddress['ShipToBlock'] ?? '';
            $shipToStreet = $shipToAddress['ShipToStreet'] ?? '';
            $shipToPostal = $shipToAddress['ShipToZipCode'] ?? '';
            $address .= $shipToBlock . "\n" . $shipToStreet . "\n" . 'Singapore-'. $shipToPostal;
            $this->orders[] = [
                'docEntry' => $order->DocEntry,
                'docNum' => $order->DocNum,
                'cardName' => $order->CardName,
                'signatureStatus' => $order->{config('udf.signature_status')},
                'shipTo' => $address,
                'listRemarks' => $order->{config('udf.list_remarks')},
            ];
        }
    }

    public function render()
    {
        return view('livewire.list-orders');
    }
}
