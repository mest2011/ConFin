<?php
include_once "../business/usuario_bus.php";
include_once "../classes/Usuario.php";
include_once "../controller/session_controller.php";

if (!isset($_SESSION)) {
    session_start();
}



if (isset($_POST['funcao'])) {

    //valida sessão
    // $sessao = new Session_security("isValid");

    // if (!$sessao->validaSessao("isValid")) {
    //     print_r(json_encode("Erro : tempo de sessao excedido!"));
    //     exit();
    // }

    // if (!isset($_SESSION['id_usuario'])) {
    //     print_r(json_encode("Erro na sessao atual, entre novamente no sistema!"));
    //     exit();
    // }

    // $user_controller = new Usuarios($_SESSION['id_usuario']);

    $user_controller = new Usuarios(1);


    try {
        //Savar foto pefil
        if ($_POST['funcao'] == 'salvarfoto' and isset($_POST['id'])) {
            
            if(isset($_POST['tipo'])){
                if($_POST['tipo'] == "default"){
                    $_POST['funcao'] = 'salvar';
                    $_POST['foto'] = "../../uploads/default.svg";
                }
            }
            $hash_name = hash('ripemd160', date("Y/m/d H:i:s"));

            if (isset($_FILES["file"]["name"], $_FILES["file"]["type"], $_FILES["file"]["size"])) {
                $nomeArqFoto      = $_FILES["file"]["name"];
                $tipoArqFoto     = $_FILES["file"]["type"];
                $tamanhoArqFoto     = $_FILES["file"]["size"];
                $nomeTempArqFoto = $_FILES["file"]["tmp_name"];

                if ($tipoArqFoto != "image/jpg" && $tipoArqFoto != "image/jpeg") {
                    print_r(json_encode("Erro : Arquivo fora do formato aceito! Apenas JPG e JPEG!"));
                }

                try {
                    $imgConvert = new Simpleimage();
                    $imgConvert->load($nomeTempArqFoto);
                    $imgConvert->resizeToHeight(200);
                    $imgConvert->save("../../uploads/{$_POST['id']}arq.{$hash_name}.jpg");

                    $_POST['funcao'] = 'salvar';
                    $_POST['foto'] = "../../uploads/{$_POST['id']}arq.{$hash_name}.jpg";

                    print_r(json_encode("Foto salva com sucesso!"));
                } catch (\Throwable $th) {
                    print_r(json_encode("Erro : Erro ao salvar o arquivo!"));
                }
            }else{
                print_r(json_encode("Erro : Erro ao receber o arquivo!"));
            }
        }
        //Create and Update
        if ($_POST['funcao'] == 'salvar') {

            $user = new Usuario();

            if (isset($_POST['foto'])) $user->foto = $_POST['foto'];
            if (isset($_POST['id'])) $user->id_usuario = $_POST['id'];
            if (isset($_POST['nome'])) $user->nome = $_POST['nome'];
            if (isset($_POST['cpf'])) $user->cpf = $_POST['cpf'];
            if (isset($_POST['email'])) $user->email = $_POST['email'];
            if (isset($_POST['endereco'])) $user->endereco = $_POST['endereco'];
            if (isset($_POST['cidade'])) $user->cidade = $_POST['cidade'];
            if (isset($_POST['pais'])) $user->pais = $_POST['pais'];
            if (isset($_POST['datanascimento'])) $user->datanascimento = $_POST['datanascimento'];
            if (isset($_POST['usuario'])) $user->usuario = $_POST['usuario'];
            if (isset($_POST['senha'])) $user->senha = $_POST['senha'];

            print_r(json_encode($user_controller->salvarUsuario($user)));
            exit();
        }
        //Read
        if ($_POST['funcao'] == 'listar') {
            print_r(json_encode($user_controller->buscarUsuarios()));
            exit();
        }
        //Delete
        if ($_POST['funcao'] == 'excluir' and isset($_POST['id'])) {
            print_r(json_encode($user_controller->deletarUsuario($_POST['id'])));
            exit();
        }
    } catch (\Throwable $th) {
        print_r(json_encode("Erro : Erro no processamento da solicitação!"));
        exit();
    }
}


class Usuarios
{
    private $_id_usuario;

    function __construct($id_usuario)
    {
        $this->_id_usuario = $id_usuario;
    }

    function salvarUsuario($obj_ganho)
    {
        return UsuarioBus::createUsuario($this->_id_usuario, $obj_ganho);
    }

    function buscarUsuarios($id_usuario = 0)
    {
        if ($id_usuario == 0) $id_usuario = $this->_id_usuario;
        return UsuarioBus::readUsuario($id_usuario);
    }

    function deletarUsuario($id_usuario)
    {
        return UsuarioBus::deleteUsuario($id_usuario);
    }
}


class SimpleImage
{

    var $image;
    var $image_type;

    function load($filename)
    {

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
