<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



class UserCodigoValidacao extends Tabela {
  protected $tabela = 'UserCodigoValidacao';
  protected $chavePrimaria = 'id';

  protected $legendas = array(
                             );


public function CriarCodigoValidacao(){

$email = $_POST['email']; unset($_POST['email']);
$codigo = rand(1000, 9999);
$minutes_to_add = 5;
$data = new DateTime();
$data->add(new DateInterval('PT' . $minutes_to_add . 'M'));
$dataStamp = $data->format("Y-m-d H:i:s");

$sql = "INSERT INTO `teentok_teste`.`UserCodigoValidacao` ( `email`, `codigo`, `dataValidade`, `ativado`, `metodoValidacao`) VALUES ('$email', '$codigo', '$dataStamp', false, 'email');";
$this->query($sql);
$arr = array('true','Código gerado e enviado com sucesso.'.$codigo);

$sender = 'fernando@aspbrasil.net';
$senderName = 'Fernando Dangelo';

$usernameSmtp = 'fernando@aspbrasil.net';
$passwordSmtp = 'oDnanref#22';
$configurationSet = 'ConfigSet';
$host = 'smtp.gmail.com';
$port = 587;

$cabecalho = '<html>
<head>
  <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
  <title>Validação de e-mail</title>
</head>
<body>';
$rodape = '</body></html>';

require './mailer/PHPMailerAutoload.php';

$to = $email;
$subject = 'Cadastro TryEdu';
$conteudo = '<p>Código de Ativação:'.$codigo.'</p> Nós protegemos você e seus dados, caso tenha alguma dúvida, pode acessar nossa política de privacidade: <a href="https://tryedu.com.br/politica-de-privacidade">https://tryedu.com.br/politica-de-privacidade</a></p>';
$conteudo .= '<p>Para mais informações sobre o jogo basta acessar nosso site: <a href="http://umaaventuranaescola.com.br/">http://umaaventuranaescola.com.br/</a></p>';
$conteudo .= '<p>Nos não enviamos anúncios, você não receberá e-mail de publicidade.</p>';
$message = $cabecalho . $conteudo . $rodape;

$mail = new PHPMailer;

//Tell PHPMailer to use SMTP
$mail->isSMTP();

//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug = 0;
$mail->Debugoutput = 'html';
$mail->IsHTML(true);
$mail->Host = 'smtp.gmail.com';
$mail->Port = 587;
$mail->SMTPSecure = 'tls';
$mail->SMTPAuth = true;
$mail->Username = "fernando@aspbrasil.net";
$mail->Password = "oDnanref#22";
$mail->setFrom('from@example.com', 'First Last');
$mail->addAddress('fernando@aspbrasil.net', 'Woods');
$mail->Subject = 'PHPMailer GMail SMTP test';
$mail->Body = $message;

if (!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
    $arr = array('false','Erro no envio do email.');
} else {
    echo 'Message has been sent';
}

return $arr;
//DISPARAR EMAIL
// Retornar via JSON codigo criado: "true" ou "false".

}


public function ValidarCodigo(){

  $email = $_POST['email']; unset($_POST['email']);
  $codigo = $_POST['codigo']; unset($_POST['codigo']);
  $data = date("Y-m-d H:i:s");

  //TOD0: VAlidar o código. Se estiver certo, faz o update
  $sql = "SELECT * FROM `teentok_teste`.`UserCodigoValidacao` WHERE `email` = '$email' AND codigo = '$codigo' AND dataValidade >= '$data' ";
  $retorno = $this->query($sql);
  $count = $retorno->rowCount();
  $arr = array('false','Código Inválido ou Expirado.');

  if ($count == 1)
  {
      $sql = "UPDATE `teentok_teste`.`UserCodigoValidacao` SET `ativado` = true WHERE `email` = '$email'";
      $this->query($sql);
      $arr = array('true','Código Validado com Sucesso!');
  }
  return $arr;    


  }


}

?>