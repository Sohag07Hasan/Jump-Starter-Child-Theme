<?php
class CustomizedDeal{
	
	
	function __construct(){
		//extra fields with form
		add_filter('gb_crowdfunding_deal_submission_fields', array(&$this, 'custom_deal_submission_fields'));
		add_filter('gb_get_form_field', array(get_class(), 'manage_mulitple_uploader'), 10, 4);
		add_action('submit_deal', array(&$this, 'process_form_submission'));
		//add_action('init', array(&$this, 'check'));
	}
	
	function check(){
		var_dump(get_theme_root_uri() );
	}
	
	function custom_deal_submission_fields($fields){
	
		$fields['project_extra_field'] = array(
				'weight' => 1990,
				'label' => 'Extra Fields',
				'type' => 'heading',
				'required' => false
		);
		
		$fields['project_category'] = array(
				'weight' => 1998,
				'label' => 'Categories',
				'type' => 'category_checkbox',
				'required' => false					
			);
	
		$fields['business_stage'] = array(
				'weight' => 2000,
				'label' => 'Stage of Business',
				'type' => 'radios',
				'options' => array(
						0 => 'Idea / Concept',
						1 => 'Startup',
						3 => 'Growth Business'
				),
		);
	
		$fields['profit_history'] = array(
				'weight' => 2005,
				'label' => 'Profit History',
				'type' => 'select'
		
		);
	
		for($i = 0; $i < 20; $i++){
			if($i == 0){
				$k = 'Not Yet';
			}
			elseif($i == 1){
				$k = "$i Year";
			}
			else{
				$k = "$i Years";
			}
	
			$fields['profit_history']['options'][$i] = $k;
		}
	
		$fields['key_executives'] = array(
				'weight' => 2010,
				'label' => 'Key Executives',
				'type' => 'textarea'
	
		);
	
		$fields['tax_relief'] = array(
				'weight' => 2015,
				'label' => 'Tax Relief',
				'type' => 'radios',
				'options' => array(
						1 => 'SSE',
						2 => 'EIS',
				),
		);
	
		$fields['shape_type'] = array(
				'weight' => 2020,
				'label' => 'Shape Type',
				'type' => 'radios',
				'options' => array(
						1 => 'Shpae A',
						2 => 'Shape B',
						3 => 'Shape C'
				),
		);
	
		$fields['equity'] = array(
				'weight' => 2025,
				'label' => 'Equity',
				'type' => 'select'
	
		);
	
		for($i = 0; $i < 101; $i++){
			$fields['equity']['options'][$i] = "$i %";
		}
		
		
		$fields['documents'] = array(
					'weight' => 2025,
					'label' => 'Documents',
					'type' => 'multiple_file'
				);
	
		return $fields;
	}
	
	
	function manage_mulitple_uploader($field, $key, $data, $category){
		if($data['type'] == 'multiple_file'){
			$field = '<div id="project_documents">
						<p class="first-element">
							<input placeholder="Document Title" type="text" name="documentsname[]" /> &nbsp; &nbsp;
							<input type="file" name="documents[]" /> &nbsp; &nbsp;<a class="add-new" href="#">Add new</a>
						</p>
					</div>';
		}

		if($data['type'] == 'category_checkbox'){
			$terms = get_terms('gb_category', array('hide_empty' => false));
			
			//var_dump($terms);
			
			$field = '<div id="project_category">';
			if($terms){
				foreach($terms as $term){
					$field .= '<input id="term_'.$term->id.'" type="checkbox" name="project_category[]" value="'.$term->name.'" > ' .$term->name . '<br/>'; 
				}
			}
			
			$field .= '</div>';
		}
		
		
		return $field;
	}
	
	function process_form_submission($deal){
		$fields = $this->custom_deal_submission_fields(array());
				
		$posted = array();
		foreach($fields as $key => $field){
			
			//uploader
			if($key == 'documents'){
				$this->file_upload_handler($deal);
				continue;
			}
			
			if($key == 'project_category'){
				$this->set_category($deal->get_id());
				continue;
			}
			
			$key = 'gb_deal_' . $key;
			if(!empty($_POST[$key])){
				$posted[$key] = $_POST[$key];				
			}
		}		
		
		return $deal->save_post_meta($posted);
	}
	
	
	function set_category($post_id){
		//var_dump($_POST['project_category']);
		//exit;
		wp_set_object_terms($post_id, $_POST['project_category'], 'gb_category');
	}
	
	function file_upload_handler($deal){		
		
		if(!empty($_FILES['documents']['tmp_name'])){
			foreach($_FILES['documents']['tmp_name'] as $key => $tmp){
				$attch_id = $this->handle_attachment($deal->get_id(), array(
					'name' => $_FILES['documents']['name'][$key],
					'tmp_name' => $tmp,
					'type' => $_FILES['documents']['type'][$key],
					'key' => $key,
					'title' => $_POST['documentsname'][$key]									
				));
				
				if($attch_id){
					$documents[] = $attch_id;
				}
			}
		}
				
		
		//update_post_meta($deal->get_id(), 'latest_documents', serialize($documents));		
		$deal->save_post_meta(array('documents' => serialize($documents)));
		
	}
	
	//handle attachments
	static function handle_attachment($post_id, $file){		
		
		if($file['type'] != 'application/pdf') return;
		
		$upload_dir = wp_upload_dir();
		$image_dir = $upload_dir['basedir'] . '/projects' ;
		if(!file_exists($image_dir)){
			@ mkdir($image_dir);
		}
	
		$unique_name = $post_id . '_' . current_time('timestamp') . "_" . $key;	
	
		$outPath = $image_dir . '/' . $unique_name . '.pdf';
		$outUrl = $upload_dir['baseurl'] . $unique_name . '.pdf';
			
		//if(self::save_image($inPath, $outPath)){
		
		if(move_uploaded_file($file['tmp_name'], $outPath)){
	
			$wp_filetype = wp_check_filetype(basename($outUrl), null );
	
			$info = array(
					'guid' => $outUrl,
					'post_mime_type' => $wp_filetype['type'],
					'post_title' => $file['title'],
					'post_content' => '',
					'post_status' => 'inherit'
			);
	
			$attach_id = wp_insert_attachment( $info, $outPath, $post_id );
			return $attach_id;
		}	
	
	}
		
	
}