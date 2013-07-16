<?php 

include dirname(__FILE__) . '/classes/class.deal.php';
$customzied_deal = new CustomizedDeal();

add_action('wp_enqueue_scripts', 'extra_jumpstart_js_include');
function extra_jumpstart_js_include(){
	wp_enqueue_script('jquery');
	wp_register_script('_extra_jump_start_js_', get_theme_root_uri() . '/jumpstart-child-theme/javascript/extra-custom-js.js', array('jquery'));
	wp_enqueue_script('_extra_jump_start_js_');
}



//get time interval 
function get_interval($now, $post_time){

	//php less than 5.3 compitable
	if(version_compare(phpversion(), 5.3) < 0) return self::interval($now, $post_time/1000);


	$datetime1 = new DateTime();
	$datetime1->setTimestamp($now);

	$datetime2 = new DateTime();
	$post_time = floor($post_time/1000);
	$datetime2->setTimestamp($post_time);

	$interval = $datetime1->diff($datetime2);

		
	if($interval->d > 0){
		$string = $interval->d;
	}
	
	return $string;


}


function get_attachments_pdfs($post_id = null){
	ob_start();
	$template = get_template_path('content/custom/attachments');
	include $template;

	$attachment = ob_get_contents();
	return $attachment;
}