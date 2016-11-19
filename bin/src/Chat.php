<?php

namespace Chat;


use Chat\HubClient\HubClientConnection;
use Chat\MySQLC\MySQLC;
use SplObjectStorage;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface
{

    
    private $HubClient;
    private $sql;
    
    /* Chat Constructor */
    public function __construct()
    {
        $this->HubClinet = new SplObjectStorage;
        $this->sql = new MySQLC();
        
        
    }

    /**
     * Called when a connection is opened$client->getName()
     *
     * @param ConnectionInterface $conn
     * @return void
     */
    public function onOpen(ConnectionInterface $conn)
    {
        echo "Connected to a client\n";
    }

    /**
     * Called when a message is sent through the socket
     *
     * @param ConnectionInterface $conn
     * @param string              $msg
     * @return voidib game
     */
    public function onMessage(ConnectionInterface $conn, $msg)
    {
        
        // Parse the json
        $data = $this->parseMessage($msg);
        echo "\n\n onMessage action: " . $data ->action . "\n";

        
        
        
        //command for client
        if($data->action == 'questionAnswer' || $data->action == 'finalAnswer' || $data->action == 'setServer'){
            echo "client \n";
            
            if($data->action == "setServer"){
                $room = $this->findServer($data->id);
                $room->addClient($conn, $data->userName);
            }
            else if($data->action == 'questionAnswer'){
                $room = $this->findServer($data->id);
                $room->receiveQuestionAnswer($data->questionAnswer, $conn);
            }
            else if($data->action == 'finalAnswer'){
                $room = $this->findServer($data->id);
                $room->receivedFinalAnswer($data->finalAnswer, $conn);
            }

        }
        //command for server
        else{
            echo "ServerClient \n";
            
            if($data->action == "createServerGetroomkey"){
                $this->createServer($conn);
            }

            else if($data->action == 'startGame'){
                $questionAnswer = $this->sql->getQuestionAndAnswer();
                $room = $this->findServerHub($conn);
                $room->sendQuestionAndAnswer($questionAnswer[0], $questionAnswer[1]);
            }
        }
        
        


    
    }

    
    /**
     * Parse raw string data
     *
     * @param string $msg
   sendInitNames  * @return stdClass
     */
    private function parseMessage($msg)
    {
        return json_decode($msg);
    }

    /**
     * Called when a connection is closed
     *
     * @param ConnectionInterface $conn
     * @return void
     */
    
    /*need to fix - currently noCLose funciton*/
    public function onClose(ConnectionInterface $conn)
    {
        echo "somebody left";
        /*going to have to find all the hubs and do some fun stuff*/
        //$this->repository->removeClient($conn);
    }

    /**
    This function is called by the web hubClient user interface
     * Called when an error occurs on a connection
     *
     * @param ConnectionInterface $conn
     * @param Exception           $e
     * @return void
     */
    
    
    /*need to fix - currently no on error handling*/
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "The following error occured: " . $e->getMessage();

        //$client = $this->repository->getClientByConnection($conn);

        // We want to fully close the connection
        /*
        if ($client !== null)
        {
            $client->getConnection()->close();
            $this->repository->removeClient($conn);
        }
        */
    }
    
    /*
    for: creates a server from a standard connection
    */
    private function createServer(ConnectionInterface $conn){
        $this->HubClinet->attach(new HubClientConnection($conn));
    }
    
    /*
    for: find the your specific server by it room number
    */
    private function findServer($id){
        foreach ($this->HubClinet as $hc)
        {
            if($hc->getRoomNumber() == $id){
                return $hc;
            }
        }
    }
    
    
    /*
    for: find the your specific server by it connection
    */
    private function findServerHub(ConnectionInterface $conn){
        $connection;
        foreach ($this->HubClinet as $hc)
        {
            if($conn == $hc->getConnection())
            {
                $connection = $hc;
            }
        }
        return $connection;
    }
}
