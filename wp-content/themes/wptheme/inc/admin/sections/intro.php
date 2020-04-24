<?php
/**
 * Welcome screen intro template
 */
?>

<div class="col two-col" style="margin-bottom: 1.618em; overflow: hidden;">
    <div class="col">
        <h1 style="margin-right: 0;"><?php echo '<strong>LaRestaurante</strong>'; ?></h1>
        <?php $formatted_string = wpautop('				In this theme you can:

														
														- print phone number
														- print opening time

														'); ?>


        <p style="font-size: 1.2em;"><?php _e($formatted_string, 'larestaurante'); ?></p>

    </div>

    <div class="col last-feature">
        <img src="<?php echo esc_url(get_template_directory_uri()) . '/screenshot.png'; ?>" alt="LaRestaurante"
             class="image-50" width="440"/>
    </div>
</div>