/*
View
The view managages all the events from the dom.
So this is very all the elements are held and events function are kept.
This is also where all the events for the server get fired at.
Thats why it has a sendServer object inside of it.
*/
var view = (function() {
    
    
    function view(socket, reConnectSocket,conn){
        this.sendServer = new sendServer(socket);
        this.a = reConnectSocket.bind(conn);
        console.log("test");
        console.log(reConnectSocket);
        console.log(this.sendServer);
        
    };
    view.prototype = {
        preGame: document.getElementById('preGame'),
        randomRoomCode: document.getElementById('randomRoomCode'),
        listUsers: document.getElementById('listUsers'),
        status: document.getElementById('status'),
        connectionButton: document.getElementById('connectionButton'),
        serverIdTag: document.getElementById('roomId'),
        displayText: document.getElementById('displayText'),
        startButton: document.getElementById('startButton'),
        
        updateStatus: function(status){
            this.status.innerHTML = "status - " + status;
        },

        updateRandomRoomCode: function(id){
            this.serverIdTag.innerHTML = "room id - " + id;
            this.connectionButton.style.display = "none";
            this.startButton.style.display = "block";
            this.listUsers.style.display = "block";
        },
        
        updateListAllUsers: function(listUsers){
            var arrayUsers = listUsers.split(' ');
            console.log(arrayUsers);
            concatUserList = 'Users - ';
            
            for(var i = 0; i < arrayUsers.length; i++ ){
                concatUserList += " , " + arrayUsers[i];
            }
            this.listUsers.innerHTML = concatUserList; 
        },
        updateQuestion: function(question){
            this.displayText.innerHTML = "<p>" + question + "</p>";
        },
        updateListAnswers: function(answers){
            concatAnswerList = "";
            for(var i = 0; i < answers.length; i++ ){
                concatAnswerList += " <p class='answers'> " + answers[i] +  "</p>";
            }
            this.displayText.innerHTML = concatAnswerList;
        },
        
        //what a hack
        // i need this but i can't use bind because
        // i'll loose the value's of the event.
        run:  function(){  
            var that = this;
            //setTimeout(that.sendServer.getRoomKey,3000);
            this.connectionButton.addEventListener("click",function(){
                console.log("connection");
                that.sendServer.getRoomKey();
            });
            
            this.startButton.addEventListener("click",function(){
                console.log("started Game");
                that.sendServer.startGame();
            });
            
            this.status.addEventListener("click", function(){
               console.log("peer clicked");
                that.a();
                console.log(that);
            });
            

        }
        
    }

    return view;
})();

