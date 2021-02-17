<link rel="stylesheet" href="css/bootstrap.min.css">

<?php include "imports/head_parameters.php"; ?>

<title>Lista de carteiras</title>

</head>

<body class="d-flex flex-wrap" onload="loadInfPerfil()">
    <header class="col-md-1 col-sm-2">
        <?php include "imports/menu_lateral.php"; ?>
    </header>
    <main class="col-md-11 col-sm-10 d-block mx-4 mx-sm-0">
        <section>
            <div class="d-block ml-2">
                <div class="d-flex mt-5 mb-3">
                    <h4 class="font-purple my-auto">Minhas configurações de perfil</h4>
                </div>
                <div class="d-block mt-5">
                    <h5 class="font-purple my-auto">Sua foto</h5>
                    <div class="d-flex my-5">
                        <img id="foto" class="foto-perfil rounded-circle my-auto mr-4" src="./images/avatar.svg" alt="Foto de perfil" onerror="this.src='../../uploads/avatar.svg'">
                        <div class=" my-auto">
                            <div class="d-flex">
                                <p class="font-green font-weight-bold pointer hover-color-darkgreen" onclick="document.getElementById('form-foto').click()">Enviar nova foto</p>
                                <input id="form-foto" class="form-control-file d-none" onchange="salvarFoto('none')" type="file" accept=".jpg, .jpeg">
                            </div>

                            <a class="pointer" onclick="(confirm('Deseja realmente excluir sua foto de perfil?'))? salvarFoto('default'): false;">
                                <p class="font-gray font-weight-bold hover-color-darkred">Remover foto</p>
                            </a>
                        </div>
                    </div>
                    <label class="pt-4 font-weight-bold" for="form-nome">Seu nome</label>
                    <input class="form-control col-sm-6" maxlength="50" id="form-nome" value="" required>
                    <label class="pt-4 font-weight-bold" for="form-email">E-mail</label>
                    <input class="form-control col-sm-6" maxlength="50" id="form-email" value="" disabled>

                    <button class="btn btn-success col-2 mt-5" onclick="saveUsuario()">Salvar</button>

                    <button class="btn btn-danger d-block  mt-5" onclick="(confirm('Deseja sair do sistema?'))? window.location.href = './exit.php' : false">Sair</button>
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
        toastr.options.closeButton = true;
        toastr.options.closeMethod = 'fadeOut';
        toastr.options.closeDuration = 300;
        toastr.options.closeEasing = 'swing';
        toastr.options.preventDuplicates = true;


        const id_usuario = <?php echo $_SESSION['id_usuario']; ?>

        const loadInfPerfil = async () => {

            var myHeaders = new Headers();
            myHeaders.append("post", `funcao=listar&id=${id_usuario}`);

            var formdata = new FormData();
            formdata.append("funcao", "listar");
            formdata.append("id", id_usuario);

            var requestOptions = {
                method: 'POST',
                body: formdata,
                headers: myHeaders,
                redirect: 'follow'
            };

            const response = await fetch(`../controller/usuario_controller.php`, requestOptions)
            const resultJson = await response.json();

            if (resultJson === "0 dados encontrados") {
                return;
            }

            for (var i = 0; i < resultJson.length; i++) {
                document.getElementById('form-nome').value = `${resultJson[i]['nome']}`;
                document.getElementById('form-email').value = `${resultJson[i]['email']}`;
                document.getElementById('foto').src = `../../uploads/${resultJson[i]['foto']}`;
            }
        }

        const saveUsuario = async () => {

            let nome = document.getElementById('form-nome').value;

            console.log(nome);

            // try {

            var myHeaders = new Headers();
            myHeaders.append("post", `funcao=salvar&id=${id_usuario}&nome=${nome}`);


            var formdata = new FormData();
            formdata.append("funcao", "salvar");
            formdata.append("id", id_usuario);
            formdata.append("nome", nome);



            const response = await fetch(`../controller/usuario_controller.php`, {
                method: "POST",
                body: formdata,
                headers: myHeaders,
            });

            const resultJson = await response.json();
            if (resultJson.search("Erro") > -1 && response.status == 200) {
                toastr.error(resultJson, 'Atenção:');
            } else {
                _msgEnviadaAnteriormente = true;
                toastr.success(resultJson, 'Parabéns:');
                loadInfPerfil()
                //setTimeout(() => { window.location.href = "./index.html" }, 5000);
            }
            // } catch (error) {
            //     console.log(error)
            //     toastr.clear();
            //     toastr.warning("Erro no envio dos dados.<br/>Problema ao comunicar-se com o sistema!<br/>Tente mais tarde, por favor!", 'Ops!');
            // }
        }

        const salvarFoto = async (tipo) => {

            try {

                const form = new FormData();

                const file = document.querySelector('#form-foto').files[0];
                if(file){
                    form.append('foto', file);
                }
                form.append("funcao", "salvarfoto");
                form.append("id", id_usuario);
                form.append("tipo", `${tipo}`);

                const url = '../controller/usuario_controller.php'
                const request = new Request(url, {
                    method: 'POST',
                    body: form
                });



                const response = await fetch(request);

                const resultJson = await response.json();
                if (resultJson.search("Erro") > -1 && response.status == 200) {
                    toastr.error(resultJson, 'Atenção:');
                } else {
                    _msgEnviadaAnteriormente = true;
                    toastr.success(resultJson, 'Parabéns:');
                    loadInfPerfil()
                    //setTimeout(() => { window.location.href = "./index.html" }, 5000);
                }
            } catch (error) {
                console.log(error)
                toastr.clear();
                toastr.warning("Erro no envio dos dados.<br/>Problema ao comunicar-se com o sistema!<br/>Tente mais tarde, por favor!", 'Ops!');
            }
        }
    </script>
</body>

</html>