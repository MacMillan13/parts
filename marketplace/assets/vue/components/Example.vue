<template>
  <div>
    <p>This is an example of a new components in VueJs</p>
  </div>
</template>
<script setup>

import { loadScript } from "vue-plugin-load-script";

// const socket = new WebSocket('ws://localhost:8080');
// socket.onopen = function() {
//   console.log('Connected');
//   socket.send(
//       JSON.stringify({
//         event: 'events',
//         data: 'test',
//       }),
//   );
//   socket.onmessage = function(data) {
//     console.log(data);
//   };
// };

loadScript("https://cdn.socket.io/4.3.2/socket.io.min.js")
    .then(() => {

      const oldSock = new WebSocket('ws://localhost:7000');

      oldSock.addEventListener('open', async (event) => {
        console.log('Connected to WebSocket server');
        oldSock.send(JSON.stringify({
          event: 'events',
          data: 'test',
        }));
        oldSock.send(JSON.stringify({
          event: 'identity',
          data: 'Thanks Chris!',
        }));
      });


      const socket = io('ws://localhost:7000');
      console.log(socket)

      socket.on('connect', function() {
        console.log('Connected');

        socket.emit('events', { test: 'test' });
        socket.emit('identity', 0, response =>
            console.log('Identity:', response),
        );
      });
      socket.on('events', function(data) {
        console.log('event', data);
      });
      socket.on('exception', function(data) {
        console.log('event', data);
      });
      socket.on('disconnect', function() {
        console.log('Disconnected');
      });

    })
    .catch((error) => {
      console.log('socket error ' + error)
    });


// loadScript("https://cdn.socket.io/4.3.2/socket.io.min.js")
//     .then(() => {
//           const socket = io('ws//localhost:7000');
//
//           socket.on('connect', function() {
//             console.log('Connected');
//
//             socket.emit('events', { test: 'test' });
//             socket.emit('identity', 0, response =>
//                 console.log('Identity:', response),
//             );
//           });
//
//           socket.on('events', async (event) => {
//             console.log('Message');
//           });
//
//           socket.on('identity', async (event) => {
//             console.log('Message');
//           });
//
//           socket.on('events', (event) => {
//             console.log('Message from server:', event.data);
//           });
//
//           socket.on('error', (event) => {
//             console.log('error');
//           });
//         }
//     )

// const socket = new WebSocket('ws://localhost:7000');
//
// socket.addEventListener('open', async (event) => {
//   console.log('Connected to WebSocket server');
//   socket.send('events');
// });
//
// socket.addEventListener('events', async (event) => {
//   console.log('Message');
// });
//
// socket.addEventListener('identity', async (event) => {
//   console.log('Message');
// });
//
// socket.addEventListener('events', (event) => {
//   console.log('Message from server:', event.data);
// });
//
// socket.addEventListener('error', (event) => {
//   console.log('error');
// });
</script>
<style scoped>
</style>
