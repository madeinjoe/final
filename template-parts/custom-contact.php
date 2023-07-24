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
            <form id="form-contact-message" class="flex flex-col gap-2 px-2 pt-2 pb-3">
                <div class="input-group">
                    <label for="input-contact-message-name">Your Name</label>
                    <input type="text" name="contact-message-name" id="input-contact-message-name" class="form-control" placeholder="Please enter your name">
                    <span id="error-msg-name" class="text-sm italic text-red-400"></span>
                </div>
                <div class="input-group">
                    <label for="input-contact-message-email">Your Email</label>
                    <input type="email" name="contact-message-email" id="input-contact-message-email" class="form-control" placeholder="Please enter your email">
                    <span id="error-msg-email" class="text-sm italic text-red-400"></span>
                </div>
                <div class="input-group">
                    <label for="input-contact-message-subject">Subject</label>
                    <input type="text" name="contact-message-subject" id="input-contact-message-subject" class="form-control" placeholder="Please enter message subject">
                    <span id="error-msg-subject" class="text-sm italic text-red-400"></span>
                </div>
                <div class="input-group">
                    <label for="input-contact-message-message">Message</label>
                    <textarea name="contact-message-message" id="input-contact-message-message" cols="30" rows="10" class="form-control"></textarea>
                </div>
                <hr class="h-1 bg-gray-200/75">
                <button type="submit" class="bg-teal-300 px-2 py-1.5 text-white font-semibold">Leave a Message</button>
            </form>
        </div>
    </div>
</main>

<?php
get_footer();
