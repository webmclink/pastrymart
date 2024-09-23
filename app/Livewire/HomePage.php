<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\App;
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

