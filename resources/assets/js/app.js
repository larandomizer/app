
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

window.Event = new Vue();

Vue.component('stat', require('./components/Stat.vue'));
Vue.component('user', require('./components/User.vue'));
Vue.component('connection', require('./components/Connection.vue'));
Vue.component('notifications', require('./components/Notifications.vue'));

const app = new Vue({
    el: '#app',

    computed: {
        prizesStatus() {
            return this.numPrizesWon + ' / ' + this.numPrizes;
        }
    },

    data: {
        currentUser: {name: "Eoghan O'Brien"},
        currentConnection: false,
        numConnections: 0,
        numPrizes: 3,
        numPrizesWon: 0,
        serverUptime: 0,
        messages: [
            {from: 'Joseph James', created_at: '2017-02-19 19:13:00'},
            {from: 'Lettie Jordan', created_at: '2017-02-19 19:09:00'},
            {from: 'Daniel Labarge', created_at: '2017-02-19 19:03:00'},
        ]
    },

    created() {
        Event.$on("connection.toggle", () => {
            this.currentConnection = !this.currentConnection;
        });
    },

    methods: {
        // Prize Control Methods
        prizeNew(e) {},
        prizeWinner(e) {},
        prizeReset(e) {},
        // Server Control Methods
        serverStart(e) {},
        serverRestart(e) {},
        serverStop(e) {},
        // Connection Control Methods
        disconnectSpectators(e) {
            this.disconnect(e, 'spectators')
        },
        disconnectPlayers(e) {
            this.disconnect(e, 'players')
        },
        disconnect(e, group) {
            e.preventDefault();
            group = group || 'all';
            console.log('Disconnect ' + group);
        }
    }
});
