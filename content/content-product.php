<?php
/**
 * The template used for displaying GBS product content
 *
 * @package gb_t
 * @since gb_t 1.0
 */
 

$attachment = get_attachments_pdfs();
 
$purchasers = gb_get_deal_purchasers( get_the_ID(), TRUE ); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry_header">
		<h1 class="entry_title">
			<?php the_title(); ?>
			<?php if ( gb_has_merchant() ) : ?>
			<span class="byline font_xx_small"><?php gb_e('by') ?> <a href="<?php gb_merchant_url( gb_get_merchant_id() ) ?>" title="<?php gb_merchant_name( gb_get_merchant_id() ) ?>"><?php gb_merchant_name( gb_get_merchant_id() ); ?></a></span><!--  .byline -->
			<?php endif; ?>
		</h1>
	</header><!-- .entry_header -->

	<div class="entry_content">

		<div id="project_context" class="pull_right muted clearfix">

			<div class="context_wrap project_category font_small">
				<span class="glyph">'</span>
				<?php
					$categories = gb_get_deal_categories();
					$cat = $categories[0];
					if ( !empty( $categories ) ) {
						echo get_post_type_category_parents( $cat, 'projects', TRUE, ' ' );
					} ?>
			</div>

			<?php
				$locations = gb_get_deal_locations() ?>

			<?php if ( !empty( $locations ) ): ?>
			<div class="context_wrap project_local font_small">
				<span class="glyph">7</span>
				<?php
				$links = array();
				foreach ( $locations as $location ) {
					$links[] = '<a href="'.get_term_link( $location->slug, gb_get_location_tax_slug() ).'">'.$location->name.'</a>';
				}
				echo implode( ', ', $links ); ?>
			</div><!--  .project_local -->
			<?php endif ?>

		</div><!-- #project_context.pull_right -->

		<ul id="project_tabs" class="nav nav-tabs">

			<li class="info_tab"><a href="#project_lead" data-toggle="tab"><span class="glyph">2</span> <?php gb_e('Info') ?></a></li>
			<li class="details_tab"><a href="#project_details" data-toggle="tab"><span class="glyph">?</span> <?php gb_e('Details') ?></a></li>
			<li class="purchasers_tab"><a href="#project_sponsors" data-toggle="tab"><span class="glyph">,</span> <?php gb_e('Backers') ?> <span class="purchase_count badge"><?php echo count( $purchasers ); ?></span></a></li>
			<li class="updates_tab"><a href="#deal_blog" data-toggle="tab"><span class="glyph">6</span> <?php gb_e('Updates') ?> <span class="blog_count badge"><?php gb_project_update_count(); ?></span></a></li>
			<?php if ( comments_open() ): ?>
				<li class="comments_tab"><a href="#project_comments" data-toggle="tab"><span class="glyph">"</span> <?php gb_e('Community') ?> <span class="comment_count badge"><?php echo get_comments_number() ?></span></a></li>
			<?php endif ?>
			<li class="purchase_tab"><a href="#pledge" data-toggle="tab"><span class="glyph">$</span><?php gb_e('Pledge') ?></a></li>

		</ul>


		<div class="tab-content content">

			<div id="project_lead" class="tab-pane section clearfix">

				<div class="featured_content lead_section clearfix">
					<?php if ( gb_has_featured_content() ) :?>
						<div class="featured_content_wrap">
							<?php gb_featured_content(); ?>
						</div>
					<?php elseif ( has_post_thumbnail() ) : ?>
						<div class="featured_content_wrap">
							<?php the_post_thumbnail( 'gbs_lead', array( 'title' => get_the_title() ) ); ?>
						</div>
					<?php else: ?>
						<div class="no_featured_image" style="background: url(<?php gb_header_logo(); ?>) no-repeat 50px center;"></div>
					<?php endif; ?>
				</div><!-- #deal_img -->

				<div id="share_section" class="section_content border_bottom clearfix">
					<?php get_template_part( 'section/social-share' ) ?>
				</div>

				<div id="the_content" class="entry_content section_content clearfix">
					<div id="project_meta_info"> 
						<?php 
							$template = get_template_path('content/custom/project-meta', 'single');
							include $template;
						?>
					 </div>
					 					 
					
					
					<div id="project_description">
					 	<h2>About Pitch</h2>					 
						<?php the_content(); ?>
					</div>
					
					<div>
						<h2>Documents</h2>
						<?php echo $attachment; ?>
					</div>
					
				</div><!-- .entry_content -->

			</div>

			<div id="project_details" class="tab-pane section clearfix">
							
				<div class="section_content clearfix">
					<h3 class="gb_ff"><?php gb_e('Highlights:') ?></h3>
					<?php echo apply_filters( 'the_content', gb_get_highlights() ) ?>

					<h3 class="gb_ff"><?php gb_e('FAQs:') ?></h3>
					<?php echo apply_filters( 'the_content', gb_get_fine_print() ) ?>
				</div>
			</div>

			<?php
				$template = get_template_path( 'section/project/sponsors', 'single' );
				include( $template ); ?>

			<?php
				$template = get_template_path( 'section/project/updates', 'single' );
				include( $template ); ?>


			<?php if ( comments_open() ): ?>
			<div id="project_comments" class="tab-pane section clearfix">
				<div class="section_content">
					<?php comments_template( '', true ); ?>
				</div>
			</div>
			<?php endif ?>

			<div id="pledge" class="tab-pane section clearfix">
				<div class="section_content clearfix">

					<div class="manual_pledge clearfix">
						<h3 class="pledge_section_title"><?php gb_e('Enter your own custom pledge amount') ?></h3>
						<div class="pledge_section well well_invert clearfix">
							<h4><?php printf( gb__('Custom pledge amounts are in increments of %s and do not include rewards.'), gb_get_formatted_money( gb_get_price(), FALSE ) ) ?></h4>
							<form class="add-to-cart" method="post" action="<?php gb_add_to_cart_action(); ?>">
								<div class="control_group clearfix">
									<div class="input_prepend input_append">
										<span class="add-on"><?php gb_formatted_money( gb_get_price(), FALSE ) ?> <?php gb_e('x') ?></span>
										<input type="hidden" name="add_to_cart" value="<?php the_ID() ?>" />
										<input class="span1" type="number" name="qty" min="1" <?php if ( gb_max_purchases_per_user() ) echo 'max="'.gb_get_max_purchases_per_user().'"' ?> value="1" id="pledge_qty">
										<?php
											$symbol = gb_get_currency_symbol( FALSE );
											if ( strstr( $symbol, '%' ) ) {
												$aftersymbol = str_replace( '%', '', $symbol );
												$symbol = '';
											}
										?>
										<button class="btn btn_primary" type="submit"><?php gb_e('Pledge') ?> <span class="pledge_calculation_wrap"><?php echo $symbol ?><span id="pledge_calculation"><?php echo gb_get_price() ?></span><?php echo $aftersymbol ?></span></button>
									</div><!--  .input_append -->
								</div><!--  .control_group -->
							</form>
						</div><!--  .pledge_section well -->
					</div><!--  .manual_pledge -->

					<?php
						$template = get_template_path( 'section/project/pledges', 'single' );
						include( $template ); ?>
				</div>
			</div>

		</div><!--  .tab-content -->

	</div><!-- .entry_content -->

	<footer class="entry_meta">

		<?php edit_post_link( gb__( 'Edit' ), '<span class="edit-link">', '</span>' ); ?>

	</footer><!-- .entry_meta -->
</article><!-- #post-<?php the_ID(); ?> -->
