/*
connection 
deals with astiblishing the connection.
It also deal with in the inbound traffic.
It doesn't do anything with sending information to the server.
All it does is deal with the inbound traffic and the effects
it has on the view. 
*/
var Connection = (function() {

    function Connection(username, chatWindowId, url) {
        this.socket = new WebSocket("ws://" + url);
        this.view = new view(this.socket);
        console.log(this.view);
        this.view.run();
        
        this.currentState = false;
        this.setupConnectionEvents();
    }

    Connection.prototype = {
        setupConnectionEvents: function() {
            var self = this;

            self.socket.onopen = function(evt) { self.connectionOpen(evt); };
            self.socket.onmessage = function(evt) { self.connectionMessage(evt); };
            self.socket.onclose = function(evt) { self.connectionClose(evt); };
        },

        connectionOpen: function(evt) {
            console.log("got open connection");
            this.open = true;
            this.view.updateStatus("open");
        },

        connectionMessage: function(evt) {
            console.log("recived message");
            //console.log(evt);
            if (!this.open)
                return;
            
            
            
            var data = JSON.parse(evt.data);
            console.log(data);
              
        },

        connectionClose: function(evt) {
            console.log("got closed connection");
            this.open = false;
            this.view.updateStatus("closed");
            
        },
    };

    return Connection;

})();



var conn = new Connection("server","chatwindow", "127.0.0.1:2000");