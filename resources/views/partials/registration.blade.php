<div v-if="connected && !isRegistered" class="registration container">
  <div class="row justify-content-center">
    <div class="col-xs-12 col-sm-9 col-lg-6">
      <div class="card card-form">
        <div class="card-header">Welcome to Prize Pool</div>
        <div class="card-body">
          <join-form :registration="isRegistered"></join-form>
        </div>
      </div>
    </div>
  </div>
</div>
