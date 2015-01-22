<?php

	function validatable_operation_line($operation, $clickable){
		$operation = select_operation($operation["id"], array("id", "date", "comment", "created_by", "amount", "binet", "term"));
		$line_str = "<tr>
			            <td>".pretty_date($operation["date"])."</td>
									<td>".$operation["comment"]."</td>
									<td>".pretty_student($operation["created_by"])."</td>
									<td>".pretty_amount(-$operation["amount"])."</td>
			          </tr>";
		if ($clickable) {
			return link_to(path("show", "operation", $operation["id"], binet_prefix($operation["binet"], $operation["term"])), $line_str);
		} else {
			return $line_str;
		}
	}

	function ratio_bar($numerator, $denominator) {
		return "
		<script>
			ratio1 = ".($numerator/$denominator).";
			ratiobar( ratio1 , 'real_budget');
		</script>
		".$numerator."/".$denominator;
	}
