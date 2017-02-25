
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

window.Event = require('./lib/Event');

Vue.component('stat-dropdown-item', require('./components/StatDropdownItem.vue'));
Vue.component('stat-dropdown', require('./components/StatDropdown.vue'));
Vue.component('stat', require('./components/Stat.vue'));
Vue.component('user', require('./components/User.vue'));
Vue.component('connection', require('./components/Connection.vue'));
Vue.component('notifications', require('./components/Notifications.vue'));
Vue.component('grid-col', require('./components/GridColumn.vue'));
Vue.component('grid', require('./components/Grid.vue'));

const app = new Vue({
    el: '#app',

    created() {
        Event.listen("connection.toggle", () => {
            this.currentConnection = !this.currentConnection;
        });
        Event.listen("connection.players.disconnect", () => {
            this.disconnectPlayers();
        });
        Event.listen("connection.spectators.disconnect", () => {
            this.disconnectSpectators();
        });
        Event.listen("connection.all.disconnect", () => {
            this.disconnect();
        });
        Event.listen('connection.ping', connection => {
            console.log('Ping: ', connection);
        });
    },

    computed: {
        prizesStatus() {
            return this.numPrizesWon + ' / ' + this.numPrizes;
        }
    },

    data: {
        currentUser: {
            name: "Eoghan O'Brien",
            email: 'eoghan@artisanscollaborative.com',
            player_id: '123456780',
            ip_address: '210.13.71.1',
            timestamp: 0,
            status: 'waiting'
        },
        currentConnection: false,
        numPrizes: 3,
        numPrizesWon: 0,
        serverUptime: 0,
        messages: [
            {from: 'Joseph James', created_at: '2017-02-25 10:19:00', read: false},
            {from: 'Lettie Jordan', created_at: '2017-02-25 10:14:00', read: false},
            {from: 'Daniel Labarge', created_at: '2017-02-25 10:08:00', read: false},
            {from: 'Ben Batschelet', created_at: '2017-02-25 10:03:00', read: false},
        ],
        menus: {
            connections: [
                {icon: 'power', event: 'connection.spectators.disconnect', title: 'Disconnect Spectators'},
                {icon: 'power', event: 'connection.players.disconnect', title: 'Disconnect Players'},
                {icon: 'power', event: 'connection.all.disconnect', title: 'Disconnect All'}
            ],
            prizes: [
                {icon: 'plus', event: 'prizes.new', title: 'Add New Prize'},
                {icon: 'trophy-variant-outline', event: 'prizes.winner.new', title: 'New Winner'},
                {icon: 'refresh', event: 'prizes.reset', title: 'Reset Prizes'}
            ],
            server: [
                {icon: 'autorenew', event: 'server.restart', title: 'Restart Server'}
            ],
        },
        columns: [
            { name: 'Name', key: 'name', width: '' },
            { name: 'Email', key: 'email', width: '' },
            { name: 'Player ID', key: 'player_id', width: '' },
            { name: 'IP Address', key: 'ip_address', width: '' },
            { name: 'Time', key: 'timestamp', width: '' },
            { name: 'Status', key: 'status', width: '80' }
        ],
        connections: [
            {
                name: 'Gary Bryan',
                email: 'gary@bryan.com',
                player_id: '123456781',
                ip_address: '210.13.71.1',
                timestamp: '0',
                status: 'winner'
            },
            {
                name: 'Anonymous',
                email: 'Not Available',
                player_id: '123456782',
                ip_address: '210.13.71.1',
                timestamp: 0,
                status: 'spectator'
            },
            {
                name: "Eoghan O'Brien",
                email: 'eoghan@artisanscollaborative.com',
                player_id: '123456780',
                ip_address: '210.13.71.1',
                timestamp: 0,
                status: 'waiting'
            },
            {
                name: "Ada Thompson",
                email: 'ada@thompson.com',
                player_id: '123456783',
                ip_address: '210.13.71.1',
                timestamp: 0,
                status: 'loser'
            },
            {
                name: "Lela Harris",
                email: 'lela@harris.com',
                player_id: '123456784',
                ip_address: '210.13.71.1',
                timestamp: 0,
                status: 'ready'
            }
        ]
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
        disconnectSpectators() {
            this.disconnect('spectators')
        },
        disconnectPlayers() {
            this.disconnect('players')
        },
        disconnect(group) {
            group = group || 'all';
            console.log('Disconnect ' + group);
        }
    }
});
