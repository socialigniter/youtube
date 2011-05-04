<div id="upload_info">
	<form id="form_upload_info" action="<?= base_url() ?>youtube/connections/upload" method="post" enctype="multipart/form-data">
		<h3>Title</h3>
		<input type="text" name="title" id="title" value="" class="input_large">
		<h3>Description</h3>
		<textarea name="description" id="description" value="" rows="7" cols="72"></textarea>
		<h3>Category</h3>
		<p><?= form_dropdown('category_id', $categories, '') ?></p>	  
		<h3>Tags</h3>
		<input type="text" name="tags" id="tags" value="" class="input_large">
		<p><input type="submit" value="Create" /></p>
	</form>
</div>

<div id="upload_file">
	<form id="form_upload_file" action="" method="post" enctype="multipart/form-data" onsubmit="return checkForFile();">
	  <h3>Video File</h3>
	  <p><input id="file" type="file" name="file"/></p>
	  <div id="errMsg" style="display:none;color:red">
	    You need to specify a file.
	  </div>
	  <div class="clear"></div>
	  <input type="hidden" name="token" id="upload_token" value=""/>
	  <input type="submit" value="Upload" />
	</form>
</div>

<script type="text/javascript">

// Elements for Placeholder
var validation_rules = [{
	'element' 	: '#title', 
	'holder'	: 'Funny Kitty Chase', 
	'message'	: 'You need an article title'
},{
	'element' 	: '#description', 
	'holder'	: 'This is the most funny video of my cats chasing each other. The best part is at the end when Muffy catches Bill and licks his face', 
	'message'	: ''	
},{
	'element' 	: '#tags', 
	'holder'	: 'Cats, Fires, Heroes, Cute', 
	'message'	: ''	
}]

$(document).ready(function()
{
	// Placeholders
	makePlaceholders(validation_rules);

	$('#upload_file').hide('fast');

	// Autocomplete Tags
	autocomplete("[name=tags]", 'api/tags/all');

	$("#form_upload_info").bind('submit', function(eve)
	{	
		eve.preventDefault();
		
		// Validation	
		if (validationRules(validation_rules))
		{
			// Strip Empty
			cleanAllFieldsEmpty(validation_rules);
		
			var video_data = $('#form_upload_info').serializeArray();	
			
			console.log(video_data);	
	
			$(this).oauthAjax(
			{	
				oauth 		: user_data,		
				url			: base_url + 'api/youtube/upload_info',
				type		: 'POST',
				dataType	: 'json',
				data		: video_data,
		  		success		: function(result)
		  		{
		  			console.log(result);
		  		
					$('html, body').animate({scrollTop:0});
					
					if (result.status == 'success')
					{
						$('#content_message').notify({scroll:true,status:result.status,message:result.message});
						
						$('#upload_info').hide('slow');
						$('#upload_file').show('slow');
						
						$('#form_upload_file').attr('action', result.data.url + '?nexturl=<?= base_url() ?>connections/youtube/success');
						$('#upload_token').val(result.data.token);
						
					}
					else
					{
						$('#content_message').notify({scroll:true,status:result.status,message:result.message});					
					}
			 	}
			});
		}
		else
		{
			eve.preventDefault();
		}
	});	
  
});  
</script>