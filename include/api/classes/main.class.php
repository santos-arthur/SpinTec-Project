<?php
    class MainClass{
    
        public $DBConnection;
        public $dadosSQL;
        public $fileLogsAPI;

        function __construct(){
            $this->DBConnection = new DBConnection;
            $this->DBConnection = $this->DBConnection->connection();
            $this->fileLogsAPI = implode(DIRECTORY_SEPARATOR, array(logsPath,'APIS.txt'));
            $this->dadosSQL = array(
                'user' => dbUser,
                'pass' => dbPass,
                'db'   => dbBase,
                'host' => dbHost
            );
    
        }

        function hashSenha($senha){
            return password_hash($senha, PASSWORD_ARGON2I);
        }

        function validaCPF($cpf) {
    
            // Extrai somente os números
            $cpf = preg_replace( '/[^0-9]/is', '', $cpf );
            
            // Verifica se foi informado todos os digitos corretamente
            if (strlen($cpf) != 11) {
                return false;
            }
        
            // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
            if (preg_match('/(\d)\1{10}/', $cpf)) {
                return false;
            }
        
            // Faz o calculo para validar o CPF
            for ($t = 9; $t < 11; $t++) {
                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpf[$c] * (($t + 1) - $c);
                }
                $d = ((10 * $d) % 11) % 10;
                if ($cpf[$c] != $d) {
                    return false;
                }
            }
            return true;
        
        }

        function validaEmail($email){
            //verifica se e-mail esta no formato correto de escrita
            return filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match('/@.+\./', $email);
        }
    }
?>