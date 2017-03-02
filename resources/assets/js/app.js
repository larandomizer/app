/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.onbeforeunload = function() {
    return "Are you sure you want to disconnect?";
};

window.Server;
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
        window.Event.fire('ConnectionClosed', err);
    }
}

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

const app = new Vue({
    el: '#app',

    created() {
        // Server Messages
        Event.listen('ConnectionEstablished', message => {
            this.connected = true;
            this.connection = message.connection;
        });
        Event.listen('ConnectionRegistered', message => {
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
        Event.listen('Join', registration => {
            localStorage.setItem('first_name', registration.name.first);
            localStorage.setItem('last_name', registration.name.last);
            localStorage.setItem('email', registration.email);

            if( registration.type === 'player' ) {
                Server.send('JoinAsPlayer', {registration});
            } else {
                Server.send('JoinAsSpectator', {registration});
            }
        });
        Event.listen('DismissAllNotifications', () => {
            Server.send('DismissNotifications', {
                connection: this.connection.uuid
            });
        });
        Event.listen('NotificationSend', connection => {
            Server.send('NotifyConnection', {
                sender: this.connection.uuid,
                receiver: connection.uuid
            });
        });
        Event.listen('Disconnect', connection => {
            this.disconnect(connection.uuid);
        });
        Event.listen('Reconnect', connection => {
            this.reconnect(connection.uuid);
        });
        Event.listen('ConnectionClosed', err => {
            this.connected = false;
        });
        Event.listen('ConnectionFailed', err => {
            this.connected = false;
        });
        Event.listen('DisconnectPlayers', () => {
            this.displayPasswordModal();
        });
        Event.listen('DisconnectSpectators', () => {
            this.displayPasswordModal();
        });
        Event.listen('DisconnectAll', () => {
            this.displayPasswordModal();
        });
        Event.listen('RestartServer', () => {
            Server.send('StopServer');
        });
        Event.listen('NewPrize', () => {
            this.displayAddPrizeModal();
        });
        Event.listen('PickRandomWinner', () => {
            Server.send('PickRandomWinner');
        });
        Event.listen('ResetPrizes', () => {
            Server.send('ResetPrizes');
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
            return this.connection.type !== 'anonymous';
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
        connected: false,
        connection: {
            uuid: '',
            name: "Anonymous",
            email: 'Not Available',
            ipAddress: '127.0.0.1',
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
                {icon: 'power', event: 'DisconnectSpectators', title: 'Disconnect Spectators'},
                {icon: 'power', event: 'DisconnectPlayers', title: 'Disconnect Players'},
                {icon: 'power', event: 'DisconnectAll', title: 'Disconnect All'}
            ],
            prizes: [
                {icon: 'plus', event: 'NewPrize', title: 'Add New Prize'},
                {icon: 'trophy-variant-outline', event: 'PickRandomWinner', title: 'New Winner'},
                {icon: 'refresh', event: 'ResetPrizes', title: 'Reset Prizes'}
            ],
            server: [
                {icon: 'autorenew', event: 'RestartServer', title: 'Restart Server'}
            ],
        },
        columns: {
            'name': 'Name',
            'email': 'Email',
            'uuid': 'Connection',
            //'ipAddress': 'IP Address',
            'timestamp': 'Time',
            'type': 'Status'
        }
    },

    methods: {

        // Connection Control Methods
        connect() {
            window.Server = connect();
        },
        reconnect() {
            if( this.connected ) {
                this.disconnect(this.connection.uuid);
            }
            this.connect();
        },
        disconnect(uuid) {
            Server.close();
            this.connection = {
                uuid: '',
                name: "Anonymous",
                email: 'Not Available',
                ip_address: '127.0.0.1',
                timestamp: 0,
                type: 'anonymous',
                resource_id: ''
            };
            this.connected = false;
            this.connections = [];
            this.notifications = [];
            this.prizes = [];
            this.topics = [];
            this.uptime = 0;
            this.prize = {
                name:null,
                sponsor:null
            };
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
            this.showWinnerModal = true;
        },

        // Auth Control Methods
        displayRegisterPrompt() {
        },
        displayPasswordModal() {
            this.showPasswordModal = true;
        },
        sendAuthentication() {
            this.password = '';
            this.showPasswordModal = false;
            Server.send('Authenicate', {
                password: this.password
            });
        }
    }
});
