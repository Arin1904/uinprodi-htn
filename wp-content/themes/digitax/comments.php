<?php if (post_password_required()) return; ?>
        
    <div class="content_comments">
        <div id="comments" class="comments">

            <?php if(have_comments()){ ?>
                <div>
                    <h4 class="number-comments"> 
                        <?php comments_number( esc_html__('0 Comments', 'digitax'), esc_html__( '1 Comment', 'digitax' ), esc_html__( '% Comments', 'digitax' ) ); ?>
                    </h4>
                </div>

            <?php } ?>

            <?php if (have_comments()) { ?>
                <ul class="commentlists">
                    <?php wp_list_comments('callback=digitax_theme_comment'); ?>
                </ul>
                <?php
                // Are there comments to navigate through?

                if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
                    <footer class="navigation comment-navigation" role="navigation">
                        <div class="nav_comment_text"><?php esc_html_e( 'Comment navigation', 'digitax' ); ?></div>
                        <div class="previous"><?php previous_comments_link(__('&larr; Older Comments', 'digitax')); ?></div>
                        <div class="next right"><?php next_comments_link(__('Newer Comments &rarr;', 'digitax')); ?></div>
                    </footer><!-- .comment-navigation -->
                <?php endif; // Check for comment navigation ?>

                <?php if (!comments_open() && get_comments_number()) : ?>
                    <p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'digitax' ); ?></p>
                <?php endif; ?>
                
            <?php } ?>

            <?php

            $aria_req = ($req ? " aria-required='true'" : '');
            $comment_args = array(
                'title_reply' => wp_kses('<span class="title-comment">' . esc_html__( 'Leave a Comment', 'digitax' ) . '</span><span class="sub-title-comment">' . esc_html__( 'Your email address will not be published', 'digitax' ) . '</span>', true),
                'fields' => apply_filters('comment_form_default_fields', array(
                    'author' => '<input type="text" name="author" value="' . esc_attr($commenter['comment_author']) . '"  class="form-control" placeholder="'. esc_attr__('Your Name','digitax') .'" />',
                    'email' => '<input type="text" name="email" value="' . esc_attr($commenter['comment_author_email']) . '" class="form-control" placeholder="'. esc_attr__('Your Email','digitax') .'" />',
                    'phone' => '<input type="text" name="url" value="' . esc_url($commenter['comment_author_url']) . '" class="form-control" placeholder="'. esc_attr__('Your Website','digitax') .'" />',            
                    
                )),
                'comment_field' => '<textarea class="form-control" rows="7" name="comment" placeholder="'. esc_attr__('Comment','digitax') .'"></textarea>',
                'label_submit' => esc_html__('Submit Now','digitax'),
                'comment_notes_before' => '',
                'comment_notes_after' => '',
            );
            ?>

            <?php global $post; ?>
            <?php if ('open' == $post->comment_status) { ?>
                <div class="wrap_comment_form">
                        <div class="row">
                            <div class="col-md-12">
                                <?php comment_form($comment_args); ?>        
                            </div>
                        </div>
                        
                    
                </div><!-- end commentform -->
            <?php } ?>


        </div><!-- end comments -->
    </div>