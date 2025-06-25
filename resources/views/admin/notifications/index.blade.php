@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Admin Notifications</h1>
        <button onclick="markAllRead()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Mark All Read
        </button>
    </div>
    
    <div id="notifications-list" class="space-y-4">
        <!-- Notifications will be dynamically added here -->
    </div>
    
    <div class="mt-6 text-center">
        <button id="loadMore" onclick="loadMore()" class="bg-gray-200 px-4 py-2 rounded hover:bg-gray-300 hidden">
            Load More
        </button>
    </div>
</div>

<script>
    let notifications = [];
    let page = 1;
    const perPage = 20;

    function getIcon(type) {
        const icons = {
            'book_borrowed': '📚',
            'book_returned': '🔄',
            'late_return': '⚠️'
        };
        return icons[type] || '🔔';
    }

    function renderNotifications() {
        const list = document.getElementById('notifications-list');
        list.innerHTML = '';
        
        notifications.forEach(n => {
            const div = document.createElement('div');
            div.className = `p-4 bg-white rounded-lg shadow flex items-start space-x-4 ${n.read ? 'opacity-75' : 'border-l-4 border-blue-500'}`;
            div.innerHTML = `
                <span class="text-2xl">${getIcon(n.type)}</span>
                <div class="flex-1">
                    <div class="flex items-center space-x-2">
                        <span class="font-semibold">${n.user}</span>
                        <span class="text-sm text-gray-500">${n.time}</span>
                    </div>
                    <p class="text-gray-600">${n.message}</p>
                </div>
                <div class="flex flex-col space-y-2">
                    <button onclick="markRead(${n.id})" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-sm">
                        ${n.read ? 'Read' : 'Mark Read'}
                    </button>
                    <button onclick="deleteNotification(${n.id})" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm">
                        Delete
                    </button>
                </div>
            `;
            list.appendChild(div);
        });

        document.getElementById('loadMore').classList.toggle('hidden', notifications.length < perPage);
    }

    async function fetchNotifications() {
        try {
            const response = await fetch(`/admin/notifications/fetch?page=${page}&per_page=${perPage}`, {
                headers: { 'Accept': 'application/json' }
            });
            const data = await response.json();
            
            notifications = page === 1 ? data : [...notifications, ...data];
            renderNotifications();
        } catch (error) {
            console.error('Fetch error:', error);
        }
    }

    async function markRead(id) {
        try {
            const response = await fetch(`/admin/notifications/mark-read/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            });
            if (response.ok) {
                notifications = notifications.map(n => n.id === id ? { ...n, read: true } : n);
                renderNotifications();
            }
        } catch (error) {
            console.error('Mark read error:', error);
        }
    }

    async function markAllRead() {
        try {
            const response = await fetch(`/admin/notifications/mark-all-read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            });
            if (response.ok) {
                notifications = notifications.map(n => ({ ...n, read: true }));
                renderNotifications();
            }
        } catch (error) {
            console.error('Mark all read error:', error);
        }
    }

    async function deleteNotification(id) {
        try {
            const response = await fetch(`/admin/notifications/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            });
            if (response.ok) {
                notifications = notifications.filter(n => n.id !== id);
                renderNotifications();
            }
        } catch (error) {
            console.error('Delete error:', error);
        }
    }

    function loadMore() {
        page++;
        fetchNotifications();
    }

    function initEcho() {
        if (typeof Echo !== 'undefined') {
            Echo.private(`notifications.${window.Laravel.userId}`)
                .listen('.notification.created', (e) => {
                    notifications = [...e, ...notifications];
                    renderNotifications();
                    new Audio('/sounds/notification.mp3').play().catch(console.error);
                });
        } else {
            console.warn('Laravel Echo not initialized');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        fetchNotifications();
        initEcho();
    });
</script>
@endsection