import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: 'rsy2sly6yh2wgk24vnox', // tu REVERB_APP_KEY
    wsHost: window.location.hostname,
    wsPort: 8080,
    forceTLS: false,
    disableStats: true,
});

window.Echo.private('chat')
    .listen('.mensaje.enviado', (e) => {
        console.log('🎉 Evento recibido:', e.mensaje);
    });
