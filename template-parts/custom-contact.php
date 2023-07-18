<?php

/**
 * Template Name: Contact Us
 *
 */

defined('ABSPATH') || die('Direct Access not allowed');

get_header();

?>
    <main class="flex justify-center">
        <div class="container">
            <form id="form-leave-a-message" class="flex flex-col gap-2 px-2 pt-2 pb-3">
                <div class="input-group">
                    <label for="input-lam-name">Your Name</label>
                    <input type="text" name="lam-name" id="input-lam-name" class="form-control" placeholder="Please enter your name">
                </div>
                <div class="input-group">
                    <label for="input-lam-email">Your Email</label>
                    <input type="email" name="lam-email" id="input-lam-email" class="form-control" placeholder="Please enter your email">
                </div>
                <div class="input-group">
                    <label for="input-lam-subject">Subject</label>
                    <input type="text" name="lam-subject" id="input-lam-subject" class="form-control" placeholder="Please enter message subject">
                </div>
                <div class="input-group">
                    <label for="input-lam-message">Message</label>
                    <textarea name="lam-message" id="input-lam-message" cols="30" rows="10" class="form-control"></textarea>
                </div>
                <hr class="h-1 bg-gray-200/75">
                <button type="submit" class="bg-teal-300 px-2 py-1.5 text-white font-semibold">Leave a Message</button>
            </form>
        </div>
    </main>

<?php
get_footer();