<?php if ( !defined( 'ABSPATH' ) ) exit; ?>
<?php get_header(); ?>

<div class="column">
  <!-- ヘッダーセクション -->
  <div class="column-header">
    <div class="column-header-inner">
      <div class="column-title-container">
        <h1 class="column-title">お役立ちコラム</h1>
      </div>
    </div>
  </div>

  <div class="column-container">
    <main id="main" class="column-main">
      <div class="column-main-inner">
        <?php breadcrumb(); ?>
        
        <section class="post-column-sec01">
          <?php while (have_posts()) : the_post(); ?>
            <article class="post-column">
              <div class="post-column__cd">
                <?php the_category(); ?>
                <p class="post-column__date"><?php the_time(get_option('date_format')); ?></p>
              </div>
              
              <h1 class="post-column__title"><?php the_title(); ?></h1>
              
              <?php if (has_post_thumbnail()) : /* もしアイキャッチが登録されていたら */ ?>
                <figure class="post-column__thumbnail">
                  <?php the_post_thumbnail('large'); ?>
                </figure>
              <?php else: /* 登録されていなかったら */ ?>
                <figure class="post-column__thumbnail">
                  <img src="<?php echo get_no_image_url(); ?>" alt="NO IMAGE">
                </figure>
              <?php endif; ?>
              
              <div class="post-column__content">
                <?php the_content(); ?>
              </div>
              
              <!-- タグがあれば表示 -->
              <?php if (has_tag()): ?>
                <div class="post-column__tags">
                  <?php the_tags('<div class="post-tag-list"><span class="post-tag-icon"><i class="fas fa-tags"></i></span>', '', '</div>'); ?>
                </div>
              <?php endif; ?>
            </article>
          <?php endwhile; ?>
          <?php wp_reset_postdata(); ?>
        </section>
        
        <!-- 関連記事 -->
        <?php get_template_part('tmp/related-entries'); ?>
        
        <!-- 前後の記事へのナビゲーション -->
        <?php get_template_part('tmp/pager-post-navi'); ?>
      </div>
    </main>
    
    <!-- サイドバー -->
    <aside id="sidebar" class="column-sidebar">
      <div class="sidebar-inner">
        <!-- 人気記事 -->
        <div class="sidebar-widget sidebar-popular">
          <h3 class="sidebar-widget-title">人気記事</h3>
          <div class="sidebar-widget-content">
            <?php
            // Popular Posts Widget
            if (function_exists('wpp_get_mostpopular')) {
              wpp_get_mostpopular(array(
                'limit' => 5,
                'thumbnail_width' => 100,
                'thumbnail_height' => 100,
                'stats_views' => 0,
                'post_html' => '<div class="popular-post">
                                <div class="popular-post-thumbnail">{thumb}</div>
                                <div class="popular-post-content">
                                  <h4 class="popular-post-title">{title}</h4>
                                  <div class="popular-post-date">{date}</div>
                                </div>
                              </div>'
              ));
            } else {
              // Fallback if WordPress Popular Posts is not active
              $popular_args = array(
                'posts_per_page' => 5,
                'meta_key' => 'post_views_count',
                'orderby' => 'meta_value_num',
                'order' => 'DESC'
              );
              $popular_query = new WP_Query($popular_args);
              
              if ($popular_query->have_posts()) {
                while ($popular_query->have_posts()) {
                  $popular_query->the_post();
                  ?>
                  <div class="popular-post">
                    <div class="popular-post-thumbnail">
                      <?php if (has_post_thumbnail()): ?>
                        <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('thumbnail'); ?></a>
                      <?php else: ?>
                        <a href="<?php the_permalink(); ?>"><img src="<?php echo get_no_image_url(); ?>" alt="NO IMAGE"></a>
                      <?php endif; ?>
                    </div>
                    <div class="popular-post-content">
                      <h4 class="popular-post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                      <div class="popular-post-date"><?php the_time(get_option('date_format')); ?></div>
                    </div>
                  </div>
                  <?php
                }
              } else {
                echo '<p>人気記事はまだありません。</p>';
              }
              wp_reset_postdata();
            }
            ?>
          </div>
        </div>
        
        <!-- カテゴリー -->
        <div class="sidebar-widget sidebar-categories">
          <h3 class="sidebar-widget-title">カテゴリー</h3>
          <div class="sidebar-widget-content">
            <ul class="column-categories-list">
              <?php
              $categories = get_categories(array(
                'orderby' => 'name',
                'order' => 'ASC'
              ));
              
              foreach ($categories as $category) {
                printf(
                  '<li class="column-category-item"><a href="%1$s" class="column-category-link"><span class="column-category-name">%2$s</span><span class="column-category-count">(%3$s)</span></a></li>',
                  esc_url(get_category_link($category->term_id)),
                  esc_html($category->name),
                  esc_html($category->count)
                );
              }
              ?>
            </ul>
          </div>
        </div>
        
        <!-- 新着記事 -->
        <div class="sidebar-widget sidebar-recent">
          <h3 class="sidebar-widget-title">新着記事</h3>
          <div class="sidebar-widget-content">
            <?php
            $recent_args = array(
              'posts_per_page' => 5,
              'orderby' => 'date',
              'order' => 'DESC'
            );
            $recent_query = new WP_Query($recent_args);
            
            if ($recent_query->have_posts()) {
              while ($recent_query->have_posts()) {
                $recent_query->the_post();
                ?>
                <div class="recent-post">
                  <div class="recent-post-thumbnail">
                    <?php if (has_post_thumbnail()): ?>
                      <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('thumbnail'); ?></a>
                    <?php else: ?>
                      <a href="<?php the_permalink(); ?>"><img src="<?php echo get_no_image_url(); ?>" alt="NO IMAGE"></a>
                    <?php endif; ?>
                  </div>
                  <div class="recent-post-content">
                    <h4 class="recent-post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                    <div class="recent-post-date"><?php the_time(get_option('date_format')); ?></div>
                  </div>
                </div>
                <?php
              }
            } else {
              echo '<p>新着記事はまだありません。</p>';
            }
            wp_reset_postdata();
            ?>
          </div>
        </div>
      </div>
    </aside>
  </div>
</div>

<?php get_footer(); ?>