<?php
/**
 * @Author: Roni Laukkarinen
 * @Date:   2021-02-04 18:15:59
 * @Last Modified by:   Roni Laukkarinen
 * @Last Modified time: 2021-08-25 20:18:20
 *
 * @package rollekino
 */

namespace Air_Light;

/**
 * Update movie title when saving article
 */
 add_filter( 'wp_insert_post_data', __NAMESPACE__ . '\save_post_function', 10, 2 );

 function save_post_function( $data, $id ) {
   if ( 'movie' === $data['post_type'] ) {
     // Save things in post meta
     update_post_meta( $id['ID'], '_imdb_rating', '7.5' );

     // Update the post's title.
     $data['post_title'] = 'Test';
     return $data;
   } else {
     return $data;
   }
 }
