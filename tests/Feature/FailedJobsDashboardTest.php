<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

function insertFailedJob(): string
{
    $uuid = (string) Str::uuid();

    DB::table('failed_jobs')->insert([
        'uuid' => $uuid,
        'connection' => 'database',
        'queue' => 'default',
        'payload' => json_encode([
            'uuid' => $uuid,
            'displayName' => 'App\Jobs\SendWhatsAppReply',
            'job' => 'Illuminate\Queue\CallQueuedHandler@call',
            'maxTries' => null,
            'attempts' => 1,
        ]),
        'exception' => "RuntimeException: WhatsApp API timed out\n#0 /app/vendor/laravel/framework/src/Queue/Worker.php(439)",
        'failed_at' => now(),
    ]);

    return $uuid;
}

it('lists failed jobs on the dashboard without exposing the payload', function () {
    $uuid = insertFailedJob();

    $this->get(route('failed-jobs.index'))
        ->assertOk()
        ->assertSee($uuid)
        ->assertSee('RuntimeException: WhatsApp API timed out')
        ->assertDontSee('SendWhatsAppReply')
        ->assertDontSee('CallQueuedHandler');
});

it('shows an empty state when there are no failed jobs', function () {
    $this->get(route('failed-jobs.index'))
        ->assertOk()
        ->assertSee('No failed jobs');
});

it('retries a failed job from the dashboard', function () {
    $uuid = insertFailedJob();

    $this->post(route('failed-jobs.retry', $uuid))
        ->assertRedirect(route('failed-jobs.index'))
        ->assertSessionHas('status');

    expect(DB::table('failed_jobs')->count())->toBe(0)
        ->and(DB::table('jobs')->where('queue', 'default')->count())->toBe(1);
});

it('flushes all failed jobs from the dashboard', function () {
    insertFailedJob();
    insertFailedJob();

    $this->post(route('failed-jobs.flush'))
        ->assertRedirect(route('failed-jobs.index'))
        ->assertSessionHas('status');

    expect(DB::table('failed_jobs')->count())->toBe(0)
        ->and(DB::table('jobs')->count())->toBe(0);
});

it('returns 404 when retrying an unknown job', function () {
    $this->post(route('failed-jobs.retry', (string) Str::uuid()))
        ->assertNotFound();
});
