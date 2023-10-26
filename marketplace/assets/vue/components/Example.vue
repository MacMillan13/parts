<template>
  <div>
    <p>This is an example of a new components in VueJs</p>
  </div>
</template>
<script setup>

// const socket = new WebSocket(((window.location.protocol == 'https:') ? 'wss:' : 'ws:')
//     + '//localhost:7000');

const socket = new WebSocket('ws://localhost:7000');

socket.addEventListener('open', async (event) => {
  console.log('Connected to WebSocket server');
  socket.send(JSON.stringify({
    event: 'events',
    data: '11128654272',
  }));
});

socket.addEventListener("message", (event) => {
  console.log(event)
  console.log("Message from server ", event.data);
});

socket.addEventListener('events', async (event) => {
  console.log('Message');
});

socket.addEventListener('identity', async (event) => {
  console.log('Message');
});

socket.addEventListener('error', (event) => {
  console.log('error');
});

</script>
<style scoped>
</style>
