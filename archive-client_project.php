<?php
/**
 * Template for displaying client projects archive
 * Place this file in your active theme directory to customize archive display
 * File name: archive-client_project.php
 */

get_header(); ?>

<div class="container">
    <div class="row">
        <div class="col-12">
            <header class="page-header">
                <h1 class="page-title"><?php _e('Client Projects', 'client-projects-manager'); ?></h1>
                <p class="page-description"><?php _e('Browse our portfolio of client projects and see what we\'ve accomplished.', 'client-projects-manager'); ?></p>
            </header>
            
            <!-- Project Filters -->
            <div class="project-filters">
                <div class="filter-buttons">
                    <button class="filter-btn active" data-filter="all"><?php _e('All Projects', 'client-projects-manager'); ?></button>
                    <button class="filter-btn" data-filter="planning"><?php _e('Planning', 'client-projects-manager'); ?></button>
                    <button class="filter-btn" data-filter="in_progress"><?php _e('In Progress', 'client-projects-manager'); ?></button>
                    <button class="filter-btn" data-filter="review"><?php _e('Under Review', 'client-projects-manager'); ?></button>
                    <button class="filter-btn" data-filter="completed"><?php _e('Completed', 'client-projects-manager'); ?></button>
                    <button class="filter-btn" data-filter="on_hold"><?php _e('On Hold', 'client-projects-manager'); ?></button>
                </div>
            </div>
            
            <?php if (have_posts()) : ?>
                <div class="projects-archive-grid">
                    <?php while (have_posts()) : the_post(); ?>
                        <?php
                        $client_name = get_post_meta(get_the_ID(), '_client_name', true);
                        $project_status = get_post_meta(get_the_ID(), '_project_status', true);
                        $project_deadline = get_post_meta(get_the_ID(), '_project_deadline', true);
                        
                        // Status display names
                        $status_names = array(
                            'planning' => __('Planning', 'client-projects-manager'),
                            'in_progress' => __('In Progress', 'client-projects-manager'),
                            'review' => __('Under Review', 'client-projects-manager'),
                            'completed' => __('Completed', 'client-projects-manager'),
                            'on_hold' => __('On Hold', 'client-projects-manager')
                        );
                        
                        $status_display = isset($status_names[$project_status]) ? $status_names[$project_status] : ucfirst($project_status);
                        ?>
                        
                        <article class="project-archive-card status-<?php echo esc_attr($project_status); ?>" data-status="<?php echo esc_attr($project_status); ?>">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="project-thumbnail">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('medium', array('class' => 'img-responsive')); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <div class="project-card-content">
                                <h3 class="project-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>
                                
                                <div class="project-meta-archive">
                                    <?php if ($client_name) : ?>
                                        <div class="project-client">
                                            <strong><?php _e('Client:', 'client-projects-manager'); ?></strong> 
                                            <?php echo esc_html($client_name); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($project_status) : ?>
                                        <div class="project-status">
                                            <span class="status-badge status-<?php echo esc_attr($project_status); ?>">
                                                <?php echo esc_html($status_display); ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($project_deadline) : ?>
                                        <div class="project-deadline">
                                            <strong><?php _e('Deadline:', 'client-projects-manager'); ?></strong> 
                                            <?php echo esc_html(date('M j, Y', strtotime($project_deadline))); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if (has_excerpt()) : ?>
                                    <div class="project-excerpt">
                                        <?php the_excerpt(); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="project-card-footer">
                                    <a href="<?php the_permalink(); ?>" class="project-link">
                                        <?php _e('View Project Details', 'client-projects-manager'); ?>
                                    </a>
                                    <span class="project-date"><?php echo get_the_date('M j, Y'); ?></span>
                                </div>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>
                
                <!-- Pagination -->
                <div class="projects-pagination">
                    <?php
                    the_posts_pagination(array(
                        'mid_size' => 2,
                        'prev_text' => __('← Previous', 'client-projects-manager'),
                        'next_text' => __('Next →', 'client-projects-manager'),
                    ));
                    ?>
                </div>
                
            <?php else : ?>
                <div class="no-projects-message">
                    <h2><?php _e('No Projects Found', 'client-projects-manager'); ?></h2>
                    <p><?php _e('There are currently no client projects to display. Check back later for updates.', 'client-projects-manager'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.page-header {
    text-align: center;
    margin-bottom: 40px;
    padding-bottom: 20px;
    border-bottom: 1px solid #eee;
}

.page-title {
    margin-bottom: 10px;
    color: #333;
}

.page-description {
    color: #666;
    font-size: 1.1em;
    margin: 0;
}

.project-filters {
    margin-bottom: 40px;
    text-align: center;
}

.filter-buttons {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 10px;
}

.filter-btn {
    padding: 8px 16px;
    border: 2px solid #ddd;
    background: white;
    color: #666;
    border-radius: 20px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.9em;
    font-weight: 500;
}

.filter-btn:hover,
.filter-btn.active {
    border-color: #0073aa;
    background: #0073aa;
    color: white;
}

.projects-archive-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 30px;
    margin-bottom: 50px;
}

.project-archive-card {
    background: white;
    border: 1px solid #e1e1e1;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}

.project-archive-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.project-thumbnail {
    position: relative;
    overflow: hidden;
}

.project-thumbnail img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.project-archive-card:hover .project-thumbnail img {
    transform: scale(1.05);
}

.project-card-content {
    padding: 25px;
}

.project-title {
    margin: 0 0 15px 0;
    font-size: 1.3em;
    line-height: 1.3;
}

.project-title a {
    text-decoration: none;
    color: #333;
    transition: color 0.3s ease;
}

.project-title a:hover {
    color: #0073aa;
}

.project-meta-archive {
    margin-bottom: 15px;
}

.project-meta-archive > div {
    margin-bottom: 8px;
    font-size: 0.9em;
    color: #666;
}

.project-meta-archive strong {
    color: #333;
}

.project-excerpt {
    margin: 15px 0;
    color: #555;
    line-height: 1.6;
}

.project-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 20px;
    padding-top: 15px;
    border-top: 1px solid #f0f0f0;
}

.project-link {
    display: inline-block;
    padding: 8px 16px;
    background: #0073aa;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    font-size: 0.9em;
    font-weight: 500;
    transition: background 0.3s ease;
}

.project-link:hover {
    background: #005a87;
    color: white;
}

.project-date {
    font-size: 0.85em;
    color: #999;
}

.projects-pagination {
    text-align: center;
    margin-top: 50px;
}

.no-projects-message {
    text-align: center;
    padding: 60px 20px;
    background: #f8f9fa;
    border-radius: 10px;
}

.no-projects-message h2 {
    color: #666;
    margin-bottom: 15px;
}

.no-projects-message p {
    color: #888;
    font-size: 1.1em;
}

/* Status-based styling */
.project-archive-card.status-planning {
    border-left: 4px solid #1976d2;
}

.project-archive-card.status-in_progress {
    border-left: 4px solid #f57c00;
}

.project-archive-card.status-review {
    border-left: 4px solid #7b1fa2;
}

.project-archive-card.status-completed {
    border-left: 4px solid #388e3c;
}

.project-archive-card.status-on_hold {
    border-left: 4px solid #d32f2f;
}

/* Hidden state for filtered items */
.project-archive-card.hidden {
    display: none;
}

/* Responsive design */
@media (max-width: 768px) {
    .projects-archive-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .project-card-content {
        padding: 20px;
    }
    
    .filter-buttons {
        flex-direction: column;
        align-items: center;
    }
    
    .project-card-footer {
        flex-direction: column;
        gap: 10px;
        align-items: stretch;
    }
    
    .project-link {
        text-align: center;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const projectCards = document.querySelectorAll('.project-archive-card');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            
            // Update active button
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Filter projects
            projectCards.forEach(card => {
                if (filter === 'all') {
                    card.classList.remove('hidden');
                } else {
                    const cardStatus = card.getAttribute('data-status');
                    if (cardStatus === filter) {
                        card.classList.remove('hidden');
                    } else {
                        card.classList.add('hidden');
                    }
                }
            });
        });
    });
});
</script>

<?php get_footer(); ?>
