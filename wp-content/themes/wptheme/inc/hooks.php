<?php
/**
 * Custom hooks
 *
 * @package LaRestaurante
 */

function add_content_before(){?>
	<div id="content" class="content-area">
	<main id="main" class="site-main" role="main">
<?php } 
add_action('larestaurante_before_content','add_content_before');

function add_content_after(){?>
</div></main></div>	
<?php } 
add_action('larestaurante_after_content','add_content_after');