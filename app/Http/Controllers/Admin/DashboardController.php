<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GroupSetting;
use App\Models\UserSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'failedJobsCount' => DB::table('failed_jobs')->count(),
            'queuedJobsCount' => DB::table('jobs')->count(),
            'groupSettingsCount' => GroupSetting::query()->count(),
            'userSettingsCount' => UserSetting::query()->count(),
        ]);
    }
}
