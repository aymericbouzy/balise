<?php

  function create_binet($name, $term) {
    $values["name"] = $name;
    $values["current_term"] = $term;
    $values["clean_name"] = clean_string($values["name"]);
    $values["description"] = "";
    return create_entry(
      "binet",
      array("current_term"),
      array("name", "clean_name", "description"),
      $values
    );
  }

  function select_binet($binet, $fields = array()) {
    $binet = select_entry(
      "binet",
      array("id", "name", "clean_name", "description", "current_term", "subsidy_provider", "subsidy_steps"),
      $binet,
      $fields
    );
    return $binet;
  }

  function exists_binet($binet) {
    return select_binet($binet) ? true : false;
  }

  function select_binets($criteria = array(), $order_by = "", $ascending = true) {
    return select_entries(
      "binet",
      array("subsidy_provider", "current_term", "id"),
      array("name", "clean_name"),
      array(),
      $criteria,
      $order_by,
      $ascending
    );
  }

  function update_binet($binet, $hash) {
    if (isset($hash["name"])) {
      $hash["clean_name"] = clean_string($hash["name"]);
    }
    update_entry("binet",
                  array(),
                  array("description", "name", "clean_name", "subsidy_steps"),
                  $binet,
                  $hash);
  }

  function set_subsidy_provider($binet) {
    update_entry(
      "binet",
      array("subsidy_provider"),
      array(),
      $binet,
      array("subsidy_provider" => 1)
    );
  }

  function unset_subsidy_provider($binet) {
    update_entry(
      "binet",
      array("subsidy_provider"),
      array(),
      $binet,
      array("subsidy_provider" => 0)
    );
  }

  function current_term($binet) {
    return select_binet($binet, array("current_term"))["current_term"];
  }

  function deactivate_binet($binet) {
    $sql = "UPDATE binet
            SET current_term = NULL
            WHERE id = :binet
            LIMIT 1";
    $req = Database::get()->prepare($sql);
    $req->bindValue(':binet', $binet, PDO::PARAM_INT);
    $req->execute();
  }

  function change_term_binet($binet, $term) {
    update_entry(
      "binet",
      array("current_term"),
      array(),
      $binet,
      array("current_term" => $term)
    );
  }
