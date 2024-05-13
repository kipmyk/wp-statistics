<?php use WP_STATISTICS\Admin_Template; ?>

<div class="metabox-holder" id="authors-performance">
    <div class="postbox-container" id="wps-postbox-container-1">
        <div class="wps-card">
            <div class="wps-author-info">
                <div>
                    <?php $user = wp_get_current_user();
                    if ($user) : ?>
                        <img src="<?php echo esc_url(get_avatar_url($user->ID)); ?>" alt="username"/>
                    <?php endif ?>
                    <a href="" title="Author Name" class="wps-author-info__name">Author Name</a>
                    <p class="wps-author-info__desc">Author for more than 10 years</p>
                </div>
                <ul class="wps-author-info__socials">
                    <li>
                        <a href="mailto:mail.com" title=""><i class="mail"></i></a>
                    </li>
                    <li>
                        <a href="tel:123456" title=""><i class="phone"></i></a>
                    </li>
                    <li>
                        <a href="" title=""><i class="blog"></i></a>
                    </li>
                </ul>
            </div>

        </div>
        <?php
            $items = [
                [
                    'title_text'   => esc_html__('Published Posts', 'wp-statistics'),
                    'tooltip_text' => esc_html__('Published Posts tooltip', 'wp-statistics'),
                    'icon_class'   => 'authors',
                    'total'        => '2.5K',
                    'avg'          => '1.1K',
                    'avg_title'    => esc_html__('Avg. Per Post', 'wp-statistics')
                ],
                [
                    'title_text'   => esc_html__('Views', 'wp-statistics'),
                    'tooltip_text' => esc_html__('Views tooltip', 'wp-statistics'),
                    'icon_class'   => 'views',
                    'total'        => '12.3K',
                    'avg'          => '1.1K',
                    'avg_title'    => esc_html__('Avg. Per Post', 'wp-statistics')
                ],
                [
                    'title_text'   => esc_html__('Visitors', 'wp-statistics'),
                    'tooltip_text' => esc_html__('Visitors tooltip', 'wp-statistics'),
                    'icon_class'   => 'visitors',
                    'total'        => '8.2K',
                    'avg'          => '850',
                    'avg_title'    => esc_html__('Avg. Per Post', 'wp-statistics')
                ],
                [
                    'title_text'   => esc_html__('Words', 'wp-statistics'),
                    'tooltip_text' => esc_html__('Words tooltip', 'wp-statistics'),
                    'icon_class'   => 'words',
                    'total'        => '61K',
                    'avg'          => '300',
                    'avg_title'    => esc_html__('Avg. Per Post', 'wp-statistics')
                ],
                [
                    'title_text'   => esc_html__('Comments', 'wp-statistics'),
                    'tooltip_text' => esc_html__('Comments tooltip', 'wp-statistics'),
                    'icon_class'   => 'comments',
                    'total'        => '61K',
                    'avg'          => '300',
                    'avg_title'    => esc_html__('Avg. Per Post', 'wp-statistics')
                ],
            ];

            foreach ($items as $args) {
                Admin_Template::get_template(array('layout/author-analytics/performance-summary'), $args);
            }

            $topCategories = [
                'title_text'   => esc_html__('Top Categories', 'wp-statistics'),
                'tooltip_text' => esc_html__('Top Categories tooltip', 'wp-statistics'),
            ];
            Admin_Template::get_template(['layout/author-analytics/top-categories'], $topCategories);

            $operating_system = [
                'title_text'   => esc_html__('Operating Systems', 'wp-statistics'),
                'tooltip_text' => esc_html__('Operating Systems tooltip', 'wp-statistics'),
            ];
            Admin_Template::get_template(['layout/author-analytics/operating-systems'], $operating_system);

            $browsers = [
                'title_text'   => esc_html__('Browsers', 'wp-statistics'),
                'tooltip_text' => esc_html__('Browsers tooltip', 'wp-statistics'),
            ];
            Admin_Template::get_template(['layout/author-analytics/browsers'], $browsers);
        ?>
    </div>
    <div class="postbox-container" id="wps-postbox-container-2">
        <?php
            $overview_args = [
                'title_text'        => esc_html__('Publishing Overview', 'wp-statistics'),
                'tooltip_text'      => esc_html__('Publishing Overview tooltip', 'wp-statistics'),
                'title_description' => esc_html__('Last 12 Months', 'wp-statistics'),
            ];
            Admin_Template::get_template(['layout/author-analytics/publishing-overview'], $overview_args);

            $top_posts = [
                'title_text'   => esc_html__('Top Posts', 'wp-statistics'),
                'tooltip_text' => esc_html__('Top Posts tooltip', 'wp-statistics'),
            ];
            Admin_Template::get_template(['layout/author-analytics/top-posts'], $top_posts);

            $author_summary = [
                'title_text'   => esc_html__('Summary', 'wp-statistics'),
                'tooltip_text' => esc_html__('Summary tooltip', 'wp-statistics'),
            ];
            Admin_Template::get_template(['layout/author-analytics/author-summary'], $author_summary);

            $top_countries = [
                'title_text'   => esc_html__('Top Countries', 'wp-statistics'),
                'tooltip_text' => esc_html__('Top Countries tooltip', 'wp-statistics'),
            ];
            Admin_Template::get_template(['layout/author-analytics/top-countries'], $top_countries);
        ?>
    </div>
</div>
