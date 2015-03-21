<div class="form-container">
  <?php
  $current_term = current_term($binet["id"]);
  if (is_empty($current_term)) {
    ?>
    <h1>Réactiver le binet</h1>
    <?php echo get_html_form("binet"); ?>
    <?php
  } else {
    ?>
    <h1><i class="fa fa-fw fa-arrow-right"></i> Faire la passation</h1>
    <div class="buttons">
      <?php
        $new_term = $current_term + 1;
        if (is_transferable($binet["id"])) {
          echo link_to(path("power_transfer", "binet", $binet["id"], "", array("term" => $new_term), true), "Passer à la promo ".$new_term, array("class" => "btn btn-primary"));
          echo "\t";
        } else {
          echo tip("Pour faire la passation de la Kès, il faut qu'il y ait au moins un administrateur du mandat suivant.");
        }
        if (is_deactivatable($binet["id"])) {
          echo link_to(path("deactivate", "binet", $binet["id"], "", array(), true), "Désactiver le binet",  array("class" => "btn btn-danger"));
        }
      ?>
    </div>
    <?php
  }
  ?>
</div>
