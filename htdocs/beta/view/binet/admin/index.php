<div id="admin-wrapper">
<?php
$admins = select_current_admins($binet);
if (!empty($admins)) {
  foreach ($admins as $admin) {
    ?>
    <span class="admin opanel">
      <i class="fa fa-fw fa-user logo"></i>
      <i class="fa fa-fw fa-send logo"></i>
      <?php echo pretty_student($admin["id"]);
      /* TODO : path */
        echo button(path("",""),"Retirer cet administrateur","times","red",true,"small");?>
    </span>
    <?php
  }
}
?>
