<?php

namespace Chat\Connection;

interface ChatConnectionInterface
{
    public function getConnection();

    public function getName();

    public function setName($name);

    public function sendAllNames($names);
    
    public function sendQuestion($question);
}
