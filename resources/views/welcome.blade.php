<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Yaarpool — Carpool with your yaars, right inside WhatsApp</title>
        <meta name="description" content="Yaarpool is the carpool that lives in your group chat. Post a lift or ask for one in plain language on WhatsApp — no app to install.">

        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-cream text-ink antialiased">

        {{-- Background flourish --}}
        <div aria-hidden="true" class="pointer-events-none fixed inset-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 h-96 w-96 rounded-full bg-amber-300/30 blur-3xl"></div>
            <div class="absolute top-1/3 -left-40 h-96 w-96 rounded-full bg-emerald-300/30 blur-3xl"></div>
        </div>

        <div class="relative">
            {{-- Nav --}}
            <header class="mx-auto flex max-w-6xl items-center justify-between px-6 py-5">
                <a href="#" class="flex items-center gap-2.5 font-display font-extrabold">
                    <span class="flex h-10 w-10 -rotate-6 items-center justify-center rounded-xl border-2 border-ink bg-saffron text-ink shadow-[3px_3px_0_#1c1a17] transition hover:rotate-0">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/></svg>
                    </span>
                    <span class="text-xl tracking-tight">Yaarpool</span>
                </a>
                <nav class="hidden items-center gap-8 text-sm font-medium sm:flex">
                    <a href="#how" class="transition hover:text-emerald-700">How it works</a>
                    <a href="#features" class="transition hover:text-emerald-700">Features</a>
                    <a href="#why" class="transition hover:text-emerald-700">Why</a>
                    <a href="#examples" class="transition hover:text-emerald-700">Examples</a>
                </nav>
                <a href="#cta" class="rounded-xl border-2 border-ink bg-emerald-400 px-5 py-2 text-sm font-bold shadow-[3px_3px_0_#1c1a17] transition hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[2px_2px_0_#1c1a17]">
                    Get started
                </a>
            </header>

            {{-- Hero --}}
            <section class="mx-auto grid max-w-6xl items-center gap-14 px-6 pt-12 pb-20 lg:grid-cols-[1.1fr_1fr] lg:gap-8 lg:pt-20">
                <div>
                    <span class="inline-flex animate-fade-up -rotate-1 items-center gap-2 rounded-full border-2 border-ink bg-white px-4 py-1.5 text-xs font-bold shadow-[2px_2px_0_#1c1a17]">
                        💬 The carpool that lives in your group chat.
                    </span>

                    <h1 class="mt-7 animate-fade-up font-display text-5xl font-extrabold tracking-tight [animation-delay:100ms] sm:text-6xl lg:text-7xl">
                        Arre yaar, anyone
                        <span class="relative inline-block text-emerald-700">
                            going my way?
                            <svg class="absolute -bottom-4 left-0 w-full overflow-visible" viewBox="0 0 200 14" fill="none" preserveAspectRatio="none" aria-hidden="true">
                                <path id="hero-squiggle" d="M3 10c30-7 60 5 95-4 35-8 70 6 99-3" pathLength="100" stroke="currentColor" stroke-width="5" stroke-linecap="round" class="animate-draw text-saffron"/>
                                <g opacity="0" class="motion-reduce:hidden">
                                    <animate attributeName="opacity" to="1" dur="0.01s" begin="0.8s" fill="freeze"/>
                                    <animateMotion dur="0.8s" begin="0.8s" fill="freeze" rotate="auto" calcMode="spline" keyTimes="0;1" keySplines="0 0 0.58 1">
                                        <mpath href="#hero-squiggle" xlink:href="#hero-squiggle"/>
                                    </animateMotion>
                                    <text font-size="9" fill="#1c1a17" text-anchor="middle" y="-1" transform="scale(-1 1)">🛺</text>
                                </g>
                            </svg>
                        </span>
                    </h1>

                    <p class="mt-7 max-w-md animate-fade-up text-lg leading-relaxed [animation-delay:200ms]">
                        Yaarpool catches the ride requests flying around your chat — matches seats, splits the fuel.
                        No new app, just yaars going the same way.
                    </p>

                    <div class="mt-9 flex animate-fade-up flex-col gap-4 [animation-delay:300ms] sm:flex-row">
                        <a href="#cta" class="inline-flex items-center justify-center gap-2 rounded-2xl border-2 border-ink bg-emerald-500 px-7 py-3.5 text-sm font-bold text-white shadow-[4px_4px_0_#1c1a17] transition hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[2px_2px_0_#1c1a17]">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2 22l5.25-1.38a9.9 9.9 0 0 0 4.79 1.22h.01c5.46 0 9.9-4.45 9.9-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2Zm0 1.67c2.2 0 4.27.86 5.82 2.41a8.2 8.2 0 0 1 2.41 5.83c0 4.54-3.7 8.24-8.24 8.24a8.2 8.2 0 0 1-4.19-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.2 8.2 0 0 1-1.26-4.38c0-4.54 3.7-8.24 8.24-8.24Z"/></svg>
                            Add to WhatsApp group
                        </a>
                        <a href="#how" class="inline-flex items-center justify-center rounded-2xl border-2 border-ink bg-white px-7 py-3.5 text-sm font-bold shadow-[4px_4px_0_#1c1a17] transition hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[2px_2px_0_#1c1a17]">
                            See how it works
                        </a>
                    </div>

                    <p class="mt-6 animate-fade-up text-xs font-medium opacity-70 [animation-delay:400ms]">Free to start · Works in any group · Add it in 30 seconds</p>
                </div>

                {{-- Scattered chat bubbles --}}
                <div class="relative mx-auto w-full max-w-sm space-y-4" aria-hidden="true">
                    <div class="w-fit max-w-[85%] animate-pop -rotate-2 rounded-2xl rounded-tl-sm border-2 border-ink bg-[#D9FDD3] px-4 py-2.5 text-sm font-medium shadow-[4px_4px_0_#1c1a17] transition [animation-delay:500ms] hover:rotate-0">
                        Driving Andheri → BKC tomorrow 9am, 2 seats free 🚙
                    </div>
                    <div class="ml-auto w-fit max-w-[80%] animate-pop rotate-2 rounded-2xl rounded-tr-sm border-2 border-ink bg-white px-4 py-2.5 text-sm font-medium shadow-[4px_4px_0_#1c1a17] transition [animation-delay:750ms] hover:rotate-0">
                        anyone going BKC tmrw morning? 🙋
                    </div>
                    <div class="w-fit max-w-[90%] animate-pop -rotate-1 rounded-2xl rounded-tl-sm border-2 border-ink bg-saffron px-4 py-2.5 text-sm font-bold shadow-[4px_4px_0_#1c1a17] transition [animation-delay:1000ms] hover:rotate-0">
                        🤖 Match found! Andheri → BKC, 9:00 AM, 2 seats. Want in?
                    </div>
                    <div class="ml-auto w-fit max-w-[70%] animate-pop rotate-3 rounded-2xl rounded-tr-sm border-2 border-ink bg-[#D9FDD3] px-4 py-2.5 text-sm font-medium shadow-[4px_4px_0_#1c1a17] transition [animation-delay:1250ms] hover:rotate-0">
                        pick me!! 🚗💨
                    </div>
                </div>
            </section>

            {{-- Marquee strip --}}
            <div aria-hidden="true" class="overflow-hidden border-y-2 border-ink bg-ink py-3 text-cream">
                <div class="flex w-max animate-marquee gap-8 whitespace-nowrap font-display text-sm font-bold">
                    @php
                        $tickerItems = ['2 seats free 🚗', 'airport run at 8? ✈️', 'anyone for Pune? 🏔️', 'split the fuel ⛽', 'leaving in 10 mins 🏃', 'shotgun! 🙋', 'office at 9, who\'s in? 💼', 'weekend trip, 3 spots 🎒'];
                    @endphp
                    @foreach ([1, 2] as $copy)
                        <span class="flex gap-8">
                            @foreach ($tickerItems as $item)
                                <span>{{ $item }}</span><span class="text-saffron">·</span>
                            @endforeach
                        </span>
                    @endforeach
                </div>
            </div>

            {{-- How it works --}}
            <section id="how" class="mx-auto max-w-6xl px-6 py-20">
                <div class="mx-auto max-w-2xl text-center">
                    <h2 class="font-display text-4xl font-extrabold tracking-tight sm:text-5xl">As easy as texting a friend</h2>
                    <p class="mt-4 text-lg">No forms, no menus. Yaarpool understands what you mean.</p>
                </div>
                <div class="mt-14 grid gap-10 md:grid-cols-3 md:gap-6">
                    @php
                        $steps = [
                            ['1', '-rotate-2', 'bg-saffron', 'Drop a normal text', 'Just type like a human. "Driving to BKC tomorrow at 9 AM, 3 seats open" or "Anyone heading to Pune this weekend?"'],
                            ['2', 'rotate-1', 'bg-emerald-400', 'Let the bot match you', 'Yaarpool instantly logs the ride, matches drivers with passengers in the chat, and keeps the group list up to date.'],
                            ['3', '-rotate-1', 'bg-saffron', 'Hop in and split costs', 'Coordinate your pickup seamlessly. No platform fees, no corporate middleman — just carpooling among yaars.'],
                        ];
                    @endphp
                    @foreach ($steps as [$num, $tilt, $chip, $title, $body])
                        <div class="rounded-2xl border-2 border-ink bg-white p-6 shadow-[5px_5px_0_#1c1a17] transition hover:rotate-0 {{ $tilt }}">
                            <span class="flex h-12 w-12 items-center justify-center rounded-full border-2 border-ink font-display text-lg font-extrabold {{ $chip }}">{{ $num }}</span>
                            <h3 class="mt-4 font-display text-lg font-bold">{{ $title }}</h3>
                            <p class="mt-2 text-sm leading-relaxed">{{ $body }}</p>
                        </div>
                    @endforeach
                </div>
            </section>

            {{-- Chat demo --}}
            <section class="mx-auto max-w-6xl px-6 py-10">
                <div class="mx-auto max-w-2xl text-center">
                    <h2 class="font-display text-4xl font-extrabold tracking-tight sm:text-5xl">Watch it work</h2>
                    <p class="mt-4 text-lg">One bot quietly turning chatter into rides.</p>
                </div>
                <div class="relative mx-auto mt-12 w-full max-w-sm">
                    {{-- Tape strips --}}
                    <div aria-hidden="true" class="absolute -top-3 left-8 z-10 h-7 w-24 -rotate-6 rounded-sm bg-amber-200/80 shadow-sm"></div>
                    <div aria-hidden="true" class="absolute -top-3 right-8 z-10 h-7 w-24 rotate-6 rounded-sm bg-amber-200/80 shadow-sm"></div>
                    <div class="rotate-1 overflow-hidden rounded-3xl border-2 border-ink bg-[#ECE5DD] shadow-[6px_6px_0_#1c1a17]">
                        <div class="flex items-center gap-3 border-b-2 border-ink bg-emerald-600 px-4 py-3 text-white">
                            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-white/20 text-white">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/></svg>
                            </span>
                            <div class="leading-tight">
                                <p class="text-sm font-semibold">Yaarpool</p>
                                <p class="text-xs text-emerald-100">Office ride group · 24 members</p>
                            </div>
                        </div>
                        <div class="space-y-3 px-4 py-5 text-sm">
                            <div class="ml-auto max-w-[80%] rounded-2xl rounded-tr-sm bg-[#D9FDD3] px-3 py-2 text-[#111b21] shadow-sm">
                                Driving from Andheri to BKC tomorrow at 9am, 2 seats free 🚙
                            </div>
                            <div class="max-w-[85%] rounded-2xl rounded-tl-sm bg-white px-3 py-2 text-[#111b21] shadow-sm">
                                Got it! Posted your ride — Andheri → BKC, tomorrow 9:00 AM, 2 seats. I'll let people know. ✅
                            </div>
                            <div class="ml-auto max-w-[80%] rounded-2xl rounded-tr-sm bg-[#D9FDD3] px-3 py-2 text-[#111b21] shadow-sm">
                                anyone going towards BKC tmrw morning?
                            </div>
                            <div class="max-w-[85%] rounded-2xl rounded-tl-sm bg-white px-3 py-2 text-[#111b21] shadow-sm">
                                Yes! There's a ride: <span class="font-medium">Andheri → BKC, 9:00 AM, 2 seats.</span> Want me to connect you? 🙌
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Features --}}
            <section id="features" class="mx-auto max-w-6xl px-6 py-20">
                <div class="mx-auto max-w-2xl text-center">
                    <h2 class="font-display text-4xl font-extrabold tracking-tight sm:text-5xl">Everything a ride needs</h2>
                    <p class="mt-4 text-lg">One bot, every part of the carpool covered.</p>
                </div>
                <div class="mt-14 grid gap-8 sm:grid-cols-2 lg:grid-cols-3 lg:gap-6">
                    @php
                        $features = [
                            ['🙋', '-rotate-1', 'bg-amber-100', 'Call dibs on a seat', 'Drop your destination once. The bot keeps your request front-and-center so active drivers see it.'],
                            ['🚙', 'rotate-1', 'bg-emerald-100', 'Offer empty slots', 'Driving anyway? Tell the group your route and let your yaars hop in to split costs.'],
                            ['📋', '-rotate-2', 'bg-amber-100', 'Instant group boards', 'Type a quick question to fetch a clean, synced roster of all upcoming trips.'],
                            ['⚡', 'rotate-2', 'bg-emerald-100', 'No-fuss edits', 'Plans change. Update your timing or cancel entirely in plain text — the bot cleans up the board instantly.'],
                            ['🔒', 'rotate-1', 'bg-amber-100', 'Strict group isolation', 'What happens in your group stays there. Rides logged in Chat A never leak to Chat B.'],
                            ['📵', '-rotate-1', 'bg-emerald-100', 'Zero app footprint', 'No registrations, no passwords, no storage taken. It just works where you already talk.'],
                        ];
                    @endphp
                    @foreach ($features as [$emoji, $tilt, $chip, $title, $body])
                        <div class="rounded-2xl border-2 border-ink bg-white p-6 shadow-[5px_5px_0_#1c1a17] transition hover:rotate-0 hover:shadow-[3px_3px_0_#1c1a17] {{ $tilt }}">
                            <span class="flex h-12 w-12 items-center justify-center rounded-xl border-2 border-ink text-2xl {{ $chip }}">{{ $emoji }}</span>
                            <h3 class="mt-4 font-display text-lg font-bold">{{ $title }}</h3>
                            <p class="mt-2 text-sm leading-relaxed">{{ $body }}</p>
                        </div>
                    @endforeach
                </div>
            </section>

            {{-- Why we built this --}}
            <section id="why" class="mx-auto max-w-4xl px-6 py-20">
                <div class="relative">
                    {{-- Tape strips --}}
                    <div aria-hidden="true" class="absolute -top-3 left-10 z-10 h-7 w-24 -rotate-6 rounded-sm bg-amber-200/80 shadow-sm"></div>
                    <div aria-hidden="true" class="absolute -top-3 right-10 z-10 h-7 w-24 rotate-3 rounded-sm bg-amber-200/80 shadow-sm"></div>
                    <div class="-rotate-1 rounded-3xl border-2 border-ink bg-white p-8 shadow-[6px_6px_0_#1c1a17] sm:p-10">
                        <p class="font-display text-sm font-bold uppercase tracking-wider text-emerald-700">A note from the builder</p>
                        <h2 class="mt-3 font-display text-3xl font-extrabold tracking-tight sm:text-4xl">Why carpooling apps never worked</h2>
                        <p class="mt-5 leading-relaxed">
                            Plenty of startups have tried to turn carpooling into a business. The model fails for structural
                            reasons, not for lack of trying:
                        </p>
                        <div class="mt-8 space-y-7">
                            @php
                                $reasons = [
                                    ['1', "It's illegal to profit.", "In most countries a private car can't be used for commercial gain. Rules like India's Motor Vehicle Aggregator Guidelines mandate strict no-profit, no-loss cost-splitting — the moment a driver earns more than fuel and tolls, the ride becomes an illegal taxi service, risking heavy fines and voided private car insurance."],
                                    ['2', 'The margins are microscopic.', "A platform only ever moves the actual cost of a commute. Take a 5–15% commission on a ₹20 pool ride and you've earned a few rupees — covering engineering and server costs at those margins needs an impossibly massive, active daily user base."],
                                    ['3', 'Trust between strangers is expensive.', 'Getting strangers to share a car means continual ID and background verification, support teams for disputes and cancellations, and real-time mapping APIs for pickups — heavy recurring costs stacked against near-zero revenue.'],
                                    ['4', 'Drivers quit.', "A carpool driver is just a commuter, not a salaried taxi driver. Detouring ten minutes through heavy traffic to pick up a stranger ruins their own ride, and most decide the small payout isn't worth the lateness and lost privacy. Platforms bleed drivers."],
                                    ['5', 'Matching strangers barely works.', 'A carpool needs a perfect logistical match: homes near each other, offices near each other, identical departure times, and flexibility when a meeting runs late. Human schedules fluctuate constantly, so automated stranger-matching fails far more often than it succeeds.'],
                                ];
                            @endphp
                            @foreach ($reasons as [$num, $title, $body])
                                <div class="flex gap-4">
                                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full border-2 border-ink bg-saffron font-display text-sm font-extrabold">{{ $num }}</span>
                                    <p class="text-sm leading-relaxed sm:text-base">
                                        <span class="font-display font-bold">{{ $title }}</span>
                                        {{ $body }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                        <p class="mt-9 border-t-2 border-dashed border-ink/20 pt-7 font-medium leading-relaxed">
                            So we didn't build a platform. No commissions, no background checks, no stranger-matching
                            algorithm — just a bot in the group chats where the trust, the routes, and the schedules already
                            exist. That's why Yaarpool is free, and why it works.
                        </p>
                    </div>
                </div>
            </section>

            {{-- Privacy & Trust --}}
            <section class="mx-auto max-w-4xl px-6 py-12">
                <div class="flex -rotate-1 flex-col items-center gap-6 rounded-3xl border-2 border-dashed border-ink bg-white p-8 shadow-[5px_5px_0_#1c1a17] sm:flex-row">
                    <span class="text-5xl" aria-hidden="true">🔒</span>
                    <div>
                        <h4 class="font-display text-lg font-bold">Is it safe? What about our privacy?</h4>
                        <p class="mt-2 text-sm leading-relaxed">
                            Yaarpool belongs in the closed groups you already trust — your office crew, college batch, or
                            society circle. It reads recent chat history to understand context, but <span class="font-bold">it only ever logs structural
                            ride data</span> — routes, times, and seats. Your casual banter stays yours. Zero cross-group leakage, zero public feeds.
                        </p>
                    </div>
                </div>
            </section>

            {{-- Examples --}}
            <section id="examples" class="mx-auto max-w-4xl px-6 py-20">
                <div class="mx-auto max-w-2xl text-center">
                    <h2 class="font-display text-4xl font-extrabold tracking-tight sm:text-5xl">Built for every kind of group</h2>
                    <p class="mt-4 text-lg">Whether it's the daily hustle or a weekend getaway.</p>
                </div>
                <div class="mt-12 grid gap-6 text-left sm:grid-cols-2">
                    @php
                        $useCases = [
                            ['🏢', '-rotate-1', 'Office Commutes', 'Driving from Andheri to BKC tomorrow at 9am, 2 seats free 🚙'],
                            ['✈️', 'rotate-1', 'Airport Runs', 'Flight at midnight. Anyone heading towards the airport around 8 PM? Let\'s split the fare!'],
                            ['🏔️', 'rotate-1', 'Weekend Trips', 'Heading down to Pune early Saturday morning. Got room for 3 people.'],
                            ['🔄', '-rotate-1', 'Quick Updates', 'Change my BKC ride to 9:30 AM instead — or just "Cancel my trip tomorrow".'],
                        ];
                    @endphp
                    @foreach ($useCases as [$emoji, $tilt, $label, $quote])
                        <div class="rounded-2xl rounded-bl-sm border-2 border-ink bg-white p-5 shadow-[4px_4px_0_#1c1a17] transition hover:rotate-0 {{ $tilt }}">
                            <span class="flex items-center gap-2 font-display text-sm font-bold uppercase tracking-wider">
                                <span aria-hidden="true">{{ $emoji }}</span>{{ $label }}
                            </span>
                            <p class="mt-2 text-sm italic">“{{ $quote }}”</p>
                        </div>
                    @endforeach
                </div>
            </section>

            {{-- CTA --}}
            <section id="cta" class="mx-auto max-w-6xl px-6 pb-24">
                <div class="relative overflow-hidden rounded-3xl border-2 border-ink bg-ink px-8 py-16 text-center text-cream shadow-[8px_8px_0_#f59e0b]">
                    <div aria-hidden="true" class="absolute top-8 left-10 h-3 w-3 rounded-full bg-saffron"></div>
                    <div aria-hidden="true" class="absolute top-16 right-14 h-4 w-4 rounded-full bg-emerald-400"></div>
                    <div aria-hidden="true" class="absolute bottom-10 left-1/4 h-2.5 w-2.5 rounded-full bg-emerald-400"></div>
                    <div aria-hidden="true" class="absolute right-1/4 bottom-16 h-3 w-3 rounded-full bg-saffron"></div>
                    <h2 class="relative font-display text-4xl font-extrabold tracking-tight sm:text-5xl">Add it to your group, yaar</h2>
                    <p class="relative mx-auto mt-5 max-w-md text-cream/80">
                        Stop wasting money on empty seats and surge-priced cabs. Add Yaarpool to your group chat in 30 seconds — 100% free to start.
                    </p>
                    <div class="relative mt-9 flex justify-center">
                        <a href="https://wa.me/" class="inline-flex items-center gap-2 rounded-2xl border-2 border-ink bg-saffron px-8 py-4 text-sm font-bold text-ink shadow-[4px_4px_0_#fff6eb] transition hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[2px_2px_0_#fff6eb]">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2 22l5.25-1.38a9.9 9.9 0 0 0 4.79 1.22h.01c5.46 0 9.9-4.45 9.9-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2Zm0 1.67c2.2 0 4.27.86 5.82 2.41a8.2 8.2 0 0 1 2.41 5.83c0 4.54-3.7 8.24-8.24 8.24a8.2 8.2 0 0 1-4.19-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.2 8.2 0 0 1-1.26-4.38c0-4.54 3.7-8.24 8.24-8.24Z"/></svg>
                            Add to WhatsApp group
                        </a>
                    </div>
                </div>
            </section>

            {{-- Footer --}}
            <footer class="mx-auto max-w-6xl px-6 py-8">
                <div class="flex flex-col items-center justify-between gap-4 border-t-2 border-ink/10 pt-8 text-sm sm:flex-row">
                    <div class="flex items-center gap-2">
                        <span class="flex h-7 w-7 -rotate-6 items-center justify-center rounded-lg border-2 border-ink bg-saffron text-ink">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/></svg>
                        </span>
                        <span class="font-display font-bold">Yaarpool</span>
                    </div>
                    <div class="flex flex-col items-center gap-4 sm:flex-row">
                        <a href="https://github.com/jigar-dhulla/yaarpool-whatsapp-agent" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-xl border-2 border-ink bg-white px-4 py-2 text-xs font-bold shadow-[3px_3px_0_#1c1a17] transition hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[2px_2px_0_#1c1a17]">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/></svg>
                            Contribute on GitHub
                        </a>
                        <p>No strangers. Just yaars. © {{ date('Y') }}</p>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
