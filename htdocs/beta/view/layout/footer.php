Site créé par <a href="mailto:Nathan Eckert <nathan.eckert@polytechnique.edu>">Nathan</a>, <a href="mailto:Victor Nicolet <victor.nicolet@polytechnique.edu>">Little</a> et <a href="mailto:Aymeric Bouzy <aymeric.bouzy@polytechnique.edu>">Zouby</a>.
<?php

  function array_to_string($array) {
    ob_start();
    var_dump($array);
    $string = ob_get_clean();
    return str_replace(
      array(" "),
      array("\040"),
      $string
    );
  }

  $reference = substr(md5(rand()), 0, 10);
  $url = $_SERVER["REDIRECT_URL"];
  $email = select_student($_SESSION["student"], array("email"))["email"];
  $post = array_to_string($_POST);
  $session = array_to_string($_SESSION);
  $get = array_to_string($_GET);

  $body = "\n\n\n\n———————————\n**** Ne pas modifier cette partie ****\n\nURL demandée :\t\t\t\t\t".$url."\npersonne connectée :\t\t\t\t".$email."\nétat de la variable \$_POST :\t\t\t".$post."\nétat de la variable \$_SESSION :\t\t".$session."\nétat de la variable \$_GET :\t\t\t".$get;
  $body = urlencode($body);
  $body = str_replace(array("+"), array(" "), $body);

  echo "<a href=\"mailto:Projet Balise <balise.bugreport@gmail.com>?subject=[bug #".$reference."]&body=".$body."\" class=\"btn btn-primary\">Rapport de bug</a>";
?>
