<?php

/**
 * Template Name: Custom Activation
 *
 */

defined('ABSPATH') || die('Direct Access not allowed');

$userID = $_GET['user'] ?? null;
$activationCode = $_GET['acode'] ?? null;
$active = false;
$notfound = false;

if ($userID && $activationCode) {
    $theUser = get_user_by('ID', $userID);
    if ($theUser->data->user_activation_key === $activationCode) {
        $updateUser = wp_update_user(['ID' => $userID, 'user_status' => 0]);
        $active = true;
    } else {
        $notfound = true;
    }
    // wp_redirect(admin_url('post.php'));
}

/** Get login url */
$pageLogin = get_page_by_path('custom-login', 'object', 'page');
$loginUrl = get_permalink($pageLogin->ID);

get_header();

while (have_posts()) :
    the_post();
?>
    <main id="content" <?php post_class('site-main w-full flex flex-col items-center'); ?> role="main">
    <?php if ($active) :  ?>
        <div class="flex justify-center w-full border-2 border-red-400 page-content">
            <div class="w-full border-[1px] border-gray-300 rounded-lg bg-gray-100 px-3 pt-2 py-5">
                <?php the_title('<h1 class="mb-2 text-xl font-bold text-center">', '</h1>'); ?>
                <hr class="h-0.5 bg-gradient-to-r from-gray-200 via-gray-300 to-gray-200">
                <div class="flex flex-col items-center gap-3">
                    <p>
                        Thank you <span class="font-semibold"><?php echo $theUser->data->display_name; ?></span>, for being a subscriber!
                    </p>
                    Your account is now activated.
                    <a href="<?php echo $loginUrl; ?>" class="flex items-center justify-center gap-1 px-2 py-1 text-base font-bold text-center text-white uppercase bg-blue-400 rounded w-fit hover:bg-blue-500">Click here to login!</a>
                </div>
            </div>
        </div>
    <?php else :
            if (!$userID) {
                '<h1 class="mb-2 text-xl font-bold text-center">Provide user id!</h1>';
            } else if (!$activationCode) {
                '<h1 class="mb-2 text-xl font-bold text-center">Activation code not found!</h1>';
            } else if ($notfound) {
                '<h1 class="mb-2 text-xl font-bold text-center">User id not found!</h1>';
            } else {
                '<h1 class="mb-2 text-xl font-bold text-center">Activation code is invalid!</h1>';
        }
        endif; ?>
    </main>
<?php
endwhile;
get_footer();
