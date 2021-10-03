<?php
/**
 * The template for displaying all single posts
 *
 * @Date:   2019-10-15 12:30:02
 * @Last Modified by:   Timi Wahalahti
 * @Last Modified time: 2021-01-12 16:11:09
 *
 * @package rollekino
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 */

namespace Air_Light;

the_post();
get_header();

// Set locale
date_default_timezone_set( 'Europe/Helsinki' ); // phpcs:ignore
setlocale( LC_ALL, array( 'fi_FI.UTF-8', 'fi_FI@euro', 'fi_FI', 'Finnish' ) );
setlocale( LC_TIME, array( 'fi_FI.UTF-8', 'fi_FI@euro', 'fi_FI', 'Finnish' ) );

// Meta data
$backdrop_url = esc_url( wp_get_attachment_url( get_post_thumbnail_id() ) );
$poster_id = get_post_meta( get_the_ID(), 'poster', true );
$poster_url = wp_get_attachment_image_url( $poster_id, 'full' );
$rating = get_post_meta( get_the_ID(), 'rating', true );
$imdb_rating = get_post_meta( get_the_ID(), '_imdb_rating', true );
$imdb_url = get_post_meta( get_the_ID(), 'imdb_url', true );
$imdb_year = get_post_meta( get_the_ID(), '_imdb_year', true );
$imdb_release_date = get_post_meta( get_the_ID(), '_imdb_release_date', true );
$imdb_release_date_readable = strftime( '%e. %B', strtotime( $imdb_release_date ) ) . 'ta ' . strftime( '%Y', strtotime( $imdb_release_date ) );
$metascore_rating = get_post_meta( get_the_ID(), '_metascore_rating', true );
$imdb_runtime_total_minutes = get_post_meta( get_the_ID(), '_idmb_runtime', true );
$idmb_runtime_hours = floor( $imdb_runtime_total_minutes / 60 );
$imdb_runtime_minutes = $imdb_runtime_total_minutes % 60;
$runtime_human_readable = $idmb_runtime_hours . ' tuntia, ' . $imdb_runtime_minutes . ' minuuttia';
$trailer_youtube_key = get_post_meta( get_the_ID(), '_trailer_youtube_key', true );
$metascore_url = 'https://www.metacritic.com/movie/' . sanitize_title( get_the_title() );
$custom_watchlink = get_post_meta( get_the_ID(), 'custom_watchlink', true );
$custom_watchlink_type = get_post_meta( get_the_ID(), 'custom_watchlink_type', true );

if ( 60 <= $metascore_rating ) {
  $metascore_class = 'positive';
} elseif ( 40 <= $metascore_rating ) {
  $metascore_class = 'mixed';
} elseif ( 40 >= $metascore_rating ) {
  $metascore_class = 'negative';
} else {
  $metascore_class = 'tbd';
}
?>

<main class="site-main">

<section class="block block-hero-movies block-hero-movies-single has-dark-bg">
    <div class="shade" aria-hidden="true"></div>

    <div class="container">
        <div class="backdrop">
          <div class="lazy" style="background-image: url('<?php echo esc_url( $backdrop_url ); ?>');"></div>

          <div class="video js-video">
            <div
              class="youtube-player"
              data-video-id="<?php echo esc_html( $trailer_youtube_key ); ?>"
              data-play-button="play-<?php echo esc_html( $trailer_youtube_key ); ?>">
            </div>

            <div aria-hidden="true" class="video-preview lazy" data-bg="<?php echo esc_url( $backdrop_url ); ?>"></div>
          </div>
        </div>

        <div class="movie-meta-data">

          <div class="buttons">
            <?php if ( ! empty( $trailer_youtube_key ) ) : ?>
              <a type="button" class="mediabox no-external-link-indicator" href="https://www.youtube.com/watch?v=<?php echo esc_html( $trailer_youtube_key ); ?>">
                <?php include get_theme_file_path( '/svg/youtube.svg' ); ?>
                <span>Katso traileri</span>
              </a>
            <?php endif; ?>

            <button
              class="play play-button hidden"
              id="play-<?php echo esc_html( $trailer_youtube_key ); ?>"
              type="button">
              <span class="play-label hidden">
                <?php include get_theme_file_path( '/svg/play.svg' ); ?> Jatka trailerin pyörittämistä sittenkin
              </span>
              <span class="pause-label hidden">
                <?php include get_theme_file_path( '/svg/pause.svg' ); ?> Älä spoilaa, pysäytä taustavideo
              </span>
            </button>
          </div>

          <div class="movie-meta-data-box movie-meta-data-box-large">
            <div class="movie-poster-wrapper movie-poster-wrapper-large">
              <div class="movie-poster">
                <img src="<?php echo esc_url( $poster_url ); ?>" alt="<?php echo esc_html( get_the_title( $poster_id ) ); ?>" />
                <div class="frame" aria-hidden="true"></div>
              </div>
            </div>

            <div class="movie-meta-data-content">

              <ul class="poster-information">
                <?php
                  $terms = get_the_terms( get_the_ID(), 'director' ); ?>
                  <?php if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) : ?>
                    <li class="directed">
                      <span class="screen-reader-text">Ohjannut</span>
                      <span class="crew" aria-hidden="true">k</span>
                      <?php foreach ( $terms as $term ) : ?>
                        <span class="name"><?php echo esc_html( $term->name ); ?></span>
                      <?php endforeach; ?>
                    </li>
                <?php endif; ?>

                <?php
                  $terms = get_the_terms( get_the_ID(), 'writer' ); ?>
                  <?php if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) : ?>
                    <li class="writer">
                      <span class="screen-reader-text">Käsikirjoitus</span>
                      <span class="crew" aria-hidden="true">a</span>
                      <?php foreach ( $terms as $term ) : ?>
                        <span class="name"><?php echo esc_html( $term->name ); ?></span>
                      <?php endforeach; ?>
                    </li>
                <?php endif; ?>

                <?php
                  $terms = get_the_terms( get_the_ID(), 'actor' ); ?>
                  <?php if ( ! empty( $terms ) && ! is_wp_error( $terms ) && count( $terms ) > 0 ) : ?>
                    <li class="casting">
                      <span class="screen-reader-text">Pääosissa</span>
                      <?php foreach ( $terms as $term ) : ?>
                        <span class="name"><?php echo esc_html( $term->name ); ?></span>
                      <?php endforeach; ?>
                    </li>
                <?php endif; ?>
              </ul>

              <h1 class="movie-meta-data-title"><?php the_title(); ?> <span class="release-year"><?php echo esc_html( $imdb_year ); ?></span></h1>
              <ul class="movie-meta-data-list">
                <li><?php rating_stars( $text = true ); ?></li>
                <li class="imdb">
                  <a href="<?php echo esc_url( $imdb_url ); ?>" class="no-external-link-indicator">
                    <span class="screen-reader-text">IMDb-pisteet:</span>
                    <?php include get_theme_file_path( '/svg/imdb.svg' ); ?>
                    <span class="rating">
                      <?php echo esc_html( $imdb_rating ); ?>
                    </span>
                  </a>
                </li>
                <?php if ( ! empty( $metascore_rating ) ) : ?>
                  <li class="metascore">
                    <a href="<?php echo esc_url( $metascore_url ); ?>" class="no-external-link-indicator">
                      <span class="screen-reader-text">Metascore-pisteet:</span>
                      <span class="rating <?php echo esc_html( $metascore_class ); ?>">
                        <?php echo esc_html( $metascore_rating ); ?>
                      </span>
                    </a>
                  </li>
                <?php endif; ?>
              </ul>
            </div>
          </div>

        </div>

</section>

<section class="block block-movie-review has-dark-bg">
    <div class="container">

      <aside class="side">
        <ul class="side-information">

          <li>
            <span class="side-information-title">Julkaisuajankohta</span><span class="screen-reader-text">:</span> <span class="side-information-meta"><?php echo esc_html( $imdb_release_date_readable ); ?></span>
          </li>
          <li>
            <span class="side-information-title">Kesto</span><span class="screen-reader-text">:</span> <span class="side-information-meta"><?php echo esc_html( $runtime_human_readable ); ?></span>
          </li>

          <?php
          $terms = get_the_terms( get_the_ID(), 'director' ); ?>
          <li>
            <span class="side-information-title">Ohjaaja<?php if ( 1 < count( $terms ) ) : echo 't'; endif; ?><span class="screen-reader-text">:</span>

            <?php if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) : ?>
              <ul class="side-information-meta-crew<?php if ( 1 < count( $terms ) ) : echo ' multiple-directors'; endif; ?>">
                <?php foreach ( $terms as $term ) :
                  $avatar_url = get_field( 'avatar', 'director_' . $term->term_id )['url'];
                  ?>
                  <li>
                    <a href="<?php echo esc_url( get_term_link( $term->term_id ) ); ?>" class="global-link" aria-label="<?php echo esc_html( $term->name ); ?>"></a>
                    <?php if ( ! empty( $avatar_url ) ) : ?>
                      <div aria-hidden="true" class="avatar" style="background-image: url('<?php echo esc_url( $avatar_url ); ?>');"></div>
                    <?php else : ?>
                      <div aria-hidden="true" class="avatar avatar-empty">
                        <?php include get_theme_file_path( '/svg/avatar-empty.svg' ); ?>
                      </div>
                    <?php endif; ?>
                    <span class="side-information-meta side-information-meta-crew-name" aria-hidden="true"><?php echo esc_html( $term->name ); ?></span>
                  </li>
                <?php endforeach; ?>
              </ul>
            <?php endif; ?>
          </li>

          <?php
          wp_reset_postdata();
          $terms = get_the_terms( get_the_ID(), 'writer' ); ?>
          <li style="display: none;">
            <span class="side-information-title">Käsikirjoittaja<?php if ( 1 < count( $terms ) ) : echo 't'; endif; ?><span class="screen-reader-text">:</span>

            <?php if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) : ?>
              <ul class="side-information-meta-crew<?php if ( 1 < count( $terms ) ) : echo ' multiple-directors'; endif; ?>">
                <?php foreach ( $terms as $term ) :
                  $avatar_url = get_field( 'avatar', 'director_' . $term->term_id )['url'];
                  ?>
                  <li>
                    <a href="<?php echo esc_url( get_term_link( $term->term_id ) ); ?>" class="global-link" aria-label="<?php echo esc_html( $term->name ); ?>"></a>
                    <?php if ( ! empty( $avatar_url ) ) : ?>
                      <div aria-hidden="true" class="avatar" style="background-image: url('<?php echo esc_url( $avatar_url ); ?>');"></div>
                    <?php else : ?>
                      <div aria-hidden="true" class="avatar avatar-empty">
                        <?php include get_theme_file_path( '/svg/avatar-empty.svg' ); ?>
                      </div>
                    <?php endif; ?>
                    <span class="side-information-meta side-information-meta-crew-name" aria-hidden="true"><?php echo esc_html( $term->name ); ?></span>
                  </li>
                <?php endforeach; ?>
              </ul>
            <?php endif; ?>
          </li>

          <?php
          wp_reset_postdata();
          $terms = get_the_terms( get_the_ID(), 'actor' );
          if ( ! empty( $terms ) && ! is_wp_error( $terms ) && count( $terms ) > 0 ) : ?>
          <li>
            <span class="side-information-title">Pääosissa<span class="screen-reader-text">:</span>

            <?php if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) : ?>
              <ul class="side-information-meta-crew-actors side-information-meta-crew">
                <?php foreach ( $terms as $term ) :
                  $avatar_url = get_field( 'avatar', 'director_' . $term->term_id )['url'];
                  ?>
                  <li class="has-small-avatar">
                    <a href="<?php echo esc_url( get_term_link( $term->term_id ) ); ?>" class="global-link" aria-label="<?php echo esc_html( $term->name ); ?>"></a>
                    <?php if ( ! empty( $avatar_url ) ) : ?>
                      <div aria-hidden="true" class="avatar avatar-small" style="background-image: url('<?php echo esc_url( $avatar_url ); ?>');"></div>
                    <?php else : ?>
                      <div aria-hidden="true" class="avatar avatar-small avatar-empty">
                        <?php include get_theme_file_path( '/svg/avatar-empty.svg' ); ?>
                      </div>
                    <?php endif; ?>
                    <span class="side-information-meta side-information-meta-crew-name" aria-hidden="true"><?php echo esc_html( $term->name ); ?></span>
                  </li>
                <?php endforeach; ?>
              </ul>
            <?php endif; ?>
          </li>
          <?php endif; ?>

        </ul>
      </aside>

      <div class="content">

        <?php
          $post_year = get_the_time( 'Y' );
          $now_year = gmdate( 'Y' );
        ?>

        <?php if ( $post_year <= $now_year - 4 ) : ?>
          <div class="notification-box-old-stuff">
            <p>Ennen kuin luet, otathan huomioon, että tämä elokuva on katsottu <?php echo get_the_date(); ?>, joka tarkoittaa sitä että arvostelu on <b><?php echo esc_attr( $now_year ) - esc_attr( $post_year ); ?> vuotta vanha</b> ja saattaa siitä syystä sisältää vanhahtanutta tekstiä tai kankeaa kielenkäyttöä. Mielipiteeni on saattanut ajan saatossa muuttua ja elokuva saattaa vaatia uudelleenkatselun uusilla aivoilla. Olin arvion kirjoittamishetkellä <?php $post_year = get_the_time( 'Y' ); $age = $post_year - 1988; echo esc_attr( $age ); ?>-vuotias.</p>
          </div>
        <?php endif; ?>

        <?php if ( ! empty( get_field( 'plot' ) ) ) : ?>
          <div class="plot">
            <?php echo wpautop( get_field( 'plot' ) ); // phpcs:ignore ?>
          </div>
        <?php endif; ?>

        <?php if ( empty( get_the_content() ) ) : ?>
          <div class="content-empty">
            <p>Valitettavasti tästä elokuvasta ei ole kirjoitettu tekstiarviota. Syynä voivat olla mm. jokin seuraavista:</p>
            <ul>
              <li>Elokuva on katsottu ennen kuin tekstiarvio-ominaisuus oli käytössä (2005-2007).</li>
              <li>Elokuva on katsottu 2020-2021, jolloin Rollekinon vanha versio poistui käytöstä ja uutta alettiin työstämään.</li>
              <li>Leffa on ainoastaan pisteytetty <a href="https://www.imdb.com/user/ur12339490/ratings">IMDb-profiiliin</a> ajan puutteen vuoksi.</li>
              <li>Elokuva ei ole ollut pidemmän arvioimisen arvoinen tai siitä ei yksinkertaisesti ole riittänyt juttua tekstiksi asti.</li>
              <li>Jokin muu tuntematon syy, mutta todennäköisesti jokin edellisestä.</li>
            </ul>
            <p>Toivottavasti silti nautit suuntaa-antavasta pisteytyksestä ja elokuvan tiedoista!</p>
          </div>
        <?php else : ?>
          <?php the_content(); ?>
        <?php endif; ?>

        <ul class="side-information side-information-main-content">
          <li><span class="side-information-title">Katsottu ja arvioitu</span><span class="screen-reader-text">:</span> <?php echo get_the_date(); ?></li>

          <?php
            wp_reset_postdata();
            $terms = get_the_terms( get_the_ID(), 'genre' ); ?>
          <li><span class="side-information-title">Genret</span><span class="screen-reader-text">:</span>
            <?php if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) : ?>
              <ul class="genres">
                <?php foreach ( $terms as $term ) : ?>
                  <li>
                    <a class="genre-pill" href="<?php echo esc_url( get_term_link( $term->term_id ) ); ?>">
                      <?php echo esc_html( $term->name ); ?></span>
                    </a>
                  </li>
                <?php endforeach; ?>
              </ul>
            <?php endif; ?>
          </li>

        </ul>

        <?php
        // Get IMDb ID for JustWatch API
        $imdb_id_match = preg_match_all( '/tt\\d{7,8}/', $imdb_url, $ids );
        $imdb_id = $ids[0][0];
        ?>

        <h3 class="justwatch-title side-information-title">Mistä tämän voi katsoa?</h3>

        <?php if ( ! empty( $custom_watchlink ) ) : ?>
          <div class="custom-jw-widget">
            <div class="jw-offer">
              <a href="<?php echo esc_url( $custom_watchlink ); ?>" class="no-external-link-indicator">
                <img src="<?php echo esc_url( get_template_directory_uri() . '/images/' . $custom_watchlink_type . '.png' ); ?>" class="jw-package-icon" alt="JW icon">
                <div class="jw-offer-label">
                  Ilmainen
                </div>
              </a>
            </div>
          </div>

        <?php else : ?>
          <div
            data-jw-widget
            data-api-key="<?php echo esc_html( getenv( 'JUSTWATCH_API_KEY' ) ); ?>"
            data-id="<?php echo esc_html( $imdb_id ); ?>"
            data-object-type="movie"
            data-id-type="imdb"
            data-theme="dark"
            data-language="fi"
            data-locale="fi_FI"
            data-no-offers-message="ㅤElokuvaa {{title}} ei ole tällä hetkellä saatavilla streamauspalveluissa Suomen puolella."
            data-title-not-found-message="ㅤElokuvaa {{title}} ei ole tällä hetkellä saatavilla streamauspalveluissa Suomen puolella."
          >
          </div>
          <div>
          <a style="color: #fff;" class="no-external-link-indicator" data-original="https://www.justwatch.com" href="https://www.justwatch.com/fi">
            Linkit tarjoaa <span style="margin-left: 8px;"><?php include get_theme_file_path( '/svg/justwatch.svg' ); ?></span>
          </a>
        </div>
        <script script async src="https://widget.justwatch.com/justwatch_widget.js"></script>
        <?php endif; ?>

        <p>
          <a class="button button-small button-bmc no-external-link-indicator" href="https://www.buymeacoffee.com/Fd140aV">
            <span>Saitko leffavinkin? Tarjoa kirjoittajalle kahvit kiitokseksi!</span>
          </a>
        </p>

        <?php
          if ( get_edit_post_link() ) {
            edit_post_link( sprintf( wp_kses( __( 'Muokkaa <span class="screen-reader-text">%s</span>', 'rollekino' ), [ 'span' => [ 'class' => [] ] ] ), get_the_title() ), '<p class="edit-link">', '</p>' );
          }
        ?>
      </div>

    </div>
</section>

<?php
  if ( function_exists( 'relevanssi_the_related_posts' ) ) {
    relevanssi_the_related_posts();
  }
?>

  <?php
    wp_reset_postdata();
    if ( ! is_search() && empty( $_GET['s'] ) ) { // phpcs:ignore
      if ( ! is_front_page() ) {
        include get_theme_file_path( 'template-parts/search-modal.php' );
      }
    }
  ?>
</main>

<?php get_footer();
