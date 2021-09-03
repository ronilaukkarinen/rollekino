<?php
/**
 * Site branding & logo
 *
 * @package rollekino
 */

namespace Air_Light;

$description = get_bloginfo( 'description', 'display' );
?>

<div class="site-branding">

  <p class="site-title">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
      <?php include get_theme_file_path( THEME_SETTINGS['logo'] ); ?>
      <span class="screen-reader-text"><?php bloginfo( 'name' ); ?></span>
    </a>
  </p>

  <?php if ( $description || is_customize_preview() ) : ?>
    <p class="site-description screen-reader-text">
      <?php echo esc_html( $description ); ?>
    </p>
  <?php endif; ?>

</div>
