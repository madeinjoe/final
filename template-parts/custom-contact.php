<?php

/**
 * Template Name: Contact Us
 *
 */

defined('ABSPATH') || die('Direct Access not allowed');

get_header();

?>
<main id="content" <?php post_class('site-main w-full flex flex-col items-center page-contact'); ?> role="main">
    <div class="flex justify-center w-8/12">
        <div class="container">
            <form id="form-contact-message" class="flex flex-col gap-2 px-2 pt-2 pb-3" data-url="<?php echo admin_url('admin-ajax.php') ?>">
                <input name='action' type="hidden" value='contact_message_handle'>
                <?php wp_nonce_field('_contact_nonce', 'nonce'); ?>
                <div class="input-group">
                    <label for="input-contact-mesage-name">Your Name</label>
                    <input type="text" name="contact-mesage-name" id="input-contact-mesage-name" class="form-control" placeholder="Please enter your name">
                </div>
                <div class="input-group">
                    <label for="input-contact-mesage-email">Your Email</label>
                    <input type="email" name="contact-mesage-email" id="input-contact-mesage-email" class="form-control" placeholder="Please enter your email">
                    <span id="error-msg-email" class="text-sm italic text-red-400"></span>
                </div>
                <div class="input-group">
                    <label for="input-contact-mesage-subject">Subject</label>
                    <input type="text" name="contact-mesage-subject" id="input-contact-mesage-subject" class="form-control" placeholder="Please enter message subject">
                </div>
                <div class="input-group">
                    <label for="input-contact-mesage-message">Message</label>
                    <textarea name="contact-mesage-message" id="input-contact-mesage-message" cols="30" rows="10" class="form-control"></textarea>
                </div>
                <hr class="h-1 bg-gray-200/75">
                <button type="submit" class="bg-teal-300 px-2 py-1.5 text-white font-semibold">Leave a Message</button>
            </form>
        </div>
    </div>
</main>

<?php
get_footer();
