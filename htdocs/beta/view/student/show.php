<?php echo $student["name"]; ?>
<br>
<?php echo link_to("mailto:".$student["name"]." <".$student["email"].">", $student["email"]); ?>
