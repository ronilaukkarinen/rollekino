<?php
/**
 * @Author: Niku Hietanen
 * @Date: 2020-02-18 15:06:45
 * @Last Modified by:   Roni Laukkarinen
 * @Last Modified time: 2021-09-07 20:07:50
 *
 * @package rollekino
 **/

namespace Air_Light;

/**
 * Registers the Your Post Type post type.
 */
class Movie extends Post_Type {

  public function register() {

    // Modify all the i18ized strings here.
    $generated_labels = [
      'menu_name'          => __( 'Leffat', 'rollekino' ),
      'name'               => _x( 'Leffat', 'post type general name', 'rollekino' ),
      'singular_name'      => _x( 'Leffat', 'post type singular name', 'rollekino' ),
      'name_admin_bar'     => _x( 'Leffat', 'add new on admin bar', 'rollekino' ),
      'add_new'            => _x( 'Lisää uusi', 'thing', 'rollekino' ),
      'add_new_item'       => __( 'Lisää uusi', 'rollekino' ),
      'new_item'           => __( 'Uusi leffa-arvio', 'rollekino' ),
      'edit_item'          => __( 'Muokkaa leffa-arviota', 'rollekino' ),
      'view_item'          => __( 'Katso arvio', 'rollekino' ),
      'all_items'          => __( 'Kaikki', 'rollekino' ),
      'search_items'       => __( 'Etsi', 'rollekino' ),
      'parent_item_colon'  => __( 'Isäntäleffa-arvio:', 'rollekino' ),
      'not_found'          => __( 'Mitään ei löytynyt.', 'rollekino' ),
      'not_found_in_trash' => __( 'Roskakorista ei löydetty mitään.', 'rollekino' ),
    ];

    // Definition of the post type arguments. For full list see:
    // http://codex.wordpress.org/Function_Reference/register_post_type
    $args = [
      'labels'              => $generated_labels,
      'description'         => '',
      'menu_icon'           => 'data:image/svg+xml;base64,' . base64_encode( '<svg xmlns="http://www.w3.org/2000/svg" viewBox="-2 -2 24 24" width="24" height="24" preserveAspectRatio="xMinYMin" class="jam jam-movie" fill="currentColor"><path d="M6 15v3h8v-7H6v4zm-2-2v-2H2V9h2V7H2v6h2zm0 2H2v1a2 2 0 0 0 2 2v-3zm14-2V7h-2v2h2v2h-2v2h2zm0 2h-2v3a2 2 0 0 0 2-2v-1zm-4-8V2H6v7h8V7zm4-2V4a2 2 0 0 0-2-2v3h2zM4 5V2a2 2 0 0 0-2 2v1h2zm0-5h12a4 4 0 0 1 4 4v12a4 4 0 0 1-4 4H4a4 4 0 0 1-4-4V4a4 4 0 0 1 4-4z"/></svg>' ), // phpcs:ignore
      'public'              => true,
      'has_archive'         => true,
      'exclude_from_search' => false,
      'show_ui'             => true,
      'show_in_menu'        => true,
      'show_in_rest'        => true,
      'rewrite'             => [
        'with_front'  => true,
        'slug'        => 'leffat',
      ],
      'supports'            => [ 'title', 'editor', 'thumbnail', 'revisions' ],
      'taxonomies'          => [],
    ];

    $this->register_wp_post_type( $this->slug, $args );
  }
}
