@extends(auth()->user()->role === 'parent' ? 'layouts.parent' : 'layouts.gestion-ecole')

@section('title', 'Notifications - OPTIMASCHOOL')
@section('page-title', 'Centre de notifications')

@section('content')
<div class="mx-auto w-full max-w-6xl px-4 py-6 sm:px-6 lg:px-8">

    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wide text-blue-700">
                OPTIMASCHOOL
            </p>
            <h1 class="mt-1 text-2xl font-black text-slate-900">
                Centre de notifications
            </h1>
            <p class="mt-2 text-sm text-slate-600">
                Consultez les informations et les événements concernant votre compte.
            </p>
        </div>

        @if($unreadCount > 0)
            <form method="POST" action="{{ route('notifications.read-all') }}">
                @csrf
                <button
                    type="submit"
                    class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-blue-800 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round"
                         aria-hidden="true">
                        <path d="m5 12 4 4L19 6"/>
                        <path d="M21 12a9 9 0 1 1-3-6.7"/>
                    </svg>
                    Tout marquer comme lu
                </button>
            </form>
        @endif
    </div>

    @if(session('success'))
        <div role="status"
             class="mb-5 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 text-blue-800">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.8"
                         stroke-linecap="round" stroke-linejoin="round"
                         aria-hidden="true">
                        <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/>
                        <path d="M10 21h4"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500">Non lues</p>
                    <p class="text-2xl font-black text-slate-900">{{ $unreadCount }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-100 text-green-800">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.8"
                         stroke-linecap="round" stroke-linejoin="round"
                         aria-hidden="true">
                        <path d="M20 7 10 17l-5-5"/>
                        <path d="M21 12a9 9 0 1 1-3-6.7"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500">Notifications affichées</p>
                    <p class="text-2xl font-black text-slate-900">{{ $notifications->total() }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
            <h2 class="font-extrabold text-slate-900">Historique</h2>
            <span class="text-sm text-slate-500">
                {{ $notifications->total() }} élément(s)
            </span>
        </div>

        @forelse($notifications as $notification)
            @php
                $data = $notification->data ?? [];
                $title = $data['titre'] ?? $data['title'] ?? 'Notification OPTIMASCHOOL';
                $message = $data['message'] ?? 'Une nouvelle information est disponible.';
                $url = $data['url'] ?? null;
                $icon = $data['icone'] ?? $data['icon'] ?? 'bell';
                $isUnread = $notification->read_at === null;
            @endphp

            <article class="border-b border-slate-100 px-5 py-5 last:border-b-0 {{ $isUnread ? 'bg-blue-50/70' : 'bg-white' }}">
                <div class="flex items-start gap-4">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ $isUnread ? 'bg-blue-800 text-white' : 'bg-slate-100 text-slate-600' }}">
                        @if(in_array($icon, ['credit-card', 'payment', 'money'], true))
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.8"
                                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="2" y="5" width="20" height="14" rx="2"/>
                                <path d="M2 10h20M6 15h3"/>
                            </svg>
                        @elseif(in_array($icon, ['calendar', 'school'], true))
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.8"
                                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="3" y="5" width="18" height="16" rx="2"/>
                                <path d="M16 3v4M8 3v4M3 11h18"/>
                            </svg>
                        @else
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.8"
                                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/>
                                <path d="M10 21h4"/>
                            </svg>
                        @endif
                    </div>

                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <h3 class="font-bold text-slate-900">
                                {{ $title }}
                            </h3>

                            @if($isUnread)
                                <span class="rounded-full bg-blue-100 px-2.5 py-1 text-xs font-bold text-blue-800">
                                    Nouvelle
                                </span>
                            @else
                                <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-500">
                                    Lue
                                </span>
                            @endif
                        </div>

                        <p class="mt-2 whitespace-pre-line break-words text-sm leading-6 text-slate-700">
                            {{ $message }}
                        </p>

                        <p class="mt-3 text-xs font-medium text-slate-500">
                            {{ $notification->created_at?->format('d/m/Y à H:i') }}
                        </p>

                        <div class="mt-4 flex flex-wrap items-center gap-3">
                            @if(is_string($url) && str_starts_with($url, '/') && !str_starts_with($url, '//'))
                                <a
                                    href="{{ url($url) }}"
                                    class="inline-flex min-h-10 items-center gap-2 rounded-lg bg-blue-800 px-3 py-2 text-sm font-bold text-white transition hover:bg-blue-900"
                                >
                                    Consulter
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor" stroke-width="2"
                                         stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M7 17 17 7M7 7h10v10"/>
                                    </svg>
                                </a>
                            @endif

                            @if($isUnread)
                                <form method="POST"
                                      action="{{ route('notifications.read', ['notification' => $notification->id]) }}">
                                    @csrf
                                    <button
                                        type="submit"
                                        class="inline-flex min-h-10 items-center gap-2 rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-bold text-slate-700 transition hover:bg-slate-50"
                                    >
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                             stroke="currentColor" stroke-width="2"
                                             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="m5 12 4 4L19 6"/>
                                        </svg>
                                        Marquer comme lue
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </article>
        @empty
            <div class="px-6 py-16 text-center">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-50 text-blue-800">
                    <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.7"
                         stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/>
                        <path d="M10 21h4"/>
                    </svg>
                </div>
                <h3 class="mt-4 text-lg font-extrabold text-slate-900">
                    Aucune notification
                </h3>
                <p class="mt-2 text-sm text-slate-600">
                    Les nouvelles informations apparaîtront ici lorsqu'elles seront disponibles.
                </p>
            </div>
        @endforelse

        @if($notifications->hasPages())
            <div class="border-t border-slate-200 px-5 py-4">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
