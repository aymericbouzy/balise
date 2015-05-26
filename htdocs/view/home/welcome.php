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

  <?php include VIEW_PATH."media/tutorials.php"?>

</div>
 <!-- /container -->
