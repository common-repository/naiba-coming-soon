<?php
/*
Plugin Name: Naiba Coming Soon
Plugin URI: https://blog.naibabiji.com/files/wordpress-plugins/naiba-coming-soon.html
Description: A simple and effective coming soon plugin for WordPress.
Version: 1.0
Author: 奶爸建站笔记
Author URI: https://blog.naibabiji.com
Requires at least: 5.9
Tested up to: 6.2
Stable tag: 1.0
Requires PHP: 5.6
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// Block direct access to the plugin PHP files
if (!defined('ABSPATH')) {
    exit;
}

// Add Coming Soon page functionality
function naiba_coming_soon_page() {
    if (!is_user_logged_in()) {

        // Set page title meta
        $page_title = get_option('naiba_coming_soon_page_title', 'Coming Soon');

        // Set default page content
        $default_page_content = 'We are working on something awesome. Stay tuned!';

        // Get user-defined page content
        $page_content = get_option('naiba_coming_soon_page_content', '');

        // Use default page content if user-defined content is empty
        if (empty($page_content)) {
            $page_content = $default_page_content;
        }

        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title><?php echo esc_html($page_title); ?></title>
            <link rel="stylesheet" type="text/css" href="<?php echo esc_url( plugin_dir_url( __FILE__ ) . 'css/style.css' ); ?>">
            <meta name="robots" content="noindex, nofollow">
            <meta name="viewport" content="width=device-width, initial-scale=1">
        </head>
        <body>
            <?php
            echo '<div class="naiba-coming-soon-title">' . wp_kses_post($page_title) . '</div>';
            echo '<div class="naiba-coming-soon-content">' . wp_kses_post($page_content) . '</div>';
            ?>
        </body>
        </html>
        <?php

        // Prevent caching of the page
        nocache_headers();
        exit;
    }
}
add_action('template_redirect', 'naiba_coming_soon_page');


// Plugin Name: Naiba Coming Soon Admin Notice
function naiba_naiba_coming_soon_admin_notice() {
    $screen = get_current_screen();
    if ( $screen->id === 'dashboard' ) {
        $links = array(
            'link1' => array('name' => '1、WordPress的基本概念和术语', 'url' => 'https://blog.naibabiji.com/tutorial/wordpress-concept.html'),
            'link2' => array('name' => '2、WordPress的后台管理界面', 'url' => 'https://blog.naibabiji.com/tutorial/wordpress-dashboard.html'),
            'link3' => array('name' => '3、选择主题', 'url' => 'https://blog.naibabiji.com/tutorial/wordpress-theme.html'),
            'link4' => array('name' => '4、安装插件', 'url' => 'https://blog.naibabiji.com/tutorial/wordpress-plugin.html'),
            'link5' => array('name' => '5、创建页面', 'url' => 'https://blog.naibabiji.com/tutorial/wordpress-pages.html'),
            'link6' => array('name' => '6、创建产品', 'url' => 'https://blog.naibabiji.com/tutorial/wordpress-products.html'),
            'link7' => array('name' => '7、创建文章', 'url' => 'https://blog.naibabiji.com/tutorial/wordpress-posts.html'),
            'link8' =>  array('name' => '8、自定义菜单', 'url' => 'https://blog.naibabiji.com/tutorial/wordpress-menu.html'),
            'link9' =>  array('name' => '9、SEO优化', 'url' => 'https://blog.naibabiji.com/google-seo'),
            'link10' =>  array('name' => '10、速度优化', 'url' => 'https://blog.naibabiji.com/skill/speed-up-wordpress.html'),
            'link11' =>  array('name' => '👉建站资源导航', 'url' => 'https://blog.naibabiji.com/nav'),
        );
        ?>
        <link rel="stylesheet" type="text/css" href="<?php echo esc_url( plugin_dir_url( __FILE__ ) . 'css/style.css' ); ?>">
        <div class="notice notice-info is-dismissible naiba-notice">
            <h2><?php esc_html_e('新手WordPress建站指引', 'naiba-coming-soon'); ?></h2>
            <p><?php esc_html_e('如果你是第一次使用WordPress，可能会感到困惑。请参考下面的链接，了解如何选择主题、添加插件、创建页面和文章等基本操作。让我们开始吧！', 'naiba-coming-soon'); ?></p>
            <div class="links-container">
                <ul>
                    <?php foreach ( $links as $key => $link ) { ?>
                        <li><a href="<?php echo esc_url( $link['url'] ); ?>" target="_blank"><?php echo esc_html( $link['name'] ); ?></a></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
        <?php
    }
}
add_action( 'admin_notices', 'naiba_naiba_coming_soon_admin_notice' );

// Add plugin settings page
function naiba_coming_soon_settings_page() {
add_options_page('Naiba Coming Soon Settings', 'Naiba Coming Soon', 'manage_options', 'naiba_coming_soon_settings', 'naiba_coming_soon_settings_page_html');
}
add_action('admin_menu', 'naiba_coming_soon_settings_page');

function naiba_coming_soon_settings_link( $links ) {
    $settings_link = '<a href="admin.php?page=naiba_coming_soon_settings">插件设置</a>';
    array_push( $links, $settings_link );
    return $links;
}

$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'naiba_coming_soon_settings_link' );

// Display plugin settings page
function naiba_coming_soon_settings_page_html() {
// Check if user has appropriate permissions
if (!current_user_can('manage_options')) {
return;
}
// Get options
$page_title = get_option('naiba_coming_soon_page_title', 'Coming Soon');
$page_content = get_option('naiba_coming_soon_page_content', '');

// Save options on form submit
if (isset($_POST['naiba_coming_soon_submit'])) {
    $page_title = sanitize_text_field($_POST['naiba_coming_soon_page_title']);
    $page_content = wp_kses_post($_POST['naiba_coming_soon_page_content']);

    update_option('naiba_coming_soon_page_title', $page_title);
    update_option('naiba_coming_soon_page_content', $page_content);

    ?>
    <div id="message" class="updated notice is-dismissible"><p><?php esc_html_e('Settings saved.', 'naiba-coming-soon'); ?></p></div>
    <?php
}
?>

<div class="wrap">
    <h1><?php esc_html_e('Naiba Coming Soon Settings', 'naiba-coming-soon'); ?></h1>
    <form method="post" action="">
        <table class="form-table">
            <tr>
                <th scope="row"><label for="naiba_coming_soon_page_title"><?php esc_html_e('Page Title', 'naiba-coming-soon'); ?></label></th>
                <td><input name="naiba_coming_soon_page_title" type="text" id="naiba_coming_soon_page_title" value="<?php echo esc_attr($page_title); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th scope="row"><label for="naiba_coming_soon_page_content"><?php esc_html_e('Page Content', 'naiba-coming-soon'); ?></label></th>
<td><textarea name="naiba_coming_soon_page_content" id="naiba_coming_soon_page_content" rows="10" cols="50"><?php echo wp_kses_post($page_content); ?></textarea></td>
</tr>
</table>
<input type="submit" name="naiba_coming_soon_submit" id="naiba_coming_soon_submit" class="button button-primary" value="<?php esc_attr_e('Save Changes', 'naiba-coming-soon'); ?>">
</form>
</div>
<?php
}