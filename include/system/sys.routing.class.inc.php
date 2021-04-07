<?php

if(session_status() !== PHP_SESSION_ACTIVE){
    session_start();
}

class RoutingSystem{

    private $DBConnection;

    function __construct(){

        $this->DBConnection = new DBConnection;

    }

    function executeRoutingSystem($url){

        $userLoggedIn = $this->verifyLogin();

        if($userLoggedIn){

            $isApi = $this->verifyApiCall($url);
            if(!$isApi){
                $this->redirect($url);
            }
            else{
                $this->getApiResponse($url);
            }
        }
        else{
            $this->redirect(loginPage);
        }

    }

    function verifyLogin(){
        
        //TODO verificar login
        if( 
            isset($_SESSION['userData']['hash']) && 
            !empty($_SESSION['userData']['hash']) && 
            isset($_SESSION['userData']['userId']) && 
            !empty($_SESSION['userData']['userId']) 
            ){

            $hash = $_SESSION['userData']['hash'];
            $userId = $_SESSION['userData']['userId'];

            $requisition = $this->DBConnection->connection()->query("SELECT * FROM sessoes WHERE hash = '$hash' AND userId = '$userId'");

            $resultNumRows = $requisition->rowCount();

            if($resultNumRows !== 1){
                unset($_SESSION);
                return false;
            }
            else{
                return true;
            }

        }
        else{
            return false;
        }

    }

    function redirect(string $location = ''){

        $location = explode("/", $location);
        if(is_numeric(end($location))){
            $_REQUEST['id'] = end($location);
            array_pop($location);
        }
        $location = implode("/", $location);

        if($location != ''){
            if(file_exists(implode(DIRECTORY_SEPARATOR, array(includePath,'pages',$location,defaultFile.defaultExtension)))){
                $url = implode(DIRECTORY_SEPARATOR, array(includePath,'pages',$location,defaultFile.defaultExtension));

                require_once(implode(DIRECTORY_SEPARATOR, array(includePath,'views','structure','headerStructure.php')));
                require_once($url);
                require_once(implode(DIRECTORY_SEPARATOR, array(includePath,'views','structure','footerStructure.php')));
            }
            else{
                header('Location: ' . publicLink . '/erro_404');
            }
        }
        else{
            header('Location: ' . publicLink . '/pedidos');
        }

    }

    function showNavBar(){
        require_once(implode(DIRECTORY_SEPARATOR, array(includePath,'views','structure','header.php')));
    }

    function showFooter(){
        require_once(implode(DIRECTORY_SEPARATOR, array(includePath,'views','structure','footer.php')));
    }

    public static function isAdmin(){
        return true;

        //TODO verificar se o user é admin
    }

    function verifyApiCall(string $url = ''){
        
        if( substr($url, 0, 4) == 'api/'){
            return true;
        }
        else{
            return false;
        }

    }

    function getApiResponse(string $url = ''){
        
        require_once(implode(DIRECTORY_SEPARATOR, array(includePath,'api',defaultFile.defaultExtension)));

    }

    function __destruct(){

    }

}
?>