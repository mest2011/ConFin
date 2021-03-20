/**
  * Função para criar um objeto XMLHTTPRequest
  */
 function CriaRequest() {
    try{
        request = new XMLHttpRequest();
    }catch (IEAtual){

        try{
            request = new ActiveXObject("Msxml2.XMLHTTP");
        }catch(IEAntigo){

            try{
                request = new ActiveXObject("Microsoft.XMLHTTP");
            }catch(falha){
                request = false;
            }
        }
    }

    if (!request)
        alert("Seu Navegador não suporta Ajax!");
    else
        return request;
}

/**
 * Função para enviar os dados
 */
function checkExist(inputForCheck) {

    // Declaração de Variáveis
    var xmlreq = CriaRequest();
    var result = document.getElementById("result");
    var btnSalvar = document.getElementById("form_salvar");

    //Pegar valor referente ao campo que chamou a função
    var value = document.getElementById("form_"+inputForCheck).value;

    // Iniciar uma requisição
    xmlreq.open("GET", "../controller/cadastro_controller.php?checkExist=" + inputForCheck +"&value="+ value, true);

    // Atribui uma função para ser executada sempre que houver uma mudança de ado
    xmlreq.onreadystatechange = function(){

        // Verifica se foi concluído com sucesso e a conexão fechada (readyState=4)
        if (xmlreq.readyState == 4) {

            // Verifica se o arquivo foi encontrado com sucesso
            if (xmlreq.status == 200) {
                result.innerHTML = xmlreq.responseText;
                if (xmlreq.responseText.length > 0) {
                    btnSalvar.disabled = true;
                }else{
                    btnSalvar.disabled = false;
                }
            }else{
                result.innerHTML = "Erro: " + xmlreq.statusText;
            }
        }
    };
    xmlreq.send(null);
}