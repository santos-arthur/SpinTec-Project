<?php
    $_SESSION['page'] = 'cliente';
    $this->showNavBar();
?>
<main class='main'>
    <div class="divTabela">
        <table id="tabela" class="display tabela dt-responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th><input id="selecionarTodos" type="checkbox"></th>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>Email</th>
                        <th>Ativo</th>
                    </tr>
                </thead>
            </table>
        </main>
    </div>

<script>
$(document).ready(function() {
    table = $('#tabela').DataTable( {
        "dom": "<'row menuTabela'<'col-lg-4 col-md-12 col-sm-12 'B><'col-lg-4 col-md-12 col-sm-12 center'l><'col-lg-4 col-md-12 col-sm-12'f>>rtip",
        "buttons": [ 
            'colvis',
            {
                text: 'Remover selecionados',
                action: function(){
                    Swal.fire({
                        title: "Deseja memso apagar todos os clientes selecionados?",
                        text: 'É possível apenas desativar o cliente no cadastro',
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
                                    url: '<?=implode('/', array(publicLink,'api','clientes','remover'))?>',
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
                        });
                    } 
                });
                }                     
            },
            {
                text: 'Novo cliente',
                action: function(){
                    location.href = '<?=implode('/', array(publicLink, 'clientes','adicionar'))?>';
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
            "url": "<?=implode('/', array(publicLink,'api','clientes','listaDataTables'))?>",
            "type": "POST"
        },
        "columnDefs" : [
            { "targets": [0], "searchable": false, "orderable": false, "visible": true }
        ],
        "order":    [[ 2, "asc" ], [ 1, "desc" ]],
        "columns": [
            { "data": "select-checkbox"},
            { "data": "id" },
            { "data": "nome" },
            { "data": "cpf" },
            { "data": "email" },
            { "data": "ativo" }
        ]
    } );   

    $('#selecionarTodos').on( 'click', function () {

        $(".checkboxRemover").prop('checked',$(this).prop('checked'));

    } );
});



</script>
<?php
    
    $this->showFooter();

?>