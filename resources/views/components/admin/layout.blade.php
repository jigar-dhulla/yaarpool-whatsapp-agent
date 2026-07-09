@props(['title'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="noindex, nofollow">

        <title>{{ $title }} — Yaarpool</title>

        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-cream text-ink antialiased">
        <div class="mx-auto max-w-5xl px-6 py-10">
            <nav class="flex flex-wrap items-center gap-4 rounded-3xl border border-ink/10 bg-white px-5 py-3.5 shadow-card">
                <a href="{{ url('/') }}" class="flex items-center gap-2.5 font-display font-extrabold">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl border border-ink/10 bg-saffron text-ink shadow-card">
                        <svg class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="currentColor"><path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/></svg>
                    </span>
                    <span class="text-lg tracking-tight">Yaarpool</span>
                </a>

                <div class="flex items-center gap-1">
                    <a href="{{ route('admin.dashboard') }}" class="rounded-full px-4 py-2 text-sm font-bold transition {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-500 text-white' : 'opacity-70 hover:bg-ink/5 hover:opacity-100' }}">Dashboard</a>
                    <a href="{{ route('failed-jobs.index') }}" class="rounded-full px-4 py-2 text-sm font-bold transition {{ request()->routeIs('failed-jobs.*') ? 'bg-emerald-500 text-white' : 'opacity-70 hover:bg-ink/5 hover:opacity-100' }}">Failed jobs</a>
                    <a href="{{ route('group-settings.index') }}" class="rounded-full px-4 py-2 text-sm font-bold transition {{ request()->routeIs('group-settings.*') ? 'bg-emerald-500 text-white' : 'opacity-70 hover:bg-ink/5 hover:opacity-100' }}">Group settings</a>
                </div>

                <div class="ml-auto flex items-center gap-4">
                    <p class="text-sm font-medium opacity-70">{{ auth()->user()->name }}</p>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded-full border border-ink/10 bg-white px-4 py-2 text-sm font-bold shadow-card transition hover:-translate-y-0.5 hover:shadow-card-lg">
                            Log out
                        </button>
                    </form>
                </div>
            </nav>

            @if (session('status'))
                <div class="mt-6 rounded-2xl border border-emerald-600/20 bg-emerald-100 px-5 py-3 text-sm font-medium text-emerald-800">
                    {{ session('status') }}
                </div>
            @endif

            <main class="mt-8">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
