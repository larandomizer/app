<modal v-if="connected && isRegistered" :status="showPasswordModal" v-on:close="showPasswordModal = false">
  <div class="row">
    <div class="col-3 d-flex justify-content-center">
      <span class="mdi mdi-lock text-primary icon-large d-inline-flex align-self-start"></span>
    </div>
    <div class="col-9 card-form">
      <h5>What Is the Magic Password?</h5>
      <p>Please enter the server password to confirm that you want to do this:</p>
      <form method="post" action="#" v-on:submit.prevent="sendAuthentication">
        <div class="row">
          <div class="col-12 form-group">
            <input type="password" v-model="password" id="password" name="password" class="form-control" placeholder="Enter your password" required />
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12 col-6">
            <button type="submit" class="btn btn-primary btn-block">Submit</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</modal>
