<div class="row-centered">
  <div class="col-max">
    <h2 class="tabtitle">Opérations à valider</h2>
    <!--Script links to selected operation when line clicked-->
    <script>
      function goto(str){
        window.location.href=str;
      }
    </script>
    <div class="table-responsive" id="validations-table">
      <table class="table table-bordered table-hover table-small-char">
        <thead>
          <tr>
            <th>Date</th>
            <th>Nom</th>
            <th>Origine</th>
            <th>Montant</th>
          </tr>
        </thead>
        <tbody>
          <?php
            foreach ($pending_validations_operations as $operation) {
              echo validatable_operation_line($operation, true);
            }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php

  if ($binet == KES_ID) {
    ?>
      <div class="row-centered">
        <div class="col-max">
          <h2 class="tabtitle">Opérations à valider par la Kès</h2>
          <!--Script links to selected operation when line clicked-->
          <script>
          function goto(str){
            window.location.href=str;
          }
          </script>
          <div class="table-responsive" id="validations-table-kes">
            <table class="table table-bordered table-hover table-small-char">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Nom</th>
                  <th>Origine</th>
                  <th>Montant</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  foreach ($pending_validations_operations as $operation) {
                    echo validatable_operation_line($operation, true);
                  }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    <?php
  }

?>
