<div id="admin-wrapper">
  <div class="actionbar-right">
    <?php
    if (is_current_kessier()) {
      echo button(path("new", "member", "", binet_prefix(binet, term)),"Ajouter un administrateur","plus","green");
    }
    if (has_editing_rights(binet, term)) {
      echo button(path("new_viewer", "member", "", binet_prefix(binet, term)),"Ajouter un observateur","plus","teal");
    }
    ?>
  </div>
  <div class="panel shadowed light-blue-background">
    <div class="title">
      Adminisrateurs
    </div>
    <div class="content">
    <?php
    $admins = select_admins(binet, term);
    if (!empty($admins)) {
      foreach ($admins as $admin) {
        ?>
        <span class="admin shadowed">
          <i class="fa fa-fw fa-user logo"></i>
          <i class="fa fa-fw fa-send logo"></i>
          <?php
            echo pretty_student($admin["id"]);
            if (is_current_kessier() && (binet != KES_ID || $admin["id"] != connected_student())) {
              echo button(path("delete", "member", $admin["id"], binet_prefix(binet, term), array(), true),"Retirer cet administrateur","times","red",true,"small");
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
  </div>
  <div class="panel shadowed light-blue-background">
    <div class="title">
      Observateurs
    </div>
    <div class="content">
      <?php
      $viewers = select_viewers(binet, term);
      if (!empty($viewers)) {
        foreach ($viewers as $viewer) {
          ?>
          <span class="admin shadowed">
            <i class="fa fa-fw fa-eye logo"></i>
            <i class="fa fa-fw fa-send logo"></i>
            <?php
              echo pretty_student($viewer["id"]);
              if (has_editing_rights(binet, term)) {
                echo button(path("delete_viewer", "member", $viewer["id"], binet_prefix(binet, term), array(), true),"Retirer cet observateur","times","red",true,"small");
              }
            ?>
          </span>
          <?php
        }
      } else {
        echo " Il n'y aucun observateur pour ce binet.";
      }
      ?>
    </div>
  </div>
</div>
