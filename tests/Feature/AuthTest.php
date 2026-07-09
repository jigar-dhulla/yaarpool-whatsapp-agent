<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

it('renders the login screen', function () {
    $this->get(route('login'))
        ->assertOk()
        ->assertSee('Log in');
});

it('does not expose a registration page', function () {
    $this->get('/register')->assertNotFound();
    $this->post('/register')->assertNotFound();
});

it('registers a user through the console command', function () {
    $this->artisan('user:register', ['name' => 'Jigar', 'email' => 'Jigar@Example.com'])
        ->expectsQuestion('Password', 'super-secret-password')
        ->assertSuccessful();

    $user = User::sole();

    expect($user->email)->toBe('jigar@example.com')
        ->and(Hash::check('super-secret-password', $user->password))->toBeTrue();
});

it('refuses to register a duplicate email through the console command', function () {
    User::factory()->create(['email' => 'jigar@example.com']);

    $this->artisan('user:register', ['name' => 'Jigar', 'email' => 'jigar@example.com'])
        ->expectsQuestion('Password', 'super-secret-password')
        ->assertFailed();

    expect(User::count())->toBe(1);
});

it('logs in with valid credentials', function () {
    $user = User::factory()->create();

    $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect(route('failed-jobs.index'));

    $this->assertAuthenticatedAs($user);
});

it('rejects invalid credentials', function () {
    $user = User::factory()->create();

    $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'wrong-password',
    ])->assertSessionHasErrors('email');

    $this->assertGuest();
});

it('logs the user out', function () {
    $this->actingAs(User::factory()->create())
        ->post(route('logout'))
        ->assertRedirect('/');

    $this->assertGuest();
});

it('redirects authenticated users away from the login screen', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('login'))
        ->assertRedirect('/failed-jobs');
});

it('redirects guests to the login screen from the failed jobs dashboard', function () {
    $this->get(route('failed-jobs.index'))->assertRedirect(route('login'));
    $this->post(route('failed-jobs.flush'))->assertRedirect(route('login'));
    $this->post(route('failed-jobs.retry', 'some-uuid'))->assertRedirect(route('login'));
});
