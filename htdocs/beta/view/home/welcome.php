 <!-- Very small CSS for login page : if needed, move to another file -->
<style>
  	body {
     	margin-top:0px;
    	margin-left:-225px;
    	height: 225px;
    	background-color: #eee;
   		}
 	.signin {
   	margin-top: auto;
  		max-width: 330px;
  		padding: 15px;
   	margin: 0 auto;
   	}
</style>


  <!-- page-wrapper content : simple button to login using Frankiz-->
  <!-- TODO : update to show a nicer homepage -->
 <div class="container">
    <div class="signin">
       <?php echo link_to(path("login", "home"), "<h3>Connexion via Frankiz</h3>", "btn btn-primary"); ?>
    </div>
 </div>

 <div class="well well-lg">
   <p>
     Bienvenu sur la version beta du projet Balise ! Les informations sur le site sont pour l'instant <b>fictives</b>. Nous t'invitons à naviguer sur le site, à tester les fonctionnalités et à nous faire remonter tous les bugs rencontrés grâce au bouton en bas à droite.
   </p>
   <p>
     Lorsque tu rencontres un bug, essaye de décrire aussi précisément que possible les étapes qui l'ont généré. Pense également à inclure une capture d'écran s'il s'agit d'un bug d'affichage. La personne que nous jugerons la plus utile pour le debbugage du site recevra une récompense ! Tu peux aussi l'utiliser pour nous faire tes remarques, suggestions et compliments ;)
   </p>
   <p>
     Merci !
   </p>
   <p>
     L'équipe du projet Balise.
   </p>
 </div>
 <!-- /container -->
