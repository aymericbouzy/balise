<div class="row-centered">
    <div class="row-centered">
     	<div class="col-max">
     		<h2 class="tabtitle">Valider l'opération :</h2>

     		<div class="table-responsive" id="validations-table">
     			<table class="table table-bordered table-hover table-small-char">
    				<tbody>
     					<?php echo line_noclickable($date,$title,$origin,$amount) ?>
    					</tbody>
     				</table>
     			</div>
     		</div>                  
     	</div>
	</div>
                
  <div class="row-centered">
      <div class="col-max">
           <h2 class="tabtitle">Budgets :</h2>
                            
              <div class="table-responsive" id="validations-table">
                  <table class="table table-bordered table-hover table-small-char">
                       <tbody>
                       	<?php foreach ($budgets as $budget) { ?>
                            <tr>
            							<td><?php echo link_to(path("show", "budget", $budget["id"], binet_prefix($binet["id"], $term)), $budget["label"]); ?></td>
            							<td><?php echo pretty_tags(select_tags_budget($budget["id"]), true); ?></td>
            							<td><?php echo pretty_amount($budget["amount"]); ?></td>
            							<td><?php echo pretty_amount($budget["real_amount"]); ?></td>
            							<td><?php echo pretty_amount($budget["subsidized_amount_granted"]); ?></td>
            							<td><?php echo pretty_amount($budget["subsidized_amount_used"]); ?></td>
            							<td><input type="text" class="amount-input" onchange="total()"></td>
          						</tr>
                          <?php } ?>              
                         </tbody>
                       <thead class="separator">
                             <tr>
                                <td colspan="7"></td>
                             </tr>
                         </thead>
                       <tbody>
                            <tr class="total">
                              <td colspan="2">Total</td>
										<td>1000</td>
										<td>5000</td>
										<td>10000</td>
                             	<td id="sum">0</td>
                            </tr>
                                    <script>
                                        function total(){
                                            var inputs=document.getElementsByTagName('input');
                                            var i,s;
                                            s=0;
                                            for(i=1;i<=inputs.length;i++){
                                                s+=inputs.item(i).innerHTML;
                                            }
                                            document.getElementById('sum').innerHTML=s;
                                        }
                                    </script>
                        </tbody>
                        </table>
              </div>
         </div>
</div>