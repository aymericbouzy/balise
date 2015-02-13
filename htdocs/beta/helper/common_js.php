<?php
/*
options are :
- minFontPixels	Minimal font size (in pixels)
- maxFontPixels	Maximum font size the text could resize (in pixels).
For size <= 0, the text is sized to as big as the container can accommodate.
- innerTag	The child element tag to resize.
- widthOnly	only resizing for width restraint
- explicitWidth	explicit width
- explicitHeight	explicit height
- debug	Output debugging messages to console
*/
function initialize_textfill($id,$options = array() ){
  $options_str = "\n";
  foreach($options as $property => $value){
    $options_str.= $property." : ".$value. "," ;
  }
  return "<script>\n$('#".$id."').textfill({".$options_str."});\n</script>";
}
