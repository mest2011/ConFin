<?php

include_once "../database/crud.php";

class CarteiraBus extends Crud{
    static function carteiras($id_usuario){
        $sql = "SELECT * FROM tb_carteiras WHERE id_usuario = {$id_usuario}";
        return parent::read($sql);
    }
}




?>