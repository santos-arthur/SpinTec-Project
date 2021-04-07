<?php
    $_SESSION['page'] = 'pedido';
    $this->showNavBar();
?>
<main class='main'>
    <form class='container containerForm col-md-6 col-sm-12'>
        <div class='row'>
            <div class='col-sm-12 center formTitle'>
                Cadastro de item no pedido
            </div>
        </div>
        <input type="hidden" id="inputPedido" value=''>
        <div class='row'>
            <div class='col-sm-12 col-md-3 center'>
                <label class="formLabel" for="inputProduto">Produto:</label>
            </div>
            <div class='col-sm-12 col-md-9 center'>
                <select class="form-control" name="inputProduto" id="inputProduto">
                    
                </select>
            </div>
        </div>

        <div class='row'>
            <div class='col-sm-12 col-md-3 center'>
                <label class="formLabel" for="inputQuantidade">Quantidade:</label>
            </div>
            <div class='col-sm-12 col-md-9 center'>
                <input class="form-control" id="inputQuantidade" name="inputQuantidade" type="number" placeholder="Quantidade">
            </div>
        </div>

        <div class='row'>
            <div class='col-sm-12 col-md-3 center'>
                <label class="formLabel" for="inputDesconto">Desconto:</label>
            </div>
            <div class='col-sm-12 col-md-9 center'>
                <input class="form-control" id="inputDesconto" name="inputDesconto" type="text" placeholder="Desconto">
            </div>
        </div>

        <div class='row'>
            <div class='col-12 center'>
                <div class="btn btn-success col-sm-12 col-md-6" id="salvar" onclick="salvar()">Salvar</div>
            </div>
        </div>
    </form>
</main>

<script>
$(document).ready(function(){

    $('#inputDesconto').mask('999999990,00',{reverse: true});
    
    
    var cliente;
    var usuario;
    $.ajax({
        type: "POST",
        url: '<?=implode('/', array(publicLink,'api','itensPedido','dados'))?>',
        data: {
            'id' : <?=$_REQUEST['id'];?>
        },
        success: function(dataPedido){
            if(dataPedido.status == true){
                $.ajax({
                    type: "POST",
                    url: '<?=implode('/', array(publicLink,'api','produtos','selectAtivos'))?>',
                    data: {
                        'id' : dataPedido.dados.idProduto
                    },
                    success: function(data){
                        if(data.status == true){
                            $('#inputProduto').html(data.dados);
                        }else{
                            Swal.fire({
                                title: data.mensagem,
                                text: 'Voltar a tela de pedidos?',
                                icon: 'error',
                                confirmButtonText: 'OK!'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.href = '<?=implode('/', array(publicLink, 'pedidos'))?>';
                                } 
                            });
                        }
                    }
                });
                $("#inputQuantidade").val(dataPedido.dados.quantidade);
                $("#inputDesconto").val(dataPedido.dados.desconto);
                $("#inputPedido").val(dataPedido.dados.idPedido);
            }else{
                Swal.fire({
                    title: data.mensagem,
                    text: 'Voltar a tela de pedidos?',
                    icon: 'error',
                    confirmButtonText: 'OK!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.href = '<?=implode('/', array(publicLink, 'pedidos'))?>';
                    } 
                });
            }
        }
    });
});

function salvar(){
    var produto     = $("#inputProduto").val().toString();
    var quantidade  = $("#inputQuantidade").val().toString();
    var pedido      = $("#inputPedido").val().toString();
    var desconto    = $("#inputDesconto").val().replace(/[^0-9,-]+/g,"").replace(',','.').toString();
    $.ajax({
        type: "POST",
        url: '<?=implode('/', array(publicLink,'api','itensPedido','alterar'))?>',
        data: {
            'id'         : '<?=$_REQUEST['id']?>',
            'idPedido'   : pedido,
            'idProduto'  : produto,
            'quantidade' : quantidade,
            'desconto'   : desconto
        },
        success: function(data){
            if(data.status == true){
                Swal.fire({
                    title: data.mensagem,
                    icon: 'success',
                    confirmButtonText: 'OK!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.href = '<?=implode('/', array(publicLink, 'pedidos', 'alterar'))?>/'+pedido
                    } 
                });
            }else{
                Swal.fire({
                    title: data.mensagem,
                    icon: 'error',
                    confirmButtonText: 'OK!'
                });
            }
        }
    });
}  

</script>
<?php
    
    $this->showFooter();

?>