<?php
/**
 * Block
 *
 * @package rollekino
 */

namespace Air_Light;

?>

<section class="block block-best-movies has-dark-bg">
  <div class="container">
    <div class="cols">

      <div class="col">

        <?php
        wp_reset_postdata();
        $best_movies = [];
        $best_post_type = 'movie';
        $best_args = [
          'orderby' => 'meta_value_num',
          'meta_key' => 'rating',
          'order' => 'DESC',
          'post_type' => $best_post_type,
          'posts_per_page' => 10,
          'meta_query' => array(
            array(
              'key' => '_imdb_year',
              'value' => gmdate( 'Y' ),
              'compare' => '=',
            ),
          ),
        ];

        $best_query = new \WP_Query( $best_args );
        if ( ! empty( $best_query->posts ) ) : ?>

          <h2 class="block-title-secondary">Top 10 tänä vuonna julkaistut</h2>
          <p class="read-more">
            <a href="<?php echo esc_url( get_post_type_archive_link( 'movie' ) ); ?>">
              Katso kaikki
              <?php include get_theme_file_path( '/svg/arrow-right.svg' ); ?>
            </a>
          </p>

          <ol>
          <?php while ( $best_query->have_posts() ) :
          $best_query->the_post();

          // Meta data
          $post_id = get_the_ID();
          $backdrop_url = esc_url( wp_get_attachment_url( get_post_thumbnail_id() ) );
          $poster_id = get_post_meta( get_the_ID(), 'poster', true );
          $poster_url = wp_get_attachment_image_url( $poster_id, 'full' );
          $rating = get_post_meta( get_the_ID(), 'rating', true );
          $imdb_rating = get_post_meta( get_the_ID(), '_imdb_rating', true );
          $imdb_year = get_post_meta( get_the_ID(), '_imdb_year', true );
          $imdb_release_date = get_post_meta( get_the_ID(), '_imdb_release_date', true );
          $metascore_rating = get_post_meta( get_the_ID(), '_metascore_rating', true );
          $imdb_runtime_total_minutes = get_post_meta( get_the_ID(), '_idmb_runtime', true );
          $trailer_youtube_key = get_post_meta( get_the_ID(), '_trailer_youtube_key', true );
          ?>

          <li class="movie-meta-data has-link">
            <div class="movie-meta-data-box">
              <a aria-label="Elokuvan <?php the_title(); ?> arvioon" class="global-link" href="<?php echo esc_url( get_the_permalink() ); ?>"></a>
              <?php if ( ! empty( $poster_id ) ) : ?>
                <div class="movie-poster-wrapper movie-poster-wrapper-small">
                  <div class="movie-poster">
                    <img src="<?php echo esc_url( $poster_url ); ?>" alt="<?php echo esc_html( get_the_title( $poster_id ) ); ?>" />
                    <div class="frame" aria-hidden="true"></div>
                  </div>
                </div>
              <?php endif; ?>

              <div class="movie-meta-data-content">
                <h3 class="movie-meta-data-title"><?php the_title(); ?> <span class="release-year"><?php echo esc_html( $imdb_year ); ?></span></h3>
                <ul class="movie-meta-data-list">
                  <li class="movie-meta-data-watched<?php if ( ! empty( get_the_content( $post_id ) ) ) echo ' has-review-tag'; ?>">
                    Katsottu <?php echo get_the_date(); ?>
                    <?php if ( ! empty( get_the_content( $post_id ) ) ) : ?>
                      <span class="has-review-tag">
                        <?php include get_theme_file_path( '/svg/check.svg' ); ?>
                        <span>tekstiarvio löytyy</span>
                      </span>
                    <?php endif; ?>
                  </li>
                  <li><?php rating_stars(); ?></li>
                </ul>
              </div>
            </div>
          </li>

          <?php endwhile; ?>
        </ol>

        <?php else : ?>
          <h2 class="block-title-secondary">Top 10 tänä vuonna julkaistut</h2>
          <p class="read-more">
            <a href="<?php echo esc_url( get_post_type_archive_link( 'movie' ) ); ?>">
              Katso kaikki
              <?php include get_theme_file_path( '/svg/arrow-right.svg' ); ?>
            </a>
          </p>

          <p style="color: rgb(255 255 255 / .6); max-width: 70%; font-size: 15px;">Ei vielä katsottu yhtään vuonna <?php echo esc_html( gmdate( 'Y' ) ); ?> julkaistua elokuvaa.</p>
        <?php endif; ?>

      </div>

      <div class="col">

        <?php
        wp_reset_postdata();
        $best_movies = [];
        $best_post_type = 'movie';
        $best_args = [
          'orderby' => 'rating_sort date',
          'order' => 'DESC',
          'post_type' => $best_post_type,
          'posts_per_page' => 10,
          'meta_query' => array(
            // 'relation' => 'AND',
            'rating_sort' => array(
                'key' => 'rating',
                'value' => '10',
                'compare' => '=',
            ),
            // 'other_clause' => array(
            //     'key' => 'othermeta',
            //     'compare' => 'EXISTS',
            // ),
        ),
        ];

        $best_query = new \WP_Query( $best_args );
        if ( ! empty( $best_query->posts ) ) : ?>

          <h2 class="block-title-secondary">Kaikkien aikojen Top 10</h2>
          <p class="read-more">
            <a href="<?php echo esc_url( get_post_type_archive_link( 'movie' ) ); ?>">
              Katso kaikki
              <?php include get_theme_file_path( '/svg/arrow-right.svg' ); ?>
            </a>
          </p>

          <ol>
          <?php while ( $best_query->have_posts() ) :
          $best_query->the_post();

          // Meta data
          $post_id = get_the_ID();
          $backdrop_url = esc_url( wp_get_attachment_url( get_post_thumbnail_id() ) );
          $poster_id = get_post_meta( get_the_ID(), 'poster', true );
          $poster_url = wp_get_attachment_image_url( $poster_id, 'full' );
          $rating = get_post_meta( get_the_ID(), 'rating', true );
          $imdb_rating = get_post_meta( get_the_ID(), '_imdb_rating', true );
          $imdb_year = get_post_meta( get_the_ID(), '_imdb_year', true );
          $imdb_release_date = get_post_meta( get_the_ID(), '_imdb_release_date', true );
          $metascore_rating = get_post_meta( get_the_ID(), '_metascore_rating', true );
          $imdb_runtime_total_minutes = get_post_meta( get_the_ID(), '_idmb_runtime', true );
          $trailer_youtube_key = get_post_meta( get_the_ID(), '_trailer_youtube_key', true );
          ?>

          <li class="movie-meta-data has-link">
            <div class="movie-meta-data-box">
              <a aria-label="Elokuvan <?php the_title(); ?> arvioon" class="global-link" href="<?php echo esc_url( get_the_permalink() ); ?>"></a>
              <?php if ( ! empty( $poster_id ) ) : ?>
                <div class="movie-poster-wrapper movie-poster-wrapper-small">
                  <div class="movie-poster">
                    <img src="<?php echo esc_url( $poster_url ); ?>" alt="<?php echo esc_html( get_the_title( $poster_id ) ); ?>" />
                    <div class="frame" aria-hidden="true"></div>
                  </div>
                </div>
              <?php endif; ?>

              <div class="movie-meta-data-content">
                <h3 class="movie-meta-data-title"><?php the_title(); ?> <span class="release-year"><?php echo esc_html( $imdb_year ); ?></span></h3>
                <ul class="movie-meta-data-list">
                  <li class="movie-meta-data-watched<?php if ( ! empty( get_the_content( $post_id ) ) ) echo ' has-review-tag'; ?>">
                    Katsottu <?php echo get_the_date(); ?>
                    <?php if ( ! empty( get_the_content( $post_id ) ) ) : ?>
                      <span class="has-review-tag">
                        <?php include get_theme_file_path( '/svg/check.svg' ); ?>
                        <span>tekstiarvio löytyy</span>
                      </span>
                    <?php endif; ?>
                  </li>
                  <li><?php rating_stars(); ?></li>
                </ul>
              </div>
            </div>
          </li>

          <?php endwhile; ?>
        </ol>
        <?php endif; ?>

      </div>

      <div class="col">

        <?php
        wp_reset_postdata();
        $best_movies = [];
        $best_post_type = 'movie';
        $best_args = [
          // 'orderby' => 'meta_value_num',
          'orderby' => 'rand',
          'order' => 'DESC',
          'post_type' => $best_post_type,
          'posts_per_page' => 10,
          'meta_query' => array(
            'relation' => 'AND',
            array(
              'key' => 'rating',
              'value' => '8',
              'type' => 'numeric',
              'compare' => '>=',
            ),
            array(
              'key' => '_imdb_rating',
              'value' => '6.5',
              'type' => 'numeric',
              'compare' => '<',
            ),
          ),
        ];

        $best_query = new \WP_Query( $best_args );
        if ( ! empty( $best_query->posts ) ) : ?>

          <h2 class="block-title-secondary">Top 10 satunnaista yllättäjää</h2>
          <p class="read-more">
            <a href="<?php echo esc_url( get_post_type_archive_link( 'movie' ) ); ?>">
              Katso kaikki
              <?php include get_theme_file_path( '/svg/arrow-right.svg' ); ?>
            </a>
          </p>

          <ol>
          <?php while ( $best_query->have_posts() ) :
          $best_query->the_post();

          // Meta data
          $post_id = get_the_ID();
          $backdrop_url = esc_url( wp_get_attachment_url( get_post_thumbnail_id() ) );
          $poster_id = get_post_meta( get_the_ID(), 'poster', true );
          $poster_url = wp_get_attachment_image_url( $poster_id, 'full' );
          $rating = get_post_meta( get_the_ID(), 'rating', true );
          $imdb_rating = get_post_meta( get_the_ID(), '_imdb_rating', true );
          $imdb_year = get_post_meta( get_the_ID(), '_imdb_year', true );
          $imdb_release_date = get_post_meta( get_the_ID(), '_imdb_release_date', true );
          $metascore_rating = get_post_meta( get_the_ID(), '_metascore_rating', true );
          $imdb_runtime_total_minutes = get_post_meta( get_the_ID(), '_idmb_runtime', true );
          $trailer_youtube_key = get_post_meta( get_the_ID(), '_trailer_youtube_key', true );
          ?>

          <li class="movie-meta-data has-link">
            <div class="movie-meta-data-box">
              <a aria-label="Elokuvan <?php the_title(); ?> arvioon" class="global-link" href="<?php echo esc_url( get_the_permalink() ); ?>"></a>
              <?php if ( ! empty( $poster_id ) ) : ?>
                <div class="movie-poster-wrapper movie-poster-wrapper-small">
                  <div class="movie-poster">
                    <img src="<?php echo esc_url( $poster_url ); ?>" alt="<?php echo esc_html( get_the_title( $poster_id ) ); ?>" />
                    <div class="frame" aria-hidden="true"></div>
                  </div>
                </div>
              <?php endif; ?>

              <div class="movie-meta-data-content">
                <h3 class="movie-meta-data-title"><?php the_title(); ?> <span class="release-year"><?php echo esc_html( $imdb_year ); ?></span></h3>
                <ul class="movie-meta-data-list">
                  <li class="movie-meta-data-watched<?php if ( ! empty( get_the_content( $post_id ) ) ) echo ' has-review-tag'; ?>">
                    Katsottu <?php echo get_the_date(); ?>
                    <?php if ( ! empty( get_the_content( $post_id ) ) ) : ?>
                      <span class="has-review-tag">
                        <?php include get_theme_file_path( '/svg/check.svg' ); ?>
                        <span>tekstiarvio löytyy</span>
                      </span>
                    <?php endif; ?>
                  </li>
                  <li><?php rating_stars(); ?></li>
                </ul>
              </div>
            </div>
          </li>

          <?php endwhile; ?>
        </ol>
        <?php endif; ?>

      </div>

      </div>

    </div>
  </section>
