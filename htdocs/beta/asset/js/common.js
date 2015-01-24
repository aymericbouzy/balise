function ratiobar (spending, ratio, elementId) {
  var element = document.getElementById(elementId);
  var r = Math.floor(ratio*100);
  if (ratio <= 1) {
    width = "" + r + "%";

    if (ratio < 0.6) {
      if (spending)
        element.style.backgroundColor = '#52CC29'; /* Green */
      else
        element.style.backgroundColor = '#FF4D4D'; /* Red */
    }

  } else {
    /* Trop de dépenses ou de bonnes recettes*/
    if (spending)
      element.style.backgroundColor = '#FF4D4D'; /* Red */
    else
      element.style.backgroundColor = '#52CC29'; /* Green */

  }
  element.style.width = width ;
}


function goto (str) {
  window.location.href = str;
}
