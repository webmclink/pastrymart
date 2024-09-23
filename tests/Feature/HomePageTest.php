<?php

use App\Livewire\HomePage;
use Livewire\Livewire;
use Illuminate\Support\Facades\Session;

it('sets the locale on mount', function () {
    // Arrange: Set a specific locale in the session
    Session::put('locale', 'fr');

    // Act: Create the Livewire component and mount it
    $component = Livewire::test(HomePage::class);

    // Assert: Check if the locale is set correctly
    $component->assertSet('locale', 'fr');
});

it('updates the locale when setLocale is called', function () {
    // Arrange: Create the Livewire component
    $component = Livewire::test(HomePage::class);

    // Act: Call the setLocale method with a new locale
    $newLocale = 'es';
    $component->set('locale', $newLocale);
    $component->call('setLocale');

    // Assert: Check if the locale is updated correctly
    $this->assertEquals($newLocale, app()->getLocale());
    $this->assertEquals($newLocale, Session::get('locale'));
});

it('shows the homepage and returns 200', function () {
    // Act: Visit the homepage
    $response = $this->get('/'); // Adjust this if your homepage route is different

    // Assert: Check that the response status is 200
    $response->assertStatus(200);
});
