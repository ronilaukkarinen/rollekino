<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @Date:   2019-10-15 12:30:02
 * @Last Modified by:   Timi Wahalahti
 * @Last Modified time: 2021-01-12 17:23:47
 *
 * @package rollekino
 */

namespace Air_Light;

$results = [];

if ( ! empty( $_GET['s'] ) && have_posts() ) { // phpcs:ignore
  while ( have_posts() ) {
    the_post();
    $post_type = get_post_type();

    $results[ $post_type ]['posts'][] = [
      'title'     => (string) get_the_title(),
      'permalink' => (string) get_permalink(),
      'excerpt'   => (string) get_the_excerpt(),
      'id'        => (string) get_the_ID(),
      'post_type' => (string) get_post_type(),
      'date'      => (string) get_the_date(),
    ];
  }
} wp_reset_postdata();

// Get post type objects for results
foreach ( $results as $slug => $post_type ) {
  $results[ $slug ]['object'] = (object) get_post_type_object( $slug );
  $results[ $slug ]['count']  = (int) count( $results[ $slug ]['posts'] );
}

get_header(); ?>

<main class="site-main">

<?php
$movies = [];
$post_type = 'movie';
$args = [
  'orderby' => 'rand',
  'post_type' => $post_type,
  'posts_per_page' => 1,
];

$query = new \WP_Query( $args );

if ( ! empty( $query->posts ) ) : ?>
  <section class="block block-search block-hero-movies has-dark-bg">
    <div class="shade" aria-hidden="true"></div>

    <div class="container">
      <div class="content">
        <h1 class="block-title block-title-persistent">Haku</h1>

        <?php if ( empty( $_GET['s'] ) ) : // phpcs:ignore ?>
          <div id="search"></div>
        <?php else : ?>

          <form id="search-form" role="search" method="get" class="search-form<?php if ( ! empty( $_GET['s'] ) ) : echo esc_html( ' filled' ); endif; // phpcs:ignore ?>" action="<?php echo esc_url( get_home_url() ); ?>">
				    <label for="search-field" class="search-form-label">Hae elokuvaa</label>
					  <input id="search-field" type="search" class="search-field search-form-field<?php if ( ! empty( $_GET['s'] ) ) : echo esc_html( ' filled' ); endif; // phpcs:ignore ?>" value="<?php if ( ! empty( $_GET['s'] ) ) : echo esc_html( $_GET['s'] ); endif; // phpcs:ignore ?>" name="s" autocomplete="off">
				    <button type="submit" class="search-submit" aria-label="Hae"><?php include get_theme_file_path( '/svg/search.svg' ); ?></button>
			    </form>

          <?php if ( ! empty( $results ) ) : ?>
            <div class="result-container loaded">
              <div class="container">
                <div class="results">
                <?php foreach ( $results as $slug => $post_type ) : ?>

                  <div class="result-group <?php echo esc_attr( $slug ) ?>">

                    <h2><?php echo esc_html( $post_type['object']->labels->name ); ?> <span><?php echo esc_html( $post_type['count'] ); ?></span></h2>

                    <ul>
                    <?php foreach ( $post_type['posts'] as $post ) : ?>
                      <li>
                        <div>

                        <?php
                          if ( 'movie' === $post['post_type'] ) :
                          $post_id = $post['id'];

                          // Metadata
                          $poster_id = get_post_meta( $post_id, 'poster', true );
                          $poster_url = wp_get_attachment_image_url( $poster_id, 'full' );
                          $rating = get_post_meta( $post_id, 'rating', true );
                          $imdb_rating = get_post_meta( $post_id, '_imdb_rating', true );
                          $imdb_year = get_post_meta( $post_id, '_imdb_year', true );
                          ?>

                          <div class="movie-meta-data has-link">
                            <div class="movie-meta-data-box">
                              <a aria-label="Elokuvan <?php echo esc_html( $post['title'] ); ?> arvioon" class="global-link" href="<?php echo esc_url( $post['permalink'] ); ?>"></a>
                                <?php if ( ! empty( $poster_id ) ) : ?>
                                  <div class="movie-poster-wrapper movie-poster-wrapper-small">
                                    <div class="movie-poster">
                                      <img src="<?php echo esc_url( $poster_url ); ?>" alt="<?php echo esc_html( get_the_title( $poster_id ) ); ?>" />
                                      <div class="frame" aria-hidden="true"></div>
                                    </div>
                                  </div>
                              <?php endif; ?>

                              <div class="movie-meta-data-content">
                                <h3 class="movie-meta-data-title"><?php echo esc_html( $post['title'] ); ?> <span class="release-year"><?php echo esc_html( $imdb_year ); ?></span></h3>
                                <ul class="movie-meta-data-list">
                                  <li class="movie-meta-data-watched">Katsottu <?php echo esc_html( $post['date'] ); ?></li>
                                  <li><?php rating_stars_by_id( $post['id'] ); ?></li>
                                </ul>
                              </div>
                            </div>
                          </div>
                          <?php else : ?>
                            <div class="result-post">
                              <h3>
                                <a href="<?php echo esc_url( $post['permalink'] ); ?>">
                                  <?php echo esc_html( $post['title'] ); ?>
                                </a>
                              </h3>

                              <p class="excerpt">
                                <?php echo wp_kses_post( $post['excerpt'] ); ?>
                              </p>
                            </div>
                          <?php endif; ?>

                        </div>
                      </li>
                    <?php endforeach; ?>
                    </ul>

                  </div>

                <?php endforeach; ?>
                </div>
              </div>
            </div>
          <?php else : ?>
            <div class="no-results">
              <p>Ei hakutuloksia haulla <strong><?php if ( ! empty( $_GET['s'] ) ) : echo esc_html( $_GET['s'] ); endif; // phpcs:ignore ?></strong></p>
            </div>
          <?php endif; ?>
        <?php endif; ?>
      </div>

      <?php while ( $query->have_posts() ) :
        $query->the_post();

        // Meta data
        $backdrop_url = esc_url( wp_get_attachment_url( get_post_thumbnail_id() ) );
        $poster_id = get_post_meta( get_the_ID(), 'poster', true );
        $poster_url = wp_get_attachment_image_url( $poster_id, 'full' );
        $rating = get_post_meta( get_the_ID(), 'rating', true );
        $imdb_rating = get_post_meta( get_the_ID(), '_imdb_rating', true );
        $imdb_year = get_post_meta( get_the_ID(), '_imdb_year', true );
        $imdb_release_date = get_post_meta( get_the_ID(), '_imdb_release_date', true );
        ?>

        <div class="backdrop">
          <div class="lazy" style="background-image: url('<?php echo esc_url( $backdrop_url ); ?>');"></div>
        </div>

      <?php endwhile; ?>

    </div>

  </section>
  <?php endif; ?>

</main>

<?php get_footer();
