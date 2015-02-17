<div id="admin-wrapper">
<div class="actionbar-left">
  <?php echo button(path("show", "binet", $binet),"Retour au résumé du binet","chevron-left","blue",true,"","left");?>
</div>
<div class="actionbar-right">
  <?php echo button(path("new", "admin", "", binet_prefix($binet, $term)),"Ajouter un administrateur","plus","green");?>
</div>
<?php
$admins = select_current_admins($binet);
if (!empty($admins)) {
  foreach ($admins as $admin) {
    ?>
    <span class="admin opanel">
      <i class="fa fa-fw fa-user logo"></i>
      <i class="fa fa-fw fa-send logo"></i>
      <?php
        echo pretty_student($admin["id"]);
        if ($binet != KES_ID || $admin["id"] != connected_student()) {
          echo button(path("delete", "admin", $admin["id"], binet_prefix($binet, $term), array(), true),"Retirer cet administrateur","times","red",true,"small");
        }
      ?>
    </span>
    <?php
  }
} else {
  echo " Il n'y aucun administrateur pour ce binet.";
}
?>
</div>
