<p>
  La vague de subventions <?php echo pretty_wave($parameters["wave"]); ?> vient d'être créée !
</p>
<p>
  Vous pouvez créer <?php echo link_to(full_path(path("new", "request", "", binet_prefix($parameters["binet"], $parameters["term"]))), "une nouvelle demande de subvention"); ?>.
</p>
