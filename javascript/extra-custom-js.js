
//document uploader
jQuery(document).ready(function($){
	
	var elem = '<p class="first-element"><input placeholder="Document Title" type="text" name="documentsname[]" /> &nbsp; &nbsp; <input type="file" name="documents[]" /> &nbsp; &nbsp;<a href="#" class="add-new">Add new</a> &nbsp; <a class="rem-existing" href="#">Remove</a></p>';
	var addDiv = $('#project_documents');
	var i = $('#project_documents p').size() + 1;
	
	$('.add-new').live('click', function(){
		$(elem).appendTo(addDiv);
		i ++;
		return false;
	});
	
	$('.rem-existing').live('click', function(){
		if(i > 2){
			$(this).parent().remove();
			i--;
		}
		
		return false;
	});
	
});


