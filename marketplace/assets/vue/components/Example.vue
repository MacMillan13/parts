<template>
  <div>
    <p>This is an example of a new components in VueJs</p>
  </div>
</template>
<script setup>

import { loadScript } from "vue-plugin-load-script";

// loadScript("https://cdn.socket.io/3.0.3/socket.io.min.js")
//     .then(() => {
//
//       const socket = io('ws//localhost:7000');
//
//       socket.on('connect', function() {
//         console.log('Connected');
//
//         socket.emit('events', { test: 'test' });
//         socket.emit('identity', 0, response =>
//             console.log('Identity:', response),
//         );
//       });
//       socket.on('events', function(data) {
//         console.log('event', data);
//       });
//       socket.on('exception', function(data) {
//         console.log('event', data);
//       });
//       socket.on('disconnect', function() {
//         console.log('Disconnected');
//       });
//
//     })
//     .catch((error) => {
//       console.log('socket error ' + error)
//     });

const socket = new WebSocket('ws://localhost:7000');

socket.addEventListener('open', async (event) => {
  console.log('Connected to WebSocket server');
  socket.send('Hello, server!');
});

socket.addEventListener('events', async (event) => {
  console.log('Message');
});

socket.addEventListener('identity', async (event) => {
  console.log('Message');
});

socket.addEventListener('events', (event) => {
  console.log('Message from server:', event.data);
});

socket.addEventListener('error', (event) => {
  console.log('error');
});
</script>
<style scoped>
</style>
