
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
window.Event = require('./lib/Event');
window.Server = require('./lib/Server')(window.Event, document.location.host, document.location.port, '/socket/', document.location.protocol === 'https:');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

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
        Event.listen('ConnectionEstablished', message => {
            message.connection.timestamp = message.timestamp;
            this.connected = true;
            this.connection = message.connection;
        });
        Event.listen('UpdateConnections', message => {
            this.connections = _.values(message.connections);
        });
        Event.listen('UpdatePrizes', message => {
            this.prizes = _.values(message.prizes);
        });
        Event.listen('UpdateNotifications', message => {
            this.notifications = _.values(message.notifications);
        });
        Event.listen('UpdateTopics', message => {
            this.topics = _.values(message.topics);
        });
        Event.listen('CurrentUptime', message => {
            this.uptime = message.elapsed;
        });
        Event.listen('connection.disconnect', connection => {
            this.disconnect(connection.uuid);
        });
        Event.listen('connection.reconnect', connection => {
            this.reconnect();
        });
        Event.listen('connection.disconnect.players', () => {
            this.disconnectPlayers();
        });
        Event.listen('connection.disconnect.spectators', () => {
            this.disconnectSpectators();
        });
        Event.listen('connection.disconnect.all', () => {
            this.disconnectAll();
        });
        Event.listen('notification.dismiss.all', () => {
            this.dismissNotifications();
        });
        Event.listen('notification.send', connection => {
            this.notifyConnection(connection.uuid);
        });
        Event.listen('server.restart', () => {
            this.restartServer();
        });
        Event.listen('prizes.add', () => {
            this.addPrize();
        });
        Event.listen('prizes.pick_winner', () => {
            this.pickRandomWinner();
        });
        Event.listen('prizes.reset', () => {
            this.resetPrizes();
        });
    },

    computed: {
        uptimeLabel() {
            return _.padStart(Math.floor(this.uptime / 60), 2, '0') + ':' + _.padStart(this.uptime % 60, 2, '0');
        },
        prizesLabel() {
            return (this.prizesTotal - this.prizesWon) + ' / ' + this.prizesTotal;
        },
        prizesWon() {
            return _.filter(this.prizes, prize => !_.isEmpty(prize.winner)).length;
        },
        prizesTotal() {
            return this.prizes.length;
        }
    },

    data: {
        uptime: 0,
        connected: false,
        connection: {
            uuid: '',
            name: "Anonymous",
            email: 'Not Available',
            ip_address: '127.0.0.1',
            timestamp: 0,
            type: 'anonymous',
            resource_id: ''
        },
        connections: [],
        notifications: [],
        topics: [],
        prizes: [],
        menus: {
            connections: [
                {icon: 'power', event: 'connection.disconnect.spectators', title: 'Disconnect Spectators'},
                {icon: 'power', event: 'connection.disconnect.players', title: 'Disconnect Players'},
                {icon: 'power', event: 'connection.disconnect.all', title: 'Disconnect All'}
            ],
            prizes: [
                {icon: 'plus', event: 'prizes.add', title: 'Add New Prize'},
                {icon: 'trophy-variant-outline', event: 'prizes.pick_winner', title: 'New Winner'},
                {icon: 'refresh', event: 'prizes.reset', title: 'Reset Prizes'}
            ],
            server: [
                {icon: 'autorenew', event: 'server.restart', title: 'Restart Server'}
            ],
        },
        columns: [
            { name: 'Name', key: 'name', width: '' },
            { name: 'Email', key: 'email', width: '' },
            { name: 'Connection', key: 'uuid', width: '' },
            { name: 'IP Address', key: 'ip_address', width: '' },
            { name: 'Time', key: 'timestamp', width: '' },
            { name: 'Status', key: 'type', width: '80' }
        ],
    },

    methods: {

        // Connection Control Methods
        connect() {
            console.log('Connecting...');
            this.connected = true;
        },
        reconnect() {
            console.log('Reconnecting...');
            this.connected = true;
        },
        disconnect(uuid) {
            console.log('Disconnect ' + uuid);
            this.connected = false;
        },
        disconnectAll(type) {
            console.log('Disconnect all');
        },
        disconnectType(type) {
            console.log('Disconnect ' + type);
        },
        disconnectSpectators() {
            this.disconnectType('spectator')
        },
        disconnectPlayers() {
            this.disconnectType('player')
        },

        // Prize Control Methods
        showAddPrizeModal() {
            console.log('Show add prize modal');
        },
        addPrize() {
            console.log('Add prize');
        },
        pickRandomWinner() {
            console.log('Pick new random winner');
        },
        showWinnerPrizeModal() {
            console.log('Show the winner what they won');
        },
        resetPrizes() {
            console.log('Reset prizes');
        },

        // Server Control Methods
        restartServer() {
            console.log('Restart server by sending StopServer message');
        },

        // Notification Control Methods
        notifyConnection(uuid) {
            Server.send('NotifyConnection', {
                sender: this.connection.uuid,
                receiver: uuid
            });
        },
        dismissNotifications() {
            console.log('Dismissing all notifications');
        },

        // Auth Control Methods
        showRegisterPrompt() {
            console.log('Show anonymous connection the register prompt');
        },
        showPasswordModal() {
            console.log('Show password confirmation modal');
        }
    }
});
