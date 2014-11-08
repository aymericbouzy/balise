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
          <?php echo $budget["label"];
          foreach (select_tags_budget($budget["id"]) as $tag) {
            $tag = select_tag($tag, array("name", "id"));
            ?><span class="label"><?php echo $tag["name"]?></span><?php
          }?>
        </td>
        <td>
          
        </td>
      </tr>
    <?php
  }
  ?>
</table>
