<div id="sidebar">
<?php
$options = twentyeleven_get_theme_options();
$current_layout = $options['theme_layout'];

if ( 'content' != $current_layout ) {
get_search_form();
dynamic_sidebar('widget-zone');
}
?>
</div>

