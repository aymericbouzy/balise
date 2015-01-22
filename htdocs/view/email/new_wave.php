<p>
  La vague de subventions <?php echo pretty_wave($parameters["wave"]); ?> vient d'être créée !
</p>
<p>
  Tu peux créer une nouvelle demande de subvention pour tes binets :
</p>
<ul>
  <?php
  foreach ($parameters["binet_term"] as $binet_term) {
    $binet_term = select_term_binet($binet_term, array("id", "binet", "term"));
    ?>
    <li><?php echo link_to(full_path(path("new", "request", "", binet_prefix($binet_term["binet"], $binet_term["term"]))), pretty_binet_term($binet_term["id"], false)); ?></li>
    <?php
  }
  ?>
</ul>
