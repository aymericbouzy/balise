function ratiobar( ratio , elementId){
  var element = document.getElementById(elementId);
  var r = Math.floor(ratio*100);
  if (ratio <= 1 ){
    width = "" + r + "%";

    if (ratio < 0.6){
      element.style.backgroundColor= '#52CC29'; /* Green */
    }

  } else {
    /* Warning : trop de dépenses */
    element.style.backgroundColor= '#FF4D4D'; /* Red */
    width = '100%';
  }
  element.style.width = width ;
}
