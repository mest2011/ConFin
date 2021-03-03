<?php


class ImageBus
{
    private $hash_name;

    function salvar($nomeCampo, $nomePrefixo, $size)
    {
        $this->hash_name = hash('ripemd160', date("Y/m/d H:i:s"));
        try {
            if ($this->verificarImagem($nomeCampo)) {
                $nomeArqFoto      = $_FILES[$nomeCampo]["name"];
                $tipoArqFoto     = $_FILES[$nomeCampo]["type"];
                $tamanhoArqFoto     = $_FILES[$nomeCampo]["size"];
                $nomeTempArqFoto = $_FILES[$nomeCampo]["tmp_name"];
    
                if ($this->validaFormato($tipoArqFoto)) {
                    $imgConvert = new Simpleimage();
                    $imgConvert->load($nomeTempArqFoto);
                    if ($size <> null) {
                        $imgConvert->resizeToWidth($size);
                    }
                    //$imgConvert->crop();
                    $imgConvert->save("../../uploads/{$nomePrefixo}.arq.{$this->hash_name}.jpg");
    
                    return "{$nomePrefixo}.arq.{$this->hash_name}.jpg";
                }
            }
        } catch (\Throwable $th) {
            return false;
        }
        
        return false;
    }

    function verificarImagem($nomeCampo)
    {
        if (!isset($_FILES[$nomeCampo]["name"], $_FILES[$nomeCampo]["type"], $_FILES[$nomeCampo]["size"], $_FILES[$nomeCampo]["tmp_name"])) {
            return false;
        } else {
            return true;
        }
    }

    function validaFormato($tipoArqFoto)
    {
        if ($tipoArqFoto != "image/jpg" && $tipoArqFoto != "image/jpeg") {
            return false;
        } else {
            return true;
        }
    }
}


class SimpleImage
{

    var $image;
    var $image_type;
    var $dir_file;

    function load($filename)
    {
        $this->dir_file = $filename;
        $image_info = getimagesize($filename);
        $this->image_type = $image_info[2];
        if ($this->image_type == IMAGETYPE_JPEG) {

            $this->image = imagecreatefromjpeg($filename);
        } elseif ($this->image_type == IMAGETYPE_GIF) {

            $this->image = imagecreatefromgif($filename);
        } elseif ($this->image_type == IMAGETYPE_PNG) {

            $this->image = imagecreatefrompng($filename);
        }
    }
    function save($filename, $image_type = IMAGETYPE_JPEG, $compression = 75, $permissions = null)
    {

        if ($image_type == IMAGETYPE_JPEG) {
            imagejpeg($this->image, $filename, $compression);
        } elseif ($image_type == IMAGETYPE_GIF) {

            imagegif($this->image, $filename);
        } elseif ($image_type == IMAGETYPE_PNG) {

            imagepng($this->image, $filename);
        }
        if ($permissions != null) {

            chmod($filename, $permissions);
        }
    }
    function output($image_type = IMAGETYPE_JPEG)
    {

        if ($image_type == IMAGETYPE_JPEG) {
            imagejpeg($this->image);
        } elseif ($image_type == IMAGETYPE_GIF) {

            imagegif($this->image);
        } elseif ($image_type == IMAGETYPE_PNG) {

            imagepng($this->image);
        }
    }
    function getWidth()
    {

        return imagesx($this->image);
    }
    function crop()
    {
        $image = imagecreatefromjpeg($this->dir_file);
        $filename = $this->image;

        $thumb_width = 200;
        $thumb_height = 200;

        $width = imagesx($image);
        $height = imagesy($image);

        $original_aspect = $width / $height;
        $thumb_aspect = $thumb_width / $thumb_height;

        if ($original_aspect >= $thumb_aspect) {
            // If image is wider than thumbnail (in aspect ratio sense)
            $new_height = $thumb_height;
            $new_width = $width / ($height / $thumb_height);
        } else {
            // If the thumbnail is wider than the image
            $new_width = $thumb_width;
            $new_height = $height / ($width / $thumb_width);
        }

        $thumb = imagecreatetruecolor($thumb_width, $thumb_height);

        // Resize and crop
        imagecopyresampled(
            $thumb,
            $image,
            0 - ($new_width - $thumb_width) / 2, // Center the image horizontally
            0 - ($new_height - $thumb_height) / 2, // Center the image vertically
            0,
            0,
            $new_width,
            $new_height,
            $width,
            $height
        );
        $this->image = $thumb;
    }
    function getHeight()
    {

        return imagesy($this->image);
    }
    function resizeToHeight($height)
    {

        $ratio = $height / $this->getHeight();
        $width = $this->getWidth() * $ratio;
        $this->resize($width, $height);
    }

    function resizeToWidth($width)
    {
        $ratio = $width / $this->getWidth();
        $height = $this->getheight() * $ratio;
        $this->resize($width, $height);
    }

    function scale($scale)
    {
        $width = $this->getWidth() * $scale / 100;
        $height = $this->getheight() * $scale / 100;
        $this->resize($width, $height);
    }

    function resize($width, $height)
    {
        $new_image = imagecreatetruecolor($width, $height);
        imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
        $this->image = $new_image;
    }
}
