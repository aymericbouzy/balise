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
      Pour recevoir les droits d'administration de ton binet, <?php echo link_to("https://docs.google.com/spreadsheets/d/1haTwZQud3of6Tmc_5dGDUqnicxTtTQtt0UhJKWMAt3g/edit#gid=0", "inscris-toi à un créneau à la Kès"); ?>.
    </p>
    <p>
      Si tu as des remarques ou des suggestions, n'hésite pas à nous en faire part à l'aide du bouton "Contact".
    </p>
  </div>
</div>
 <!-- /container -->
