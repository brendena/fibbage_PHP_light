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
        echo "got inital connection\n";
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
        echo "on Message Chat";
        
        // Parse the json
        $data = $this->parseMessage($msg);
        echo $msg;
        
        /*
        Parse the data
        
        its also going to divide between server and client
        */
        
        //create server
        if($data->action == "createServerGetroomkey"){
            $this->createServer($conn);
        }
        else if($data->action == "setServer"){
            echo "\nadding user \n";
            $room = $this->findServer($data->id);
            $room->addClient($conn, $data->userName);
        }
        else if($data->action == 'startGame'){
            $question = $this->sql->getQuestion();
            print($question);
            $room = $this->findServerHub($conn);
            $room->sendQuestion($question);
            echo "\n question \n ";
        }
        else if($data->action == 'questionAnswer'){
            /*data->id data->questionAnswer*/
            $room = $this->findServer($data->id);
            print("\n this is \n ");
            print($data->questionAnswer);
            $room->receiveQuestionAnswer($data->questionAnswer, $conn);
                
            
        }
        else if(data->action == 'answerListAnswer'){
            print("received - answerListAnswer");
        }
        //$this->HubClinet.onMessage($conn, $data);

    
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
    public function onClose(ConnectionInterface $conn)
    {
        /*going to have to find all the hubs and do some fun stuff*/
        //$this->repository->removeClient($conn);
    }

    /**
     * Called when an error occurs on a connection
     *
     * @param ConnectionInterface $conn
     * @param Exception           $e
     * @return void
     */
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
    
    public function createServer(ConnectionInterface $conn){
        $this->HubClinet->attach(new HubClientConnection($conn));
    }
    
    public function findServer($id){
        echo "\n find server \n";
        foreach ($this->HubClinet as $hc)
        {
            if($hc->getRoomNumber() == $id){
                echo "found it \n";
                return $hc;
            }
        }
    }
    
    private function findServerHub(ConnectionInterface $conn){
        $connection;
        foreach ($this->HubClinet as $hc)
        {
            if($conn == $hc->getConnection())
            {
                $connection = $hc;
                print("\n got it \n");
            }
        }
        return $connection;
    }
}
