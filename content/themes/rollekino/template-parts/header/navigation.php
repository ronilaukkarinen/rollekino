<?php
/**
 * Navigation layout.
 *
 * @Author: Roni Laukkarinen
 * @Date: 2020-05-11 13:22:26
 * @Last Modified by:   Roni Laukkarinen
 * @Last Modified time: 2021-09-11 18:11:49
 *
 * @package rollekino
 */

namespace Air_Light;

?>

<div class="main-navigation-wrapper" id="main-navigation-wrapper">

  <button class="open-search open-search-mobile" id="open-search-mobile" aria-expanded="false" aria-haspopup="true" aria-label="Avaa haku">
    <svg class="icon-search" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 27 27"><g stroke="currentColor" stroke-width="1.5" fill="none" fill-rule="evenodd" stroke-linecap="round" stroke-linejoin="round"><circle cx="11.813" cy="11.813" r="11.25"></circle><path d="M26.438 26.438l-6.671-6.671"></path></g></svg>
    <svg class="icon-close" width="22" height="22" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M505.943 6.058c-8.077-8.077-21.172-8.077-29.249 0L6.058 476.693c-8.077 8.077-8.077 21.172 0 29.249A20.612 20.612 0 0020.683 512a20.614 20.614 0 0014.625-6.059L505.943 35.306c8.076-8.076 8.076-21.171 0-29.248z"/><path d="M505.942 476.694L35.306 6.059c-8.076-8.077-21.172-8.077-29.248 0-8.077 8.076-8.077 21.171 0 29.248l470.636 470.636a20.616 20.616 0 0014.625 6.058 20.615 20.615 0 0014.624-6.057c8.075-8.078 8.075-21.173-.001-29.25z"/></svg>
  </button>

  <button aria-controls="nav" id="nav-toggle" class="nav-toggle hamburger" type="button" aria-label="<?php echo esc_html( get_default_localization( 'Open main menu' ) ); ?>">
    <span class="hamburger-box">
      <span class="hamburger-inner"></span>
    </span>
    <span id="nav-toggle-label" class="nav-toggle-label"><?php echo esc_html( get_default_localization( 'Open main menu' ) ); ?></span>
  </button>

  <nav id="nav" class="nav-primary nav-menu" aria-label="<?php echo esc_html( get_default_localization( 'Main navigation' ) ); ?>">

    <?php wp_nav_menu( array(
      'theme_location' => 'primary',
      'container'      => false,
      'depth'          => 4,
      'menu_class'     => 'menu-items',
      'menu_id'        => 'main-menu',
      'echo'           => true,
      'fallback_cb'    => __NAMESPACE__ . '\Nav_Walker::fallback',
      'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
      'has_dropdown'   => true,
      'walker'         => new Nav_Walker(),
    ) ); ?>

  </nav><!-- #nav -->
</div>
