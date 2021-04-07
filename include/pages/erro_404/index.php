<?php
    $this->showNavBar();
?>
<main class='main'>
<div class="row">
        <div class="col-md-12">
            <div class="error-template">
                <h1>
                    Oops!</h1>
                <h2>
                    Página não econtrada!</h2>
                <div class="error-details">
                    Á pagina que você estava procurando não está disponível :(
                </div>
                <div class="error-actions">
                    <a href="<?=implode(array(publicLink))?>" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-home"></span>
                        De volta ao sistema </a><a href="<?=implode(array(publicLink))?>" class="btn btn-default btn-lg"><span class="glyphicon glyphicon-envelope"></span> Contate o suporte </a>
                </div>
            </div>
        </div>
    </div>
</main>
<?php
    
    $this->showFooter();

?>