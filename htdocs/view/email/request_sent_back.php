<p>
  Ta <?php echo link_to(path("show", "request", $parameters["request"], binet_prefix($parameters["binet"], $parameters["term"])), "demande de subventions"); ?>
  pour le binet <?php echo pretty_binet_term(term_id($parameters["binet"], $parameters["term"]), true, false); ?> t'a été renvoyée. Elle se trouve à nouveau dans tes brouillons,
  tu peux la modifier avant de la renvoyer. Si tu ne la renvoies pas, tu ne pourras pas recevoir de subventions pour cette demande.
</p>
