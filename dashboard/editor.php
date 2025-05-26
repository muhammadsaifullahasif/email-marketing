<?php

include "config.php";

if(!isset($_GET['id']) && $_GET['id'] == '' && $_GET['id'] == 0) {
	header('location: templates.php');
}

?>
<!DOCTYPE html>
<html>
<head>
	<?php // include "head.php"; ?>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Editor</title>
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/grapesjs/0.16.34/css/grapes.min.css">
	<link rel="stylesheet" type="text/css" href="dist/css/grapesjs-preset-newsletter.css">
	<link href="https://unpkg.com/grapesjs/dist/css/grapes.min.css" rel="stylesheet"/>
	<style type="text/css">
		* {
			box-sizing: border-box;
			margin: 0;
			padding: 0;
		}
		html {
			overflow: scroll;
			overflow-x: hidden;
			-ms-overflow-style: none;
			scrollbar-width: none;
		}
		::-webkit-scrollbar {
			display: none;
		}
		.panel__top {
			padding: 0;
			width: 100%;
			display: flex;
			position: initial;
			justify-content: center;
			justify-content: space-between;
		}
		.panel__basic-actions {
			position: initial;
		}
	</style>
</head>
<body>
	<div class="panel__top">
		<div class="panel__basic-actions"></div>
	</div>
	<div id="editor">
		<?php

		if(isset($_GET['id']) && $_GET['id'] != '' && $_GET['id'] != 0) {

			$id = strip_tags(mysqli_real_escape_string($conn, $_GET['id']));

			$query = mysqli_query($conn, "SELECT * FROM templates WHERE id='$id' && delete_status='0'");
			if(mysqli_num_rows($query) > 0) {
				$result = mysqli_fetch_assoc($query);
				echo html_entity_decode(htmlspecialchars_decode($result['template_content']));
			}

		}

		?>
	</div>

	<?php include "javascript.php"; ?>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/grapesjs/0.16.34/grapes.min.js"></script>
	<script type="text/javascript" src="dist/js/grapesjs-preset-newsletter.min.js"></script>

	<script type="text/javascript">

		const editor = grapesjs.init({
			// Indicate where to init the editor. You can also pass an HTMLElement
			container: '#editor',
			// Get the content for the canvas directly from the element
			// As an alternative we could use: `components: '<h1>Hello World Component!</h1>'`,
			fromElement: true,
			// Size of the editor
			// height: '300px',
			width: 'auto',
			// Disable the storage manager for the moment
			storageManager: false,
			// Avoid any default panel
			// panels: { defaults: [] },
			plugins: [
				"gjs-preset-newsletter", 
				// "grapesjs-blocks-bootstrap4"
			],
			pluginsOpts: {
				"gjs-preset-newsletter": {
					modalTitleImport: 'Import template',
				},
				// "grapesjs-blocks-bootstrap4": {},
			},
			assetManager: {
				storageType  	: '',
				storeOnChange  : true,
				storeAfterUpload  : true,
				upload: dashboard_url + 'grapesjs_upload_assets.php',        //for temporary storage
				assets    	: [],
				uploadFile: function(e) {
					var files = e.dataTransfer ? e.dataTransfer.files : e.target.files;
					var formData = new FormData();
					for(var i in files){
						formData.append(i, files[i]) //containing all the selected images from local
					}
					$.ajax({
						url: 'grapesjs_upload_assets.php',
						type: 'POST',
						data: formData,
						contentType:false,
						crossDomain: true,
						dataType: 'json',
						mimeType: "multipart/form-data",
						processData:false,
						success: function(result){
							var myJSON = [];
							$.each( result['data'], function( key, value ) {
								myJSON[key] = value;    
							});
							var images = myJSON;    
							editor.AssetManager.add(images); //adding images to asset manager of GrapesJS
						}
					});
				},
			},
		});

		editor.Panels.addPanel({
			id: 'panel-top',
			el: '.panel__top',
		});
		editor.Panels.addPanel({
			id: 'basic-actions',
			el: '.panel__basic-actions',
			buttons: [{
				id: 'back',
				active: false,
				className: 'btn btn-primary btn-sm back',
				label: 'Back',
				command() {
					window.top.location = 'templates.php';
				}
			}, {
				id: 'save_template',
				active: true, // active by default
				className: 'btn btn-success btn-sm save_template',
				label: 'Save',
			}],
		});
		$('.save_template').on('click', function(){
			$.ajax({
				url: api_url,
				type: 'POST',
				data: JSON.stringify({ action: 'save_template', template_id:<?php echo strip_tags(mysqli_real_escape_string($conn, $_GET['id'])); ?>, user_id: user_info.id, content: editor.runCommand('gjs-get-inlined-html') }),
				success: function(result) {
					if(result.status_code == '001') {
						alert('Template save successfully');
					} else if(result.status_code == '002' || result.status_code == '003') {
						alert('Please Try Again');
					}
				}
			});
		});
	</script>
</body>
</html>