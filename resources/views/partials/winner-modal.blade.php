<modal v-if="connected && isRegistered" :status="showWinnerModal" v-on:close="showWinnerModal = false">
  <div class="row">
    <div class="col-3 d-flex justify-content-center">
      <span class="mdi mdi-trophy-variant-outline text-warning icon-large d-inline-flex align-self-start"></span>
    </div>
    <div class="col-9">
      <h5>Congratulations! You Won!</h5>
      <p>
        How about that? You won a <strong v-text="'Prize Name'"></strong> which
        was graciously provided by <strong v-text="'Prize Sponsor'"></strong>.
        Contact the host to claim your prize.
      </p>
    </div>
  </div>
</modal>
