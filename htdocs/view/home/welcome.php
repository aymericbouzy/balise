<!-- page-wrapper content : simple button to login using Frankiz-->
<div id="welcome">
  <?php echo link_to(path("login","home"), "<div>Connexion via Frankiz</div>", array("class" => "shadowed", "id" => "login", "goto"=>true)); ?>
  <div id="presentation" class="shadowed">
    <p>
      Le site Balise te permet de :
      <ul>
        <li>gérer la trésorerie de ton binet</li>
        <li>demander et gérer tes subventions</li>
      </ul>
      Pour recevoir les droits d'administration de ton binet, va faire ta passation à la Kès et demande les droits aux kessiers binets.
    </p>
    <p>
      Si tu as des remarques ou des suggestions, sens toi libre d'utiliser le bouton "Contact" pour nous en faire part.</br>
      N'hésite pas à te mettre sympathisant <a href="https://www.frankiz.net/groups/see/projetbalise">frankiz</a> ou à consulter notre page <a href="http://wikix.polytechnique.org/balise">wikiX</a>.</br>
      N'hésite pas non plus à aller regarder les tutoriels vidéos si tu as un souci.
    </p>
  </div>

  <div class="container">
  	<div class="col-lg-2 col-md-1 col-sm-0"></div>
  	<div class="col-lg-8 col-md-10 col-sm-12">
	  	<?php
				$collapse_control = "<div class=\"panel shadowed\"><div class=\"title\"> Voir les tutos vidéo </div></div>";
				echo make_collapse_control($collapse_control, "tutorials");
			?>
			<div id="tutorials" class="collapse">
			  <?php
			  	// TODO : mettre ici les vidéos, infos sur les vidéos, ou même faire un fichier contenant ces infos ?
			  	// --> fichier de métadonnées externe type json

			  	$videos = array(
			  			array("pathname"=>"../../video/presentation",
			  					"title" => "Hello world",
			  					"id" => "video1"
			  					),
			  			array("pathname"=>"../../video/presentation",
			  					"title" => "Hello world",
			  					"id" => "video1"
			  			)
			  	);

			  	// TODO : ajouter un petit index avec des ancres vers les vidéos

			  	foreach($videos as $video){
			  		$title_tag = "<div class=\"title\">".$video['title']."</div>";

						ob_start();
						echo "<video controls poster=\"".$video['pathname'].".png\" width=\"75%\">";
						foreach(array(".mp4", ".webm", ".ogv") as $video_format) {
							if(file_exists($video['pathname'].$video_format)) {
								echo "<source src=\"".$video['pathname'].$video_format."\">";
							}
						}
						echo	"Votre navigateur n'est pas à jour, il ne peut pas lire cette vidéo !".
			    			"</video>";
						$video_tag = ob_get_clean();

						$back_to_top = link_to("#","<i class=\"fa fa-fw fa-arrow-up\"></i> Retour en haut de page" );

						echo "<div class=\"panel shadowed light-blue-background\" id=\"".$video['id']."\" >".
										$title_tag.
										"<div class=\"content\">
			      					<div class=\"video-content\">".
			      						$video_tag.
			      					"</div>".
			      					$back_to_top.
			  						"</div>
			  					</div>";

			  	}
			  ?>
		  </div>
	  </div>
	</div>
  <!-- TODO : supprimer le contenu ci-dessous une fois que le code ci dessus fonctionne -->
  <div id="video1" class="panel shadowed">
    <div class="title">
      Tutoriel : présentation générale du site
    </div>
    <video controls poster="../../video/presentation.png" width="75%">
      <source src="../../video/presentation.mp4">
      <source src="../../video/presentation.webm">
      <source src="../../video/presentation.ogv">
        Votre navigateur n'est pas à jour, il ne peut pas lire cette vidéo !
    </video>
  </div>
  <div id="video2" class="panel shadowed">
    <div class="title">
      Tutoriel : mon binet et son budget
    </div>
    <video controls poster="../../video/monbinet.png" width="75%">
      <source src="../../video/monbinet.mp4">
      <source src="../../video/monbinet.webm">
      <source src="../../video/monbinet.ogv">
        Votre navigateur n'est pas à jour, il ne peut pas lire cette vidéo !
    </video>
  </div>
  <div class="panel shadowed">
    <div class="title">
      Tutoriel : mon budget et mes opérations
    </div>
    <video controls poster="../../video/operation.png" width="75%">
      <source src="../../video/operation.mp4">
      <source src="../../video/operation.webm">
      <source src="../../video/operation.ogv">
        Votre navigateur n'est pas à jour, il ne peut pas lire cette vidéo !
    </video>
  </div>
  <div class="panel shadowed">
    <div class="title">
      Tutoriel : qui a accès à ma trésorerie
    </div>
    <video controls poster="../../video/droits.png" width="75%">
      <source src="../../video/droits.mp4">
      <source src="../../video/droits.webm">
      <source src="../../video/droits.ogv">
        Votre navigateur n'est pas à jour, il ne peut pas lire cette vidéo !
    </video>
  </div>
  <div class="panel shadowed">
    <div class="title">
      Tutoriel : faire ma demande de subventions
    </div>
    <video controls poster="../../video/subvention.png" width="75%">
      <source src="../../video/subvention.mp4">
      <source src="../../video/subvention.webm">
      <source src="../../video/subvention.ogv">
        Votre navigateur n'est pas à jour, il ne peut pas lire cette vidéo !
    </video>
  </div>
</div>
 <!-- /container -->
