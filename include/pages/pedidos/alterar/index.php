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
            echo "<input type='hidden' value='' id='inputUsuario'>";
        }?>
        
        <div class='row'>
            <div class='col-sm-12 col-md-3 center'>
                <label class="formLabel" for="inputSituacao">Situacao:</label>
            </div>
            <div class='col-sm-12 col-md-9 center'>
                <select class="form-control" name="inputSituacao" id="inputSituacao">
                    <option value='0'>Em Aberto</option>
                    <option value='1'>Pago</option>
                    <option value='2'>Cancelada</option>
                </select>
            </div>
        </div>

        <div class='row'>
            <div class='col-12 center'>
                <div class="btn btn-success col-sm-12 col-md-6" id="salvar" onclick="salvar()">Salvar</div>
            </div>
        </div>
    </form>
    <div class="divTabelaPedido">
        <table id="tabela" class="display tabela dt-responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th><input id="selecionarTodos" type="checkbox"></th>
                        <th>Produto</th>
                        <th>Valor unitário</th>
                        <th>Quantidade</th>
                        <th>Desconto</th>
                        <th>Valor Total</th>
                    </tr>
                </thead>
            </table>
        </main>
    </div>
</main>

<script>
$(document).ready(function(){
    var cliente;
    var usuario;
    $.ajax({
        type: "POST",
        url: '<?=implode('/', array(publicLink,'api','pedidos','dados'))?>',
        data: {
            'id' : <?=$_REQUEST['id'];?>
        },
        success: function(dataPedido){
            if(dataPedido.status == true){
                $.ajax({
                    type: "POST",
                    url: '<?=implode('/', array(publicLink,'api','clientes','selectAtivos'))?>',
                    data: {
                        'id' : dataPedido.dados.idCliente
                    },
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
                    data: {
                        'id' : dataPedido.dados.idUsuario
                    },
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
                <?php }
                else{
                    echo '$("#inputUsuario").val(dataPedido.dados.idUsuario)';
                } ?>
                $("#inputSituacao").val(dataPedido.dados.situacao).prop('selected', true);
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

    table = $('#tabela').DataTable( {
        "dom": "<'row menuTabela'<'col-lg-4 col-md-12 col-sm-12 'B><'col-lg-4 col-md-12 col-sm-12 center'l><'col-lg-4 col-md-12 col-sm-12'f>>rtip",
        "buttons": [ 
            'colvis',
            {
                text: 'Remover selecionados',
                action: function(){
                    Swal.fire({
                        title: "Deseja memso apagar todos os itens selecionados?",
                        icon: 'warning',
                        cancelButtonText: 'Não',
                        showCancelButton: true,
                        confirmButtonText: 'Sim'
                    }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: "Tem certeza?",
                            text: 'Os dados são apagados permanentemente',
                            icon: 'warning',
                            cancelButtonText: 'Não',
                            showCancelButton: true,
                            confirmButtonText: 'Sim'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                var ids = [];
                                $(".checkboxRemover:checked").each(function(){ ids.push($(this).val()); });
                                $.ajax({
                                    type: "POST",
                                    url: '<?=implode('/', array(publicLink,'api','itensPedido','remover'))?>',
                                    data: {
                                        'id'    :  ids
                                    },
                                    success: function(data){
                                        if(data.status == true){
                                            Swal.fire({
                                                title: data.mensagem,
                                                icon: 'success',
                                                confirmButtonText: 'OK!'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    location.href = '<?=implode('/', array(publicLink, 'pedidos', 'alterar', $_REQUEST['id']))?>';
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
                        });
                    } 
                });
                }                     
            },
            {
                text: 'Novo item',
                action: function(){
                    location.href = '<?=implode('/', array(publicLink, 'itensPedido','adicionar', $_REQUEST['id']))?>';
                }                     
            }
        ],
        "language": {
            "buttons": {
                'colvis' : 'Colunas',
            },
            "search": "Pesquisar: ",   
            "lengthMenu": "Exibir _MENU_ linhas",       
            "zeroRecords": "Nenhum resultado encontrado!",
            "info": "Exibindo página _PAGE_ de _PAGES_",
            "infoEmpty": "Sem dados disponíveis",
            "infoFiltered": "(Filtrado(s) _TOTAL_ de _MAX_)",      
            "loadingRecords": "Carregando...",
            "processing":     "Processando...",    
            "paginate": {
                "first":      "Primerio",
                "last":       "Último",
                "next":       "Próximo",
                "previous":   "Anterior"
            },
            "aria": {
                "sortAscending":  ": Ordenar crescentemente pela coluna ",
                "sortDescending": ": Ordenar decrescentemente pela coluna "
            }
        },
        "lengthMenu": [ [20, 50, 100, -1], [20, 50, 100, "Todas"] ],
        "ajax": {
            "url": "<?=implode('/', array(publicLink,'api','itensPedido','listaDataTables'))?>",
            "type": "POST",
            "data": {
                "idPedido" : "<?=$_REQUEST['id']?>"
            }
        },
        "columnDefs" : [
            { "targets": [0], "searchable": false, "orderable": false, "visible": true }
        ],
        "order":    [[ 2, "asc" ], [ 4, "desc" ]],
        "columns": [
            { "data": "select-checkbox"},
            { "data": "produto" },
            { "data": "valorUnitario" },
            { "data": "quantidade" },
            { "data": "desconto" },
            { "data": "valorTotal" }
        ]
    } );   

    $('#selecionarTodos').on( 'click', function () {

        $(".checkboxRemover").prop('checked',$(this).prop('checked'));

    } );

});
function salvar(){
    var cliente  = $("#inputCliente").val().toString();
    var usuario  = $("#inputUsuario").val().toString();
    var situacao = $("#inputSituacao").val().toString();
    $.ajax({
        type: "POST",
        url: '<?=implode('/', array(publicLink,'api','pedidos','alterar'))?>',
        data: {
            'id'         : '<?=$_REQUEST['id']?>',
            'idCliente'  : cliente,
            'idUsuario'  : usuario,
            'situacao'   : situacao
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