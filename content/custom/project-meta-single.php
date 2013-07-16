<?php 
	
	global $post;
	

	$deal = Group_Buying_Deal::get_instance($post->ID);
	$merchant = $deal->get_merchant();
	
	$name = $merchant->get_contact_name();
	if(!$name) $name = $merchant->get_post()->post_title;
	$uri = $merchant->get_url();
	
	
?>

<table class="outer_table">
	<tr>
	
		<td>
			<table class="inner_table">
				<tr>
					<td class="meta-title"> <div class="meta_title">Founder</div> </td>
					<td class="meta-description"> <div class="meta_description"> <a href="<?php echo $uri ?>"><?php echo $name; ?></a> </div> </td>
				</tr>
			</table>
		</td>
		
		<td>
			<table class="inner_table">
				<tr>
					<td class="meta-title"> <div class="meta_title">Location</div> </td>
					<td class="meta-description"> <div class="meta_description"> N/A </div> </td>
				</tr>
			</table>
		</td>
		
	</tr>
	
	<tr>
	
		<td>
			<table class="inner_table">
				<tr>
					<td class="meta-title">  <strong>Target</strong> </td>
					<td class="meta-description"> <?php echo $deal->get_max_purchases(); ?> </td>
				</tr>
			</table>
		</td>
		
		<td>
			<table class="inner_table">
				<tr>
					<td class="meta-title"> <strong>Sectors</strong> </td>
					<td class="meta-description"> N/A (Category?) </td>
				</tr>
			</table>
		</td>
		
	</tr>
	
	<tr>
	
		<td>
			<table class="inner_table">
				<tr>
					<td class="meta-title"><strong>Equity</strong> </td>
					<td class="meta-description"> <?php echo $deal->get_post_meta('gb_deal_equity'); ?>% </td>
				</tr>
			</table>
		</td>
		
		<td>
			<table class="inner_table">
				<tr>
					<td class="meta-title"> <strong>Day Left</strong> </td>
					<td class="meta-description"> <?php echo get_interval(current_time('timestamp'), $deal->get_expiration_date()); ?> </td>
				</tr>
			</table>
		</td>
		
	</tr>
	
	<tr>
	
		<td>
			<table class="inner_table">
				<tr>
					<td class="meta-title"> <strong>Share Type</strong> </td>
					<td class="meta-description">
						<?php 
							$share_types =  array(
										1 => 'Shpae A',
										2 => 'Shape B',
										3 => 'Shape C'
								);
							$share_type = $deal->get_post_meta('gb_deal_shape_type');
							
							echo $share_types[$share_type];
							
						?>
					</td>
				</tr>
			</table>
		</td>
		
		<td>
			<table class="inner_table">
				<tr>
					<td class="meta-title"> <strong>Tax Relief</strong> </td>
					<td class="meta-description" > ?? (Tax?) </td>
				</tr>
			</table>
		</td>
		
	</tr>
	
</table>