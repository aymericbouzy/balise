<p>
  Les résultats de la vague de subventions <?php echo pretty_wave($parameters["wave"]); ?> ont été publiés.
</p>
<p>
  Tu peux voir en particulier les subventions qui t'ont été accordées pour tes binets :
</p>
<ul>
  <?php
    foreach ($parameters["requests"] as $request) {
      ?>
      <li><?php echo pretty_request($request); ?></li>
      <?php
    }
  ?>
</ul>
