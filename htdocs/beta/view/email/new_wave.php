<p>
  La vague de subventions <?php echo pretty_wave($parameters["wave"]); ?> vient d'être créée !
</p>
<p>
  Tu peux créer une nouvelle demande de subvention pour tes binets :
</p>
<ul>
  <?php
    foreach ($parameters["binets"] as $binet) {
      ?>
      <li><?php echo link_to(full_path(path("new", "request", "", binet_prefix($binet, current_term($binet)))), pretty_binet($binet, false)); ?></li>
      <?php
    }
  ?>
</ul>
