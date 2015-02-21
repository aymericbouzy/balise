<div id="home-wrapper">
  <?php
  // TODO define news and how to store/get them
  foreach (array() as $news) {
    ?>
    <div class="flashcard opanel news alert alert-dismissible fade in">
      <button class="close" data-dismiss="alert">
        <i class="fa fa-fw fa-close"></i>
      </button>
      <h1><?php echo $news["title"]; ?></h1>
      <p>
        <?php echo $news["content"]; ?>
      </p>
      <div class="controls">
        <?php
        foreach ($news["controls"] as $control) {
          ?>
          <span class="ctrl"><i class="fa fa-fw <?php echo $control["icon"]; ?> bt"></i><span class="which-action"><?php echo $control["name"]; ?></span></span>
          <?php
        }
        ?>
      </div>
    </div>
    <?php
  }
  ?>
  <div class="panel opanel">
    <div class="title">
      Mes binets
    </div>
  </div>
  <div id="mybinets">
    <?php
      $term_admins = select_terms(array("student"=>$_SESSION["student"]));
      foreach($term_admins as $term_admin) {
        $term_admin = select_term_binet($term_admin["id"],array("id","binet","term"));
        $id = "binet".$term_admin["binet"];
        $number_pending_validations = count_pending_validations($term_admin["binet"], $term_admin["term"]);
        echo link_to(
          path("", "binet", binet_term_id($term_admin["binet"], $term_admin["term"])),
          "<div class=\"spot opanel\">".
            ($number_pending_validations > 0 ?
                insert_tooltip(
                  link_to(path("", "validation", "", binet_prefix($term_admin["binet"], $term_admin["term"])),
                    $number_pending_validations,array("class"=>"validations opanel0")),
                  "Validations en attente")
                : "")."
            <div class=\"binet-name\" id=\"".$id."\"><span>".pretty_binet($term_admin["binet"], false)."</span></div>
            <div class=\"binet-term\">".$term_admin["term"]."</div>
          </div>",
          array("goto"=>true)
        );
        echo initialize_textfill($id,array("minFontPixels"=>"10","maxFontPixels"=>"20"));
      }
      if (is_empty($term_admins)) {
      echo "<p>
        Tu n'as pas de binets pour le moment ...
        </p>";
      }
    ?>
  </div>
  <div class="panel opanel3">
    <div class="title">
      Informations générales
    </div>
  </div>
  <div id="homelinks" class="panel opanel2">
    <?php
      echo link_to(
        path("","wave"),
        "<div>   <i class=\"fa-fw fa fa-money\"></i> Subventions </div>",
        array("class" => "homelink", "id" => "subsidies","goto" => "true" ));
      ?>
    <?php
      echo link_to(
        path("","binet"),
        "<div>   <i class=\"fa-fw fa fa-group\"></i> Binets </div>",
        array("class" => "homelink", "id" => "binets","goto" => "true" ));
      ?>
  </div>
</div>
