<x-admin.layout title="Failed jobs">
    <header class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
        <h1 class="font-display text-3xl font-extrabold tracking-tight">Failed jobs</h1>
        <div class="flex flex-wrap items-center gap-4">
            <p class="text-sm opacity-70">{{ $failedJobs->count() }} {{ Str::plural('job', $failedJobs->count()) }} waiting for a retry</p>
            @if ($failedJobs->isNotEmpty())
                <form method="POST" action="{{ route('failed-jobs.flush') }}" onsubmit="return confirm('Flush all failed jobs? This cannot be undone.');">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 rounded-full border border-rose-200 bg-white px-5 py-2.5 text-sm font-bold text-rose-600 shadow-card transition hover:-translate-y-0.5 hover:bg-rose-50 hover:shadow-card-lg">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                        Flush all
                    </button>
                </form>
            @endif
        </div>
    </header>

    @if ($failedJobs->isEmpty())
        <div class="mt-10 rounded-3xl border border-ink/10 bg-white p-12 text-center shadow-card">
            <p class="font-display text-xl font-bold">No failed jobs 🎉</p>
            <p class="mt-2 text-sm opacity-70">Everything on the queue has been processed successfully.</p>
        </div>
    @else
        <div class="mt-8 space-y-4">
            @foreach ($failedJobs as $job)
                <div class="rounded-2xl border border-ink/10 bg-white p-5 shadow-card">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                        <div class="min-w-0">
                            <h2 class="font-mono text-lg font-bold break-words">{{ $job->uuid }}</h2>
                            <p class="mt-1 text-xs font-medium opacity-70">
                                {{ $job->connection }} / {{ $job->queue }}
                                &middot; failed {{ \Illuminate\Support\Carbon::parse($job->failed_at)->diffForHumans() }}
                            </p>
                            <p class="mt-3 rounded-xl bg-rose-50 px-3 py-2 font-mono text-xs text-rose-700 break-words">{{ $job->exception_summary }}</p>
                        </div>
                        <form method="POST" action="{{ route('failed-jobs.retry', $job->uuid) }}" class="shrink-0">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-emerald-500 px-5 py-2.5 text-sm font-bold text-white shadow-card transition hover:-translate-y-0.5 hover:bg-emerald-600 hover:shadow-card-lg">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/><path d="M21 21v-5h-5"/></svg>
                                Retry
                            </button>
                        </form>
                    </div>
                    <details class="mt-4">
                        <summary class="cursor-pointer text-xs font-bold uppercase tracking-wider opacity-60 transition hover:opacity-100">Full stack trace</summary>
                        <pre class="mt-3 max-h-96 overflow-auto rounded-xl bg-ink p-4 text-xs leading-relaxed text-cream">{{ $job->exception }}</pre>
                    </details>
                </div>
            @endforeach
        </div>
    @endif
</x-admin.layout>
