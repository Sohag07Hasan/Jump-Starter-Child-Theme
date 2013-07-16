<?php

$post_id = get_the_ID();
$documents = get_post_meta($post_id, 'documents', true);
$documents = unserialize($documents);

if(is_array($documents)){
	
	echo '<ul class="attchments">';
	
	foreach($documents as $document_id){
		$document = get_post($document_id);
		$link = wp_get_attachment_url($document_id);
		
		echo '<li class="attachment-pdf"><a href="'.$link.'" alt="'.$document->post_title.'" >'.$document->post_title.'</li>';
	}
	
	echo '</ul>';
}

return;