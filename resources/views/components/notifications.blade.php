<div class="notifications-dropdown">
    <button class="notification-button" aria-label="Notifications ({{ $unreadNotificationsCount }})">
        <i class="bell-icon fas fa-bell"></i>
        @if($unreadNotificationsCount > 0)
            <span class="badge badge-primary">{{ $unreadNotificationsCount }}</span>
        @endif
    </button>
    
    <div class="dropdown-content" aria-live="polite">
        @forelse($unreadNotifications as $notification)
            <a href="{{ $notification->data['url'] ?? '#' }}" 
               class="notification-item {{ $notification->read_at ? 'read' : 'unread' }}"
               data-notification-id="{{ $notification->id }}">
                <div class="notification-icon">
                    <i class="{{ $notification->data['icon'] ?? 'fas fa-bell' }}"></i>
                </div>
                <div class="notification-content">
                    <h6>{{ $notification->data['title'] ?? 'Untitled Notification' }}</h6>
                    <p>{{ $notification->data['message'] ?? 'No message available' }}</p>
                    <small>{{ $notification->created_at->diffForHumans() }}</small>
                </div>
            </a>
        @empty
            <div class="notification-empty">
                No new notifications
            </div>
        @endforelse
        
        @if($unreadNotificationsCount > 5)
            <div class="notification-footer">
                <a href="{{ route('notifications.index') }}" aria-label="View all notifications">View all notifications</a>
            </div>
        @endif
    </div>
</div>