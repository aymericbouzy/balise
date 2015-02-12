<p>
  La vague de subventions <?php echo pretty_wave($parameters["wave"]); ?> vient d'être créée !
</p>
<p>
  Tu peux créer une nouvelle demande de subvention pour tes binets :
</p>
<ul>
  <?php
    foreach ($parameters["binets"] as $binet_administred) {
      ?>
      <li><?php echo link_to(path("new", "request", "", binet_prefix($binet_administred, current_term($binet_administred)), array("wave" => $parameters["wave"])), pretty_binet($binet_administred, false, false)); ?></li>
      <?php
    }
  ?>
</ul>
