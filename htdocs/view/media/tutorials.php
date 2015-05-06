<div class="container">
  <div class="col-lg-2 col-md-1 col-sm-0"></div>
    <div class="col-lg-8 col-md-10 col-sm-12">
      <?php
        $collapse_control = "<div class=\"panel shadowed\"><div class=\"title\"> Voir les tutos vidéo </div></div>";
        echo make_collapse_control($collapse_control, "tutorials");
      ?>
      <div id="tutorials" class="collapse">
        <?php
          $videos = json_decode(file_get_contents("asset/video/tutorials.json"), true)['videos'];

          // TODO : ajouter un petit index avec des ancres vers les vidéos

          foreach($videos as $video) {
            $video['pathname'] = ASSET_PATH."video/".$video['name'];
            $title_tag = "<div class=\"title\">".$video['title']."</div>";

            ob_start();
            foreach(array(".mp4", ".webm", ".ogv") as $video_format) {
              if(file_exists($video['pathname'].$video_format)) {
                echo "<source src=\"".$video['pathname'].$video_format."\">";
              }
            }
            echo  "Votre navigateur n'est pas à jour, il ne peut pas lire cette vidéo !";

            $video_tag = "<video controls poster=\"".$video['pathname'].".png\" width=\"75%\">".ob_get_clean().
              <source src=$video['pathname'].".mp4">
              <source src=$video['pathname'].".ogv">
              <source src=$video['pathname'].".webm">
              "</video>";

            $back_to_top = link_to("#", "<i class=\"fa fa-fw fa-arrow-up\"></i> Retour en haut de page" );

           ?>

            <div class="panel shadowed light-blue-background" id="<?php echo $video['id']; ?>" >
              <?php echo $title_tag; ?>
              <div class="content">
                <div class="video-content">
                  <?php echo $video_tag; ?>
                </div>
                <?php echo $back_to_top; ?>
              </div>
            </div>
          <?php
          }
        ?>
      </div>
    </div>
  </div>
