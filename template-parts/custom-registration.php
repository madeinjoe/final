<?php

/**
 * Template Name: Custom Registration
 *
 */

defined('ABSPATH') || die('Direct Access not allowed');

get_header();

/** Get login url */
$pageLogin = get_page_by_path('login', 'object', 'page');
if ($pageLogin) {
    $loginUrl = get_permalink($pageLogin->ID);
}

while (have_posts()) :
    the_post();
?>

    <main id="content" <?php post_class('site-main w-full flex flex-col items-center page-admission'); ?> role="main">
        <div class="flex justify-center w-full page-content">
            <div class="w-4/12 border-[1px] border-gray-300 rounded-lg bg-gray-100 px-3 pt-2 py-5">
                <?php the_title('<h1 class="mb-2 text-xl font-bold text-center">', '</h1>'); ?>
                <!-- <h2 class="mb-2 text-xl font-bold text-center">Log In</h2> -->
                <hr class="h-0.5 bg-gradient-to-r from-gray-200 via-gray-300 to-gray-200 mb-2">
                <div id="success-msg" class="hidden w-full px-2 py-2 text-center transition-all duration-150 border-2 border-green-300 text-medium bg-green-200/80">
                </div>
                <div id="error-msg" class="hidden w-full px-2 py-2 text-center transition-all duration-150 border-2 border-red-300 text-medium bg-red-200/80">
                </div>
                <form id="registration-form" class="flex flex-col gap-2 mt-2">
                    <div class="w-full input-group">
                        <label for="ft-registration-username" class="form-label">Username<span class="required">*</span></label>
                        <input type="text" id="ft-registration-username" name="registration-username" class="form-control" placeholder="Input your username ..." required />
                        <span id="error-msg-username" class="text-sm italic text-red-400"></span>
                    </div>
                    <div class="w-full input-group">
                        <label for="ft-registration-email" class="form-label">Email<span class="required">*</span></label>
                        <input type="text" id="ft-registration-email" name="registration-email" class="form-control" placeholder="Input your username ..." required />
                        <span id="error-msg-email" class="text-sm italic text-red-400"></span>
                    </div>
                    <div class="relative w-full input-group">
                        <label for="ft-registration-password" class="form-label">Password<span class="required">*</span></label>
                        <input type="password" id="ft-registration-password" name="registration-password" class="form-control" placeholder="Input your password ..." required="required" autocomplete="off">
                        <i class="absolute fa-solid fa-eye password-sh-toggle pw-s"></i>
                        <i class="absolute hidden fa-solid fa-eye-slash password-sh-toggle pw-h"></i>
                        <span id="error-msg-password" class="text-sm italic text-red-400"></span>
                    </div>
                    <div class="relative w-full input-group">
                        <label for="ft-registration-re-password" class="form-label">Password Confirm<span class="required">*</span></label>
                        <input type="password" id="ft-registration-re-password" name="registration-re-password" class="form-control" placeholder="Input your password ..." required="required" autocomplete="off">
                        <span id="error-msg-re-password" class="text-sm italic text-red-400"></span>
                        <i class="absolute fa-solid fa-eye password-sh-toggle pw-s"></i>
                        <i class="absolute hidden fa-solid fa-eye-slash password-sh-toggle pw-h"></i>
                    </div>
                    <div class="relative w-full input-group">
                        <label for="ft-registration-address" class="form-label">Address</label>
                        <textarea id="ft-registration-address" name="registration-user-address" class="form-control"></textarea>
                    </div>
                    <hr class="h-0.5 bg-gradient-to-r from-gray-200 via-gray-300 to-gray-200">
                    <button class="w-full rounded btn btn-outline-blue">Register</button>
                    <a href="" class="text-sm text-right text-gray-400 cursor-pointer hover:underline hover:underline-offset-2 hover:text-blue-400 hover:decoration-blue-400">Forgot
                        Password?</a>
                    <h5 class="text-sm text-right text-gray-400">
                        already have an account?
                        <a href="<?php echo $loginUrl; ?>" class="text-blue-400 underline cursor-pointer underline-offset-2 decoration-blue-400">Login
                            Here</a>
                    </h5>
                </form>
            </div>
        </div>
    </main>

<?php
endwhile;
get_footer();
