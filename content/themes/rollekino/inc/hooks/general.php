<?php
/**
 * General hooks.
 *
 * @Author: Niku Hietanen
 * @Date: 2020-02-20 13:46:50
 * @Last Modified by:   Roni Laukkarinen
 * @Last Modified time: 2021-09-05 12:23:56
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
