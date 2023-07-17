<?php 
   
   session_start();
   // REQUERIMENTO DO PHP MAILER;
   use PHPMailer\PHPMailer\PHPMailer;
   use PHPMailer\PHPMailer\Exception;

   require 'PHPMAILER/src/Exception.php';
   require 'PHPMailer/src/PHPMailer.php';
   require 'PHPMailer/src/SMTP.php';

    //DOIS MODOS POSSIVEIS -> local e produção;

    $modo = 'local';

   if($modo == 'local'){
    $servidor = "localhost";
    $usuario = "root";
    $senha = "";
    $banco = "login";
   }

   if($modo == 'producao'){
    $servidor = "";
    $usuario = "";
    $senha = "";
    $banco = "";
   }

   try{
    $pdo = new PDO("mysql:host=$servidor;dbname=$banco",$usuario,$senha,);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Banco conectado com sucesso";
   }catch(PDOException $erro){
    //echo "Falha ao se conectar com o banco".$erro->getMessage();
   }

   function limparPost($dados){
    $dados = trim($dados);
    $dados = stripcslashes($dados);
    $dados = htmlspecialchars($dados);
    return $dados;
   }

   function auth($tokenSessao){
      //VERIFICAR SE TEM AUTORIZAÇÃO;
      global $pdo;
      $sql = $pdo->prepare("SELECT * FROM usuarios WHERE token=? LIMIT 1");
      $sql->execute(array($tokenSessao));
      $usuario = $sql->fetch(PDO::FETCH_ASSOC);
      //SE NÃO ENCONTRAR O USUÁRIO;
      if(!$usuario){
         return false;
      }else{
         return $usuario;
      }
   }

 

 

?>