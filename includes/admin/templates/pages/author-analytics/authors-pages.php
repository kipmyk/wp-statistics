<?php 
use WP_STATISTICS\Menus;
$order = !empty($_GET['order']) ? ($order === 'DESC' ? 'ASC' : 'DESC') : 'DESC';
?>

<div class="postbox-container wps-postbox-full">
    <div class="metabox-holder">
        <div class="meta-box-sortables">
            <div class="postbox">
                <div class="inside">
                    <?php if (!empty($data['authors'])) : ?>
                        <div class="o-table-wrapper">
                            <table width="100%" class="o-table wps-authors-table">
                                <thead>
                                    <tr>
                                        <th class="wps-pd-l">
                                            <a href="<?php echo esc_url(add_query_arg(['order_by' => 'name', 'order' => $order])) ?>" class="sort"><?php esc_html_e('Author', 'wp-statistics') ?></a>
                                        </th>
                                        <th class="wps-pd-l">
                                            <a href="<?php echo esc_url(add_query_arg(['order_by' => 'total_posts', 'order' => $order])) ?>" class="sort">
                                                <?php esc_html_e('Published Posts', 'wp-statistics') ?>
                                            </a>
                                        </th>
                                        <th class="wps-pd-l">
                                            <a href="<?php echo esc_url(add_query_arg(['order_by' => 'total_views', 'order' => $order])) ?>" class="sort">
                                                <?php esc_html_e('Author\'s Page Views', 'wp-statistics') ?>
                                                <span class="wps-tooltip" title="<?php esc_attr_e('Published Posts tooltip', 'wp-statistics') ?>"><i class="wps-tooltip-icon info"></i></span>
                                            </a>
                                        </th>
                                        <th></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php foreach ($data['authors'] as $author) : ?>
                                        <tr>
                                            <td class="wps-pd-l">
                                                <div class="wps-author-name">
                                                    <img src="<?php echo esc_url(get_avatar_url($author->id)); ?>" alt="<?php echo esc_attr($author->name) ?>"/>
                                                    <span title="<?php echo esc_attr($author->name) ?>"><?php echo esc_html($author->name) ?></span>
                                                </div>
                                            </td>
                                            <td class="wps-pd-l"><?php echo esc_html($author->total_posts) ?></td>
                                            <td class="wps-pd-l"><?php echo $author->total_views ? esc_html($author->total_views) : 0 ?></td>
                                            <td class="view-more">
                                                <!-- add class disabled-->
                                                <a class="disabled" href="<?php echo esc_url(Menus::admin_url('author-analytics', ['type' => 'single-author', 'author_id' => $author->id])) ?>" title="<?php esc_html_e('View Details', 'wp-statistics') ?>">
                                                    <?php esc_html_e('View Details', 'wp-statistics') ?>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else : ?>
                        <div class="o-wrap o-wrap--no-data wps-center">
                            <?php esc_html_e('No recent data available.', 'wp-statistics')   ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>