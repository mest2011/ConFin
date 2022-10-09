<?php
include_once '../home_page/imports.html';
include_once '../home_page/header.html';
echo "<main>";
include_once "../controller/cadastro_controller.php";


?>
<html>

<head>
    <meta charset="UTF-8">
    <title>Cadastro de Usuario</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        body {
            background-image: linear-gradient(180deg, #44BBA4, #393E41);
        }

        .container {
            margin: 30px auto 50px auto;
            display: flex;
            justify-content: center;
        }

        .box {
            background-color: #fafafa;
            padding: 25px;
            width: 1200px;
        }

        label {
            margin: 10px auto 15px auto;
            text-align: right;
        }

        label,
        input {
            display: flex;
        }

        input {
            width: 300px;
        }

        #form_salvar {
            margin: 70px auto;
            width: auto;
            padding: 0 30px 0 30px;

        }

        #form_salvar:hover {
            font-weight: 600;
        }

        #form_date_of_birth {
            color: gray;
        }

        span {
            margin: 15px;
            display: block;
        }

        .ocultar{
            display: none;
        }
    </style>
    <script type="text/javascript" src="../view/js/cadastro_ajax.js"></script>
</head>

<body>
    <div id="formulario" class="container">
        <div class="box">
            <form method="POST" action="cadastro.php">

                <label for="form_name">*Nome:</label>
                <input id="form_name" type="text" placeholder="Nome" name="name" required>
                <label for="form_date_of_birth">*Data de nascimento:</label>
                <input id="form_date_of_birth" type="date" name="date_of_birth" placeholder="Data de nascimento" required>
                <label for="form_cpf" class="ocultar">CPF:</label>
                 <input id="form_cpf" type="text" pattern="[0-9]{3}.?[0-9]{3}.?[0-9]{3}-?[0-9]{2}" name="cpf" placeholder="CPF" onfocusout="checkExist('cpf')" value="00000000000" class="ocultar">
                <label for="form_email">*E-mail:</label>
                <input id="form_email" type="email" name="email" placeholder="E-mail" required onfocusout="checkExist('email')">
                <label for="form_endereco" class="ocultar">Endereço:</label>
                <input id="form_endereco" type="text" name="address" placeholder="Endereço e numero da casa" value="null" class="ocultar">
                <label for="form_cidade" class="ocultar">Cidade:</label>
                <input id="form_cidade" type="text" name="city" placeholder="Cidade" value="null" class="ocultar">
                <label for="form_pais" class="ocultar">País:</label>
                <input id="form_pais" type="text" name="country" placeholder="País" value="null" class="ocultar">
                <label for="form_user">*Usuario</label>
                <input id="form_user" type="text" name="user" minlength="4" maxlength="20" placeholder="Usuario" required onfocusout="checkExist('user')">
                <label for="form_password">*Senha</label>
                <input id="form_password" type="password" name="password" minlength="4" maxlength="20" placeholder="Senha" required>
                <span id="result"></span>
                <input id="form_salvar" type="submit" name="Salvar">
                <p>*Campos minimos para ao cadastro!</p>
            </form>
        </div>
    </div>



    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>

<?php
echo "</main>";
include_once '../home_page/footer.html';
?>