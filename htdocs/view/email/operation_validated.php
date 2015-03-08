<p>
  L'opération <?php echo pretty_operation($parameters["operation"]); ?> a été validée par la Kès !
</p>
<p>
  Elle apparaît désormais dans les <?php echo link_to("", "operation", "", binet_prefix($parameters["binet"], $parameters["term"]), "opérations"); ?>
  du binet <?php echo pretty_binet_term($parameters["binet"]."/".$parameters["term"], true, false); ?>.
</p>
