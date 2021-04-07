<?php
    $_SESSION['page'] = 'cliente';
    $this->showNavBar();
?>
<main class='main'>
    <form class='container containerForm col-md-6 col-sm-12'>
        <div class='row'>
            <div class='col-sm-12 center formTitle'>
                Cadastro de novo cliente
            </div>
        </div>
        <div class='row'>
            <div class='col-sm-12 col-md-2 center'>
                <label class="formLabel" for="inputNome">Nome:</label>
            </div>
            <div class='col-sm-12 col-md-10 center'>
                <input class="form-control" id="inputNome" name="inputNome" type="text" placeholder="Nome">
            </div>
        </div>
        <div class='row'>
            <div class='col-sm-12 col-md-2 center'>
                <label class="formLabel" for="inputCPF">CPF:</label>
            </div>
            <div class='col-sm-12 col-md-10 center'>
                <input class="form-control" id="inputCPF" name="inputCPF" type="text" placeholder="CPF">
            </div>
        </div>
        <div class='row'>
            <div class='col-sm-12 col-md-2 center'>
                <label class="formLabel" for="inputEmail">Email:</label>
            </div>
            <div class='col-sm-12 col-md-10 center'>
                <input class="form-control" id="inputEmail" name="inputEmail" type="email" placeholder="Email">
            </div>
        </div>
        <div class='row'>
            <div class='col-sm-12 col-md-2 center'>
                <label class="formLabel">Ativo:</label>
            </div>
            <div class='col-sm-12 col-md-10 center insideMargin10px'>
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
    $('#inputCPF').mask('000.000.000-00');
});

function salvar(){
    var nome  = $("#inputNome").val();
    var cpf   = $("#inputCPF").val().toString().split('.').join('').split('-').join('');
    var email = $("#inputEmail").val();
    var ativo = $("input[name='inputAtivo']:checked").val();
    $.ajax({
        type: "POST",
        url: '<?=implode('/', array(publicLink,'api','clientes','adicionar'))?>',
        data: {
            'ativo' : ativo,
            'nome'  : nome,
            'cpf'   : cpf,
            'email' : email
        },
        success: function(data){
            if(data.status == true){
                Swal.fire({
                    title: data.mensagem,
                    icon: 'success',
                    confirmButtonText: 'OK!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.href = '<?=implode('/', array(publicLink, 'clientes'))?>';
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