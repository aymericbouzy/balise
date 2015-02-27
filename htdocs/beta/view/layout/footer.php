<span id="link-to-webmasters">
	Site créé par <?php echo link_to("mailto:Nathan Eckert <nathan.eckert@polytechnique.edu>", "Nathan"); ?>,
	<?php echo link_to("mailto:Victor Nicolet <victor.nicolet@polytechnique.edu>", "Little"); ?> et
	<?php echo link_to("mailto:Aymeric Bouzy <aymeric.bouzy@polytechnique.edu>", "Zouby"); ?>.
</span>
<span id="bug-report-action">
<?php
	echo modal_toggle("bug-report-toggle", "Rapport de bug", "btn btn-primary", "bug-report");
	echo modal("bug-report", "Rapport de bug", get_html_form("bug_report"));
?>
</span>
