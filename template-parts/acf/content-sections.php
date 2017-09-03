<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Brett_Wysocki
 * @since 1.0
 * @version 1.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		<?php //twentyseventeen_edit_link( get_the_ID() ); ?>
	</header><!-- .entry-header -->
	<div class="entry-content">
		
		<?php if ( get_field('sections') ) {
			
			$sections = array();

			while ( have_rows('sections') ) { the_row();

				$section = '';

				$row_layout = str_replace( '_', '-', get_row_layout() );

				$section_classes = array( 'section', $row_layout );

				if ( $row_layout == 'content_section' ) {

					$section_classes[] = get_sub_field('content_layout');

				}

				if ( !empty( $section ) ) {
					$sections[] = 	'<div class="' . implode( ' ', $section_classes ) . '">' .
										'<div class="section-inner inner">' .
											$section .
										'</div>' .
									'</div>';
				}

			}

		} else { ?>
			<h1>THIS PAGE DOESN'T HAVE CONTENT YET</h1>
		<?php } ?>

	</div><!-- .entry-content -->
</article><!-- #post-## -->
