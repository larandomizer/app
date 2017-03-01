"use strict";

let UUID = require('uuid-js')

class Broker {
    constructor(event, ip, port, domain, secure) {
        this._event = event;
        this._connection = null;
        this.ip = ip;
        this.domain = domain;
        this.scheme = port === 443 || secure === true ? 'wss' : 'ws';
        this.port = (port === '') ? (secure === true ? 443 : 80) : port;
        this.url = this.scheme +'://' + this.ip + ':' + this.port + this.domain;

        try {
            this._connection = new WebSocket(this.url);
            // Dispatch the open event to the processing board
            this._connection.onopen = this.onOpen.bind(this);
            // Dispatch the exception as an event to the processing board
            this._connection.onerror = this.onError.bind(this);
            // Dispatch the message as an event to the processing board
            this._connection.onmessage = this.onMessage.bind(this);
            // Dispatch the close event to the processing board
            this._connection.onclose = this.onClose.bind(this);
        } catch(e) {
            this._event.fire('connection.failed', e);
        }
    }

    onOpen(e) {
        e.message = 'Opening connection to ' + this.url;
        this._event.fire('connection.opened', e);
    }

    onMessage(e) {
        var payload = JSON.parse(e.data);
        this._event.fire(payload.name, payload);
    }

    onError(e) {
        this._event.fire('connection.errored', e);
    }

    onClose(e) {
        var data = {};
        switch(e.code) {
            case 1000:
                data.message = 'Connection closed.';
                break;

            case 1006:
                data.message = 'Connection closed: remote server hung up.';
                if (e.reason !== undefined) {
                    data.reason = e.reason;
                }
                this._event.fire('connection.failed', data);
                return;
        }
        this._event.fire('connection.closed', data);
    }

    send(message, payload) {
        payload = payload || {};
        payload.id = UUID.create().hex;
        payload.name = message;
        payload.timestamp = new Date().getTime();

        return this._connection.send(JSON.stringify(payload));
    }

    close(code, reason) {
        this._connection.close(code, reason);
    }
}

module.exports = Broker;
