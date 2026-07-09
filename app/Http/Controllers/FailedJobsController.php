<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class FailedJobsController extends Controller
{
    public function index(): View
    {
        $failedJobs = DB::table('failed_jobs')
            ->orderByDesc('failed_at')
            ->get(['uuid', 'connection', 'queue', 'exception', 'failed_at'])
            ->map(function (object $job): object {
                $job->exception_summary = strtok($job->exception, "\n");

                return $job;
            });

        return view('failed-jobs.index', ['failedJobs' => $failedJobs]);
    }

    public function retry(string $uuid): RedirectResponse
    {
        abort_unless(DB::table('failed_jobs')->where('uuid', $uuid)->exists(), 404);

        Artisan::call('queue:retry', ['id' => [$uuid]]);

        return redirect()
            ->route('failed-jobs.index')
            ->with('status', 'Job pushed back onto the queue for another attempt.');
    }

    public function flush(): RedirectResponse
    {
        Artisan::call('queue:flush');

        return redirect()
            ->route('failed-jobs.index')
            ->with('status', 'All failed jobs have been flushed.');
    }
}
