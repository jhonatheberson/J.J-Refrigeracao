
  <?php

      $nome = $_POST['nome'];
      $sobrenome = $_POST['sobrenome'];
      $mensagem = $_POST['mensagem'];
      $email = $_POST['email'];
      $telefone = $_POST['telefone'];


      ini_set('display_errors', 1);

      error_reporting(E_ALL);

      $from = $email;

      $to = "jhonatheberson@outlook.com.br";

      $subject = "Mensagem de contato do site de J.J";

      $message =  'tetses';

      $headers = "De:". $from;


      mail($to, $subject, $message, $headers);

      echo "<a href="index.html"></a>";

  ?>
