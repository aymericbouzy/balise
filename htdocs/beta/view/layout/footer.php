<span>
	Site créé par <?php echo link_to("mailto:Nathan Eckert <nathan.eckert@polytechnique.edu>", "Nathan"); ?>,
	<?php echo link_to("mailto:Victor Nicolet <victor.nicolet@polytechnique.edu>", "Little"); ?> et
	<?php echo link_to("mailto:Aymeric Bouzy <aymeric.bouzy@polytechnique.edu>", "Zouby"); ?>.
</span>
<?php

  $reference = substr(md5(rand()), 0, 10);
  $url = isset($_SERVER["REDIRECT_URL"]) ? $_SERVER["REDIRECT_URL"] : $_SERVER["REQUEST_URI"];
  $email = connected_student() ? select_student($_SESSION["student"], array("email"))["email"] : "";
  $post = array_to_string($_POST);
  $session = array_to_string($_SESSION);
  $get = array_to_string($_GET);

  $body = "\n\n\n\n———————————\n**** Ne pas modifier cette partie ****\n\nURL demandée :\t\t\t\t\t".$url."\npersonne connectée :\t\t\t\t".$email."\nétat de la variable \$_POST :\t\t\t".$post."\nétat de la variable \$_SESSION :\t\t".$session."\nétat de la variable \$_GET :\t\t\t".$get;
  $body = urlencode($body);
  $body = str_replace(array("+"), array(" "), $body);

  echo link_to("mailto:Projet Balise <balise.bugreport@gmail.com>?subject=[bug #".$reference."]&body=".$body, "Rapport de bug", array("class" => "btn btn-primary"));
?>
