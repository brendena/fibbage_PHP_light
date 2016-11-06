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
        this.serverId = "";
        console.log(this.sendServer);
        
    };
    view.prototype = {
        status: document.getElementById('status'),
        userName: document.getElementById('userName'),
        listName: document.getElementById('listName'),
        UserNameInput: document.getElementById('username'),
        ServerIdInput: document.getElementById('serverIdInput'),
        SubmitUserButton: document.getElementById('submitUser'),
        questionInput: document.getElementById("questionInput"),
        questionButton:document.getElementById("questionButton"),
        question: document.getElementById("question"),
        waitingMessage: document.getElementById("waitMessage"),
        
        
        setUpSection: document.getElementById('setUpSection'),
        gameSection: document.getElementById('gameSection'),
        answerSection: document.getElementById('answerSection'),
        
        
        this: this,

        updateStatus: function(status){
            this.status.innerHTML = "status - " + status + "";
        },

        updateUsername: function(username){
            this.userName.innerHTML = "username - " + username ;
        },
        updateListAllUsers: function(listUsers){
            var arrayUsers = listUsers.split(' ');
            console.log(arrayUsers);
            this.listName.innerHTML = "";
            for(var i = 0; i < arrayUsers.length - 1; i++ ){
                this.listName.innerHTML +=  arrayUsers[i] + " , ";
            }
        },
        updateUserInterfaceStates: function(number){
            switch(number){
                //waiting to get a question
                case 0:
                    this.setUpSection.style.display = "block";
                    this.gameSection.style.display = "none";
                    break;
                case 1:
                    this.waitingMessage.style.display = "none";
                    this.setUpSection.style.display = "none";
                    this.gameSection.style.display = "block";
                    this.answerSection.style.display = "none";
                    break;
                case 2:
                    this.waitingMessage.style.display = "block";
                    this.setUpSection.style.display = "none";
                    this.gameSection.style.display = "none";
                    this.answerSection.style.display = "none";
                    break;
                case 3:
                    this.waitingMessage.style.display = "none";
                    this.setUpSection.style.display = "none";
                    this.gameSection.style.display = "none";
                    this.answerSection.style.display = "block";
                    break;
                case 4:
                    this.waitingMessage.style.display = "block";
                    this.setUpSection.style.display = "none";
                    this.gameSection.style.display = "none";
                    this.answerSection.style.display = "none";
                    break;
            }
                    
            
        },
        updateUserIsAdded: function(username){
            this.updateUsername(username);
            this.updateUserInterfaceStates(1);
        },
        updateQuestion: function(question){
            this.question.innerHTML = question;
            this.updateUserInterfaceStates(1);
        },
        //what a hack
        // i need this but i can't use bind because
        // i'll loose the value's of the event.
        run:  function(){  
            this.updateUserInterfaceStates(0);
            var that = this;

            
            this.SubmitUserButton.addEventListener("click",(function(event){
                console.log(that.ServerIdInput.value);
                console.log(that.UserNameInput.value);
                
                that.serverId = that.ServerIdInput.value;
                
                that.sendServer.connectHost(that.ServerIdInput.value,that.UserNameInput.value);
            }));
            
            this.questionButton.addEventListener("click",(function(event){
                console.log(that.questionInput.value);
                that.sendServer.sendQuestionAnswer(that.questionInput.value, that.serverId);
            }));
            
        }
        
    }

    return view;
})();

