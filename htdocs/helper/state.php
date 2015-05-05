<?php
  function request_state($state , $can_view_pseudo_state = false){
    if (!$can_view_pseudo_state && in_array($state, array("reviewed_accepted", "reviewed_rejected"))) {
      $state = "reviewed";
    }
    switch ($state) {
      case "rough_draft":
      return array("name"=>"Brouillon","color"=>"grey","icon"=>"question");
      break;
      case "late_rough_draft":
      return array("name" => "En retard", "color" => "grey", "icon" => "warning");
      break;
      case "overdue_rough_draft":
      return array("name" => "En retard", "color" => "grey", "icon" => "bed");
      break;
      case "reviewed_accepted":
      return array("name"=>"Prête à être acceptée","color"=>"green","icon"=>"check");
      break;
      case "reviewed_rejected":
      return array("name"=>"Prête à être refusée","color"=>"red","icon"=>"times");
      break;
      case "accepted":
      return array("name"=>"Acceptée","color"=>"green","icon"=>"check");
      break;
      case "rejected":
      return array("name"=>"Refusée","color"=>"red","icon"=>"times");
      break;
      case "sent":
      return array("name"=>"Envoyée","color"=>"blue","icon"=>"send");
      break;
      case "sent_late":
      return array("name"=>"Envoyée en retard","color"=>"orange","icon"=>"send");
      break;
      case "reviewed":
      return array("name"=>"Traitée","color"=>"blue","icon"=>"check");
    }
  }

  function wave_state($state) {
    switch ($state) {
      case "rough_draft":
      return array("name" => "Brouillon", "color" => "grey", "icon" => "user-secret");
      case "submission":
      return array("name" => "Demandes en cours", "color" => "blue", "icon" => "crosshairs");
      case "deliberation":
      return array("name" => "Études des demandes", "color" => "teal", "icon" => "cogs");
      case "distribution":
      return array("name" => "Émise", "color" => "green", "icon" => "money");
      case "closed":
      return array("name" => "Expirée", "color" => "red", "icon" => "times");
    }
  }

  function request_used_amount_status($request){
    if($request["granted_amount"] == 0) return "grey";
    
    $remaining = ($request["granted_amount"] - $request["used_amount"]) / $request["granted_amount"];
    if($remaining < 0){
      return "red";
    } else if ($remaining == 0){
      return "red";
    } else if($remaining > 0 && $remaining < 0.3){
      return "teal";
    } else {
      return "green";
    }
  }
