<?
require_once 'init.php'; // database connection, etc

require_once 'class_pageable_list.php';  // Could instead load this in the init file

$query = "SELECT * FROM " . PEOPLE_TABLE . " WHERE 1 ";    // OK to put SQL in this file (instead of table class) 
                                                           // since the listing config in this file 
                                                           // is directly dependent upon the SQL query
                                                          
$listing = new pg_list($query, 'ppl_id', 'ppl_age', 'ASC', '', '', 1, 5, true,    4,'even_row_css','odd_row_css','highlight_css');

// Columns straight from the database - don't get any additional formating.
$listing->add_column('ppl_name', 'Name');
$listing->add_column('ppl_age', 'Age');

// For simple reformatting of the column's value, use the third parameter as shown below.
// It must match a case in the format_value() function near the end of class_pageable_list.php
$listing->add_column('ppl_age', 'Geriatric Status','young_or_old');
$listing->add_column('ppl_states_visited', 'States Visited','json_array_to_comma_delimited','','','',false); 
// Last parameter just above parameter makes column not sortable.

// For more elaborate formatting of a column, use the 9th parameter as shown below.
// Such custom formattting reqires custom coding in the get_row() function in class_pageable_list.php
$listing->add_column('', 'actions','','','','',false,'','column_action_links','page_form.php');

// Get that puppy ready - It's put into the page below with $listing->get_html()
$listing->init_list();

$page_title = "Pageable Listing";
?>
<? require "ssi_top.php"; ?>

<style>
/*
  Would be best to put this stuff in an external stylesheet used by the app.
  Putting here so that this example is self contained. 
*/
.even_row_css {
   background-color:#EEE;
   font-size:10pt;
}
.odd_row_css {
   background-color:#DDD;
   font-size:10pt;
}
.highlight_css {
   background-color:#DDF;
   font-size:10pt;
}
tbody th {
  text-align: left;
}
</style>

Example Page Implementing the Pageable Listing Tool
<br><br>
<a href="page_form.php">Go To New Person Form</a>
<br><br>

<?=$listing->get_html()?>

<script type="text/javascript">

  function confirm_delete(ppl_id, ppl_name) {
    var choice = confirm("Are you sure you want to delete " + ppl_name + "?");

    if ( choice == true ) {
      window.location.href = "page_form.php?task=delete&ppl_id="+ppl_id;
    }
  }

</script>

<? require "ssi_bottom.php"; ?>