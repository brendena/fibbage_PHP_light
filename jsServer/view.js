/*
View
The view managages all the events from the dom.
So this is very all the elements are held and events function are kept.
This is also where all the events for the server get fired at.
Thats why it has a sendServer object inside of it.
*/
var view = (function() {
    
    
    function view(socket){
        this.sendServer = new sendServer(socket);
        console.log(this.sendServer);
        
    };
    view.prototype = {
        preGame: document.getElementById('preGame'),
        randomRoomCode: document.getElementById('randomRoomCode'),
        listUsers: document.getElementById('listUsers'),
        status: document.getElementById('status'),
        connectionButton: document.getElementById('connectionButton'),

        updateStatus: function(status){
            //var keyRoom = Math.random().toString(36).substr(2, 5);
            this.status.innerHTML = "<p>status - " + status + "</p>";
        },

        updateRandomRoomCode: function(username){
            var roomNumber = 
            this.userName.innerHTML = "<p>username - " + username + "</p>";
        },
        
        updateListAllUsers: function(listUsers){
            var arrayUsers = listUsers.split(' ');
            console.log(arrayUsers);
            this.listName.innerHTML = "";
            for(var i = 0; i < arrayUsers.length - 1; i++ ){
                this.listName.innerHTML += "<p>" + arrayUsers[i] + "</p>";
            }
        },
        //what a hack
        // i need this but i can't use bind because
        // i'll loose the value's of the event.
        run:  function(){  
            var that = this;
            //setTimeout(that.sendServer.getRoomKey,3000);
            this.connectionButton.addEventListener("click",function(){
                console.log("click");
                that.sendServer.getRoomKey();
            });
            /*
            this.UserNameInput.addEventListener('keypress', function(evt) {
                if (evt.keyCode != 13 || this.value == "")
                    return;


                evt.preventDefault();
                this.style.display = "none";
                that.sendServer.updateUsername(this.value);
                that.updateUsername(this.value);

                //first connection

            });
            */
        }
        
    }

    return view;
})();
