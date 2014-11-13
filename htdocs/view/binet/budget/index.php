<h1>Comptes</h1>
<div>
  <input type="text" name="search" value="">
  <div class="btn">
    Filtrer
  </div>
  <div id="filters">

  </div>
  <div class="switch">
    <div class="btn selected">
      Budget
    </div>
    <div class="btn">
      Opérations
    </div>
  </div>
</div>
<table>
  <?php foreach ($budgets as $budget) {
    ?>
      <tr>
        <td>
          <?php
          echo $budget["label"];
          echo pretty_tags(select_tags_budget($budget["id"]), true);
          ?>
        </td>
        <td>

        </td>
      </tr>
    <?php
  }
  ?>
</table>
