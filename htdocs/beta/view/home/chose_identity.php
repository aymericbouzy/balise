<h1>Choisir une identité</h1>
<ul>
  <?php
  foreach (select_students() as $student) {
    $student = select_student($student["id"], array("id", "name"));
    echo "<li>".link_to(path("login", "home", "", "", array("student" => $student["id"], "response" => "chose_identity")), $student["name"]);
    foreach (select_terms(array("student" => $student["id"], "query_array")) as $term) {
      echo " ".pretty_binet_term($term["id"], false);
    }
    echo "</li>";
  }
  ?>
</ul>
