<?php

namespace Chat\HubClient;

use Chat\Repository\ChatRepository;
use Ratchet\ConnectionInterface;

class HubClientConnection implements HubClientConnectionInterface
{
    protected $repository;
    private $connection;
    private $roomNumber;
    private $question; //officalQuestion
    private $answer; // officalAnswer
    private $answerList;
    private $clientFinalAnswersList;
    
    public function __construct(ConnectionInterface $conn)
    {
        $this->repository = new ChatRepository;
        $this->connection = $conn;
        $this->answer = array();
        $this->answerConn = array();
        
        $this->setRoomNumber();
        echo $this->roomNumber;
        
        $this->connection->send(
            json_encode([
                    'action'   => 'roomcode',
                    'success'  => true,
                    'roomCode' => $this->roomNumber
                ]
            )
        );
    }

    public function addClient(ConnectionInterface $conn, $userName){
        
        if($this->repository->addClient($conn,$userName) == true){
            $conn->send(
                json_encode([
                    'action' => 'responseAddClient',
                    'success' => true,
                    'userName' => $userName
                ])
            );
            //updating the server with user names
            $this->connection->send(
                json_encode([
                    'action' => 'listOfNames',
                    'success' => true,
                    'names' => $this->repository->getNamesOfClients()
                ])
            );
        }
        else{
            $conn->send(
                json_encode([
                    'action' => 'responseAddClient',
                    'success' => false,
                    'userName' => $userName
                ])
            );
        }
        

        
        

        
    }
    
    public function sendQuestionAndAnswer($question, $answer){
        $this->question = $question;
        $this->answer = $answer;
        
        
        $this->repository->sendQuestion($question);
        
        $this->connection->send(
            json_encode([
                'action' => 'sentQuestion',
                'success' => true,
                'text' => $question
            ])
        );
    }
    
    public function receiveQuestionAnswer($answer,ConnectionInterface $conn){
        echo "recieved question \n\n\n";
        $i = 0;
        
        
        
        for($i; $i < count($this->answerList) &&  $i == -1; $i++){
            if($this->answerList[$i][1] == $conn){
                echo "you've already submitted";
                $i = -1;
            }
        }
        
        if($i != -1){
            $this->answerList[] = [$answer, $conn];
        }
        
        
        $conn->send(
            json_encode([
                'action' => 'receivedQuestionAnswer',
                'success' => true
            ])
        );
        
        if($this->checkEverbodyAnswered($this->answerList)){
            $justAnswers = [];
            $i = 0;
            for($i;  $i < count($this->answerList); $i++){
                echo "went through\n";
                array_push($justAnswers, $this->answerList[$i][0]);
            }
            
            $justAnswers[] = $this->answer;
            
            shuffle($justAnswers);
            
            $this->repository->sendAnswers($justAnswers);
            
            // also need answer
            $this->sendServerAnswers($justAnswers);
        }
        
    }
    
    public function receivedFinalAnswer($answer,ConnectionInterface $conn){
        echo "recieved question \n\n\n";
        $i = 0;
        
        for($i; $i < count($this->clientFinalAnswersList) &&  $i == -1; $i++){
            if($this->clientFinalAnswersList[$i][1] == $conn){
                echo "you've already submitted";
                $i = -1;
            }
        }
        
        if($i != -1){
            $this->clientFinalAnswersList[] = [$answer, $conn];
        }
        
        
        $conn->send(
            json_encode([
                'action' => 'receivedFinalAnswer',
                'success' => true
            ])
        );
        
        if($this->checkEverbodyAnswered($this->clientFinalAnswersList)){

        }
    }
    
    
    public function getConnection(){
      
      echo "getConnection \n";   
      return $this->connection;
    }
    
    public function onMessage(ConnectionInterface $conn, $msg){
        echo "got message";
    }

    public function getRoomNumber(){
        return $this->roomNumber;
    }
    
    private function setRoomNumber(){
        $this->roomNumber = mt_rand();
    }
    
    private function checkEverbodyAnswered($list){
        $i = 0;
        if(count($list) == $this->repository->getCount()){
            $i = 1;
        }
        return $i;
    }
    
    private function sendServerAnswers($listAnswers){
         $this->connection->send(
            json_encode([
                'action' => 'sendAnswers',
                'success' => true,
                'answers' => $listAnswers
            ])
        );
    }
}
