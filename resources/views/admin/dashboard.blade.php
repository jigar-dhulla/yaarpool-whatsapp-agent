<x-admin.layout title="Dashboard">
    <h1 class="font-display text-3xl font-extrabold tracking-tight">Dashboard</h1>

    <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
        <a href="{{ route('failed-jobs.index') }}" class="block rounded-3xl border border-ink/10 bg-white p-6 shadow-card transition hover:-translate-y-0.5 hover:shadow-card-lg">
            <p class="text-xs font-bold uppercase tracking-wider opacity-60">Failed jobs</p>
            <p class="mt-2 font-display text-4xl font-extrabold {{ $failedJobsCount > 0 ? 'text-rose-600' : 'text-emerald-600' }}">{{ $failedJobsCount }}</p>
            <p class="mt-2 text-sm opacity-70">
                {{ $failedJobsCount === 0 ? 'Queue is healthy — nothing waiting for a retry.' : 'Review and retry from the failed jobs page.' }}
            </p>
        </a>

        <div class="rounded-3xl border border-ink/10 bg-white p-6 shadow-card">
            <p class="text-xs font-bold uppercase tracking-wider opacity-60">Queued jobs</p>
            <p class="mt-2 font-display text-4xl font-extrabold">{{ $queuedJobsCount }}</p>
            <p class="mt-2 text-sm opacity-70">Jobs waiting to be processed.</p>
        </div>

        <a href="{{ route('group-settings.index') }}" class="block rounded-3xl border border-ink/10 bg-white p-6 shadow-card transition hover:-translate-y-0.5 hover:shadow-card-lg">
            <p class="text-xs font-bold uppercase tracking-wider opacity-60">Group settings</p>
            <p class="mt-2 font-display text-4xl font-extrabold">{{ $groupSettingsCount }}</p>
            <p class="mt-2 text-sm opacity-70">
                {{ $groupSettingsCount === 0 ? 'No chats have default locations yet — add one from the group settings page.' : Str::ucfirst(Str::plural('chat', $groupSettingsCount)).' with default ride locations configured.' }}
            </p>
        </a>

        <a href="{{ route('user-settings.index') }}" class="block rounded-3xl border border-ink/10 bg-white p-6 shadow-card transition hover:-translate-y-0.5 hover:shadow-card-lg">
            <p class="text-xs font-bold uppercase tracking-wider opacity-60">User settings</p>
            <p class="mt-2 font-display text-4xl font-extrabold">{{ $userSettingsCount }}</p>
            <p class="mt-2 text-sm opacity-70">
                {{ $userSettingsCount === 0 ? 'No users have personal defaults yet — they can save them over WhatsApp.' : Str::ucfirst(Str::plural('user', $userSettingsCount)).' with personal ride defaults configured.' }}
            </p>
        </a>
    </div>
</x-admin.layout>
