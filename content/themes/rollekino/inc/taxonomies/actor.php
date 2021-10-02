<?php
/**
 * @Author: Roni Laukkarinen
 * @Date: 2021-08-25 18:23:12
 * @Last Modified by:   Roni Laukkarinen
 * @Last Modified time: 2021-10-02 14:38:03
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
class Actor extends Taxonomy {

  public function register( array $post_types = [] ) {
    // Taxonomy labels.
    $labels = [
      'name'                  => _x( 'Näyttelijät', 'Taxonomy plural name', 'rollekino' ),
      'singular_name'         => _x( 'näyttelijä', 'Taxonomy singular name', 'rollekino' ),
      'search_items'          => __( 'Etsi näyttelijää', 'rollekino' ),
      'popular_items'         => __( 'Suositut näyttelijät', 'rollekino' ),
      'all_items'             => __( 'Kaikki näyttelijät', 'rollekino' ),
      'parent_item'           => __( 'Päänäyttelijä', 'rollekino' ),
      'parent_item_colon'     => __( 'Päänäyttelijä', 'rollekino' ),
      'edit_item'             => __( 'Muokkaa näyttelijää', 'rollekino' ),
      'update_item'           => __( 'Päivitä näyttelijä', 'rollekino' ),
      'add_new_item'          => __( 'Lisää näyttelijä', 'rollekino' ),
      'new_item_name'         => __( 'Uusi näyttelijä', 'rollekino' ),
      'add_or_remove_items'   => __( 'Lisää tai poista näyttelijäjä', 'rollekino' ),
      'choose_from_most_used' => __( 'Valitse käytetyimmät näyttelijät', 'rollekino' ),
      'menu_name'             => __( 'Näyttelijät', 'rollekino' ),
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
        // 'slug' => 'nayttelijat',
      ],
    ];

    $this->register_wp_taxonomy( $this->slug, $post_types, $args );
  }
}
