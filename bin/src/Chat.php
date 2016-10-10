<?php

namespace Chat;

use Chat\Repository\ChatRepository;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface
{
    /**
     * The chat repository
     *
     * @var ChatRepository
     */
    protected $repository;

    /**
     * Chat Constructor
     */
    public function __construct()
    {
        $this->repository = new ChatRepository;
    }

    /**
     * Called when a connection is opened$client->getName()
     *
     * @param ConnectionInterface $conn
     * @return void
     */
    public function onOpen(ConnectionInterface $conn)
    {
        $this->repository->addClient($conn);
        echo "got inital connection\n";
        /*
        foreach ($this->repository->getClients() as $client)
        {

            echo $client->getName() . '\n';
            echo "going through\n";
            //$client->sendMsg($client->getName());
        }
        */
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
        // Parse the json
        $data = $this->parseMessage($msg);
        $currClient = $this->repository->getClientByConnection($conn);
        
        /*client / users */
        // Distinguish between the actions
        if ($data->action === "setname")
        {
            /*!!!!!!!!!!!!!!!!!!!!!!*/
            //probably should get a return to see if it changed or not
            $currClient->setName($data->username);
            
            $names = '';
            //first i have to get all the names
            foreach ($this->repository->getClients() as $client)
            {
                echo $client->getName();
                $names = $names . $client->getName() . ' ';
            }
            
            echo $names . PHP_EOL ;
            
            //then i have to send all the name to each person
            foreach ($this->repository->getClients() as $client)
            {
                $client->sendAllNames($names);
            }
            
            
        }
        else if ($data->action === "message")
        {
            // We don't want to handle messages if the name isn't set
            if ($currClient->getName() === "")
                return;

            foreach ($this->repository->getClients() as $client)
            {
                // Send the message to the clients if, except for the client who sent the message originally
                if ($currClient->getName() !== $client->getName())
                    $client->sendMsg($currClient->getName(), $data->msg);
            }
        }
        /*end client / users */
        
        /*server client */
        else if ($data->action === "getroomkey")
        {
           echo "got getroomkey message";
        }
        /*end server client */
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
        $this->repository->removeClient($conn);
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

        $client = $this->repository->getClientByConnection($conn);

        // We want to fully close the connection
        if ($client !== null)
        {
            $client->getConnection()->close();
            $this->repository->removeClient($conn);
        }
    }
}
