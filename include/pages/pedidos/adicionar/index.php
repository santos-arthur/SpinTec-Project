<?php
    $_SESSION['page'] = 'pedido';
    $this->showNavBar();
?>
<main class='main'>
    
<form class='container containerForm col-md-6 col-sm-12'>
        <div class='row'>
            <div class='col-sm-12 center formTitle'>
                Cadastro de pedido
            </div>
        </div>
        <div class='row'>
            <div class='col-sm-12 col-md-3 center'>
                <label class="formLabel" for="inputCliente">Cliente:</label>
            </div>
            <div class='col-sm-12 col-md-9 center'>
                <select class="form-control" name="inputCliente" id="inputCliente">

                </select>
            </div>
        </div>

        <?php if(RoutingSystem::isAdmin()){?>
        <div class='row'>
            <div class='col-sm-12 col-md-3 center'>
                <label class="formLabel" for="inputUsuario">Usuário:</label>
            </div>
            <div class='col-sm-12 col-md-9 center'>
                <select class="form-control" name="inputUsuario" id="inputUsuario">

                </select>
            </div>
        </div>
        <?php } 
        else{
            $id = $_SESSION['userId'];
            echo "<input type='hidden' value='$id' id='inputUsuario'>";
        }?>
        
        <div class='row'>
            <div class='col-12 center'>
                <div class="btn btn-success col-sm-12 col-md-6" id="salvar" onclick="salvar()">Salvar</div>
            </div>
        </div>
    </form>
</main>

<script>
$(document).ready(function(){
    $.ajax({
        type: "POST",
        url: '<?=implode('/', array(publicLink,'api','clientes','selectAtivos'))?>',
        success: function(data){
            if(data.status == true){
                $('#inputCliente').html(data.dados);
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
    <?php if(RoutingSystem::isAdmin()){?>
    $.ajax({
        type: "POST",
        url: '<?=implode('/', array(publicLink,'api','usuarios','selectAtivos'))?>',
        success: function(data){
            if(data.status == true){
                $('#inputUsuario').html(data.dados);
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
    <?php }?>
});
function salvar(){
    var cliente  = $("#inputCliente").val().toString();
    var usuario  = $("#inputUsuario").val().toString();
    $.ajax({
        type: "POST",
        url: '<?=implode('/', array(publicLink,'api','pedidos','adicionar'))?>',
        data: {
            'idCliente'  : cliente,
            'idUsuario'  : usuario
        },
        success: function(data){
            if(data.status == true){
                Swal.fire({
                    title: data.mensagem,
                    icon: 'success',
                    confirmButtonText: 'OK!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.href = '<?=implode('/', array(publicLink, 'pedidos'))?>';
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