<?php
    $_SESSION['page'] = 'produto';
    $this->showNavBar();
?>
<main class='main'>
    <form class='container containerForm col-md-6 col-sm-12'>
        <div class='row'>
            <div class='col-sm-12 center formTitle'>
                Cadastro de produto
            </div>
        </div>
        <div class='row'>
            <div class='col-sm-12 col-md-3 center'>
                <label class="formLabel" for="inputNome">Produto:</label>
            </div>
            <div class='col-sm-12 col-md-9 center'>
                <input class="form-control" id="inputNome" name="inputNome" type="text" placeholder="Produto">
            </div>
        </div>
        <div class='row'>
            <div class='col-sm-12 col-md-3 center'>
                <label class="formLabel" for="inputCodigoBarras">Código de barras:</label>
            </div>
            <div class='col-sm-12 col-md-9 center'>
                <input class="form-control" id="inputCodigoBarras" name="inputCodigoBarras" type="number" maxlength='10' minlengh='10' placeholder="Código de barras">
            </div>
        </div>
        <div class='row'>
            <div class='col-sm-12 col-md-3 center'>
                <label class="formLabel" for="inputValor">Valor Unitário:</label>
            </div>
            <div class='col-sm-12 col-md-9 center'>
                <input class="form-control" id="inputValor" name="inputValor" type="text" placeholder="00,00">
            </div>
        </div>
        <div class='row'>
            <div class='col-sm-12 col-md-3 center'>
                <label class="formLabel">Ativo:</label>
            </div>
            <div class='col-sm-12 col-md-9 center insideMargin10px'>
                <div>
                    <input type="radio" id="ativoSim" name="inputAtivo" value="1" checked>
                    <label class="formLabel" for="ativoSim">SIM</label>
                </div>
                <div>
                    <input type="radio" id="ativoNao" name="inputAtivo" value="0">
                    <label class="formLabel" for="ativoNao">NÃO</label>
                </div>
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
    $('#inputValor').mask('999999990,00',{reverse: true});
});

function salvar(){
    var nome          = $("#inputNome").val();
    var codigoBarras  = $("#inputCodigoBarras").val().toString();
    var valorUnitario = $("#inputValor").val().replace(/[^0-9,-]+/g,"").replace(',','.').toString();
    var ativo         = $("input[name='inputAtivo']:checked").val();
    $.ajax({
        type: "POST",
        url: '<?=implode('/', array(publicLink,'api','produtos','adicionar'))?>',
        data: {
            'ativo'         : ativo,
            'nome'          : nome,
            'codigoBarras'  : codigoBarras,
            'valorUnitario' : valorUnitario
        },
        success: function(data){
            if(data.status == true){
                Swal.fire({
                    title: data.mensagem,
                    icon: 'success',
                    confirmButtonText: 'OK!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.href = '<?=implode('/', array(publicLink, 'produtos'))?>';
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