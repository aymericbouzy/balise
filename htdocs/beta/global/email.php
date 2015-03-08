<?php

  function send_email($to, $subject, $file, $parameters) {
    $student = select_student($to, array("email", "name"));
    $to = $student["name"]." <".(STATE == "development" ? WEBMASTER_EMAIL : $student["email"]).">";

    ob_start();
    $GLOBALS["full_path_links"] = true;

    ?>

    <html lang="fr">
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta name="description" content="">
      <meta name="author" content="">
      <title>Balise trézo</title>
      <link rel="shortcut icon" type="image/png" href="<?php echo full_path(IMG_PATH."balise.png"); ?>">
      <link rel="stylesheet" href="<?php echo full_path(ASSET_PATH."dist/css/bootstrap.min.css"); ?>">
      <link rel="stylesheet" href="<?php echo full_path(ASSET_PATH."dist/css/bootstrap-theme.min.css"); ?>">
      <link rel="stylesheet" type="text/css" href="<?php echo full_path(ASSET_PATH."css/email.css"); ?>">

      <!--[if IE]>
      <script src="https://cdn.jsdelivr.net/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://cdn.jsdelivr.net/respond/1.4.2/respond.min.js"></script>
      <![endif]-->
    </head>

    <body>
      <!-- Wrap tout le contenu de la page -->
      <div id="wrap">
        <img id="entete" alt="balise" src="<?php echo full_path(IMG_PATH."balise.png"); ?>">
        <!-- Création d'un header -->
        <header class="masthead">
          <div class="container">
            <div id="tete" class="row">
              <div class="col-xs-offset-3 col-xs-9 col-sm-offset-2 col-md-offset-1">
                <h1>Balise</h1>
                <p class="lead">Trézo facile</p>
              </div> <!-- Fermeture de la colonne -->
            </div> <!-- Fermeture de la ligne -->
          </div> <!-- Fermeture container -->
        </header> <!-- Fermeture du header -->
        <div id="message" class="row">
          <div class="col-md-offset-1 col-md-10">
            <?php include EMAIL_PATH.$file.".php"; // $parameters are used in this file ?>
          </div>
        </div>
      </div> <!-- Fermeture du wrap -->
    </body>

    </html>

    <?php

    unset($GLOBALS["full_path_links"]);
    $message = ob_get_clean();

    return mail_with_headers($to, $subject, $message);
  }
