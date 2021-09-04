<?php
/**
 * @Author: Roni Laukkarinen
 * @Date: 2021-08-25 18:23:12
 * @Last Modified by:   Roni Laukkarinen
 * @Last Modified time: 2021-09-04 19:20:39
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
class Genre extends Taxonomy {

  public function register( array $post_types = [] ) {
    // Taxonomy labels.
    $labels = [
      'name'                  => _x( 'Genret', 'Taxonomy plural name', 'rollekino' ),
      'singular_name'         => _x( 'Genre', 'Taxonomy singular name', 'rollekino' ),
      'search_items'          => __( 'Etsi genrejä', 'rollekino' ),
      'popular_items'         => __( 'Suositut genret', 'rollekino' ),
      'all_items'             => __( 'Kaikki genret', 'rollekino' ),
      'parent_item'           => __( 'Päägenre', 'rollekino' ),
      'parent_item_colon'     => __( 'Päägenre', 'rollekino' ),
      'edit_item'             => __( 'Muokkaa genreä', 'rollekino' ),
      'update_item'           => __( 'Päivitä genre', 'rollekino' ),
      'add_new_item'          => __( 'Lisää genre', 'rollekino' ),
      'new_item_name'         => __( 'Uusi genre', 'rollekino' ),
      'add_or_remove_items'   => __( 'Lisää tai poista genrejä', 'rollekino' ),
      'choose_from_most_used' => __( 'Valitse käytetyimmät genret', 'rollekino' ),
      'menu_name'             => __( 'Genret', 'rollekino' ),
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
        'slug' => 'genre',
      ],
    ];

    $this->register_wp_taxonomy( $this->slug, $post_types, $args );
  }

}
