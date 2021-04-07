<?php
class Pedidos extends MainClass{

    private $tabela = 'pedidos';
    private $view   = 'viewpedidos';

    private $tiposSituacao = array(0,1,2);

    function adicionar(){
        $idUsuario = $idCliente = '';

        foreach($_REQUEST as $key => $value){
            $$key = $value;
        }

        $return['status'] = true;
        
        if ($idUsuario == '' || $idUsuario <= 0) {
            $return['status'] = false;
            $return['mensagem'] = 'Código de usuário inválido!';
        } else if ($idCliente == '' || $idCliente <= 0) {
            $return['status'] = false;
            $return['mensagem'] = 'Código de cliente inválido!';
        }

        
        if($return['status']){
            
            $stmt = $this->DBConnection->prepare("SELECT * FROM usuarios WHERE id = :id");
            $stmt->bindParam(':id', $idUsuario, PDO::PARAM_INT);
            $stmt->execute();
            $numRows = $stmt->rowCount();

            if($numRows != 1){
                $return['status'] = false;
                $return['mensagem'] = 'Usuário inexistente na base de dados!';
            }

        }
        
        if($return['status']){
            
            $stmt = $this->DBConnection->prepare("SELECT * FROM clientes WHERE id = :id");
            $stmt->bindParam(':id', $idCliente, PDO::PARAM_INT);
            $stmt->execute();
            $numRows = $stmt->rowCount();

            if($numRows != 1){
                $return['status'] = false;
                $return['mensagem'] = 'Cliente inexistente na base de dados!';
            }

        }

        if($return['status']){

            $stmt = $this->DBConnection->prepare("INSERT INTO $this->tabela (idCliente, dataPedido, idUsuario) VALUES (:idCliente, NOW(), :idUsuario)");
            $stmt->bindParam(':idCliente', $idCliente, PDO::PARAM_INT);
            $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);

            try{
                $stmt->execute();
                $return['mensagem'] = 'Pedido adicionado com sucesso!';
            }
            catch (PDOException $e){
                $now = date('Y/m/d - H:i:s');
                $return['status'] = false;
                $return['mensagem'] = 'Erro ao inserir pedido ao banco de dados!';
                $return['dataHora'] = $now;
                file_put_contents($this->fileLogsAPI, "DATA E HORÁRIO : $now \nERRO: $e \n\n\n\n", FILE_APPEND);
            }

        }
        
        return $return;

    }

    function listar(){
        
        $stmt = $this->DBConnection->prepare("SELECT * FROM $this->tabela");

        try{
            $stmt->execute();
            $return['status'] = true;
            $return['dados'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e){
            $now = date('Y/m/d - H:i:s');
            $return['status'] = false;
            $return['mensagem'] = 'Erro ao pesquisar pedidos no banco de dados!';
            $return['dataHora'] = $now;
            file_put_contents($this->fileLogsAPI, "DATA E HORÁRIO : $now \nERRO: $e \n\n\n\n", FILE_APPEND);
        }

        return $return;       

    }

    function alterar(){
        
        $idUsuario = $idCliente = $situacao = $id = '';
        
        foreach($_REQUEST as $key => $value){
            $$key = $value;
        }

        $return['status'] = true;

        if ($idUsuario == '' || $idUsuario <= 0) {
            $return['status'] = false;
            $return['mensagem'] = 'Código de usuário inválido!';
        } else if ($idCliente == '' || $idCliente <= 0) {
            $return['status'] = false;
            $return['mensagem'] = 'Código de cliente inválido!';
        } else if (!in_array($situacao, $this->tiposSituacao)) {
            $return['status'] = false;
            $return['mensagem'] = 'Tipo de situação inválida!';
        } else if($id == '' || $id <= 0){
            $return['status'] = false;
            $return['mensagem'] = 'ID de pedido inválido!';
        } 
        
        if($return['status']){
            
            $stmt = $this->DBConnection->prepare("SELECT * FROM $this->tabela WHERE id = :id");
            $stmt->bindParam(':id',    $id,    PDO::PARAM_INT);
            $stmt->execute();
            $numRows = $stmt->rowCount();

            if($numRows != 1){
                $return['status'] = false;
                $return['mensagem'] = 'ID inexistente na base de dados!';
            }

        }

        if($return['status']){

            $stmt = $this->DBConnection->prepare("UPDATE $this->tabela SET idCliente = :idCliente, idUsuario = :idUsuario, situacao = :situacao WHERE id = :id");

            $stmt->bindParam(':idCliente', $idCliente, PDO::PARAM_STR);
            $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_STR);
            $stmt->bindParam(':situacao',  $situacao,  PDO::PARAM_INT);
            $stmt->bindParam(':id',        $id,        PDO::PARAM_INT);

            try{
                $stmt->execute();
                $return['mensagem'] = 'Pedido alterado com sucesso!';
            }
            catch (PDOException $e){
                $now = date('Y/m/d - H:i:s');
                $return['status'] = false;
                $return['mensagem'] = 'Erro ao editar pedido ao banco de dados!';
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
                    $return['mensagem'] = 'Pedido(s) removido(s) com sucesso!';
                }
                catch (PDOException $e){
                    $now = date('Y/m/d - H:i:s');
                    $return['status'] = false;
                    $return['mensagem'] = 'Erro ao remover pedido(s) ao banco de dados!';
                    $return['dataHora'] = $now;
                    file_put_contents($this->fileLogsAPI, "DATA E HORÁRIO : $now \nERRO: $e \n\n\n\n", FILE_APPEND);
                }

            }
        }

        return $return;
    }
    
    function listaDataTables(){
        $primaryKey = 'id';
        
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
                     $link = implode('/', array(publicLink,'pedidos','alterar',$row['id']));
                     return "<a href='$link'>$d</a>";
                }
            ),
            array(
                'db'        => 'vendedor',
                'dt'        => 'vendedor',
                'formatter' => function( $d, $row ) {
                    $link = implode('/', array(publicLink,'pedidos','alterar',$row['id']));
                    return "<a href='$link'>$d</a>";
                }
            ),
            array(
                'db'        => 'cliente',
                'dt'        => 'cliente',
                'formatter' => function( $d, $row ) {
                    $link = implode('/', array(publicLink,'pedidos','alterar',$row['id']));
                    return "<a href='$link'>$d</a>";
                }
            ),
            array(
                'db'        => 'data',
                'dt'        => 'data',
                'formatter' => function( $d, $row ) {
                    $link = implode('/', array(publicLink,'pedidos','alterar',$row['id']));
                    return "<a href='$link'>$d</a>";
                }
            ),
            array(
                'db'        => 'valor',
                'dt'        => 'valor',
                'formatter' => function( $d, $row ) {
                    $d = "R$ ".number_format($d, 2, ',', '');
                    $link = implode('/', array(publicLink,'pedidos','alterar',$row['id']));
                    return "<a href='$link'>$d</a>";
                }
            ),
            array(
                'db'        => 'situacao',
                'dt'        => 'situacao',
                'formatter' => function( $d, $row ) {
                    if($d == 0) $d = "Em Aberto";
                    elseif($d == 1) $d = "Pago";
                    elseif($d == 2) $d = "Cancelado";
                    else $d = "Situação não prevista";
                    $link = implode('/', array(publicLink,'pedidos','alterar',$row['id']));
                    return "<a href='$link'>$d</a>";
                }
            )
        );
        
        die (json_encode(
            SSP::simple( $_REQUEST, $this->dadosSQL, $this->view, $primaryKey, $colunas )
        ));
    }
    
    function dados(){
        
        $id = $_REQUEST['id'];

        $stmt = $this->DBConnection->prepare("SELECT * FROM $this->tabela WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        try{
            $stmt->execute();
            if($stmt->rowCount() != 1){                
                $return['status'] = false;
                $return['mensagem'] = 'Produto não encontrado!';
            }else{
                $return['status'] = true;
                $array = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $return['dados'] =  $array[0];
            }
        }
        catch (PDOException $e){
            $now = date('Y/m/d - H:i:s');
            $return['status'] = false;
            $return['mensagem'] = 'Erro ao pesquisar pedido no banco de dados!';
            $return['dataHora'] = $now;
            file_put_contents($this->fileLogsAPI, "DATA E HORÁRIO : $now \nERRO: $e \n\n\n\n", FILE_APPEND);
        }

        return $return;       

    }
}
?>