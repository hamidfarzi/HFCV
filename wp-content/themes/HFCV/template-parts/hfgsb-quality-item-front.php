<?php

/**
 * The hfgsb gutenberg form template file
 * 
 * @package hfcv
 */

$title = $args['title'];
$value = $args['value'];

?>

<div class="quality-item">
    <div class="quality-title accent-text">
        <?php echo $title; ?>
    </div>
    <div class="quality-value">
        <?php echo $value; ?>
    </div>
</div>