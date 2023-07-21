<?php

/**
 * Template Name: Custom Login
 *
 */

defined('ABSPATH') || die('Direct Access not allowed');

/** Check if user is logged in */
// if (is_user_logged_in()) {
//     wp_redirect(admin_url('/'));
// }

/** Get login url */
$pageRegistration = get_page_by_path('registration', 'object', 'page');
$registrationUrl = get_permalink($pageRegistration->ID);

get_header();

while (have_posts()) :
    the_post();
?>

    <main id="content" <?php post_class('site-main w-full flex flex-col items-center page-admission'); ?> role="main">
        <div class="flex justify-center w-full border-2 border-red-400 page-content">
            <div class="w-4/12 border-[1px] border-gray-300 rounded-lg bg-gray-100 px-3 pt-2 py-5">
                <?php the_title('<h1 class="mb-2 text-xl font-bold text-center">', '</h1>'); ?>
                <hr class="h-0.5 bg-gradient-to-r from-gray-200 via-gray-300 to-gray-200">
                <div id="error-msg" class="hidden w-full px-2 py-2 text-center transition-all duration-150 border-2 border-red-300 text-medium bg-red-200/80"></div>
                <form id="login-form" class="flex flex-col gap-2 mt-2" data-url="<?php echo admin_url('admin-ajax.php') ?>">
                    <input name='action' type="hidden" value='login_handle'>
                    <?php wp_nonce_field('_custom_login', 'nonce'); ?>
                    <div class="w-full input-group">
                        <label for="ft-login-username" class="form-label">Username<span class="required">*</span></label>
                        <input type="text" id="ft-login-username" name="login-username" class="form-control" placeholder="Input your username ..." required />
                    </div>
                    <div class="relative w-full input-group">
                        <label for="ft-login-password" class="form-label">Password<span class="required">*</span></label>
                        <input type="password" id="ft-login-password" name="login-password" class="form-control" placeholder="Input your password ..." required="required">
                        <i class="absolute fa-solid fa-eye password-sh-toggle pw-s"></i>
                        <i class="absolute hidden fa-solid fa-eye-slash password-sh-toggle pw-h"></i>
                    </div>
                    <div class="relative flex items-center w-full gap-1">
                        <input type="checkbox" id="ft-login-remember" name="login-remember" class="relative appearance-none flex justify-center items-center w-5 h-5 border border-gray-300 rounded bg-white checked:bg-blue-400 checked:border-blue-400 checked:text-white checked:after:content-['&#10003;'] checked:after:ablsolute checked:after:text-base focus:outline-none transition duration-[25ms] cursor-pointer" value="true">
                        <label for="ft-login-remember" class="form-label">Remember me</label>
                    </div>
                    <hr class="h-0.5 bg-gradient-to-r from-gray-200 via-gray-300 to-gray-200">
                    <button class="w-full rounded btn btn-outline-blue">Let me in</button>
                    <a href="" class="text-sm text-right text-gray-400 cursor-pointer hover:underline hover:underline-offset-2 hover:text-blue-400 hover:decoration-blue-400">Forgot Password?</a>
                    <h5 class="text-sm text-right text-gray-400">
                        doesn't have an account?
                        <a href="<?php echo $registrationUrl ?>" class="text-blue-400 underline cursor-pointer underline-offset-2 decoration-blue-400">Register now!</a>
                    </h5>
                </form>
            </div>
        </div>
    </main>

<?php
endwhile;
get_footer();
