<?php
/**
 * @Author: Roni Laukkarinen
 * @Date: 2021-08-25 18:23:12
 * @Last Modified by:   Roni Laukkarinen
 * @Last Modified time: 2021-10-02 14:37:59
 *
 * @package rollekino
 */

namespace Air_Light;

/**
 * Registers the Your Taxonomy taxonomy.
 *
 * @param Array $post_types Optional. Post types in
 * which the taxonomy should be registered.
 */
class Writer extends Taxonomy {

  public function register( array $post_types = [] ) {
    // Taxonomy labels.
    $labels = [
      'name'                  => _x( 'Käsikirjoittajat', 'Taxonomy plural name', 'rollekino' ),
      'singular_name'         => _x( 'Käsikirjoittaja', 'Taxonomy singular name', 'rollekino' ),
      'search_items'          => __( 'Etsi käsikirjoittajaa', 'rollekino' ),
      'popular_items'         => __( 'Suositut käsikirjoittajat', 'rollekino' ),
      'all_items'             => __( 'Kaikki käsikirjoittajat', 'rollekino' ),
      'parent_item'           => __( 'Pääkäsikirjoittaja', 'rollekino' ),
      'parent_item_colon'     => __( 'Pääkäsikirjoittaja', 'rollekino' ),
      'edit_item'             => __( 'Muokkaa käsikirjoittajaa', 'rollekino' ),
      'update_item'           => __( 'Päivitä käsikirjoittaja', 'rollekino' ),
      'add_new_item'          => __( 'Lisää käsikirjoittaja', 'rollekino' ),
      'new_item_name'         => __( 'Uusi käsikirjoittaja', 'rollekino' ),
      'add_or_remove_items'   => __( 'Lisää tai poista käsikirjoittajajä', 'rollekino' ),
      'choose_from_most_used' => __( 'Valitse käytetyimmät käsikirjoittajat', 'rollekino' ),
      'menu_name'             => __( 'Käsikirjoittajat', 'rollekino' ),
    ];

    $args = [
      'labels'            => $labels,
      'public'            => false,
      'show_in_nav_menus' => false,
      'show_admin_column' => false,
      'hierarchical'      => false,
      'show_tagcloud'     => false,
      'show_ui'           => false,
      'query_var'         => false,
      'show_in_rest'      => true,
      'meta_box_cb'       => false,
      'rewrite'           => [
        // 'slug' => 'kasikirjoittajat',
      ],
    ];

    $this->register_wp_taxonomy( $this->slug, $post_types, $args );
  }
}
