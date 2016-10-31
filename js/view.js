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
        status: document.getElementById('status'),
        userName: document.getElementById('userName'),
        listName: document.getElementById('listName'),
        UserNameInput: document.getElementById('username'),
        ServerIdInput: document.getElementById('serverIdInput'),
        SubmitUserButton: document.getElementById('submitUser'),
        
        
        this: this,

        updateStatus: function(status){
            this.status.innerHTML = "<p>status - " + status + "</p>";
        },

        updateUsername: function(username){
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
            
            this.SubmitUserButton.addEventListener("click",(function(event){
                console.log(that.ServerIdInput.value);
                console.log(that.UserNameInput.value);
                that.sendServer.connectHost(that.ServerIdInput.value,that.UserNameInput.value);
            }));
            
            
        }
        
    }

    return view;
})();

