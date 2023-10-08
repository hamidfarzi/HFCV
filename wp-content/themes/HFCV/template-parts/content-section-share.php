<?php

/**
 * The post share section template file
 * 
 * @package hfcv
 */

global $post;

$shortened_url = get_site_url() . '/?p=' . $post->ID;
?>
<section class="post-share">
    <input type="text" class="shortened-url" value="<?php echo esc_url($shortened_url); ?>" readonly>
    <button class="share-button">Copy/Share</button>
</section>