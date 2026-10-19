<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Historique des notifications de l'utilisateur connecté.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $notifications = $user->notifications()
            ->latest()
            ->paginate(15);

        $unreadCount = $user->unreadNotifications()->count();

        return view('notifications.index', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ]);
    }

    /**
     * Marquer une notification appartenant à l'utilisateur connecté
     * comme lue.
     */
    public function markAsRead(
        Request $request,
        string $notification
    ): RedirectResponse {
        $item = $request->user()
            ->notifications()
            ->whereKey($notification)
            ->firstOrFail();

        if ($item->read_at === null) {
            $item->markAsRead();
        }

        return redirect()
            ->back()
            ->with('success', 'Notification marquée comme lue.');
    }

    /**
     * Marquer toutes les notifications non lues comme lues.
     */
    public function markAllAsRead(Request $request): RedirectResponse
    {
        $request->user()
            ->unreadNotifications()
            ->update(['read_at' => now()]);

        return redirect()
            ->route('notifications.index')
            ->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }
}
