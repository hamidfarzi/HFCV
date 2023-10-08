<?php

/**
 * The hfgsb gutenberg form template file
 * 
 * @package hfcv
 */

$words = str_replace('|', '<br>', $args['words']);
?>

<div class="typing">
    <?php echo $words ?>
</div>