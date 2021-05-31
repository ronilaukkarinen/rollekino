<?php
/**
 * @Author: Roni Laukkarinen
 * @Date:   2021-02-04 18:15:59
 * @Last Modified by:   Roni Laukkarinen
 * @Last Modified time: 2021-02-04 18:19:44
 *
 * @package rollekino
 */

namespace Air_Light;

/**
 * Update movie title when saving article
 */
add_filter( 'wp_insert_post_data' , __NAMESPACE__ . '\save_post_function', 10, 2);

function save_post_function( $data, $id ) {
  if ( 'post' != $data['post_type'] ) {
    return;
  }

  // Save things in post meta
  update_post_meta( $id['ID'], '_rollekino_rating', '4' );

  // Update the post's title.
  $data['post_title'] = 'Test';
  return $data;
}
