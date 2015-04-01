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
  $options_str = substr($options_str,0,-1);
  return "\n<script>\n$('#".$id."').textfill({".$options_str."});\n</script>\n";
}

function initialize_tablefilter($container_id,$options = array()){
    $options_str = "";
    foreach($options as $option){
      $options_str .= "'".$option."' ,";
    }
    $options_str = substr($options_str,0,-1);
    return "\n<script>\n var options = { valueNames: [ ".$options_str." ]};\n var userList = new List('".$container_id."', options); \n</script>\n" ;
}

/* Use initialize tooltips once in a page using tooltips */
function initialize_tooltips(){
  return "\n<script>\n $(function () { $('[data-toggle=\"tooltip\"]').tooltip() })\n</script>\n";
}

function insert_tooltip($html_tag, $tooltip_content, $placement = "top") {
  return insert_properties_in_html_tag($html_tag, array(
    "data-toggle" => "tooltip",
    "data-placement" => $placement,
    "title" => $tooltip_content
  ));
}

function initialize_popovers(){
  return "\n<script>\n $(function () { $('[data-toggle=\"popover\"]').popover() })\n</script>\n";
}

function insert_popover($html_tag, $popover_content, $popover_title, $placement = "top") {
  return insert_properties_in_html_tag($html_tag, array(
    "data-toggle" => "popover",
    "data-placement" => $placement,
    "title" => $popover_title,
    "data-content" => $popover_content
  ));
}

/* Inserts attributes in html tag to create a collapse control
@param  string  $html_tag  The element used to create a control ( e.g. button)
@param  string  $id   The id of the content to collapse.
                      The html tag using the id must have the .collpase class if
                      aria_hidden = true or .collapse.in if aria-hidden = false.
@param string  $aria_hidden  "true" if collapsible is initially hidden ( use of
                              .collapse.in in collapsible element), else "false".
@return string The html tag with inserted attributes
*/
function make_collapse_control($html_tag, $id, $aria_hidden = "true") {
  return insert_properties_in_html_tag(
    $html_tag,
    array(
      "data-toggle" => "collapse",
      "data-target" => "#".$id ,
      "aria-hidden" => $aria_hidden,
      "aria-controls" => $id,
      "style" => "cursor : pointer"
    )
  );
}
