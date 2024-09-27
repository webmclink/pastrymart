<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Str;
use App\Services\SAPService;
use Illuminate\Support\Facades\Storage;

class OrderDetail extends Component
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

    public $signature = null;

    public bool $signStatus = false;

    public function mount($orderId)
    {
        $cacheKey = 'order_' . $orderId . '_' . auth()->user()->sap_user_code;
        $order = cache()->remember($cacheKey, 1, function () use ($orderId) {
            return (new SAPService)->getOdataClient()
                ->from("Orders")
                ->where('DocEntry', (int) $orderId)
                ->where(config('udf.deliver_by'), auth()->user()->sap_user_code)
                ->first();
        });

        if (!$order) {
            abort(404);
        }

        $this->docEntry = $order->DocEntry;
        $this->docNum = $order->DocNum;
        $this->customerName = $order->CardName;
        $this->address = $order->Address;
        $this->vatSum = $order->VatSum;
        $this->docTotal = $order->DocTotal;
        $this->currency = $order->DocCurrency;
        $this->signStatus = ($order->{config('udf.signature_status')} === 'SIGNED') ? true : false;
        $this->name = $order->{config('udf.signed_name')} ?? '';
        $this->dateTime = $order->{config('udf.signed_date')} ?? '';
        foreach ($order->DocumentLines as $documentLine) {
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
        'name' => 'required|string|max:50',
        'signature' => 'required'
    ];

    public function store()
    {
        $this->validate();
        
        $fileName = "PMSO_{$this->docNum}.png";
        
        Storage::disk('public')->put("signatures/{$fileName}", base64_decode(Str::of($this->signature)->after(',')));

        (new SAPService)->getOdataClient()->from("Orders")
            ->whereKey((int) $this->docEntry)
            ->patch([
                config('udf.signed_name') => $this->name,
                config('udf.signed_date') => Carbon::now()->format('Y-m-d h:i:s'),
                config('udf.signed_image') => asset('storage/signatures/'. $fileName),
                config('udf.signature_status') => "SIGNED",
            ]);

        return redirect()->route('order.detail', ['orderId' => $this->docEntry]);
    }

    public function render()
    {
        return view('livewire.order-detail');
    }
}
