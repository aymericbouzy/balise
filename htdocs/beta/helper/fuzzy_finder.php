<?php
  function fuzzy_load_scripts($container_name,$attribute_name){
    return "<script src=\"".ASSET_PATH."js/filter.js\"></script>
    <script src=\"".ASSET_PATH."js/list.fuzzysearch.js\"></script>
    <script>
    var objects_list = new List('".$container_name."', {
      valueNames: ['".$attribute_name."'],
      plugins: [ ListFuzzySearch() ]
    });
    </script>\n";
  }

  function fuzzy_input(){
    return "<input type=\"search\" class=\"fuzzy-search\">
    <i class=\"fa fa-fw fa-search\"></i>";
  }