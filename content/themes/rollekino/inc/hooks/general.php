<?php
/**
 * General hooks.
 *
 * @Author: Niku Hietanen
 * @Date: 2020-02-20 13:46:50
 * @Last Modified by:   Roni Laukkarinen
 * @Last Modified time: 2021-09-11 18:11:28
 *
 * @package rollekino
 */

namespace Air_Light;

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function widgets_init() {
  register_sidebar( array(
    'name'          => esc_html__( 'Sidebar', 'rollekino' ),
    'id'            => 'sidebar-1',
    'description'   => esc_html__( 'Add widgets here.', 'rollekino' ),
    'before_widget' => '<section id="%1$s" class="widget %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h2 class="widget-title">',
    'after_title'   => '</h2>',
  ) );
} // end widgets_init

/**
 * Rewrite term link.
 */
function rewrite_term_link( $termlink, $term, $taxonomy ) {
  $taxonomies = [
    'genre',
    'director',
    'writer',
    'actor',
  ];
  if ( ! in_array( $taxonomy, $taxonomies, true ) ) {
    return $termlink;
  }
  return add_query_arg( $taxonomy, $term->term_id, get_post_type_archive_link( 'movie' ) );
}

/**
 * Register custom setting group post ids for Air Helper.
 */
function custom_settings_post_ids( $post_ids = [] ) {
  if ( ! isset( THEME_SETTINGS['custom_settings_post_ids'] ) ) {
    return $post_ids;
  }

  return wp_parse_args( THEME_SETTINGS['custom_settings_post_ids'], $post_ids );
} // end custom_settings_post_ids

function maybe_clear_transient_cache( $post_id ) {
  if ( wp_is_post_revision( $post_id ) ) {
    return;
  }

  transient_cache_clear();
} // end maybe_clear_transient_cache

function adminbar_add_cache_clear_link( $wp_admin_bar ) {
  global $wp;
  $wp_admin_bar->add_node( array(
    'id'    => 'rollekinoccaheclear',
    'title' => 'Tyhjennä välimuisti',
    'class' => 'no-external-link-indicator',
    'href'  => wp_nonce_url( home_url( add_query_arg( array( 'clear-rollekino-cache' => true ), $wp->request ) ), 'clear_cache', 'rollekino_cache_clear_nonce' ),
  ) );
} // end adminbar_add_cache_clear_link

function adminbar_maybe_clear_transient_cache() {
  // bail if current user not logged in
  if ( ! is_user_logged_in() ) {
    return;
  }

  // bail if nonce is not there or is invalid.
  if ( ! isset( $_GET['rollekino_cache_clear_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_GET['rollekino_cache_clear_nonce'] ), 'clear_cache' ) ) {
    return;
  }

  transient_cache_clear();
} // end adminbar_maybe_clear_transient_cache

/**
 * Add extra item to navigation
 */
add_filter( 'wp_nav_menu_items', function( $items, $args ) {

  $link = '<li class="menu-item menu-item-search">
      <button class="open-search" id="open-search" aria-expanded="false" aria-haspopup="true" aria-label="Avaa haku">
        <svg class="icon-search" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 27 27"><g stroke="currentColor" stroke-width="1.5" fill="none" fill-rule="evenodd" stroke-linecap="round" stroke-linejoin="round"><circle cx="11.813" cy="11.813" r="11.25"></circle><path d="M26.438 26.438l-6.671-6.671"></path></g></svg>
        <svg class="icon-close" width="22" height="22" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M505.943 6.058c-8.077-8.077-21.172-8.077-29.249 0L6.058 476.693c-8.077 8.077-8.077 21.172 0 29.249A20.612 20.612 0 0020.683 512a20.614 20.614 0 0014.625-6.059L505.943 35.306c8.076-8.076 8.076-21.171 0-29.248z"/><path d="M505.942 476.694L35.306 6.059c-8.076-8.077-21.172-8.077-29.248 0-8.077 8.076-8.077 21.171 0 29.248l470.636 470.636a20.616 20.616 0 0014.625 6.058 20.615 20.615 0 0014.624-6.057c8.075-8.078 8.075-21.173-.001-29.25z"/></svg>
      </button>
    </li>';

  return $items . $link;

}, 20, 2);
