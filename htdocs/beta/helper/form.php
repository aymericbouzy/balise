<?php

  function form_group($label, $field, $content, $object_name) {
    return "<div class=\"form-group".(isset($_SESSION[$object_name]["errors"]) && in_array($field, $_SESSION[$object_name]["errors"]) ? " has-error" : "")."\">
              <label for=\"".$field."\">".$label."</label>
              ".$content."
            </div>";
  }

  function form_group_text($label, $field, $object, $object_name) {
    return form_group(
      $label,
      $field,
      "<input type=\"text\" class=\"form-control\" id=\"".$field."\" name=\"".$field."\" value=\"".(isset($object[$field]) ? $object[$field] : "")."\">",
      $object_name
    );
  }

  function form_group_date($label, $field, $object, $object_name){
    set_if_not_set($object[$field], "");
    $regex = "/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/";
    if (preg_does_match($regex, $object[$field])) {
      $object[$field] = preg_replace($regex, "$3/$2/$1", $object[$field]);
    }
    return form_group(
    $label,
    $field,
    "<input type=\"text\" class=\"form-control\" id=\"".$field."\" name=\"".$field."\" value=\"".$object[$field]."\">
    <script type=\"text/javascript\">
    $(function () {
      $('#".$field."').datetimepicker({
        format: 'DD/MM/YYYY'
      });
    });
    </script>",
    $object_name
  );
  }

  function form_csrf_token() {
    return form_hidden("csrf_token", get_csrf_token());
  }

  function form_hidden($field, $value) {
    return "<input type=\"hidden\" name=\"".$field."\" value=\"".$value."\">";
  }

  function form_group_checkbox($label, $field, $object, $object_name) {
    return "<div class=\"checkbox".(isset($_SESSION[$object_name]["errors"]) && in_array($field, $_SESSION[$object_name]["errors"]) ? " has-error" : "")."\">
              <label>
                <input type=\"hidden\" name=\"".$field."\" value=\"0\">
                <input type=\"checkbox\" id=\"".$field."\" name=\"".$field."\" value=\"1\"".(is_empty($object[$field]) ? "" : " checked").">
                ".$label."
              </label>
            </div>";
  }

  function form_submit_button($label) {
    return "<input type=\"submit\" class=\"btn btn-default\" value=\"".$label."\">";
  }

  function form_group_select($label, $field, $options, $object, $object_name) {
    $select_tag = "<select class=\"form-control\" id=\"".$field."\" name=\"".$field."\">";
    foreach ($options as $value => $option_label) {
      $select_tag .= "<option value=\"".$value."\"".($object[$field] == $value ? " selected=\"selected\"" : "").">".$option_label."</option>";
    }
    $select_tag .= "</select>";
    return form_group(
      $label,
      $field,
      $select_tag,
      $object_name
    );
  }

  function option_array($entries, $key_field, $value_field, $model_name) {
    $return_array = array();
    foreach ($entries as $entry) {
      $entry = call_user_func("select_".$model_name, $entry["id"], array($key_field, $value_field));
      $return_array[$entry[$key_field]] = $entry[$value_field];
    }
    return $return_array;
  }

  function translate_form_field($form_field) {
    switch ($form_field) {
      case "binet":
      return "binet";
      case "term":
      return "mandat";
      case "comment":
      return "description";
      case "bill":
      return "référence de facture";
      case "reference":
      return "référence de paiement";
      case "amount":
      return "montant";
      case "sign":
      return "dépense";
      case "type":
      return "type de transaction";
      case "paid_by":
      return "payé par";
      case "binet_term":
      return "mandat";
      case "name":
      return "nom";
      case "description":
      return "description";
      case "subsidy_steps":
      return "étapes pour la récupération des subventions";
      case "current_term":
      return "mandat actuel";
      case "submission_date":
      return "date de soumission";
      case "expiry_date":
      return "date d'expiration";
      case "question":
      return "question à poser aux binets";
    }
  }
