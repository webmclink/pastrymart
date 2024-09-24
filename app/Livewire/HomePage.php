<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Services\SAPService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class HomePage extends Component
{
    public string $locale = 'en';

    public string $username = '';
    public string $password = '';
    public bool $remember = true;

    public function mount()
    {
        $this->locale = session('locale', app()->getLocale());
        $this->updateLocale();
    }

    protected $rules = [
        'username' => 'required|string|max:50',
        'password' => 'required|string',
        'remember' => 'boolean'
    ];

    public function auth()
    {
        $this->validate();

        $loginResponse = Http::withOptions([
            'verify' => false,
        ])->post(config('sap.path') . '/Login', [
            'CompanyDB' => config('sap.db'),
            'UserName' => $this->username,
            'Password' => $this->password,
        ]);

        if ($loginResponse->status() !== 200) {
            $response = $loginResponse->object();
            return $this->addError('username', $response->error->message->value);
        }

        $userCode = (new SAPService())->getOdataClient()
            ->select('InternalKey', 'UserName')
            ->from('Users')
            ->where('UserCode', $this->username)
            ->first();

        $user = User::updateOrCreate(
            ['username' => $this->username],
            [
                'sap_user_code' => $userCode->InternalKey,
                'name' => $userCode->UserName,
                'email' => str_replace(' ', '', $this->username) . '@pastrymart.com.sg',
                'password' => Hash::make($this->password),
            ]
        );

        Auth::loginUsingId($user->id, $this->remember);

        return redirect()->intended('invoices');
    }

    public function setLocale()
    {
        $this->updateLocale();
        Session::put('locale', $this->locale);
    }

    private function updateLocale()
    {
        App::setLocale($this->locale);
    }

    public function render()
    {
        return view('livewire.home-page');
    }
}

