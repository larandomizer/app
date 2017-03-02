
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// -----

window.Event = require('./lib/Event');

let Broker = require('./lib/Broker');

function connect() {
    try {
        return new Broker(
            window.Event,
            document.location.host,
            document.location.port,
            '/socket/',
            document.location.protocol === 'https:'
        );
    } catch (err) {
        window.Event.fire('connection.closed', err);
    }
}

window.Server = connect();

// -----

Vue.component('stat-dropdown-item', require('./components/StatDropdownItem.vue'));
Vue.component('stat-dropdown', require('./components/StatDropdown.vue'));
Vue.component('stat', require('./components/Stat.vue'));
Vue.component('user', require('./components/User.vue'));
Vue.component('connection', require('./components/Connection.vue'));
Vue.component('notifications', require('./components/Notifications.vue'));
Vue.component('grid-col', require('./components/GridColumn.vue'));
Vue.component('grid', require('./components/Grid.vue'));
Vue.component('modal', require('./components/Modal.vue'));
Vue.component('join-form', require('./components/JoinForm.vue'));

// -----

const app = new Vue({
    el: '#app',

    created() {
        // Server Messages
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
        Event.listen('AwardWinner', message => {
            if (message.uuid === this.connection.uuid) {
                this.showWinnerPrizeModal();
            }
        });

        // Client Commands
        Event.listen('join', registration => {
            localStorage.setItem('first', registration.name.first);
            localStorage.setItem('last', registration.name.last);
            localStorage.setItem('email', registration.email);

            this.$set(this.connection, 'name', registration.name.first +' '+registration.name.last);
            this.$set(this.connection, 'email', registration.email);
            this.$set(this.connection, 'type', registration.type);
            this.registered = true;

            // Server.send('JoinAsPlayer', registration);
            // Server.send('JoinAsSpectator', registration);
        });
        Event.listen('notification.dismiss.all', () => {
            console.log('Dismissing all notifications');
        });
        Event.listen('notification.send', connection => {
            Server.send('NotifyConnection', {
                sender: this.connection.uuid,
                receiver: connection.uuid
            });
        });
        Event.listen('connection.disconnect', connection => {
            console.log('Disconnect ' + connection.uuid);
            this.disconnect(connection.uuid);
        });
        Event.listen('connection.reconnect', connection => {
            this.reconnect(connection.uuid);
        });
        Event.listen('connection.closed', err => {
            this.connected = false;
        });
        Event.listen('connection.failed', err => {
            this.connected = false;
        });
        Event.listen('connection.disconnect.players', () => {
            console.log('Disconnect spectators');
        });
        Event.listen('connection.disconnect.spectators', () => {
            console.log('Disconnect spectators');
        });
        Event.listen('connection.disconnect.all', () => {
            this.displayPasswordModal();
            console.log('Disconnect all connections');
        });
        Event.listen('server.restart', () => {
            console.log('Restart server by sending StopServer message');
            Server.send('StopServer', {
                password: this.password
            });
        });
        Event.listen('prizes.add', () => {
            this.displayAddPrizeModal();
        });
        Event.listen('prizes.pick_winner', () => {
            Server.send('AwardWinner', {
                password: this.password
            });
        });
        Event.listen('prizes.reset', () => {
            console.log('Reset prizes');
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
        },
        isRegistered() {
            return this.registered;
        }
    },

    data: {
        password: '',
        prize: {
            name: null,
            sponsor: null
        },
        showAddPrizeModal: false,
        showPasswordModal: false,
        showWinnerModal: false,
        uptime: 0,
        registered: false,
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
        columns: {
            'name': 'Name',
            'email': 'Email',
            'uuid': 'Connection',
            'ipAddress': 'IP',
            'timestamp': 'Time',
            'type': 'Status'
        }
    },

    methods: {

        // Connection Control Methods
        connect() {
            connect();
        },
        reconnect() {
            console.log('Reconnecting...');
            this.connected = true;
        },
        disconnect(uuid) {
            console.log('Disconnect ' + uuid);
            this.connected = false;
        },

        // Prize Control Methods
        displayAddPrizeModal() {
            this.showAddPrizeModal = true;
        },
        sendNewPrize() {
            this.showAddPrizeModal = false;
            this.prize.name = null;
            this.prize.sponsor = null;
        },
        displayWinnerPrizeModal() {
            console.log('Show the winner what they won');
            this.showWinnerModal = true;
        },

        // Auth Control Methods
        displayRegisterPrompt() {
            console.log('Show anonymous connection the register prompt');
        },
        displayPasswordModal() {
            console.log('Show password confirmation modal');
            this.showPasswordModal = true;
        },
        sendAuthentication() {
            this.password = '';
            this.showPasswordModal = false;
            Server.send('Authenicate', {
                password: this.password
            })
        }
    }
});

window.onbeforeunload = function() {
    return "Are you sure you want to disconnect?";
};
