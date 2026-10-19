@auth
@php
    $notificationUser = auth()->user();
    $notificationUnreadCount = $notificationUser->unreadNotifications()->count();

    $notificationLatestItems = $notificationUser->notifications()
        ->latest()
        ->limit(5)
        ->get();
@endphp

<div class="optima-notification" data-optima-notification>
    <button
        type="button"
        class="optima-notification-trigger"
        data-optima-notification-trigger
        aria-label="Ouvrir les notifications"
        aria-expanded="false"
        aria-haspopup="true"
    >
        <svg viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="1.8"
             stroke-linecap="round" stroke-linejoin="round"
             aria-hidden="true">
            <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/>
            <path d="M10 21h4"/>
        </svg>

        @if($notificationUnreadCount > 0)
            <span class="optima-notification-count">
                {{ $notificationUnreadCount > 99 ? '99+' : $notificationUnreadCount }}
            </span>
        @endif
    </button>

    <div class="optima-notification-panel" data-optima-notification-panel hidden>
        <div class="optima-notification-heading">
            <div>
                <strong>Notifications</strong>
                <p>
                    {{ $notificationUnreadCount }}
                    non lue(s)
                </p>
            </div>

            @if($notificationUnreadCount > 0)
                <form method="POST" action="{{ route('notifications.read-all') }}">
                    @csrf
                    <button type="submit" class="optima-notification-read-all">
                        Tout lire
                    </button>
                </form>
            @endif
        </div>

        <div class="optima-notification-list">
            @forelse($notificationLatestItems as $item)
                @php
                    $notificationData = $item->data ?? [];
                    $notificationTitle = $notificationData['titre']
                        ?? $notificationData['title']
                        ?? 'Notification OPTIMASCHOOL';
                    $notificationMessage = $notificationData['message']
                        ?? 'Une nouvelle information est disponible.';
                    $notificationUrl = $notificationData['url'] ?? null;
                    $notificationIsUnread = $item->read_at === null;

                    $notificationSafeUrl = is_string($notificationUrl)
                        && str_starts_with($notificationUrl, '/')
                        && !str_starts_with($notificationUrl, '//')
                        ? url($notificationUrl)
                        : null;
                @endphp

                <div class="optima-notification-item {{ $notificationIsUnread ? 'is-unread' : '' }}">
                    <span class="optima-notification-dot"
                          aria-hidden="true"></span>

                    <div class="optima-notification-item-content">
                        <strong>{{ $notificationTitle }}</strong>

                        <p>{{ \Illuminate\Support\Str::limit($notificationMessage, 125) }}</p>

                        <time>
                            {{ $item->created_at?->format('d/m/Y à H:i') }}
                        </time>

                        <div class="optima-notification-item-actions">
                            @if($notificationSafeUrl)
                                <a href="{{ $notificationSafeUrl }}">Consulter</a>
                            @endif

                            @if($notificationIsUnread)
                                <form
                                    method="POST"
                                    action="{{ route('notifications.read', ['notification' => $item->id]) }}"
                                >
                                    @csrf
                                    <button type="submit">Marquer comme lue</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="optima-notification-empty">
                    <svg viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.7"
                         stroke-linecap="round" stroke-linejoin="round"
                         aria-hidden="true">
                        <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/>
                        <path d="M10 21h4"/>
                    </svg>
                    <strong>Aucune notification</strong>
                    <p>Les nouvelles informations apparaîtront ici.</p>
                </div>
            @endforelse
        </div>

        <a class="optima-notification-footer"
           href="{{ route('notifications.index') }}">
            Voir toutes les notifications
            <span aria-hidden="true">→</span>
        </a>
    </div>
</div>

<style>
.optima-notification {
    position: relative;
    display: inline-flex;
    align-items: center;
    flex-shrink: 0;
    z-index: 100;
}
.optima-notification-trigger {
    position: relative;
    display: inline-flex;
    width: 44px;
    height: 44px;
    align-items: center;
    justify-content: center;
    border: 1px solid #dbe3ef;
    border-radius: 12px;
    background: #fff;
    color: #1e3a8a;
    cursor: pointer;
    transition: background .15s, border-color .15s;
}
.optima-notification-trigger:hover {
    background: #eff6ff;
    border-color: #93c5fd;
}
.optima-notification-trigger svg {
    width: 23px;
    height: 23px;
}
.optima-notification-count {
    position: absolute;
    top: -6px;
    right: -6px;
    display: flex;
    min-width: 21px;
    height: 21px;
    align-items: center;
    justify-content: center;
    padding: 0 5px;
    border: 2px solid #fff;
    border-radius: 999px;
    background: #dc2626;
    color: #fff;
    font-size: 11px;
    font-weight: 800;
    line-height: 1;
}
.optima-notification-panel {
    position: absolute;
    top: calc(100% + 12px);
    right: 0;
    width: min(390px, calc(100vw - 24px));
    overflow: hidden;
    border: 1px solid #dbe3ef;
    border-radius: 16px;
    background: #fff;
    color: #0f172a;
    box-shadow: 0 18px 50px rgba(15, 23, 42, .18);
    text-align: left;
}
.optima-notification-panel[hidden] {
    display: none !important;
}
.optima-notification-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 16px;
    border-bottom: 1px solid #e2e8f0;
    background: #f8fafc;
}
.optima-notification-heading strong {
    font-size: 16px;
    font-weight: 800;
}
.optima-notification-heading p {
    margin: 4px 0 0;
    color: #64748b;
    font-size: 12px;
}
.optima-notification-read-all {
    border: 0;
    background: transparent;
    color: #1d4ed8;
    font-size: 12px;
    font-weight: 800;
    cursor: pointer;
}
.optima-notification-item {
    display: flex;
    gap: 10px;
    padding: 14px 16px;
    border-bottom: 1px solid #f1f5f9;
}
.optima-notification-item.is-unread {
    background: #eff6ff;
}
.optima-notification-dot {
    width: 9px;
    height: 9px;
    margin-top: 6px;
    flex-shrink: 0;
    border-radius: 50%;
    background: transparent;
}
.optima-notification-item.is-unread .optima-notification-dot {
    background: #2563eb;
}
.optima-notification-item-content {
    min-width: 0;
    flex: 1;
}
.optima-notification-item-content > strong {
    display: block;
    overflow-wrap: anywhere;
    font-size: 13px;
    font-weight: 800;
    line-height: 1.45;
}
.optima-notification-item-content > p {
    margin: 5px 0;
    overflow-wrap: anywhere;
    color: #475569;
    font-size: 12px;
    line-height: 1.55;
}
.optima-notification-item-content time {
    color: #64748b;
    font-size: 11px;
}
.optima-notification-item-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 8px;
}
.optima-notification-item-actions a,
.optima-notification-item-actions button {
    padding: 0;
    border: 0;
    background: transparent;
    color: #1d4ed8;
    font-size: 12px;
    font-weight: 800;
    text-decoration: none;
    cursor: pointer;
}
.optima-notification-empty {
    padding: 28px 16px;
    text-align: center;
}
.optima-notification-empty svg {
    width: 32px;
    height: 32px;
    margin: 0 auto 10px;
    color: #1e3a8a;
}
.optima-notification-empty strong {
    display: block;
    font-size: 14px;
}
.optima-notification-empty p {
    margin-top: 6px;
    color: #64748b;
    font-size: 12px;
}
.optima-notification-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    padding: 14px 16px;
    background: #fff;
    color: #1e3a8a;
    font-size: 13px;
    font-weight: 800;
    text-decoration: none;
}
.optima-notification-footer:hover {
    background: #eff6ff;
}
@media (max-width: 480px) {
    .optima-notification-panel {
        position: fixed;
        top: 78px;
        right: 12px;
        left: 12px;
        width: auto;
        max-height: calc(100vh - 100px);
        overflow-y: auto;
    }
}
</style>

<script>
(function () {
    if (window.optimaNotificationInitialized) return;
    window.optimaNotificationInitialized = true;

    document.addEventListener('click', function (event) {
        const trigger = event.target.closest('[data-optima-notification-trigger]');

        if (trigger) {
            const root = trigger.closest('[data-optima-notification]');
            const panel = root.querySelector('[data-optima-notification-panel]');
            const willOpen = panel.hidden;

            document.querySelectorAll('[data-optima-notification-panel]').forEach(function (otherPanel) {
                otherPanel.hidden = true;
                const otherTrigger = otherPanel.closest('[data-optima-notification]')
                    ?.querySelector('[data-optima-notification-trigger]');
                if (otherTrigger) otherTrigger.setAttribute('aria-expanded', 'false');
            });

            panel.hidden = !willOpen;
            trigger.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
            event.stopPropagation();
            return;
        }

        if (!event.target.closest('[data-optima-notification]')) {
            document.querySelectorAll('[data-optima-notification-panel]').forEach(function (panel) {
                panel.hidden = true;
                const button = panel.closest('[data-optima-notification]')
                    ?.querySelector('[data-optima-notification-trigger]');
                if (button) button.setAttribute('aria-expanded', 'false');
            });
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key !== 'Escape') return;

        document.querySelectorAll('[data-optima-notification-panel]').forEach(function (panel) {
            panel.hidden = true;
            const button = panel.closest('[data-optima-notification]')
                ?.querySelector('[data-optima-notification-trigger]');
            if (button) button.setAttribute('aria-expanded', 'false');
        });
    });
})();
</script>
@endauth
