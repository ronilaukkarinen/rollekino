<?php
/**
 * @Author: Roni Laukkarinen
 * @Date:   2021-02-04 18:15:59
 * @Last Modified by:   Roni Laukkarinen
 * @Last Modified time: 2021-09-01 20:34:48
 *
 * @package rollekino
 */

namespace Air_Light;

/**
 * Check duplicate by title
 *
 * @link https://stackoverflow.com/questions/62375789/wpallimport-check-duplicate-by-title
 * @link https://github.com/soflyy/wp-all-import-action-reference/blob/master/all-import/wp_all_import_is_post_to_create.php
 */
function create_only_if_unique_custom_field( $continue_import, $data, $import_id ) {

  // The custom field to check.
  $key_to_look_up = 'imdb_url';

  // The value to check where 'num' is the element name.
  $value_to_look_up = $data['url'];

  // Prepare the WP_Query arguments
  $args = array(

    // Set the post type being imported.
    'post_type'  => array( 'movie' ),

    // Check our custom field for our value.
    'meta_query' => array( // phpcs:ignore
      'relation' => 'OR',
      array(
        'key' => $key_to_look_up,
        'value' => $value_to_look_up,
        'compare' => '=',
      ),
      array(
        'key' => $key_to_look_up,
        'value' => $value_to_look_up . '/',
        'compare' => '=',
      ),
      array(
        'key' => $key_to_look_up,
        'value' => rtrim( $value_to_look_up, '/' ),
        'compare' => '=',
      ),
    ),
  );

  // Run the query and do not create post if custom
  // field value is duplicated.
  $query = new \WP_Query( $args );

  return ! ( $query->have_posts() );
}

add_filter( 'wp_all_import_is_post_to_create', __NAMESPACE__ . '\create_only_if_unique_custom_field', 10, 3 );
