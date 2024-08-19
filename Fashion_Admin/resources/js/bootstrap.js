/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'ap1',
    forceTLS: false,
    enabledTransports: ['ws'],
    namespace: '', 
});

document.addEventListener('DOMContentLoaded', function() {
    const userId = document.querySelector('meta[name="user-id"]').getAttribute('content'); 
    window.Echo.private('admin-channel')
        .listen('.OrderNotificationEvent', (e) => {
            updateNotificationCount();
            addNewNotificationToTop(e.notification);       
        }
    );
    
    window.Echo.private('user-channel-' + userId)
        .listen('.SendUserNotificationEvent', (e) => {
            updateNotificationCount();
            addNewNotification(e.notification);
        }
    );
});

window.Echo.connector.pusher.connection.bind('state_change', (states) => {
    console.log('Pusher state changed', states);
});

window.Echo.connector.pusher.connection.bind('error', (error) => {
    console.error('Pusher error:', error);
});

window.Echo.connector.pusher.connection.bind('ping', () => {
    console.log('Pusher ping');
});

