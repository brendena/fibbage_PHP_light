/*
sendServer
sendServer it just a object holding all the functions
to communicate with the server.

*/
var sendServer = (function() {

    function sendServer(socket) {
        this.socket = socket;

    }

    sendServer.prototype = {
        //this is the call
        updateUsername: function(name){
            this.socket.send(JSON.stringify({
                    action: 'setname',
                    username: name
            }));
        },
        
        connectHost: function(id, userName){
            this.socket.send(JSON.stringify({
                action: 'setServer',
                id: id,
                userName: userName
            }));
            
        }
    };

    return sendServer;

})();



