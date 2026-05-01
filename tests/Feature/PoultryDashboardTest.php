<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows the landing page for guests', function (): void {
    $this->get('/')->assertOk()->assertSee('Smart Poultry Farm');
});

it('shows the dashboard for authenticated users', function (): void {
    $user = User::factory()->create(['role' => 'admin']);

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertOk()
        ->assertSee('Quick Access');
});