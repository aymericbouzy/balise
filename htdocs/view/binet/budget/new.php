<h1>Nouvelle ligne budgétaire</h1>
<?php echo link_to(path("new_income", "budget", "", binet_prefix($binet["id"], $term)), "Recette"); ?>
<?php echo link_to(path("new_expense", "budget", "", binet_prefix($binet["id"], $term)), "Dépense"); ?>
