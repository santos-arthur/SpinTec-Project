<?php
class ItensPedido extends MainClass{
    
    private $tabela = 'itens_pedido';
    private $view = 'view_itens_pedido';

    function adicionar(){

        $idPedido = $idProduto = $quantidade = $desconto = '';

        foreach($_REQUEST as $key => $value){
            $$key = $value;
        }

        $return['status'] = true;
        
        if ($idPedido == '' || $idPedido <= 0 || !is_numeric($idPedido)) {
            $return['status'] = false;
            $return['mensagem'] = 'Código de pedido inválido!';
        }
        else if ($idProduto == '' || $idProduto <= 0 || !is_numeric($idProduto)) {
            $return['status'] = false;
            $return['mensagem'] = 'Código de produto inválido!';
        }
        else if ($quantidade == '' || $quantidade <= 0 || !is_numeric($quantidade)) {
            $return['status'] = false;
            $return['mensagem'] = 'Quantidade inválida!';
        }
        else if ($desconto == '' || $desconto <= 0 || !is_numeric($desconto)) {
            $return['status'] = false;
            $return['mensagem'] = 'Valor de desconto inválido!';
        }

        if($return['status']){
            
            $stmt = $this->DBConnection->prepare("SELECT * FROM produtos WHERE id = :id");
            $stmt->bindParam(':id', $idProduto, PDO::PARAM_INT);
            $stmt->execute();
            $numRows = $stmt->rowCount();

            if($numRows != 1){
                $return['status'] = false;
                $return['mensagem'] = 'Produto inexistente na base de dados!';
            }

        }

        if($return['status']){
            
            $stmt = $this->DBConnection->prepare("SELECT * FROM produtos WHERE id = :id");
            $stmt->bindParam(':id', $idProduto, PDO::PARAM_INT);
            $stmt->execute();
            $valor = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if($desconto > ($valor['0']['valorUnitario'] * $quantidade)){
                $return['status'] = false;
                $return['mensagem'] = 'Valor do desconto não pode ser superior ao valor do item!';
            }

        }
                
        if($return['status']){
            
            $stmt = $this->DBConnection->prepare("SELECT * FROM pedidos WHERE id = :id");
            $stmt->bindParam(':id', $idPedido, PDO::PARAM_INT);
            $stmt->execute();
            $numRows = $stmt->rowCount();

            if($numRows != 1){
                $return['status'] = false;
                $return['mensagem'] = 'Pedido inexistente na base de dados!';
            }

        }

        if($return['status']){

            $stmt = $this->DBConnection->prepare("INSERT INTO $this->tabela (idPedido, idProduto, quantidade, desconto) VALUES (:idPedido, :idProduto, :quantidade, :desconto)");
            $stmt->bindParam(':idPedido',     $idPedido,     PDO::PARAM_INT);
            $stmt->bindParam(':idProduto',    $idProduto,    PDO::PARAM_INT);
            $stmt->bindParam(':quantidade',   $quantidade,   PDO::PARAM_INT);
            $stmt->bindParam(':desconto',     $desconto,     PDO::PARAM_STR);

            try{
                $stmt->execute();
                $return['mensagem'] = 'Item adicionado com sucesso!';
            }
            catch (PDOException $e){
                $now = date('Y/m/d - H:i:s');
                $return['status'] = false;
                $return['mensagem'] = 'Erro ao inserir item ao banco de dados!';
                $return['dataHora'] = $now;
                file_put_contents($this->fileLogsAPI, "DATA E HORÁRIO : $now \nERRO: $e \n\n\n\n", FILE_APPEND);
            }

        }
        
        return $return;

    }

    function listar(){
        if(isset($_REQUEST['idPedido'])){
            $stmt = $this->DBConnection->prepare("SELECT * FROM $this->tabela WHERE idPedido = :idPedido");
            $stmt->bindParam(':idPedido', $_REQUEST['idPedido'], PDO::PARAM_INT);
        }
        else{
            $stmt = $this->DBConnection->prepare("SELECT * FROM $this->tabela");
        }

        try{
            $stmt->execute();
            $return['status'] = true;
            $return['dados'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e){
            $now = date('Y/m/d - H:i:s');
            $return['status'] = false;
            $return['mensagem'] = 'Erro ao pesquisar itens de pedidos no banco de dados!';
            $return['dataHora'] = $now;
            file_put_contents($this->fileLogsAPI, "DATA E HORÁRIO : $now \nERRO: $e \n\n\n\n", FILE_APPEND);
        }

        return $return;       

    }

    function alterar(){
        
        $idPedido = $idProduto = $quantidade = $desconto = $id = '';
        
        foreach($_REQUEST as $key => $value){
            $$key = $value;
        }

        $return['status'] = true;

        
        if ($idPedido == '' || $idPedido <= 0 || !is_numeric($idPedido)) {
            $return['status'] = false;
            $return['mensagem'] = 'Código de pedido inválido!';
        }
        else if ($idProduto == '' || $idProduto <= 0 || !is_numeric($idProduto)) {
            $return['status'] = false;
            $return['mensagem'] = 'Código de produto inválido!';
        }
        else if ($quantidade == '' || $quantidade <= 0 || !is_numeric($quantidade)) {
            $return['status'] = false;
            $return['mensagem'] = 'Quantidade inválida!';
        }
        else if ($desconto == '' || $desconto <= 0 || !is_numeric($desconto)) {
            $return['status'] = false;
            $return['mensagem'] = 'Valor de desconto inválido!';
        }
        else if ($id == '' || $id <= 0 || !is_numeric($id)){
            $return['status'] = false;
            $return['mensagem'] = 'ID de pedido inválido!';
        }

        if($return['status']){
            
            $stmt = $this->DBConnection->prepare("SELECT * FROM $this->tabela WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $numRows = $stmt->rowCount();

            if($numRows != 1){
                $return['status'] = false;
                $return['mensagem'] = 'Item de pedido inexistente na base de dados!';
            }

        }

        if($return['status']){
            
            $stmt = $this->DBConnection->prepare("SELECT * FROM produtos WHERE id = :id");
            $stmt->bindParam(':id', $idProduto, PDO::PARAM_INT);
            $stmt->execute();
            $numRows = $stmt->rowCount();

            if($numRows != 1){
                $return['status'] = false;
                $return['mensagem'] = 'Produto inexistente na base de dados!';
            }

        }

        if($return['status']){
            
            $stmt = $this->DBConnection->prepare("SELECT * FROM produtos WHERE id = :id");
            $stmt->bindParam(':id', $idProduto, PDO::PARAM_INT);
            $stmt->execute();
            $valor = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if($desconto > ($valor['0']['valorUnitario'] * $quantidade)){
                $return['status'] = false;
                $return['mensagem'] = 'Valor do desconto não pode ser superior ao valor do item!';
            }

        }
                
        if($return['status']){
            
            $stmt = $this->DBConnection->prepare("SELECT * FROM pedidos WHERE id = :id");
            $stmt->bindParam(':id', $idPedido, PDO::PARAM_INT);
            $stmt->execute();
            $numRows = $stmt->rowCount();

            if($numRows != 1){
                $return['status'] = false;
                $return['mensagem'] = 'Pedido inexistente na base de dados!';
            }

        }

        if($return['status']){

            $stmt = $this->DBConnection->prepare("UPDATE $this->tabela SET idPedido = :idPedido, idProduto = :idProduto, quantidade = :quantidade, desconto = :desconto WHERE id = :id");

            $stmt->bindParam(':idPedido',     $idPedido,     PDO::PARAM_INT);
            $stmt->bindParam(':idProduto',    $idProduto,    PDO::PARAM_INT);
            $stmt->bindParam(':quantidade',   $quantidade,   PDO::PARAM_INT);
            $stmt->bindParam(':desconto',     $desconto,     PDO::PARAM_INT);
            $stmt->bindParam(':id',           $id,           PDO::PARAM_INT);

            try{
                $stmt->execute();
                $return['mensagem'] = 'Item alterado com sucesso!';
            }
            catch (PDOException $e){
                $now = date('Y/m/d - H:i:s');
                $return['status'] = false;
                $return['mensagem'] = 'Erro ao editar item ao banco de dados!';
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
                    $return['mensagem'] = 'Item(ns) removido(s) com sucesso!';
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
        
        $idPedido = $_REQUEST['idPedido'];

        $colunas = array(
            array(
                'db'        => 'id',
                'dt'        => 'select-checkbox',
                'formatter' =>  function( $d, $row ) {
                    return "<input type='checkbox' class='checkboxRemover' value='$d' name='checkboxRemover'>"; 
               }
            ),
            array(
                'db'        => 'produto',
                'dt'        => 'produto',
                'formatter' => function( $d, $row ) {
                    $link = implode('/', array(publicLink,'itensPedido','alterar',$row['id']));
                    return "<a href='$link'>$d</a>";
                }
            ),
            array(
                'db'        => 'valorUnitario',
                'dt'        => 'valorUnitario',
                'formatter' => function( $d, $row ) {
                    $d = "R$ ".number_format($d, 2, ',', '');
                    $link = implode('/', array(publicLink,'itensPedido','alterar',$row['id']));
                    return "<a href='$link'>$d</a>";
                }
            ),
            array(
                'db'        => 'quantidade',
                'dt'        => 'quantidade',
                'formatter' => function( $d, $row ) {
                    $link = implode('/', array(publicLink,'itensPedido','alterar',$row['id']));
                    return "<a href='$link'>$d</a>";
                }
            ),
            array(
                'db'        => 'desconto',
                'dt'        => 'desconto',
                'formatter' => function( $d, $row ) {
                    $d = "R$ ".number_format($d, 2, ',', '');
                    $link = implode('/', array(publicLink,'itensPedido','alterar',$row['id']));
                    return "<a href='$link'>$d</a>";
                }
            ),
            array(
                'db'        => 'valorUnitario',
                'dt'        => 'valorTotal',
                'formatter' => function( $d, $row ) {
                    $d = ($d * $row['quantidade']) - $row['desconto'];
                    $d = "R$ ".number_format($d, 2, ',', '');
                    $link = implode('/', array(publicLink,'itensPedido','alterar',$row['id']));
                    return "<a href='$link'>$d</a>";
                }
            )
        );
        
        die (json_encode(
            SSP::complex( $_REQUEST, $this->dadosSQL, $this->view, $primaryKey, $colunas, null, " idPedido = $idPedido")
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
                $array[0]['desconto'] = number_format($array[0]['desconto'], 2, ',', '');
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