<?php
/**
 * Template for displaying the footer
 *
 * Description for template.
 *
 * @Author: Roni Laukkarinen
 * @Date: 2020-05-11 13:33:49
 * @Last Modified by:   Roni Laukkarinen
 * @Last Modified time: 2021-09-04 16:14:48
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 * @package rollekino
 */

namespace Air_Light;

?>

</div><!-- #content -->

<footer id="colophon" class="site-footer has-dark-bg">

  <div class="container">
    <h2>Seuraa <span>&</span> keskustele</h2>
    <p>Rollen leffaprofiilit löytyvät lähes jokaisesta netin leffapalvelusta. Saat ensimmäisenä tiedon uusista arvioista kun pistät seurantaan esimerkiksi <a href="<?php echo esc_url( bloginfo( 'rss2_url' ) ); ?>">RSS-syötteen</a> tai Twitterin <a href="https://twitter.com/search?q=from%3Arolle%20%23leffat&src=typed_query&f=live">#leffat</a> -hashtagin. Alla olevista palveluista löydät kätevästi leffasuosituksia.</p>

    <ul>
      <li class="imdb">
        <a
          class="no-external-link-indicator"
          href="http://www.imdb.com/mymovies/list?l=27761618">
          <span class="screen-reader-text">IMDb</span>
          <span aria-hidden="true">
            <?php include get_theme_file_path( '/svg/imdb.svg' ); ?>
          </span>
        </a>
      </li>
      <li class="listal">
        <a
        class="no-external-link-indicator"
        href="http://rolle.listal.com/">
          <span class="screen-reader-text">Listal</span>
          <span aria-hidden="true">
            <?php include get_theme_file_path( '/svg/listal.svg' ); ?>
          </span>
        </a>
      </li>
      <li class="letterboxd">
        <a
        class="no-external-link-indicator"
        href="https://letterboxd.com/rolle">
          <span class="screen-reader-text">Letterboxd</span>
          <span aria-hidden="true">
            <?php include get_theme_file_path( '/svg/letterboxd.svg' ); ?>
          </span>
        </a>
      </li>
      <li class="trakt">
        <a
        class="no-external-link-indicator"
        href="http://trakt.tv/user/rolle">
          <span class="screen-reader-text">Trakt</span>
          <span aria-hidden="true">
            <?php include get_theme_file_path( '/svg/trakt.svg' ); ?>
          </span>
        </a>
      </li>
      <li class="tmdb">
        <a
        class="no-external-link-indicator"
        href="https://www.themoviedb.org/u/rolle">
          <span class="screen-reader-text">The Movie Database</span>
          <span aria-hidden="true">
            <?php include get_theme_file_path( '/svg/tmdb.svg' ); ?>
          </span>
        </a>
      </li>
      <li class="taste">
        <a
        class="no-external-link-indicator"
        href="https://www.taste.io/users/rolle">
          <span class="screen-reader-text">Taste.io</span>
          <span aria-hidden="true">
            <?php include get_theme_file_path( '/svg/taste.svg' ); ?>
          </span>
        </a>
      </li>
    </ul>
  </div>

  <p class="back-to-top"><a href="#page" class="js-trigger top no-text-link no-external-link-indicator" data-mt-duration="300"><span class="screen-reader-text"><?php echo esc_html( get_default_localization( 'Back to top' ) ); ?></span><?php include get_theme_file_path( '/svg/chevron-up.svg' ); ?></a></p>

</footer><!-- #colophon -->

</div><!-- #page -->

<?php wp_footer(); ?>
</body>

</html>
