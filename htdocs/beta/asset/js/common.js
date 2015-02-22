function ratiobar (spending, ratio, elementId) {
  var element = document.getElementById(elementId);
  var green = '#52CC29', red = '#FF4D4D';
  if ((spending && ratio <= 1) || (!spending && ratio >= 1)) {
    element.style.backgroundColor = green;
  } else {
    element.style.backgroundColor = red;
  }
  if (ratio > 1) {
    ratio = 1;
  }
  width = "" + Math.floor(ratio*100) + "%";
  element.style.width = width ;
  element.style.overflow = "visible";
}


function goto (str) {
  window.location.href = str;
}

function hide_form_element(id) {
  document.getElementById(id).className ='hidden-element';
}

function show_hidden_form_element(id) {
    document.getElementById(id).className ='';
}
