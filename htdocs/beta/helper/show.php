<?php

	function validatable_operation_line($operation, $clickable){
		$operation = select_operation($operation["id"], array("id", "date", "comment", "created_by", "amount", "binet", "term"));

		return "<tr ".($clickable ? "onclick=\"goto('/".path("review", "operation", $operation["id"], binet_prefix($operation["binet"], $operation["term"]))."')\"" : "").">
							<td>".pretty_date($operation["date"])."</td>
							<td>".$operation["comment"]."</td>
							<td>".pretty_student($operation["created_by"])."</td>
							<td>".pretty_amount($operation["amount"])."</td>
						</tr>";
	}

	function ratio_bar($numerator, $denominator) {
		return ($denominator>0)? "
		<script>
			ratio1 = ".($numerator/$denominator).";
			ratiobar( ratio1 , 'real_budget');
		</script>
		".pretty_amount($numerator)."/".pretty_amount($denominator) :
		"
		<script>
		ratio1 = 0;
		ratiobar( ratio1 , 'real_budget');
		</script>
		".pretty_amount($numerator)."/".pretty_amount($denominator)
		;
	}
