    <?php 
    //ENCERRAR SESSÃO;
    //INICIALIZOU->LIMPOU->DESTRUIU->REDIRECIONOU;
        session_start();
        session_unset();
        session_destroy();
        header('location: index.php');
    ?>