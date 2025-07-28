<?php
/**
 * Template for displaying single client project
 * Place this file in your active theme directory to customize single project display
 * File name: single-client_project.php
 */

get_header(); ?>

<div class="container">
    <div class="row">
        <div class="col-md-8">
            <?php while (have_posts()) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('client-project-single'); ?>>
                    
                    <header class="project-header">
                        <h1 class="project-title"><?php the_title(); ?></h1>
                        
                        <div class="project-meta">
                            <?php
                            $client_name = get_post_meta(get_the_ID(), '_wcp_client_name', true);
                            $project_status = get_post_meta(get_the_ID(), '_wcp_project_status', true);
                            $project_deadline = get_post_meta(get_the_ID(), '_wcp_project_deadline', true);
                            
                            // Status display names
                            $status_names = array(
                                'not_started' => __('Not Started', 'workcity-client-project-manager'),
                                'in_progress' => __('In Progress', 'workcity-client-project-manager'),
                                'completed' => __('Completed', 'workcity-client-project-manager'),
                                'on_hold' => __('On Hold', 'workcity-client-project-manager')
                            );
                            
                            $status_display = isset($status_names[$project_status]) ? $status_names[$project_status] : ucfirst($project_status);
                            ?>
                            
                            <?php if ($client_name) : ?>
                                <div class="project-client">
                                    <strong><?php _e('Client:', 'workcity-client-project-manager'); ?></strong> 
                                    <?php echo esc_html($client_name); ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($project_status) : ?>
                                <div class="project-status">
                                    <strong><?php _e('Status:', 'workcity-client-project-manager'); ?></strong> 
                                    <span class="status-badge status-<?php echo esc_attr($project_status); ?>">
                                        <?php echo esc_html($status_display); ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($project_deadline) : ?>
                                <div class="project-deadline">
                                    <strong><?php _e('Deadline:', 'workcity-client-project-manager'); ?></strong> 
                                    <?php echo esc_html(date('F j, Y', strtotime($project_deadline))); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="project-date">
                                <strong><?php _e('Created:', 'workcity-client-project-manager'); ?></strong> 
                                <?php echo get_the_date(); ?>
                            </div>
                        </div>
                    </header>
                    
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="project-featured-image">
                            <?php the_post_thumbnail('large', array('class' => 'img-responsive')); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="project-content">
                        <?php the_content(); ?>
                    </div>
                    
                    <footer class="project-footer">
                        <div class="project-navigation">
                            <?php
                            $prev_post = get_previous_post();
                            $next_post = get_next_post();
                            
                            if ($prev_post) {
                                echo '<div class="prev-project">';
                                echo '<a href="' . get_permalink($prev_post->ID) . '">&larr; ' . __('Previous Project', 'workcity-client-project-manager') . '</a>';
                                echo '<br><small>' . get_the_title($prev_post->ID) . '</small>';
                                echo '</div>';
                            }
                            
                            if ($next_post) {
                                echo '<div class="next-project">';
                                echo '<a href="' . get_permalink($next_post->ID) . '">' . __('Next Project', 'workcity-client-project-manager') . ' &rarr;</a>';
                                echo '<br><small>' . get_the_title($next_post->ID) . '</small>';
                                echo '</div>';
                            }
                            ?>
                        </div>
                        
                        <div class="back-to-projects">
                            <a href="<?php echo get_post_type_archive_link('client_project'); ?>" class="btn btn-secondary">
                                <?php _e('← Back to All Projects', 'workcity-client-project-manager'); ?>
                            </a>
                        </div>
                    </footer>
                    
                </article>
            <?php endwhile; ?>
        </div>
        
        <div class="col-md-4">
            <aside class="project-sidebar">
                <div class="widget">
                    <h3><?php _e('Project Quick Info', 'workcity-client-project-manager'); ?></h3>
                    <ul class="project-quick-info">
                        <?php if ($client_name) : ?>
                            <li><strong><?php _e('Client:', 'workcity-client-project-manager'); ?></strong> <?php echo esc_html($client_name); ?></li>
                        <?php endif; ?>
                        
                        <?php if ($project_status) : ?>
                            <li><strong><?php _e('Status:', 'workcity-client-project-manager'); ?></strong> <?php echo esc_html($status_display); ?></li>
                        <?php endif; ?>
                        
                        <?php if ($project_deadline) : ?>
                            <li><strong><?php _e('Deadline:', 'workcity-client-project-manager'); ?></strong> <?php echo esc_html(date('F j, Y', strtotime($project_deadline))); ?></li>
                        <?php endif; ?>
                        
                        <li><strong><?php _e('Created:', 'workcity-client-project-manager'); ?></strong> <?php echo get_the_date(); ?></li>
                        <li><strong><?php _e('Last Updated:', 'workcity-client-project-manager'); ?></strong> <?php echo get_the_modified_date(); ?></li>
                    </ul>
                </div>
                
                <?php if (is_active_sidebar('sidebar-1')) : ?>
                    <?php dynamic_sidebar('sidebar-1'); ?>
                <?php endif; ?>
            </aside>
        </div>
    </div>
</div>

<style>
.client-project-single {
    margin-bottom: 40px;
}

.project-header {
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 1px solid #eee;
}

.project-title {
    margin-bottom: 20px;
    color: #333;
}

.project-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 15px;
}

.project-meta > div {
    background: #f8f9fa;
    padding: 8px 12px;
    border-radius: 4px;
    font-size: 0.9em;
}

.project-featured-image {
    margin-bottom: 30px;
}

.project-featured-image img {
    width: 100%;
    height: auto;
    border-radius: 8px;
}

.project-content {
    line-height: 1.6;
    margin-bottom: 40px;
}

.project-footer {
    border-top: 1px solid #eee;
    padding-top: 30px;
}

.project-navigation {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
}

.project-navigation a {
    text-decoration: none;
    color: #0073aa;
}

.project-navigation a:hover {
    text-decoration: underline;
}

.project-navigation small {
    color: #666;
    display: block;
    margin-top: 5px;
}

.back-to-projects {
    text-align: center;
}

.back-to-projects .btn {
    display: inline-block;
    padding: 10px 20px;
    background-color: #6c757d;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    transition: background-color 0.3s;
}

.back-to-projects .btn:hover {
    background-color: #5a6268;
    color: white;
}

.project-sidebar .widget {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 30px;
}

.project-sidebar .widget h3 {
    margin-top: 0;
    margin-bottom: 15px;
    color: #333;
}

.project-quick-info {
    list-style: none;
    padding: 0;
    margin: 0;
}

.project-quick-info li {
    padding: 8px 0;
    border-bottom: 1px solid #eee;
}

.project-quick-info li:last-child {
    border-bottom: none;
}

@media (max-width: 768px) {
    .project-meta {
        flex-direction: column;
        gap: 10px;
    }
    
    .project-navigation {
        flex-direction: column;
        gap: 15px;
    }
}
</style>

<?php get_footer(); ?>
