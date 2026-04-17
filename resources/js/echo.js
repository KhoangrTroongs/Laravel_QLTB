import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

console.log('Echo initialization starting...');
console.log('Host:', import.meta.env.VITE_REVERB_HOST);
console.log('Key:', import.meta.env.VITE_REVERB_APP_KEY);

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: true,
    enabledTransports: ['ws', 'wss'],
    disableStats: true,
});

window.Echo.connector.pusher.connection.bind('connected', () => {
    console.log('Echo successfully connected to Reverb!');
});

window.Echo.connector.pusher.connection.bind('error', (error) => {
    console.error('Echo connection error:', error);
});

// Debugging channel subscriptions
window.Echo.connector.pusher.connection.bind('state_change', (states) => {
    console.log('Echo connection state changed:', states.current);
});

if (window.Laravel && window.Laravel.userId) {
    console.log('Subscribing to private channel for user:', window.Laravel.userId);
    window.Echo.private(`App.Models.User.${window.Laravel.userId}`)
        .on('subscription_succeeded', () => {
            console.log('✅ Successfully subscribed to private channel!');
        })
        .on('subscription_error', (status) => {
            console.error('❌ Subscription error for private channel:', status);
        });
} else {
    console.log('Guest user - skipping private channel subscription');
}
