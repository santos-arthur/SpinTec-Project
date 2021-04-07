<?php
class Produtos extends MainClass{
    
    private $tabela = 'produtos';

    function adicionar(){
        $nome = $codigoBarras = $valorUnitario = '';

        foreach($_REQUEST as $key => $value){
            $$key = $value;
        }

        $return['status'] = true;

        if($nome == ''){
            $return['status'] = false;
            $return['mensagem'] = 'Nome de produto inválido!';
        } else if($codigoBarras == '' || strlen($codigoBarras) != 10){
            $return['status'] = false;
            $return['mensagem'] = 'Código de barras inválido!';
        } else if ($valorUnitario == '' || $valorUnitario <= 0) {
            $return['status'] = false;
            $return['mensagem'] = 'Valor unitário do produto inválido!';
        }

        if($return['status']){

            $stmt = $this->DBConnection->prepare("SELECT * FROM $this->tabela WHERE codigoBarras = :codigoBarras");
            $stmt->bindParam(':codigoBarras', $codigoBarras, PDO::PARAM_STR);
            $stmt->execute();
            $numRows = $stmt->rowCount();

            if($numRows != 0){
                $return['status'] = false;
                $return['mensagem'] = 'Código de barras já cadastrado!';
            }

        }

        if($return['status']){

            $stmt = $this->DBConnection->prepare("INSERT INTO $this->tabela (nome, codigoBarras, valorUnitario) VALUES (:nome, :codigoBarras, :valorUnitario)");
            $stmt->bindParam(':nome',  $nome,  PDO::PARAM_STR);
            $stmt->bindParam(':codigoBarras', $codigoBarras, PDO::PARAM_STR);
            $stmt->bindParam(':valorUnitario', $valorUnitario, PDO::PARAM_STR);

            try{
                $stmt->execute();
                $return['mensagem'] = 'Produto adicionado com sucesso!';
            }
            catch (PDOException $e){
                $now = date('Y/m/d - H:i:s');
                $return['status'] = false;
                $return['mensagem'] = 'Erro ao inserir produto ao banco de dados!';
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
            $return['mensagem'] = 'Erro ao pesquisar produtos no banco de dados!';
            $return['dataHora'] = $now;
            file_put_contents($this->fileLogsAPI, "DATA E HORÁRIO : $now \nERRO: $e \n\n\n\n", FILE_APPEND);
        }

        return $return;       

    }

    function alterar(){
        
        $nome = $codigoBarras = $valorUnitario = $ativo = '';
        
        foreach($_REQUEST as $key => $value){
            $$key = $value;
        }

        $return['status'] = true;

        if($nome == ''){
            $return['status'] = false;
            $return['mensagem'] = 'Nome de usuario inválido!';
        } else if($codigoBarras == '' || strlen($codigoBarras) != 10){
            $return['status'] = false;
            $return['mensagem'] = 'Código de barras inválido!';
        } else if ($valorUnitario == '' || $valorUnitario <= 0 || !is_numeric($valorUnitario)) {
            $return['status'] = false;
            $return['mensagem'] = 'Valor unitário do produto inválido!';
        } else if($id == '' || $id <= 0){
            $return['status'] = false;
            $return['mensagem'] = 'ID de produto inválido!';
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
                $return['mensagem'] = 'Produto inexistente na base de dados!';
            }

        }

        if($return['status']){

            $stmt = $this->DBConnection->prepare("SELECT * FROM $this->tabela WHERE codigoBarras = :codigoBarras AND id != :id");
            $stmt->bindParam(':codigoBarras', $codigoBarras, PDO::PARAM_STR);
            $stmt->bindParam(':id',    $id,    PDO::PARAM_INT);
            $stmt->execute();
            $numRows = $stmt->rowCount();

            if($numRows != 0){
                $return['status'] = false;
                $return['mensagem'] = 'Código de Barras já cadastrado!';
            }

        }

        if($return['status']){

            $stmt = $this->DBConnection->prepare("UPDATE $this->tabela SET nome = :nome, codigoBarras = :codigoBarras, valorUnitario = :valorUnitario, ativo = :ativo WHERE id = :id");

            $valorUnitario = (double) number_format($valorUnitario, 2, '.', '');

            $stmt->bindParam(':nome',           $nome,          PDO::PARAM_STR);
            $stmt->bindParam(':codigoBarras',   $codigoBarras,  PDO::PARAM_STR);
            $stmt->bindParam(':valorUnitario',  $valorUnitario, PDO::PARAM_STR);
            $stmt->bindParam(':ativo',          $ativo,         PDO::PARAM_INT);
            $stmt->bindParam(':id',             $id,            PDO::PARAM_INT);

            try{
                $stmt->execute();
                $return['mensagem'] = 'Produto alterado com sucesso!';
            }
            catch (PDOException $e){
                $now = date('Y/m/d - H:i:s');
                $return['status'] = false;
                $return['mensagem'] = 'Erro ao editar produto ao banco de dados!';
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
                    $return['mensagem'] = 'Produto(s) removido(s) com sucesso!';
                }
                catch (PDOException $e){
                    $now = date('Y/m/d - H:i:s');
                    $return['status'] = false;
                    $return['mensagem'] = 'Erro ao remover produto(s) ao banco de dados!';
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
                    $link = implode('/', array(publicLink,'produtos','alterar',$row['id']));
                    return "<a href='$link'>$d</a>";
                }
            ),
            array(
                'db'        => 'nome',
                'dt'        => 'produto',
                'formatter' => function( $d, $row ) {
                    $link = implode('/', array(publicLink,'produtos','alterar',$row['id']));
                    return "<a href='$link'>$d</a>";
                }
            ),
            array(
                'db'        => 'codigoBarras',
                'dt'        => 'codigoBarras',
                'formatter' => function( $d, $row ) {
                    $link = implode('/', array(publicLink,'produtos','alterar',$row['id']));
                    return "<a href='$link'>$d</a>";
                }
            ),
            array(
                'db'        => 'valorUnitario',
                'dt'        => 'valorUnitario',
                'formatter' => function( $d, $row ) {
                    $d = "R$ ".number_format($d, 2, ',', '');
                    $link = implode('/', array(publicLink,'produtos','alterar',$row['id']));
                    return "<a href='$link'>$d</a>";
                }
            ),
            array(
                'db'        => 'ativo',
                'dt'        => 'ativo',
                'formatter' => function( $d, $row ) {
                    if($d == 1) $d = "Sim";
                    else $d=  "Não";
                    $link = implode('/', array(publicLink,'produtos','alterar',$row['id']));
                    return "<a href='$link'>$d</a>";
                }
            )
        );
        
        die (json_encode(
            SSP::simple( $_REQUEST, $this->dadosSQL, $this->tabela, $primaryKey, $colunas )
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
                $array[0]['valorUnitario'] = number_format($array[0]['valorUnitario'], 2, ',', '');
                $return['dados'] =  $array[0];
            }
        }
        catch (PDOException $e){
            $now = date('Y/m/d - H:i:s');
            $return['status'] = false;
            $return['mensagem'] = 'Erro ao pesquisar produto no banco de dados!';
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
            
            foreach($arrayRetorno as $produto){
                $id            = $produto['id'];
                $nome          = $produto['nome'];
                $codigoBarras  = $produto['codigoBarras'];
                $valorUnitario = $produto['valorUnitario'];
            
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

                $return['dados'][] =  "<option value='$id' $selected>$nome - Código: $codigoBarras - R$ ".number_format($valorUnitario,2,',','')."</option>";
            }
        }
        catch (PDOException $e){
            $now = date('Y/m/d - H:i:s');
            $return['status'] = false;
            $return['mensagem'] = 'Erro ao pesquisar cliente no banco de dados!';
            $return['dataHora'] = $now;
            file_put_contents($this->fileLogsAPI, "DATA E HORÁRIO : $now \nERRO: $e \n\n\n\n", FILE_APPEND);
        }

        return $return;       

    }
}
?>