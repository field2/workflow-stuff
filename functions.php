<?php


if (!session_id()) {
    session_start();
}


	add_theme_support('post-thumbnails');
	add_image_size( 'logo', 300, 130 );


	




function f2_enqueue() {

	wp_register_script( 'site', get_template_directory_uri().'/js/site.js', array( 'jquery' ) );
		wp_enqueue_script( 'site' );
	
wp_register_style( 'screen', get_stylesheet_directory_uri().'/style.css', '', '', 'screen' );
        wp_enqueue_style( 'screen' );

	wp_register_style('icons','//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css');
        wp_enqueue_style( 'icons'); 

      wp_register_style('googlefonts','//fonts.googleapis.com/css?family=Open+Sans:400,700,400italic');
        wp_enqueue_style( 'googlefonts'); 
  }


        
add_action( 'wp_enqueue_scripts', 'f2_enqueue' );



/**
 * Register our sidebars and widgetized areas.
 *
 */
function widgets_init() {

	register_sidebar( array(
		'name' => 'sidebar',
    'id' => 'sidebar'
	) );
}
add_action( 'widgets_init', 'widgets_init' );

//remove the emojis
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' ); 

register_nav_menus(array('primary' => 'Primary Nav','primary_mc' => 'Primary MC Nav','primary_mp' => 'Primary MP Nav','primary_mmp' => 'Primary MMP Nav','footer' => 'Footer Nav','quicklinks' => 'Quick Links', 'social' => 'Social links'));



/* customizer */

function f2_theme_customizer( $wp_customize ) {




$wp_customize->add_section( 'f2_branding_section' , array(
    'title'      => __('Site branding','f2'),
    'priority'   => 30,
    'description' => 'Enter your branding info',
) );

$wp_customize->add_setting( 'f2_address' );
$wp_customize->add_setting( 'f2_logo' );
$wp_customize->add_setting( 'f2_phone' );

$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'f2_phone_num', array(
    'label'    => __( 'Phone', 'f2' ),
    'section'  => 'f2_branding_section',
    'settings' => 'f2_phone',
) ) );


$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'f2_address', array(
    'label'    => __( 'Address', 'f2' ),
    'type' => 'textarea',
    'section'  => 'f2_branding_section',
    'settings' => 'f2_address',
) ) );



$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'f2_logo', array(
    'label'    => __( 'Logo', 'f2' ),
    'section'  => 'f2_branding_section',
    'settings' => 'f2_logo',
) ) );

}






// $wp_customize->add_section( 'phone_number', array(
//     'title' => __( 'Phone number', 'f2' ),
//     'priority' => 50,
//     'description' => __( 'Add your phone number here', 'f2' ),
//   ) );


// $wp_customize->add_setting( 'phone_number' );


// $wp_customize->add_control( 'themename_theme_options[theme_footer]', array(
//     'section' => 'my_footer',
//     'type'   => 'text', // text (default), checkbox, radio, select, dropdown-pages
//   ) );










add_action( 'customize_register', 'f2_theme_customizer' );


function get_all_sites() {

    global $wpdb;

    // Query all blogs from multi-site install
    $blogs = $wpdb->get_results("SELECT blog_id,domain,path FROM wp_blogs where blog_id > 1 ORDER BY path");

    // Start unordered list
    echo '<ul>';

    // For each blog search for blog name in respective options table
    foreach( $blogs as $blog ) {

        // Query for name from options table
        $blogname = $wpdb->get_results("SELECT option_value FROM wp_".$blog->blog_id ."_options WHERE option_name='blogname' ");
        foreach( $blogname as $name ) { 

            // Create bullet with name linked to blog home pag
            echo '<li>';
            echo '<a href="http://';
            echo $blog->domain;
            echo $blog -> path;
            echo '">';
            echo $name->option_value;
            echo '</a></li>';

        }
    }

    // End unordered list
    echo '</ul>';
}


function list_all_sites() {

    global $wpdb;

    // Query all blogs from multi-site install
    $blogs = $wpdb->get_results("SELECT blog_id,domain,path FROM wp_blogs where blog_id > 1 ORDER BY path");

    foreach( $blogs as $blog ) {

        // Query for name from options table
        $blogname = $wpdb->get_results("SELECT option_value FROM wp_".$blog->blog_id ."_options WHERE option_name='blogname' ");
        foreach( $blogname as $name ) { 

            // Create bullet with name linked to blog home pag
            echo '"' . $name->option_value . '",';

        }
    }
}





define( 'GF_THEME_IMPORT_FILE', 'gf_import.json' );
define( 'GF_LICENSE_KEY', 'fad960cc00c769d43dc5bc8426141138' );



// // add link to homeowner registration form on login page

function homeowner_registration() { 
 echo '<a href="' . site_url( '/homeowner-signup/' ) . '">Click here to request a login for your community site.</a>';
 }
add_action( 'login_footer', 'homeowner_registration' );


// /**
//  * Filter Force Login to allow exceptions for specific URLs.
//  *
//  * @return array An array of URLs. Must be absolute.
//  **/
// function my_forcelogin_whitelist( $whitelist ) {

//   $whitelist[] = site_url('/');

//   $whitelist[] = site_url( '/homeowner-signup/' );
//   $whitelist[] = site_url( '/management/' );
//   $whitelist[] = site_url( '/managers-registration/' );
//   $whitelist[] = site_url( '/managers-registration/' );
//    return $whitelist; 
// }
// add_filter('v_forcelogin_whitelist', 'my_forcelogin_whitelist', 10, 1);






// new multisite defaults
add_action('wpmu_new_blog', 'wpb_create_my_pages', 10, 2);

function wpb_create_my_pages($blog_id, $user_id){
  switch_to_blog($blog_id);

// create new pages
  $page_id = wp_insert_post(array(
    'post_title'     => 'Homeowner signup',
    'post_name'      => 'homeowner-signup',
    'post_content'   => '[gravityform id="1" title="true" description="true" ajax="true"]',
    'post_status'    => 'publish',
    'post_author'    => $user_id, // or "1" (super-admin?)
    'post_type'      => 'page',
    'menu_order'     => 1,
    'comment_status' => 'closed',
    'ping_status'    => 'closed',
 ));  

  $page_id = wp_insert_post(array(
    'post_title'     => 'Community Documents',
    'post_name'      => 'community-documents',
    'post_content'   => '[dg ids="13"]',
    'post_status'    => 'private',
    'post_author'    => $user_id, // or "1" (super-admin?)
    'post_type'      => 'page',
    'menu_order'     => 1,
    'comment_status' => 'closed',
    'ping_status'    => 'closed',
 ));  

  $page_id = wp_insert_post(array(
    'post_title'     => 'Contact your associate',
    'post_name'      => 'contact-associate',
    'post_content'   => '[gravityform id="2" title="true" description="true" ajax="true"]',
    'post_status'    => 'private',
    'post_author'    => $user_id, // or "1" (super-admin?)
    'post_type'      => 'page',
    'menu_order'     => 1,
    'comment_status' => 'closed',
    'ping_status'    => 'closed',
 ));  

$page_id = wp_insert_post(array(
    'post_title'     => 'Welcome to your community website',
    'post_name'      => 'welcome',
    'post_content'   => 'Cras mattis consectetur purus sit amet fermentum. Curabitur blandit tempus porttitor. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Nullam id dolor id nibh ultricies vehicula ut id elit. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas faucibus mollis interdum. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.Curabitur blandit tempus porttitor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Aenean lacinia bibendum nulla sed consectetur.',
    'post_status'    => 'publish',
    'post_author'    => $user_id, // or "1" (super-admin?)
    'post_type'      => 'page',
    'menu_order'     => 1,
    'comment_status' => 'closed',
    'ping_status'    => 'closed',
 ));  



// Find and delete the WP default 'Sample Page'
$defaultPage = get_page_by_title( 'Sample Page' );
wp_delete_post( $defaultPage->ID );

  restore_current_blog();
}



// let subscribers see private pages
$subRole = get_role( 'subscriber' );
$subRole->add_cap( 'read_private_pages' );



// set new sites to use a static front page
add_action( 'wpmu_new_blog', 'process_extra_field_on_blog_signup', 10, 6 );

function process_extra_field_on_blog_signup( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {
    switch_to_blog($blog_id);
    $homepage = get_page_by_title( 'Welcome to your community website' );   
    if ( $homepage )
    {
        update_blog_option( $blog_id, 'page_on_front', $homepage->ID );
        update_blog_option( $blog_id, 'show_on_front', 'page' );
    }
    restore_current_blog();
}





//Page Slug Body Class
function add_slug_body_class( $classes ) {
global $post;
if ( isset( $post ) ) {
$classes[] = $post->post_type . '-' . $post->post_name;
}
return $classes;
}
add_filter( 'body_class', 'add_slug_body_class' );


/// property search

function search_for_properties() {
}

