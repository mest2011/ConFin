<?php
include_once "../controller/session_controller.php";
new Session_security();
?>
<div class="menu-lateral">
    <div class="menu-expand-invert font-gray d-flex py-4 px-5">
        <img class="menu-icon my-auto" src="./images/menu-gray.png" alt="https://www.freepik.com/">
    </div>
    <div class="menu-expand">
        <div class="d-flex py-4 px-5 selectable-none align-items-baseline">
            <h3 class="font-white">Cofrin</h3>
            <img class="logo" src="./images/logo_porquinho.png" alt="Logo do porquinho">
        </div>
    </div>

    <ul class="d-block">
        <a class="font-gray d-flex py-4 px-5 menu-opcoes" href="../view/dashboard.php" title="Ir para dashboard">
            <img class="menu-icon my-auto menu-expand-img" src="./images/home.png" alt="home">
            <img class="menu-icon my-auto menu-expand-invert-img" src="./images/home-gray.png" alt="home">
            <li class="menu-expand ml-3 mr-5 my-auto">Home</li>
        </a>
        <a class="font-gray d-flex py-4 px-5 menu-opcoes" href="../view/ganhos.php" title="Ver todos os ganhos do mês">
            <img class="menu-icon my-auto my-auto menu-expand-img" src="./images/rendimento.png" alt="ganhos">
            <img class="menu-icon my-auto menu-expand-invert-img" src="./images/rendimento-gray.png" alt="ganhos">
            <li class="menu-expand ml-3 mr-5 my-auto">Meus ganhos</li>
        </a>
        <a class="font-gray d-flex py-4 px-5 menu-opcoes" href="../view/gastos.php" title="Ver todas as despesas do mês">
            <img class="menu-icon my-auto my-auto menu-expand-img" src="./images/calendario.png" alt="gastos">
            <img class="menu-icon my-auto menu-expand-invert-img" src="./images/calendario-gray.png" alt="gastos">
            <li class="menu-expand ml-3 mr-5 my-auto">Minhas despesas</li>
        </a>
        <a class="font-gray d-flex py-4 px-5 menu-opcoes" href="../view/extrato.php" title="Ver extrato do mês">
            <img class="menu-icon my-auto my-auto menu-expand-img" src="./images/extrato.png" alt="extrato">
            <img class="menu-icon my-auto menu-expand-invert-img" src="./images/extrato-gray.png" alt="extarto">
            <li class="menu-expand ml-3 mr-5 my-auto">Extrato</li>
        </a>
        <a class="font-gray d-flex py-4 px-5 menu-opcoes" href="../view/carteiras.php" title="Ver todas as carteiras cadastradas!">
            <img class="font-gray menu-icon my-auto my-auto menu-expand-img" src="./images/carteira.png" alt="carteira">
            <img class="font-gray menu-icon my-auto menu-expand-invert-img" src="./images/carteira-gray.png" alt="carteira">
            <li class="menu-expand ml-3 mr-5 my-auto">Carteiras</li>
        </a>
    </ul>


    <div class="menu-perfil d-flex pl-5 pr-4">
        <img class="menu-avatar rounded-circle my-auto mx-auto" src="../../uploads/<?php echo $_SESSION['foto']?>" alt="perfil" onerror="this.src='../../uploads/avatar.svg'">
        <div class="menu-expand my-auto ml-2">
            <p class="font-white my-auto"><?php echo $_SESSION['nome']?></p>
            <a href="./perfil.php"><small class="font-green my-auto">Ver perfil ></small></a>
        </div>
    </div>
</div>