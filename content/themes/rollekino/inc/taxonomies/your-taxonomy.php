<?php
/**
 * @Author: Niku Hietanen
 * @Date: 2020-02-18 15:05:35
 * @Last Modified by:   Roni Laukkarinen
 * @Last Modified time: 2021-05-04 11:13:17
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
class Your_Taxonomy extends Taxonomy {


  public function register( array $post_types = [] ) {
    // Taxonomy labels.
    $labels = [
      'name'                  => _x( 'Your Taxonomies', 'Taxonomy plural name', 'rollekino' ),
      'singular_name'         => _x( 'Your Taxonomy', 'Taxonomy singular name', 'rollekino' ),
      'search_items'          => __( 'Search Your Taxonomies', 'rollekino' ),
      'popular_items'         => __( 'Popular Your Taxonomies', 'rollekino' ),
      'all_items'             => __( 'All Your Taxonomies', 'rollekino' ),
      'parent_item'           => __( 'Parent Your Taxonomy', 'rollekino' ),
      'parent_item_colon'     => __( 'Parent Your Taxonomy', 'rollekino' ),
      'edit_item'             => __( 'Edit Your Taxonomy', 'rollekino' ),
      'update_item'           => __( 'Update Your Taxonomy', 'rollekino' ),
      'add_new_item'          => __( 'Add New Your Taxonomy', 'rollekino' ),
      'new_item_name'         => __( 'New Your Taxonomy', 'rollekino' ),
      'add_or_remove_items'   => __( 'Add or remove Your Taxonomies', 'rollekino' ),
      'choose_from_most_used' => __( 'Choose from most used Taxonomies', 'rollekino' ),
      'menu_name'             => __( 'Your Taxonomy', 'rollekino' ),
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
      'rewrite'           => [
        'slug' => 'your-taxonomy',
      ],
    ];

    $this->register_wp_taxonomy( $this->slug, $post_types, $args );
  }

}
