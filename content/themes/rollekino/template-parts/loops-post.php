<?php
/**
 * @Author: Timi Wahalahti
 * @Date:   2021-06-11 14:43:07
 * @Last Modified by:   Roni Laukkarinen
 * @Last Modified time: 2021-09-05 20:31:54
 *
 * @package rollekino
 */

namespace Air_Light;

if ( ! isset( $args['post_id'] ) ) {
  return;
}
?>

<div class="result-post">
  <h3>
    <a href="<?php echo esc_url( get_the_permalink( $args['post_id'] ) ); ?>">
      <?php echo esc_html( get_the_title( $args['post_id'] ) ) ?>
    </a>
  </h3>

  <p class="excerpt">
    <?php echo wp_kses_post( get_the_excerpt( $args['post_id'] ) ); ?>
  </p>
</div>
