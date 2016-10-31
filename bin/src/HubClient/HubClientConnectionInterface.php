<?php

namespace Chat\HubClient;

use Ratchet\ConnectionInterface;

interface HubClientConnectionInterface
{
    public function getConnection();
    
    public function getRoomNumber();
    
    public function onMessage(ConnectionInterface $conn, $msg);
    
    public function addClient(ConnectionInterface $conn, $userName);
}
