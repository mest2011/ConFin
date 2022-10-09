<?php
  include_once 'model/crud.php';

  $result = new Read("SELECT * FROM tb_usuario;");

    foreach ($result as $key => $row){
      echo "id: " . $row['idUsuario'] . "<br>Nome: " . $row['usuario'] . "<br>";
    }
  
    // if ($result->num_rows > 0) {
    //   while($row = $result->fetch_assoc()){
    //   echo "id: " . $row['idUsuario'] . "<br>Nome: " . $row['usuario'] . "<br>";
    //   }
    // } else {
    //   echo "Zero resultados!";
    // }
?>