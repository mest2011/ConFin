<?php 
include_once "../controller/session_controller.php";
new Session_security();
?>
<div class="menu-lateral ">
    <a href="../view/dashboard.php">
        <h1 title="Seu aplicativo de gestão financeira!">CONFIN</h1>
    </a>

    <ul class="lista">
        <!-- <a href="../view/cad_ganho.php" title="Cadastrar Ganho">
            <li>↪ Cad. Ganho</li>
        </a> -->
        <a href="../view/ganhos.php" title="Ver todos os ganhos do mês">
            <li>↪ Ganhos</li>
        </a>
        <!-- <a href="../view/cad_gasto.php" title="Cadastrar Despesa">
            <li>↪ Cad. Despesa</li>
        </a> -->
        <a href="../view/gastos.php" title="Ver todas as despesas do mês">
            <li>↪ Despesas</li>

        <!-- <a href="../view/cad_carteira.php" title="Cadastro de carteiras">
            <li>↪ Cad. Carteiras</li>
        </a> -->

        <a href="../view/carteiras.php" title="Ver todas as carteiras cadastradas!">
            <li>↪ Carteiras</li>
        </a>

        </a>
        
        <a href="../view/extrato.php" title="Ver extrato do mês">
            <li>↪ Extrato</li>
        </a>
    </ul>

    <ul>
        <li>
            <a href="feedback.php">
                <p class="feedback">Deixe seu feedback!</p>
            </a>
        </li>
    </ul>
    <button class="btn-sair" onclick="sair()" title="Sair"></button>
</div>