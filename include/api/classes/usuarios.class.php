<?php
class Usuarios extends MainClass{

    private $tabela = 'usuarios';

    function adicionar(){
        $email = $nome = $senha = $admin = $ativo = '';

        foreach($_REQUEST as $key => $value){
            $$key = $value;
        }

        $return['status'] = true;

        if($senha == ''){
            $return['status'] = false;
            $return['mensagem'] = 'Senha inválida!';
        } else if(strlen($senha) < 8){
            $return['status'] = false;
            $return['mensagem'] = 'A senha precisa ter pelo menos 8 caracteres!';
        } else if($nome == ''){
            $return['status'] = false;
            $return['mensagem'] = 'Nome de usuário inválido!';
        } else if($email == '' || !$this->validaEmail($email)){
            $return['status'] = false;
            $return['mensagem'] = 'Email inválido!';
        } 

        if($return['status']){

            $stmt = $this->DBConnection->prepare("SELECT * FROM $this->tabela WHERE email = :email");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            $numRows = $stmt->rowCount();

            if($numRows != 0){
                $return['status'] = false;
                $return['mensagem'] = 'Email já cadastrado!';
            }

        }

        if($return['status']){

            $senha = $this->hashSenha($senha);

            $stmt = $this->DBConnection->prepare("INSERT INTO $this->tabela (nome, email, senha, ativo, admin) VALUES (:nome, :email, :senha, :ativo, :admin)");
            $stmt->bindParam(':nome',  $nome,  PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':senha', $senha, PDO::PARAM_STR);
            $stmt->bindParam(':ativo', $ativo, PDO::PARAM_INT);
            $stmt->bindParam(':admin', $admin, PDO::PARAM_INT);

            try{
                $stmt->execute();
                $return['mensagem'] = 'Usuário adicionado com sucesso!';
            }
            catch (PDOException $e){
                $now = date('Y/m/d - H:i:s');
                $return['status'] = false;
                $return['mensagem'] = 'Erro ao inserir usuário ao banco de dados!';
                $return['dataHora'] = $now;
                file_put_contents($this->fileLogsAPI, "DATA E HORÁRIO : $now \nERRO: $e \n\n\n\n", FILE_APPEND);
            }

        }
        
        return $return;

    }

    function listar(){
        
        $stmt = $this->DBConnection->prepare("SELECT id, nome, email, ativo FROM $this->tabela");

        try{
            $stmt->execute();
            $return['status'] = true;
            $return['dados'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e){
            $now = date('Y/m/d - H:i:s');
            $return['status'] = false;
            $return['mensagem'] = 'Erro ao pesquisar usuários no banco de dados!';
            $return['dataHora'] = $now;
            file_put_contents($this->fileLogsAPI, "DATA E HORÁRIO : $now \nERRO: $e \n\n\n\n", FILE_APPEND);
        }

        return $return;       

    }

    function alterar(){

        $email = $nome = $senha = $id = $ativo = $admin = '';
        
        foreach($_REQUEST as $key => $value){
            $$key = $value;
        }
        $return['status'] = true;

        if($nome == ''){
            $return['status'] = false;
            $return['mensagem'] = 'Nome de usuario inválido!';
        } else if($email == '' || !$this->validaEmail($email)){
            $return['status'] = false;
            $return['mensagem'] = 'Email inválido!';
        } else if ($senha != '' && strlen($senha) < 8){
            $return['status'] = false;
            $return['mensagem'] = 'Senha inválida!';
        } else if($id == '' || $id <= 0){
            $return['status'] = false;
            $return['mensagem'] = 'ID de usuário inválido!';
        } 
        if($ativo == ''){
            $ativo = 1;
        }
        
        if($return['status']){
            
            $stmt = $this->DBConnection->prepare("SELECT * FROM $this->tabela WHERE id = :id");
            $stmt->bindParam(':id',    $id,    PDO::PARAM_INT);
            $stmt->execute();
            $numRows = $stmt->rowCount();

            if($numRows != 1){
                $return['status'] = false;
                $return['mensagem'] = 'Usuario inexistente na base de dados!';
            }

        }

        if($return['status']){

            $stmt = $this->DBConnection->prepare("SELECT * FROM $this->tabela WHERE email = :email AND id != :id");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':id',    $id,    PDO::PARAM_INT);
            $stmt->execute();
            $numRows = $stmt->rowCount();

            if($numRows != 0){
                $return['status'] = false;
                $return['mensagem'] = 'Email já cadastrado!';
            }

        }

        if($return['status']){
            if($senha == ''){
                $stmt = $this->DBConnection->prepare("UPDATE $this->tabela SET nome = :nome, email = :email, ativo = :ativo, admin = :admin WHERE id = :id");
            }
            else{
                $stmt = $this->DBConnection->prepare("UPDATE $this->tabela SET nome = :nome, email = :email, senha = :senha, ativo = :ativo, admin = :admin WHERE id = :id");
                $senha = $this->hashSenha($senha);
                $stmt->bindParam(':senha', $senha, PDO::PARAM_STR);
            }
            $stmt->bindParam(':nome',  $nome,  PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':ativo', $ativo, PDO::PARAM_INT);
            $stmt->bindParam(':admin', $admin, PDO::PARAM_INT);
            $stmt->bindParam(':id',    $id,    PDO::PARAM_INT);

            try{
                $stmt->execute();
                $return['mensagem'] = 'Usuário alterado com sucesso!';
            }
            catch (PDOException $e){
                $now = date('Y/m/d - H:i:s');
                $return['status'] = false;
                $return['mensagem'] = 'Erro ao editar usuário ao banco de dados!';
                $return['dataHora'] = $now;
                file_put_contents($this->fileLogsAPI, "DATA E HORÁRIO : $now \nERRO: $e \n\n\n\n", FILE_APPEND);
            }

        }
        
        return $return;

    }
    
    function remover(){
        $return['status'] = true;

        if(!isset($_REQUEST['id']) || empty($_REQUEST['id'])){
            $return['status'] = false;
            $return['mensagem'] = "ID Inválido na requisição!";
        }
        else{
            $id = $_REQUEST['id'];
            if(is_array($id)){
                foreach($id as $value){
                    if($value == '' || $value <= 0){
                        $return['status'] = false;
                        $return['mensagem'] = "ID Inválido na requisição! (ID: $value)";
                    }
                }
            }
            else{
                if($id == '' || $id <= 0){
                    $return['status'] = false;
                    $return['mensagem'] = "ID Inválido na requisição! (ID: $id)";
                }
            }

            if($return['status']){

                $id = implode(', ', (array) $id);

                $stmt = $this->DBConnection->prepare("DELETE FROM $this->tabela WHERE id IN ($id)");

                try{
                    $stmt->execute();
                    $return['mensagem'] = 'Usuário(s) removido(s) com sucesso!';
                }
                catch (PDOException $e){
                    $now = date('Y/m/d - H:i:s');
                    $return['status'] = false;
                    $return['mensagem'] = 'Erro ao remover usuário(s) ao banco de dados!';
                    $return['dataHora'] = $now;
                    file_put_contents($this->fileLogsAPI, "DATA E HORÁRIO : $now \nERRO: $e \n\n\n\n", FILE_APPEND);
                }

            }
        }

        return $return;
    }
    
    function listaDataTables(){
        $primaryKey = 'id';
        
        if(RoutingSystem::isAdmin()){
            $colunas = array(
                array(
                    'db'        => 'id',
                    'dt'        => 'select-checkbox',
                    'formatter' =>  function( $d, $row ) {
                        return "<input type='checkbox' class='checkboxRemover' value='$d' name='checkboxRemover'>"; 
                    }
                ),
                array(
                    'db'        => 'id',
                    'dt'        => 'id',
                    'formatter' => function( $d, $row ) {
                        $d = str_pad($d, 3, '0', STR_PAD_LEFT); 
                        $link = implode('/', array(publicLink,'usuarios','alterar',$row['id']));
                        return "<a href='$link'>$d</a>";
                    }
                ),
                array(
                    'db'        => 'nome',
                    'dt'        => 'nome',
                    'formatter' => function( $d, $row ) {
                        $link = implode('/', array(publicLink,'usuarios','alterar',$row['id']));
                        return "<a href='$link'>$d</a>";
                    }
                ),
                array(
                    'db'        => 'email',
                    'dt'        => 'email',
                    'formatter' => function( $d, $row ) {
                        if($d == '') $d = 'Sem email';
                        $link = implode('/', array(publicLink,'usuarios','alterar',$row['id']));
                        return "<a href='$link'>$d</a>";
                    }
                ),
                array(
                    'db'        => 'ativo',
                    'dt'        => 'ativo',
                    'formatter' => function( $d, $row ) {
                        if($d == 1) $d = "Ativo";
                        else $d = "Desativado";
                        $link = implode('/', array(publicLink,'usuarios','alterar',$row['id']));
                        return "<a href='$link'>$d</a>";
                    }
                ),
                array(
                    'db'        => 'admin',
                    'dt'        => 'admin',
                    'formatter' => function( $d, $row ) {
                        if($d == 1) $d = "Sim";
                        else $d = "Não";
                        $link = implode('/', array(publicLink,'usuarios','alterar',$row['id']));
                        return "<a href='$link'>$d</a>";
                    }
                )
            );
        }
        else{
            
            $colunas = array(
                array(
                    'db'        => 'id',
                    'dt'        => 'id',
                    'formatter' => function( $d, $row ) {
                        $d = str_pad($d, 3, '0', STR_PAD_LEFT); 
                        $link = implode('/', array(publicLink,'usuarios','alterar',$row['id']));
                        return "<a href='$link'>$d</a>";
                    }
                ),
                array(
                    'db'        => 'nome',
                    'dt'        => 'nome',
                    'formatter' => function( $d, $row ) {
                        $link = implode('/', array(publicLink,'usuarios','alterar',$row['id']));
                        return "<a href='$link'>$d</a>";
                    }
                ),
                array(
                    'db'        => 'email',
                    'dt'        => 'email',
                    'formatter' => function( $d, $row ) {
                        if($d == '') $d = 'Sem email';
                        $link = implode('/', array(publicLink,'usuarios','alterar',$row['id']));
                        return "<a href='$link'>$d</a>";
                    }
                ),
                array(
                    'db'        => 'ativo',
                    'dt'        => 'ativo',
                    'formatter' => function( $d, $row ) {
                        if($d == 1) $d = "Ativo";
                        else $d = "Desativado";
                        $link = implode('/', array(publicLink,'usuarios','alterar',$row['id']));
                        return "<a href='$link'>$d</a>";
                    }
                )
            );
        }
        
        die (json_encode(
            SSP::simple( $_REQUEST, $this->dadosSQL, $this->tabela, $primaryKey, $colunas )
        ));
    }
    
    function dados(){
        
        $id = $_REQUEST['id'];

        $stmt = $this->DBConnection->prepare("SELECT id, nome, email, ativo, admin FROM $this->tabela WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        try{
            $stmt->execute();
            if($stmt->rowCount() != 1){                
                $return['status'] = false;
                $return['mensagem'] = 'Cliente não encontrado!';
            }else{
                $return['status'] = true;
                $array = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $return['dados'] =  $array[0];
            }
        }
        catch (PDOException $e){
            $now = date('Y/m/d - H:i:s');
            $return['status'] = false;
            $return['mensagem'] = 'Erro ao pesquisar usuario no banco de dados!';
            $return['dataHora'] = $now;
            file_put_contents($this->fileLogsAPI, "DATA E HORÁRIO : $now \nERRO: $e \n\n\n\n", FILE_APPEND);
        }

        return $return;       
    }
    
    function selectAtivos(){
        
        $stmt = $this->DBConnection->prepare("SELECT * FROM $this->tabela WHERE ativo = 1 ORDER BY nome");
        
        try{
            $stmt->execute();
            $return['status'] = true;
            $arrayRetorno = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach($arrayRetorno as $usuario){
                $id   = $usuario['id'];
                $nome = $usuario['nome'];
                
                if(isset($_REQUEST['id'])){
                    if($id == $_REQUEST['id']){
                        $selected = 'selected';
                    }
                    else{
                        $selected = '';
                    }
                }else{
                    $selected = '';
                }

                $return['dados'][] =  "<option value='$id' $selected>$nome - Código: ".str_pad($id, 3, '0', STR_PAD_LEFT)."</option>";
            }
        }
        catch (PDOException $e){
            $now = date('Y/m/d - H:i:s');
            $return['status'] = false;
            $return['mensagem'] = 'Erro ao pesquisar usuario no banco de dados!';
            $return['dataHora'] = $now;
            file_put_contents($this->fileLogsAPI, "DATA E HORÁRIO : $now \nERRO: $e \n\n\n\n", FILE_APPEND);
        }

        return $return;       

    }
}
?>