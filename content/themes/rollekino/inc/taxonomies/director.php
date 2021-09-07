<?php
/**
 * @Author: Roni Laukkarinen
 * @Date: 2021-08-25 18:23:12
 * @Last Modified by:   Roni Laukkarinen
 * @Last Modified time: 2021-09-07 19:32:48
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
class Director extends Taxonomy {

  public function register( array $post_types = [] ) {
    // Taxonomy labels.
    $labels = [
      'name'                  => _x( 'Ohjaajat', 'Taxonomy plural name', 'rollekino' ),
      'singular_name'         => _x( 'Ohjaaja', 'Taxonomy singular name', 'rollekino' ),
      'search_items'          => __( 'Etsi ohjaajaa', 'rollekino' ),
      'popular_items'         => __( 'Suositut ohjaajat', 'rollekino' ),
      'all_items'             => __( 'Kaikki ohjaajat', 'rollekino' ),
      'parent_item'           => __( 'Pääohjaaja', 'rollekino' ),
      'parent_item_colon'     => __( 'Pääohjaaja', 'rollekino' ),
      'edit_item'             => __( 'Muokkaa ohjaajaa', 'rollekino' ),
      'update_item'           => __( 'Päivitä ohjaaja', 'rollekino' ),
      'add_new_item'          => __( 'Lisää ohjaaja', 'rollekino' ),
      'new_item_name'         => __( 'Uusi ohjaaja', 'rollekino' ),
      'add_or_remove_items'   => __( 'Lisää tai poista ohjaajajä', 'rollekino' ),
      'choose_from_most_used' => __( 'Valitse käytetyimmät ohjaajat', 'rollekino' ),
      'menu_name'             => __( 'Ohjaajat', 'rollekino' ),
    ];

    $args = [
      'labels'            => $labels,
      'public'            => false,
      'show_in_nav_menus' => true,
      'show_admin_column' => true,
      'hierarchical'      => true,
      'show_tagcloud'     => false,
      'show_ui'           => true,
      'query_var'         => false,
      'show_in_rest'      => true,
      'rewrite'           => [
        // 'slug' => 'ohjaajat',
      ],
    ];

    $this->register_wp_taxonomy( $this->slug, $post_types, $args );
  }
}
