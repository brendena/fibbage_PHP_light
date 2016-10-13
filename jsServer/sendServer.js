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
        //maby this should be a built into php application
        getRoomKey: function(){
            //probably should need this.
            this.socket.send(JSON.stringify({
                    action: 'setname',
                    username: "server"
            }));
            
            this.socket.send(JSON.stringify({
                    action: 'getroomkey'
            }));
            
            console.log("getRoom");
        }
    };

    return sendServer;

})();



