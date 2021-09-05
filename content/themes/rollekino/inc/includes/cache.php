<?php
/**
 * @Author: Timi Wahalahti
 * @Date:   2020-06-11 14:06:03
 * @Last Modified by:   Roni Laukkarinen
 * @Last Modified time: 2021-09-05 12:23:33
 *
 * @package rollekino
 */

namespace Air_Light;

function get_cache_expire( $expiry = null ) {
  $expiry = is_integer( $expiry ) ? absint( $expiry ) : HOUR_IN_SECONDS;
  return $expiry + mt_rand( 60, 600 ); // differiate between 1 and 10 minutes
} // end get_cache_expire

function transient_cache_clear() {
  $last_flush = get_option( 'rollekino_transient_cache_flushed', time() );

  // Throttle
  if ( strtotime( '-1 minutes' ) < $last_flush ) {
   return;
  }

  if ( function_exists( 'wp_cache_flush' ) ) {
    wp_cache_flush();
  }

  /**
   *  Get all site content transients from databse. This comes handy later on
   *  when we need to flush transients that has dynamic values in end of the key.
   *
   *  phpcs:disable
   */
  global $wpdb;
  $sql = "SELECT `option_name` AS `name` FROM  $wpdb->options WHERE `option_name` LIKE '%transient_rollekino%'";
  $transients = $wpdb->get_results( $sql );
  // phpcs:enable

  if ( ! empty( $transients ) ) {
    // Loop transients and make nicer array for handling.
    foreach ( $transients as $key => $transient ) {
      $transients[ $key ] = @end( explode( '_transient_', $transient->name ) );
    }

    foreach ( $transients as $transient ) {
      delete_transient( $transient );
    }
  }

  transient_cache_warmup();

  update_option( 'rollekino_transient_cache_flushed', time() );
  add_action( 'admin_notices', __NAMESPACE__ . '\transient_cache_cleared_notice' );
} // transient_cache_clear

function transient_cache_cleared_notice() {
  $class = 'notice notice-info';
  $message = 'Välimuisti tyhjennetty ja kirja-arkisto esiladattu!';
  printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
}

/**
 * Call functions that warm up caches that build up slowly.
 */
function transient_cache_warmup() {
  // Prevent infinite loop on save by removing the action
  remove_action( 'save_post', __NAMESPACE__ . '\maybe_clear_transient_cache' );

  get_initial_books();

  // Add action back
  add_action( 'save_post', __NAMESPACE__ . '\maybe_clear_transient_cache' );
} // end transient_cache_warmup
