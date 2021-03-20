<?php
include_once "../business/image_bus.php";

class ImageController
{
    
    function salvar($nomeCampoPost, $nomePrefixo,  $size = null)
    {
        $img = new ImageBus();
        return $img->salvar($nomeCampoPost, $nomePrefixo, $size);
    }

}



