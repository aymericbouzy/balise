<?php

function line_clickable($date,$title,$amount,$origin,$id){
	/* TODO : put link in goto */
	return
	"<tr onclick=\"goto('---LINK HERE USING $id-----')\">
                                       <td>".$date."</td>
													<td>".$title."</td>
													<td>".$origin."</td>
													<td>".$amount."</td>
                                    </tr>";
		
}

function line_noclickable($date,$title,$amount,$origin,){
	/* TODO : put link in goto */
	return
	"<tr>
      <td>".$date."</td>
		<td>".$title."</td>
		<td>".$origin."</td>
		<td>".$amount."</td>
      </tr>";
		
}

