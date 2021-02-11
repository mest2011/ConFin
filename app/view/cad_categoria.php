<?php
include_once "../controller/categoria_controller.php";
if (!isset($_GET['id_usuario'])) header("Location: ../../cofrin/view/login.html");

$categoria_con = new Categorias($_GET['id_usuario']);

if (isset($_GET['ajax'])) {
    if (isset($_GET['nome'], $_GET['tipo'])) {
        echo $categoria_con->cadastraCategoria($_GET['tipo'], $_GET['nome']);
    }
    if (isset($_GET['apagar'])) {
        echo $categoria_con->deletaCategoria($_GET['apagar']);
    }
    if (isset($_GET['listar'])) {
        $return = $categoria_con->listaCategoria($_GET['listar']);
        if(!is_array($return)){
            echo "<span id='lista-categoria' class='alert alert-warning'>".$return."</span>";  
        } else{
           echo "<table id='lista-categoria' class=\"table table-striped table-hover\">
                    <thead class=\" table-info bg-green\">
                        <tr>
                        <th scope=\"col\" class=\"col-10\">Nome</th>
                        <th scope=\"col\" class=\"col-2\">Apagar</th>
                        </tr>
                    </thead>
                    <tbody>";
            foreach ($return as $key => $value) {
                echo "<tr>
                        <td>{$value['nome_categoria']}</td>
                        <td onclick='deletaCategoria({$value['id_categoria']});'><a class='btn btn-danger'>X</a></td>
                    </tr>";
            }
        }

       
        
    }
}

