<modal v-if="connected && isRegistered" :status="showAddPrizeModal" v-on:close="showAddPrizeModal = false">
  <div class="row">
    <div class="col-3 d-flex justify-content-center">
      <span class="mdi mdi-trophy-variant-outline text-warning icon-large d-inline-flex align-self-start"></span>
    </div>
    <div class="col-9 card-form">
      <h5>Add a New Prize</h5>
      <p>Adding a prize is an Admin action, you must know the admin password.</p>
      <form method="post" action="#" v-on:submit.prevent="sendNewPrize">
        <div class="row">
          <div class="form-group col-12">
            <input type="text" v-model="prize.name" id="prize_name" name="prize_name" class="form-control" placeholder="What is the prize name?" required />
          </div>
        </div>
        <div class="row">
          <div class="form-group col-12">
            <input type="text" v-model="prize.sponsor" id="prize_sponsor" name="prize_sponsor" class="form-control" placeholder="What is the prize sponsor's name?" required />
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12 col-6">
            <button type="submit" class="btn btn-primary btn-block">Add Prize</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</modal>
