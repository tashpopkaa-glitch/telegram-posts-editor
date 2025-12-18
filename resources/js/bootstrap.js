import Echo from 'laravel-echo';

// Agar Pusher ishlatmayotgan bo‘lsangiz,
// bu joy shunchaki disabled bo‘lib turadi
window.Pusher = null;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    wsHost: import.meta.env.VITE_PUSHER_HOST || window.location.hostname,
    wsPort: import.meta.env.VITE_PUSHER_PORT || 80,
    wssPort: import.meta.env.VITE_PUSHER_PORT || 443,
    forceTLS: true,
    encrypted: true,
    disableStats: true,
    enabledTransports: ['ws', 'wss'],
});
