import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

if (import.meta.env.VITE_PUSHER_APP_KEY) {
    window.Pusher = Pusher;

    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: import.meta.env.VITE_PUSHER_APP_KEY,
        cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
        forceTLS: true
    });
} else {
    console.warn('Pusher disabled: no VITE_PUSHER_APP_KEY');
}
