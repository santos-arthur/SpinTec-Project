<?php
    $_SESSION['page'] = 'usuarios';
    $this->showNavBar();
?>
<main class='main'>
    <div class="divTabela">
        <table id="tabela" class="display tabela dt-responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <?php if($this->isAdmin()){?><th><input id="selecionarTodos" type="checkbox"></th> <?php }?>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Ativo</th>
                        <?php if($this->isAdmin()){?><th>Admin?</th> <?php }?>
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
            <?php if($this->isAdmin()){?>
            'colvis',
                {   
                text: 'Remover selecionados',
                action: function(){
                    Swal.fire({
                        title: "Deseja memso apagar todos os usuários selecionados?",
                        text: 'É possível apenas desativar o usuário no cadastro',
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
                                    url: '<?=implode('/', array(publicLink,'api','usuarios','remover'))?>',
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
                        });
                    } 
                });
                }                     
            },
            {
                text: 'Novo usuário',
                action: function(){
                    location.href = '<?=implode('/', array(publicLink, 'usuarios','adicionar'))?>';
                }                     
            }
            <?php }else{?>
                'colvis'
            <?php }?>
            
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
            "url": "<?=implode('/', array(publicLink,'api','usuarios','listaDataTables'))?>",
            "type": "POST"
        },
        "columnDefs" : [
            { "targets": [0], "searchable": false, "orderable": false, "visible": true }
        ],
        "order":    [[ 2, "asc" ], [ 1, "desc" ]],
        "columns": [
            <?php if($this->isAdmin()){?> { "data": "select-checkbox"}, <?php } ?>
            { "data": "id" },
            { "data": "nome" },
            { "data": "email" },
            { "data": "ativo" }
            <?php if($this->isAdmin()){?> ,{ "data": "admin"}, <?php } ?>
        ]
    } );   

    <?php if($this->isAdmin()){?>
    $('#selecionarTodos').on( 'click', function () {

        $(".checkboxRemover").prop('checked',$(this).prop('checked'));

    } );
    <?php }?>
});



</script>
<?php
    
    $this->showFooter();

?>