<?php
  /**
   * Copied from the Thenty_Thirteen default theme
   */
  
  /*
   * If the current post is protected by a password and the visitor has not yet
   * entered the password we will return early without loading the comments.
   */
  if ( post_password_required() )
    return;
?>

<div id="comments" class="comments-area">
  
  <?php if ( have_comments() ) : ?>
    <h2 class="comments-title">
      <?php
        printf( _nx( 'One thought on "%2$s"', '%1$s thoughts on "%2$s"', get_comments_number(), 'comments title', 'twentythirteen' ),
                number_format_i18n( get_comments_number() ), '<span>' . get_the_title() . '</span>' );
      ?>
    </h2>
    
    <ol class="comment-list">
      <?php
        wp_list_comments( array(
          'style'       => 'ol',
          'short_ping'  => true,
          'avatar_size' => 74,
        ) );
      ?>
    </ol><!-- .comment-list -->
    
    <?php
      // Are there comments to navigate through?
      if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
    ?>
      <nav class="navigation comment-navigation" role="navigation">
        <h1 class="screen-reader-text section-heading"><?php _e( 'Comment navigation', 'twentythirteen' ); ?></h1>
        <div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'twentythirteen' ) ); ?></div>
        <div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'twentythirteen' ) ); ?></div>
      </nav><!-- .comment-navigation -->
    <?php endif; // Check for comment navigation ?>
    
    <?php if ( ! comments_open() && get_comments_number() ) : ?>
      <p class="no-comments"><?php _e( 'Comments are closed.' , 'twentythirteen' ); ?></p>
    <?php endif; ?>
    
  <?php endif; // have_comments() ?>

  <?php if (is_user_logged_in()): ?>
    <?php if (get_user_meta(get_current_user_id(), "inhuman_user_complete", true)): ?>
      <?php comment_form(); ?>
    <? else: ?>
      <p>Looks like you're new around here!</p>
      <p>Set a username and optional email (for account recovery) to
        leave a comment:</p>
      <form id="user-setup">
        <div>
          <label for="name">Name:</label>
          <input type="text" name="name" value="<?php echo user()->display_name; ?>">
        </div>
        <div>
          <label for="email">Email:</label>
          <input type="text" name="email" placeholder="(optional)">
        </div>
        <input type="button" class="button button-primary" value="Submit">
      </form>
    <? endif; ?>
  <?php endif; ?>
  
</div><!-- #comments -->