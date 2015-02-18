<?php
function request_state($state , $can_view_pseudo_state = false){
  if (!$can_view_pseudo_state && in_array($state, array("reviewed_accepted", "reviewed_rejected"))) {
    $state = "reviewed";
  }
  switch ($state) {
    case "rough_draft":
    return array("name"=>"Brouillon","color"=>"grey","icon"=>"question");
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
    case "reviewed":
    return array("name"=>"Traitée","color"=>"blue","icon"=>"check");
  }
}
