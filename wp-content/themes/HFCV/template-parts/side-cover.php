<?php

/**
 * The side cover.
 * 
 * @package hfcv
 */
?>
<aside class="side-cover">
    <div class="overlay"></div>
    <?php echo apply_filters('hfcv/filters/thumbnail', get_queried_object(), "full"); ?>
</aside>