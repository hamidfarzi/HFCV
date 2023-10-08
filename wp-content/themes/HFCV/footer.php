<?php

/**
 * The HFCV theme footer.
 * 
 * @package hfcv
 */
?>

<footer id="site-footer" class="site-footer">
    <div class="title">
        <h2>Contact
            <small class="subtitle">Let's Talk</small>
        </h2>
    </div>
    <div class="flex-break"></div>
    <section class="contact-form">
        <form name="contact-form" action="ajax" method="post">
            <div class="responce" style="display:none"></div>
            <div class="form-group">
                <input type="hidden" name="action" id="action" value="contact_form_submittion">
                <label required for="name">Name</label>
                <input type="text" name="name" id="name" required class="form-control" placeholder="Your name">
                <label required for="email">Email</label>
                <input type="email" name="email" id="email" required class="form-control" placeholder="Your email">
                <label required for="phone">Phone</label>
                <input type="text" name="phone" id="phone" required class="form-control" placeholder="Your phone">
                <label required for="message">Message</label>
                <textarea name="message" id="message" required class="form-control" placeholder="Your message"></textarea>
                <button type="submit" class="btn btn-submit">Send</button>
        </form>
    </section>
    <section class="contact-info">
        <div class="container">
            <div class="title">
                <h3>Contact Info</h3>
                <small class="subtitle"><?php echo __('Get in touch', 'hfcv'); ?></small>
            </div>
            <address>
                <div class="row">
                    <span class="row-title accent-text"><?php echo __('Phone:', 'hfcv'); ?></span>
                    <a href="tel:<?php echo get_theme_mod('--hfcv-content-contact-phone', '+989121111111'); ?>" class="row-value"><?php echo get_theme_mod('--hfcv-content-contact-phone', '+181888888888'); ?></a>
                </div>
                <div class="row">
                    <span class="row-title accent-text"><?php echo __('Email:', 'hfcv'); ?></span>
                    <a href="mailto:<?php echo get_theme_mod('--hfcv-content-contact-email', 'XXXXXXXXXXXXXXXXX'); ?>" class="row-value"><?php echo get_theme_mod('--hfcv-content-contact-email', 'email@example.com'); ?></a>
                </div>
                <div class="row">
                    <span class="row-title accent-text"><?php echo __('Address:', 'hfcv'); ?></span>
                    <span class="row-value"><?php echo get_theme_mod('--hfcv-content-contact-address', 'this is a placeholder for address contain country, city, area and etc.'); ?></span>
                </div>
            </address>
            <div class="foot-note">
                <?php echo get_theme_mod('--hfcv-content-footnote', 'this is a foot note'); ?>
            </div>
        </div>
        </div>
    </section>
</footer>
</div><!-- #page -->
<?php wp_footer(); ?>
</body>

</html>