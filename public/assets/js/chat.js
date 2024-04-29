// Import laravel-echo and pusher-js
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Initialize Laravel Echo
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'YOUR_PUSHER_APP_KEY', // Ganti dengan Pusher App Key Anda
    cluster: 'YOUR_PUSHER_APP_CLUSTER', // Ganti dengan Pusher App Cluster Anda
    encrypted: true,
});

// Example: Listen to a channel and event
window.Echo.channel('channel-name')
    .listen('EventName', (data) => {
        console.log('Data dari Pusher:', data);
        // Lakukan sesuatu dengan data yang diterima dari Pusher
    });
