const userId = window.userId || null;

if (userId) {
    window.Echo.private(`App.Models.User.${userId}`)
        .notification((notification) => {
            refreshNotifications();
        });
}

/**
 * Fetch and update notifications in the UI.
 */
async function refreshNotifications() {
    try {
        const response = await fetch('/notifications', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        });

        if (!response.ok) {
            throw new Error('Failed to fetch notifications');
        }

        const data = await response.json();
        updateNotificationUI(data);
    } catch (error) {
        console.error('Error refreshing notifications:', error);
    }
}

/**
 * Update the notification dropdown UI.
 * @param {Object} data - The notification data from the server.
 */
function updateNotificationUI(data) {
    const dropdown = document.querySelector('.dropdown-content');
    const badge = document.querySelector('.badge');
    
    if (badge) {
        badge.textContent = data.unread_count;
        badge.style.display = data.unread_count > 0 ? 'inline-block' : 'none';
    }

    if (dropdown) {
        dropdown.innerHTML = '';
        if (data.notifications.length === 0) {
            dropdown.innerHTML = '<div class="notification-empty">No new notifications</div>';
        } else {
            data.notifications.forEach(notification => {
                const item = document.createElement('a');
                item.href = notification.data.url || '#';
                item.className = `notification-item ${notification.read_at ? 'read' : 'unread'}`;
                item.dataset.notificationId = notification.id;
                item.innerHTML = `
                    <div class="notification-icon">
                        <i class="${notification.data.icon || 'fas fa-bell'}"></i>
                    </div>
                    <div class="notification-content">
                        <h6>${notification.data.title || 'Untitled'}</h6>
                        <p>${notification.data.message || 'No message'}</p>
                        <small>${new Date(notification.created_at).toLocaleString()}</small>
                    </div>
                `;
                item.addEventListener('click', handleNotificationClick);
                dropdown.appendChild(item);
            });

            if (data.unread_count > 5) {
                const footer = document.createElement('div');
                footer.className = 'notification-footer';
                footer.innerHTML = '<a href="/notifications">View all notifications</a>';
                dropdown.appendChild(footer);
            }
        }
    }
}

/**
 * Handle notification click to mark as read.
 * @param {Event} event - The click event.
 */
async function handleNotificationClick(event) {
    event.preventDefault();
    const notificationId = event.currentTarget.dataset.notificationId;
    const url = event.currentTarget.href;

    try {
        const response = await fetch(`/notifications/mark-as-read/${notificationId}`, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
        });

        if (response.ok) {
            window.location.href = url;
            refreshNotifications();
        } else {
            console.error('Failed to mark notification as read');
        }
    } catch (error) {
        console.error('Error marking notification as read:', error);
    }
}

document.addEventListener('DOMContentLoaded', refreshNotifications);