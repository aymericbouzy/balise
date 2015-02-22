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

  <?php
    $term_admins = select_terms(array("student"=>$_SESSION["student"]));
    foreach($term_admins as $term_admin) {
      $term_admin = select_term_binet($term_admin["id"],array("id","binet","term"));
      $id = "binet".$term_admin["binet"];
      echo link_to(
        path("", "binet", binet_term_id($term_admin["binet"], $term_admin["term"])),
        "<div class=\"spot opanel\">
          <div class=\"binet-name\" id=\"".$id."\"><span>".pretty_binet($term_admin["binet"], false)."</span></div>
          <div class=\"binet-term\">".$term_admin["term"]."</div>
        </div>",
        array("goto"=>true)
      );
      echo initialize_textfill($id,array("minFontPixels"=>"10","maxFontPixels"=>"20"));
    }
    if (is_empty($term_admins)) {
    echo "<p>
      Vous n'avez pas de binets pour le moment ...
      </p>";
    }
  ?>
  <div id="homelinks" class="opanel2">
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
