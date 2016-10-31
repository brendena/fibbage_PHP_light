<?php

namespace Chat;


use Chat\HubClient\HubClientConnection;

use SplObjectStorage;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface
{

    
    private $HubClient;
    
    
    /* Chat Constructor */
    public function __construct()
    {
        $this->HubClinet = new SplObjectStorage;
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
     * @return void
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
}
