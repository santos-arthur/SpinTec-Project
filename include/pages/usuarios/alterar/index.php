<?php
    $_SESSION['page'] = 'usuario';
    $this->showNavBar();
?>
<main class='main'>
    <form class='container containerForm col-md-6 col-sm-12'>
        <div class='row'>
            <div class='col-sm-12 center formTitle'>
                Cadastro de usuário
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
                <label class="formLabel" for="inputEmail">Email:</label>
            </div>
            <div class='col-sm-12 col-md-10 center'>
                <input class="form-control" id="inputEmail" name="inputEmail" type="email" placeholder="Email">
            </div>
        </div>
        <div class='row'>
            <div class='col-sm-12 col-md-2 center'>
                <label class="formLabel" for="inputSenha">Senha:</label>
            </div>
            <div class='col-sm-12 col-md-10 center'>
                <input class="form-control" id="inputSenha" name="inputSenha" type="password" placeholder="Senha">
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
            <div class='col-sm-12 col-md-2 center'>
                <label class="formLabel">Admin:</label>
            </div>
            <div class='col-sm-12 col-md-10 center insideMargin10px'>
                <div>
                    <input type="radio" id="ativoSimAdmin" name="inputAtivoAdmin" value="1">
                    <label class="formLabel" for="ativoSimAdmin">SIM</label>
                </div>
                <div>
                    <input type="radio" id="ativoNaoAdmin" name="inputAtivoAdmin" value="0" checked>
                    <label class="formLabel" for="ativoNaoAdmin">NÃO</label>
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
    $.ajax({
        type: "POST",
        url: '<?=implode('/', array(publicLink,'api','usuarios','dados'))?>',
        data: {
            'id' : '<?=$_REQUEST['id']?>'
        },
        success: function(data){
            if(data.status == true){
                $('#inputNome').val(data.dados.nome);
                $('#inputEmail').val(data.dados.email);
                $('#inputSenha').val("00000000");
                if(data.dados.ativo == 1){
                    $("#ativoSim").attr('checked', true);
                }else{
                    $("#ativoNao").attr('checked', true);
                }
                if(data.dados.admin == 1){
                    $("#ativoSimAdmin").attr('checked', true);
                }else{
                    $("#ativoNaoAdmin").attr('checked', true);
                }
            }else{
                Swal.fire({
                    title: data.mensagem,
                    text: 'Voltar a tela de usuarios?',
                    icon: 'error',
                    confirmButtonText: 'OK!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.href = '<?=implode('/', array(publicLink, 'usuarios'))?>';
                    } 
                });
            }
        }
    });
});

function salvar(){
    var nome  = $("#inputNome").val();
    var email = $("#inputEmail").val();
    var ativo = $("input[name='inputAtivo']:checked").val();
    var admin = $("input[name='inputAtivoAdmin']:checked").val();
    if($("#inputSenha").val() == "00000000"){
        var senha = "";
    }
    else{
        var senha = $("#inputSenha").val();
    }
    $.ajax({
        type: "POST",
        url: '<?=implode('/', array(publicLink,'api','usuarios','alterar'))?>',
        data: {
            'id'    : '<?=$_REQUEST['id']?>',
            'ativo' : ativo,
            'admin' : admin,
            'nome'  : nome,
            'senha' : senha,
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
                        location.href = '<?=implode('/', array(publicLink, 'usuarios'))?>';
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