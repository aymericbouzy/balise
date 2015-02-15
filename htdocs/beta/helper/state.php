<?php
function request_state($state){
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
    default:
    return "Erreur : état de la requête inconnu !";
    }
}
