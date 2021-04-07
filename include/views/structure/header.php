<?php $page = $_SESSION['page'];?>
<header class="header navbar navbar-expand-lg navbar-dark bg-primary">
    <a class="navbar-brand" href="<?=homePage?>">Logo</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navBar" aria-controls="navBar" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navBar">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
            <!-- <li class="nav-item">
                <a class="nav-link <?= $page == 'home' ? 'active' : ''?>" href="<?=homePage?>">Home</a>
            </li> -->
            <li class="nav-item">
                <a class="nav-link <?= $page == 'pedido' ? 'active' : ''?>" href="<?=pedidoPage?>">Pedidos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $page == 'cliente' ? 'active' : ''?>" href="<?=clientsPage?>">Clientes</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $page == 'produto' ? 'active' : ''?>" href="<?=productsPage?>">Produtos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $page == 'usuarios' ? 'active' : ''?>" href="<?=usersPage?>">Usuarios</a>
            </li>
        </ul>
    </div>
</header>