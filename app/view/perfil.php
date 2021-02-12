<link rel="stylesheet" href="css/bootstrap.min.css">

<?php include "imports/head_parameters.php"; ?>

<title>Lista de carteiras</title>

</head>

<body class="d-flex" onload="loadCards()">
    <header class="col-md-1 col-sm-2">
        <?php include "imports/menu_lateral.php"; ?>
    </header>
    <main class="col-md-11 col-sm-10 d-block">
        <section>
            <div class="d-block ml-2">
                <div class="d-flex mt-5 mb-3">
                    <h4 class="font-purple my-auto">Minhas configurações de perfil</h4>
                </div>
                <div class="d-block mt-5">
                    <h5 class="font-purple my-auto">Sua foto</h5>
                    <div class="d-flex my-5">
                        <img class="foto-perfil rounded-circle my-auto mr-4" src="./images/avatar.svg" alt="">
                        <div class=" my-auto">
                            <a class="pointer" onclick="funcaoIndisponivel()"><p class="font-green font-weight-bold line-height-0">Enviar nova foto</p></a>
                            <a class="pointer" onclick="funcaoIndisponivel()"><p class="font-gray font-weight-bold">Remover foto</p></a>
                        </div>
                    </div>
                    <label class="pt-4 font-weight-bold" for="nome">Seu nome</label>
                    <input class="form-control col-sm-6"  maxlength="50" id="nome" value="<?php echo $_SESSION['nome']?>" required>
                    <label class="pt-4 font-weight-bold" for="email">E-mail</label>
                    <input class="form-control col-sm-6"  maxlength="50" id="email" value="<?php echo $_SESSION['usuario']?>" disabled>

                    <button class="btn btn-success col-2 mt-5" onclick="funcaoIndisponivel()">Salvar</button>

                    <button class="btn btn-danger d-block col-1 mt-5" onclick="(confirm('Deseja sair do sistema?'))? window.location.href = './exit.php' : false">Sair</button>
                </div>
            </div>
        </section>
        <section>
            <div class="side-modal p-5" id="side-modal"></div>
            <div id="container-cards" class="col-12 d-flex flex-wrap"></div>
        </section>

    </main>
    <?php include "imports/js.php"; ?>

    <script>
        
    </script>
</body>

</html>