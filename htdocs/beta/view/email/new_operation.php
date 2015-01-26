<p>
  L'opération <?php echo pretty_operation($parameters["operation"]); ?> a été proposée par <?php echo pretty_operation($parameters["student"]); ?> pour le binet <?php echo pretty_binet_term($parameters["binet"]."/".$parameters["term"]); ?>.
  Tu peux l'<?php echo link_to(path("review", "operation", $parameters["operation"], binet_prefix($parameters["binet"], $parameters["term"])), "ajouter à ton budget"); ?>
  ou la <?php echo link_to(path("show", "operation", $parameters["operation"], binet_prefix($parameters["binet"], $parameters["term"])), "refuser"); ?>.
</p>
