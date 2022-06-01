<?php
class Dbconfig {
    protected $serverName;
    protected $userName;
    protected $passCode;
    protected $dbName;

    function Dbconfig() {
        $this -> serverName = 'localhost';
        $this -> userName = 'root';
        $this -> passCode = 'BRHL@Drivar!564';
        $this -> dbName = 'drivaar_db';
    }
}
?>

