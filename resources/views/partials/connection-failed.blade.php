<div v-if="!connected" class="connection-failed container">
  <div class="row justify-content-center">
    <div class="text-center">
      <a v-on:click="connect" class="mdi mdi-power text-primary icon-medium icon-ring d-block m-auto"></a>
      <p class="d-block mt-2">Server Connection Failed</p>
    </div>
  </div>
</div>
