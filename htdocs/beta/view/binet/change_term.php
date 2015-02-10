<div class="form-container">
  <?php
  if (is_empty($binet["term"])) {
    ?>
    <h1>Réactiver le binet</h1>
    <form role="form" id="change_term_binet" action="/<?php echo path("reactivate", "binet", $binet["id"], ""); ?>" method="post">
      <?php echo form_group_text("Promotion :", "term", $binet, "binet"); ?>
      <?php echo form_csrf_token(); ?>
      <?php echo form_submit_button("Réactiver"); ?>
    </form>
    <?php
  } else {
    ?>
    <h1>Faire la passation</h1>
    <div class="buttons">
      <?php
        $new_term = $binet["term"] + 1;
        echo link_to(path("power_transfer", "binet", $binet["id"], "", array("term" => $new_term), true), "Passer à la promo ".$new_term, array("class" => "btn btn-primary"));
        echo "\t";
        echo link_to(path("deactivate", "binet", $binet["id"], "", array(), true), "Désactiver le binet",  array("class" => "btn btn-danger"));
      ?>
    </div>
    <?php
  }
  ?>
</div>
