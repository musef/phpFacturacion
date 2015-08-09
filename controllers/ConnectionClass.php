<?php
/**

    
    * * * * * ConnectionClass es una utilidad de conexión a DDBB*** *
    * * @Author musef v.1.0 2015-08-01
    

*/

class ConnectionClass {

    private $server="TU SERVER";
    private $database="TU DATABASE";
    private $port=3306;	// port mysql: cambiar si procede
    private $user="TU USER";
    private $pass="TU PASSWORD";

        public function __construct() {
                //return getConnection();
        }

        public function getConnection() {
            $server="localhost";
            return (new mysqli($this->getServer(),$this->getUser(),$this->getPass(),$this->getDatabase(),$this->getPort()));
	    }

        public function getServer() {
            return $this->server;
        }

        public function getUser() {
            return $this->user;
        }

        public function getPass() {
            return $this->pass;
        }

        public function getDatabase() {
            return $this->database;
        }

        public function getPort() {
            return $this->port;
        }
}

