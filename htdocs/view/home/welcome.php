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
            
  
  <!-- Page wrapper content : simple button to login using Frankiz-->
  <!-- TODO : update to show a nicer homepage -->          
 <div class="container">
    <div class="signin">
       <?php echo link_to(path("login", "home"), "<h3>Connexion via Frankiz</h3>", "btn btn-primary"); ?>
    </div>
 </div>
 <!-- /container -->