<template>
    <form method="post" action="#join" v-on:submit.prevent="register">
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <input id="first_name" name="first_name" type="text" v-model="registration.name.first" class="form-control" placeholder="First Name" required />
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <input id="last_name" name="last_name" type="text" v-model="registration.name.last" class="form-control" placeholder="Last Name" required />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <input id="email" name="email" type="email" v-model="registration.email" class="form-control" placeholder="Email" required />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <button type="submit" class="btn btn-primary btn-block" v-on:click="registration.type = 'player'">I want to play</button>
            </div>
            <div class="col-6">
                <button type="submit" class="btn btn-default btn-block" v-on:click="registration.type = 'spectator'">I want to watch</button>
            </div>
        </div>
    </form>
</template>

<script>
    export default {
        created() {
            this.registration.name.first = localStorage.getItem('first_name');
            this.registration.name.last = localStorage.getItem('last_name');
            this.registration.email = localStorage.getItem('email');
        },
        data() {
            return {
                'registration': {
                    name: { first: null, last: null },
                    email: null,
                    type: 'anonymous'
                }
            };
        },
        methods: {
            register() {
                Event.fire('Join', this.registration);
            }
        }
    }
</script>
