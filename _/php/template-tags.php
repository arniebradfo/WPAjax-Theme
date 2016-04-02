<?php
/**
 *
 * custom html outputs
 * 
 * @package WordPress
 * @subpackage HTML5-Reset-Plus-PJAX
 * @since HTML5 Reset + PJAX 0.1
 */

// HTML formatted single comment
function single_comment( $comment_ID ){
	$commentdata = get_comment( $comment_ID, ARRAY_A );
	?>
	<li id="comment-<?php echo $commentdata['comment_ID']; ?>" class="<?php echo join( ' ',get_comment_class( '', $commentdata['comment_ID'], $commentdata['comment_post_ID'] )) ; ?>">
		<article id="div-comment-<?php echo $commentdata['comment_ID']; ?>" class="comment-body">

			<footer class="comment-meta">
				<div class="comment-author vcard">
					<?php echo get_avatar( $commentdata['comment_author_email'], 32 ); ?>
					<b class="fn"><?php echo $commentdata['comment_author']; ?></b> 
					<span class="says">says:</span>					
				</div><!-- .comment-author -->
				<div class="comment-metadata">
					<a href="<?php echo get_comment_link( $commentdata['comment_ID'] ); ?>">
						<time datetime="<?php echo $commentdata['comment_date_gmt']; ?>+00:00">
							<?php echo get_comment_date( 'F j, Y \a\t g:i a', $commentdata['comment_ID']); ?>
						</time>
					</a>
					<?php if ( is_user_logged_in() ): ?>
					<span class="edit-link">
						<a class="comment-edit-link" href="<?php echo home_url();?>/wp-admin/comment.php?action=editcomment&#038;c=<?php echo $commentdata['comment_content']; ?>">
							Edit
						</a>
					</span>
					<?php endif; ?>
				</div><!-- .comment-metadata -->
			</footer><!-- .comment-meta -->

			<div class="comment-content">
				<p>
					<?php echo $commentdata['comment_content']; ?>
				</p>
			</div><!-- .comment-content -->

			<div class="reply">
				<?php // echo get_comment_reply_link(array(), $commentdata['comment_ID'], $commentdata['comment_post_ID'] ); // I don't know why this doesn't work?>
				<a rel="nofollow" 
				   class="comment-reply-link" 
				   href="<?php echo get_permalink( $commentdata['comment_post_ID'] ); ?>?replytocom=<?php echo $commentdata['comment_ID']; ?>#respond" 
				   onclick="return addComment.moveForm( 'div-comment-<?php echo $commentdata['comment_ID']; ?>', '<?php echo $commentdata['comment_ID']; ?>', 'respond', '12' )"  
				   aria-label="Reply to <?php echo $commentdata['comment_author']; ?>"
				   >
					Reply
				</a>
			</div>

		</article><!-- .comment-body -->
	</li><!-- #comment-## -->
	<?php
	return;
}

// Posted On 
function posted_on() {
	?>
	<span class="sep">
		Posted 
	</span>
	<a href="<?php echo esc_url( get_permalink() ); ?>" title="<?php echo esc_attr( get_the_time() ); ?>" rel="bookmark">
		<time class="entry-date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>" pubdate>
			<?php echo esc_html( get_the_date() ); ?>
		</time>
	</a> 
	by 
	<span class="byline author vcard">
		<?php // echo get_avatar( get_the_author_meta('ID'), 32 ); ?>
		<?php echo esc_attr( get_the_author() ); ?>
	</span>
	<?php
}

// add option for fallback site author
function author_meta_tag( $nameType='display_name' ){
	global $post;
	$authorID=$post->post_author;
	$theAuthor = '';
	if( (is_single() || is_page()) && $authorID != null ){
		$theAuthor = get_the_author_meta( $nameType , $authorID );
	} else if ( get_theme_mod('site_author') ) {
		$theAuthor = get_theme_mod('site_author');
	} else { 
		return null; 
	}
	?>
		<meta name="author" content="<?php echo esc_attr($theAuthor); ?>"/>
	<?php
}

// exports the posts page pagination	
function ajax_post_pagination( $prev_text='&laquo; Previous' , $next_text='Next &raquo;' ){
	?>
	<nav class="nav post-navigation">
		<div class="next-posts">
			<?php 
				if (get_next_posts_link()){
					next_posts_link($prev_text);
				} else {
					echo '<a>'.$prev_text.'</a>';
				}
			?>
		</div>
		<?php 
			$pagination_links = paginate_links( array( 'prev_next'=>false, 'type'=>'list' )); 
			// repace the current link's <span> with an <a> 
			$pagination_links = str_replace('<span',  '<a',  $pagination_links);
			$pagination_links = str_replace('</span', '</a', $pagination_links);
			// TODO: add an href to this somehow
			echo $pagination_links;
		?>
		<div class="prev-posts">
			<?php 
				if (get_previous_posts_link()){
					previous_posts_link($next_text);
				} else {
					echo '<a>'.$next_text.'</a>';
				}
			?>
		</div>
	</nav>
	<?php
}

function ajax_comment_navigation( $prev_text='&laquo; Older Comments', $next_text='Newer Comments &raquo;' ){
	?>
	<nav class="nav comment-navigation">
		<div class="next-comments">
			<?php
				if(get_previous_comments_link()){
					previous_comments_link($prev_text);
				} else {
					echo '<a>'.$prev_text.'</a>';
				}
			?>
		</div>
		<div class="prev-comments">
			<?php
				if(get_next_comments_link()){
					next_comments_link($next_text);
				} else {
					echo '<a>'.$next_text.'</a>';
				}
			?>
		</div>
	</nav>
	<?php
}

?>