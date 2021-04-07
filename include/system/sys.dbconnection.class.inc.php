<?php

class DBConnection{

    private $instance;

    private $fileLogs;

    function __construct(){

        $this->fileLogs = implode(DIRECTORY_SEPARATOR, array(logsPath,'DBConnection.txt'));

        $dsn = 'mysql:dbname='.dbBase.';host='.dbHost;
        try{
            $this->instance = new PDO($dsn, dbUser, dbPass);
        }
        catch (PDOException $e){
            $now = date('Y/m/d - H:i:s');
            file_put_contents($this->fileLogs, "DATA E HORÁRIO : $now \nERRO: $e \n\n\n\n", FILE_APPEND);
            return false;
        }
    }

    function connection (){
        return $this->instance;
    }

    function __destruct(){
        $this->instance = null;
    }
}

?>