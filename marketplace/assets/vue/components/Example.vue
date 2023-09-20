<template>
  <div>
    <p>This is an example of a new components in VueJs</p>
  </div>
</template>
<script>
export default {
  name: "example",
  setup() {
    const socket = new WebSocket('ws://localhost:7000');

    socket.addEventListener('open', (event) => {
      console.log('Connected to WebSocket server');
      socket.send('Hello, server!');
    });

    socket.addEventListener('message', (event) => {
      console.log('Message from server:', event.data);
    });
  }
}
</script>
<style scoped>
</style>
