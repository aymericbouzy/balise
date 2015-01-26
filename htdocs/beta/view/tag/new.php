<p>
  Voulez-vous vraimnet créer le tag "<?php echo $_SESSION["tag_to_create"]; ?>" ?
  <?php echo link_to(path("create", "tag", "", "", array(), true), "Créer", array("class" => "btn btn-primary")); ?>
  <?php echo link_to(substr($_SESSION["return_to"], 1), "Revenir au formulaire", array("class" => "btn btn-default")); ?>
</p>
