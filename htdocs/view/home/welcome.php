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
      Si tu as des remarques ou des suggestions, n'hésite pas à nous en faire part à l'aide du bouton "Contact".</br>
      N'hésite pas non plus à aller regarder les tutoriels vidéos si tu as un souci.
    </p>
  </div>
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
