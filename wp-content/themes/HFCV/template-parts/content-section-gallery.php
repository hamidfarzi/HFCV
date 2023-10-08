<?php

/**
 * The post gallery section template file
 * 
 * @package hfcv
 */

global $post;
$gallery_images = get_post_meta($post->ID, "hfcv_post_gallery", true);
?>
<?php if (!empty($gallery_images)) :  ?>
    <section class="post-gallery">
        <div data-columns=1 style="--data-columns:1" class="carousel owl-carousel owl-loaded owl-drag">
            <div class="gallery-container owl-stage-outer">
                <div class="gallery-loop owl-stage">
                    <?php
                    foreach ($gallery_images as $image_id) {
                        echo wp_get_attachment_image($image_id, 'full', false, ['class' => 'post-gallery-item owl-item']);
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>