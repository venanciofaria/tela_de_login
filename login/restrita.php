<?php 

    require('config/conexao.php');
    $user = auth($_SESSION['TOKEN']);
    if($user){
        echo "<h1>SEJA BEM VINDO <b style='color:red'>".$user['nome_completo']."!</b></h1>";
        echo "<br><br><a style='background:green; color:white; text-decoration:none; padding:20px; border-radius:5px;' href='logout.php'>Sair do sistema</a>";
    }else{
        //REDIRECIONAR PARA LOGIN;
        header('location: index.php');
    }
?>