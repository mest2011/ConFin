<?php
    echo "<!DOCTYPE html>
    <html lang=\"pt-br\">
    
    <head>
    <meta http-equiv=\"cache-control\" content=\"max-age=0\" />
    <meta http-equiv=\"cache-control\" content=\"no-cache\" />
    <meta http-equiv=\"expires\" content=\"0\" />
    <meta http-equiv=\"expires\" content=\"Tue, 01 Jan 1980 1:00:00 GMT\" />
    <meta http-equiv=\"pragma\" content=\"no-cache\" />
    ";
    setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
    date_default_timezone_set('America/Sao_Paulo');
    session_start();

    echo '<link rel="stylesheet" href="../view/css/reset.css">';
    echo '<link rel="stylesheet" href="css/bootstrap.min.css">';
    
    echo '<link rel="stylesheet" href="../view/css/style.css">';
    echo '<link rel="stylesheet" href="../view/css/global.css">
            <link rel="stylesheet" href="./lib/css/toastr.css">';
    

    echo '<link rel="icon" type="imagem/png" href="images/logo_porquinho.png" />';

    echo '<link rel="stylesheet" href="../view/css/menu_lateral.css">';
    
    //echo '<link rel="stylesheet" media="screen and (max-width: 1499px)" href="../view/css/mobile.css">';

?>