<ul>
  <li><?php echo link_to(path("validations", "binet", $binet["id"]."/".$term), "Validations <span class=\"counter\">".count_pending_validations()."</span>"); ?></li>
</ul>
