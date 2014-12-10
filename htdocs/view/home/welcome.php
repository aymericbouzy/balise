<header class="masthead">
  <div class="container">
    <div class="row">
      <div class="col-sm-6">
        <h1><a href="#" title="Bootstrap Template">Projet Balise.</a>
          <p class="lead">{Trézo facile}</p></h1>
      </div>
      <div class="col-sm-6">
        <div class="pull-right  hidden-xs">
          <?php echo link_to(path("login", "home"), "<h3>Connexion via Frankiz</h3>", "btn"); ?>
        </div>
      </div>
    </div>
  </div>
</header>


<!-- Fixed navbar -->
<div class="navbar navbar-custom navbar-inverse navbar-static-top" id="nav">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
      </div>
      <div class="collapse navbar-collapse">
        <ul class="nav navbar-nav nav-justified">
          <li><a href="#section1">Le projet</a></li>
          <li class="active"><a href="#section1"><strong>Balise</strong></a></li>
          <li><a href="#contact">Contact</a></li>
        </ul>
      </div><!--/.nav-collapse -->
    </div><!--/.container -->
</div><!--/.navbar -->

    <!-- Begin page content -->
<div class="divider" id="section1"></div>

<div class="container">
  <div class="col-sm-10 col-sm-offset-1">
    <div class="page-header text-center">
      <h1>Projet Balise ?</h1>
    </div>

    <p class="lead text-center">
      Balise est un projet visant à faciliter la gestion de la trésorerie de vos binets.
    </p>
    <p class="text-center">
      Quoi de plus ennuyeux que la trésorerie dans un binet ? Et si on vous aidait à garder une comptabilité claire, de façon transparente, sans changer aucun fonctionnement ? C'est le but du Projet Balise : une gestion plus facile du coté des binets, une tenue des comptes qui aide les kessiers binets. Pas convaincus ? Voilà la démo.
    </p>
  </div>
</div>

<div class="divider" id="section2"></div>

<section class="bg-1">
  <div class="col-sm-6 col-sm-offset-3 text-center"><h2 style="padding:20px;background-color:rgba(5,5,5,.8)">Plus aucun problème de communication.</h2></div>
</section>

<div class="divider"></div>

<div class="container" id="section3">
    <div class="col-sm-8 col-sm-offset-2 text-center">
      <h1>Trézos -- Kessiers</h1>

      <p>
      Une communication facilitée. Une tenue des comptes transparente pour tout le monde. On facilite la vie des deux cotés ! Vous allez voir, c'est AMAZING !
      </p>

    </div><!--/col-->
</div><!--/container-->

<div class="divider"></div>

<div class="row" id="contact">

  <h1 class="text-center">Vous avez des idées ?</h1>

  <div class="col-sm-8">

      <div class="row form-group">
        <div class="col-xs-3">
          <input type="text" class="form-control" id="firstName" name="firstName" placeholder="Prenom" required="">
        </div>
        <div class="col-xs-3">
          <input type="text" class="form-control" id="middleName" name="firstName" placeholder="Nom" required="">
        </div>
      </div>

      <div class="row form-group">
          <div class="col-xs-10">
          <input type="homepage" class="form-control suggestion" placeholder="Questions/suggestions" required="">
          </div>
      </div>
      <div class="row form-group">
          <div class="col-xs-10">
            <button class="btn btn-default pull-right">Suggérer</button>
          </div>
      </div>

  </div>
  <div class="col-sm-3 pull-right">

      <address>
        <strong>Team Projet Balise</strong><br>
      </address>

      <address>
        <strong>Email:</strong><br>
        <a href="mailto:#">nathan.eckert@polytechnique.edu</a>
      </address>
  </div>

</div><!--/row-->

<div class="container">
  <div class="col-sm-8 col-sm-offset-2 text-center">
    <h2>Projet Balise. Nos binets le valent bien.</h2>

  </div><!--/col-->
</div><!--/container-->
