<?php

require('config/conexao.php');
//VERIFICAR SE A POSTAGEM EXISTE DE ACORDO COM OS CAMPOS;
if (isset($_POST['nome_completo']) && isset($_POST['email']) && isset($_POST['senha']) && isset($_POST['repete_senha'])) {
    //VERIFICAR SE TODOS OS CAMPOS FORAM PREENCHIDOS;
    if (empty($_POST['nome_completo']) || empty($_POST['email']) || empty($_POST['senha']) || empty($_POST['repete_senha'])) {
        $erro_geral = "Todos os campos são obrigatórios";
    }else{
        //RECEBER VALORES VINDO DO POST E LIMPAR;
        $nome = limparPost($_POST['nome_completo']);
        $email = limparPost($_POST['email']);
        $senha = limparPost($_POST['senha']);
        $senha_cript = sha1($senha);
        $repete_senha = limparPost($_POST['repete_senha']);
        $checkbox = limparPost($_POST['termos']);
        //VERIFICANDO COM UMA EXPRESSÃO REGULAR SE É SOMENTE LETRAS E ESPAÇOS EM BRANCO;
        if(!preg_match("/^[a-zA-Z-' ]*$/",$nome)){
            $erro_nome = "Somente permitido letras e espaços em branco";
        }

        //VERIFICAR SE O EMAIL É VÁLIDO;
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $erro_email = "Formato de email inválido!";
        }

        //VERIFICAR SE SENHA TEM MAIS DE 6 DÍGITOS;
        if(strlen($senha) <6){
            $erro_senha = "Senha deve ter 6 caracteres ou mais!";
        }

        //VERIFICAR SE REPETE_SENHA É IGUAL A SENHA;
        if($senha !== $repete_senha){
          $erro_repete_senha =  "As senhas não conferem!";
        }

        //VERIFICAR SE CHECKBOX FOI MARCADO!
        if($checkbox !== "ok"){
            $erro_checkbox = "Desativado!";
        }
        //VERIFICAR SE NÁO EXISTE NENHUM TIPO DE ERRO E SALVAR NO BANCO;
        if(!isset($erro_geral) && !isset($erro_nome) && !isset($erro_email) && !isset($erro_senha) && !isset($erro_repete_senha) && !isset($erro_checkbox)){
            //VERIFICAR SE ESSE USUÁRIO JÁ ESTÁ CADASTRADO NO BANCO;
            $sql= $pdo->prepare("SELECT * FROM usuarios WHERE email=? LIMIT 1");
            $sql->execute(array($email));
            $usuario = $sql->fetch();
            //SE NÁO EXISTIR O USUÁRIO ADICIONAR NO BANCO;
            if(!$usuario){
                $recupera_senha = "";
                $token = "";
                $status = "novo";
                $data_cadastro = date('d/m/Y');
                $sql = $pdo->prepare("INSERT INTO usuarios VALUES (null,?,?,?,?,?,?,?)");
                 if($sql->execute(array($nome,$email,$senha_cript,$recupera_senha,$token,$status,$data_cadastro))){
                    header('location: index.php?result=ok');
                    if($modo == "local"){
                        header('location: index.php?result=ok');
                    }
                    if($modo == "producao"){
                        //ENVIAR EMAIL PARA O USUÁRIO;
                        $mail = new PHPMailer(true);
                    }
                 }
            }else{
                //JÁ EXISTE USUÁRIO APRESENTAR ERRO;
                $erro_geral = "Usuário já cadastrado!";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
    />
    <title>Login</title>
</head>
<body>
    <form method="post">
        <h1>Cadastrar</h1>

        <?php if (isset($erro_geral)) { ?>
            <div class="erro-geral animate__animated animate__rubberBand">
                <?php echo $erro_geral; ?> 
            </div>
        <?php } ?> 

        <div class="input-group">
            <img class="input-icon" src="img/card.png">
            <input <?php if (isset($erro_geral) || isset($erro_nome)) {echo 'class="erro-input"';} ?> name="nome_completo" type="text" placeholder="Nome Completo" <?php if(isset($_POST['nome_completo'])){echo "value='".$_POST['nome_completo']."'";}?> required>
            <?php if(isset($erro_nome)){?>
            <div class="erro"><?php echo $erro_nome;?></div>
            <?php }?>
        </div>
        
        <div class="input-group">
            <img class="input-icon" src="img/user.png">
            <input <?php if (isset($erro_geral) || isset($erro_email)) {echo 'class="erro-input"';} ?> name="email" type="email" placeholder="Digite seu email" <?php if(isset($_POST['email'])){echo "value='".$_POST['email']."'";}?> required>
            <?php if(isset($erro_email)){?>
            <div class="erro"><?php echo $erro_email;?></div>
            <?php }?>
        </div>

        <div class="input-group">
            <img class="input-icon" src="img/lock.png">
            <input <?php if (isset($erro_geral) || isset($erro_senha)) {echo 'class="erro-input"';} ?> name="senha" type="password" placeholder="Senha mínimo 6 dígitos" <?php if(isset($_POST['repete_senha'])){echo "value='".$_POST['repete_senha']."'";}?> required>
            <?php if(isset($erro_senha)){?>
            <div class="erro"><?php echo $erro_senha;?></div>
            <?php }?>
        </div>

        <div class="input-group">
            <img class="input-icon" src="img/lock-open.png">
            <input <?php if (isset($erro_geral) || isset($erro_repete_senha)) {echo 'class="erro-input"';} ?> name="repete_senha" type="password" placeholder="Repita a senha" <?php if(isset($repete_senha)){echo "value='$repete_senha'";}?> required>
            <?php if(isset($erro_repete_senha)){?>
            <div class="erro"><?php echo $erro_repete_senha;?></div>
            <?php }?>
        </div>

        <div <?php if (isset($erro_geral) || isset($erro_checkbox)) {echo 'class="input-group erro-input"';} else {echo 'class="input-group"';} ?>>
            <input type="checkbox" id="termos" name="termos" value="ok" required>
            <label for="termos">Ao se cadastrar você concorda com a nossa <a class="link" href="#">Política de privacidade</a> e os <a class="link" href="#">Termos de uso</a></label>
        </div>

        <button class="btn-blue" type="submit">Cadastrar</button>
        <a href="index.php">Já tenho uma conta</a>
    </form>
</body>
</html>
