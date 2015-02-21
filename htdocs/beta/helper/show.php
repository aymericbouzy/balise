<?php

	function ratio_bar($numerator, $denominator, $id, $spending) {
		return "<script>
			ratio1 = ".($denominator != 0 ? $numerator / $denominator : "0").";
			ratiobar(".$spending.", ratio1, '".$id."');
		</script>
		".pretty_amount($numerator)."/".pretty_amount($denominator) ;
	}

	function minipane($id, $title, $numerator, $denominator) {
		return "<div class=\"minipane\" id=\"".$id."\">
		<div class=\"title\">".$title."</div>
			".pretty_amount($numerator)."
			".(isset($denominator) ? " / <span>".pretty_amount($denominator)."</span>" : "").
		"</div>";
	}
