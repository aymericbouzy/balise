<?php

function validatable_operation_line($operation, $clickable){
	$operation = select_operation($operation["id"], array("id", "date", "comment", "created_by", "amount", "binet", "term"));

	return "<tr ".($clickable ? "onclick=\"goto('".path("show", "operation", $operation["id"], binet_prefix($operation["binet"], $operation["term"]))."')\"" : "").">
            <td>".pretty_date($operation["date"])."</td>
						<td>".$operation["comment"]."</td>
						<td>".pretty_student($operation["created_by"])."</td>
						<td>".pretty_amount(-$operation["amount"])."</td>
          </tr>";

}
