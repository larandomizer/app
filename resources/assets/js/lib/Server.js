"use strict";

/**
 * Websocket Factory
 */

module.exports = function(emitter, ip, port, domain, secure){

    // Connect to the remote server
    var scheme = port === 443 || secure === true ? 'wss' : 'ws';
    port = (port === '') ? (secure === true ? 443 : 80) : port;
    var url = scheme +'://' + ip + ':' + port + domain;
    try{
        var Server = new WebSocket(url);

        // Dispatch the open event to the processing board
        Server.onopen = function(e)
        {
            e.message = 'Opening connection to ' + url;
            emitter.fire('connection.opened', e);
        };

        // Dispatch the exception as an event to the processing board
        Server.onerror = function(e)
        {
            emitter.fire('connection.errored', e);
        };

        // Dispatch the message as an event to the processing board
        Server.onmessage = function(e)
        {
            var payload = JSON.parse(e.data);
            emitter.fire(payload.name, payload);
        };

        // Dispatch the close event to the processing board
        Server.onclose = function(e)
        {
            var data = {};
            switch(e.code)
            {
                case 1000:
                    data.message = 'Connection closed.';
                    break;

                case 1006:
                    data.message = 'Connection closed: remote server hung up.';
                    if(e.reason !== undefined)
                    {
                        data.reason = e.reason;
                    }
                    emitter.fire('connection.failed', data);
                    return;
            }
            emitter.fire('connection.closed', data);
        };

        return Server;

    // Catch the errors
    } catch(e) {
        emitter.fire('connection.failed', e);
    }
}
