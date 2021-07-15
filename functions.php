<?php
// Enqueue Stylesheet from parent theme
function generic_child_enqueue_styles() {
    $parenthandle = 'parent-style';
    $theme = wp_get_theme();
    wp_enqueue_style( $parenthandle, get_template_directory_uri() . '/style.css', 
        array(),
        $theme->parent()->get('Version')
    );
    wp_enqueue_style( 'child-style', get_stylesheet_uri(),
        array( $parenthandle ),
        $theme->get('Version')
    );
}
add_action( 'wp_enqueue_scripts', 'generic_child_enqueue_styles' );

// Enqueue Google Font
function generic_child_add_google_fonts() {
  wp_enqueue_style( 'wpb-google-fonts',
    'https://fonts.googleapis.com/css2?family=Open+Sans&family=Nunito&family=Roboto&display=swap',
    false
  );
}
add_action( 'wp_enqueue_scripts', 'generic_child_add_google_fonts' );

// Enqueue Bootstrap
function generic_child_add_bootstrap() {
  wp_enqueue_style('bootstrap4', 'https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css');
  wp_enqueue_script( 'boot1','https://code.jquery.com/jquery-3.4.1.slim.min.js', array( 'jquery' ),'',true );
  wp_enqueue_script( 'boot2',
    'https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js',
    array( 'jquery' ),
    '',
    true
  );
  wp_enqueue_script( 'boot3',
    'https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js',
    array( 'jquery' ),
    '',
    true
  );
}
add_action( 'wp_enqueue_scripts', 'generic_child_add_bootstrap' );

// Remove Admin Bar
function remove_admin_bar() {
  if (!current_user_can('administrator') && !is_admin()) {
  show_admin_bar(false);
  }
}
add_action('after_setup_theme', 'remove_admin_bar');
add_filter('edit_post_link', '__return_false');

function add_menu_dynamic_user_name( $menu_items ) {
    foreach ( $menu_items as $menu_item ) {
        if ( strpos($menu_item->title, '#profile_name#') !== false) {
          if (!current_user_can('administrator') && !is_admin()) {
            $menu_item->title =  str_replace("#profile_name#", 
              wp_get_current_user()->user_firstname,
              $menu_item->title
            );
          } else {
            $menu_item->title = wp_get_current_user()->display_name;
          }
        }
    }

    return $menu_items;
}
add_filter( 'wp_nav_menu_objects', 'add_menu_dynamic_user_name' );

//Add page slug to body
function add_slug_body_class( $classes ) {
  global $post;
  if ( isset( $post ) ) {
    $classes[] = $post->post_type . '-' . $post->post_name;
  }
  return $classes;
}
add_filter( 'body_class', 'add_slug_body_class' );