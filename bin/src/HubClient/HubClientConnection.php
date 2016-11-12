<?php

namespace Chat\HubClient;

use Chat\Repository\ChatRepository;
use Ratchet\ConnectionInterface;

class HubClientConnection implements HubClientConnectionInterface
{
    protected $repository;
    private $connection;
    private $roomNumber;
    private $answer;
    
    public function __construct(ConnectionInterface $conn)
    {
        $this->repository = new ChatRepository;
        $this->connection = $conn;
        $this->answer = [];
        
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
    
    public function sendQuestion($question){
        
        
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
        array_push($this->answer, [$answer, $conn]);
        echo($answer);
        echo("\n got question answer");
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
}
