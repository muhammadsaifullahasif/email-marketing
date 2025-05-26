<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Editor</title>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/grapesjs/0.16.34/grapes.min.js"></script>
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/grapesjs/0.16.34/css/grapes.min.css">
	<!-- Add Style and Script for Preset Webpage Builder -->
	<script src="js/grapesjs-preset-webpage.min.js"></script>
	<link rel="stylesheet" href="css/grapesjs-preset-webpage.min.css" />
	<!-- <link href="https://unpkg.com/grapesjs/dist/css/grapes.min.css" rel="stylesheet"/> -->
	<!-- <script src="https://unpkg.com/grapesjs@0.20.1/dist/grapes.min.js"></script> -->
	<!-- <script src="js/grapesjs-blocks-bootstrap4.min.js"></script> -->
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
	</style>
</head>
<body>
	<div id="editor">
		<p>Testing Editor using GrapesJs</p>
	</div>

	<script src="../plugins/jquery/jquery.min.js"></script>
	<script src="../dist/js/adminlte.min.js"></script>
	<script type="text/javascript" src="../dist/js/main.js"></script>

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
				"gjs-preset-webpage", 
				// "grapesjs-blocks-bootstrap4"
			],
			pluginsOpts: {
				"gjs-preset-webpage": {
					'navbarOpts' : false,
					'countdownOpts' : false,
					'formsOpts' : false,
					'exportOpts' : false,
					'aviaryOpts' : false
				},
				// "grapesjs-blocks-bootstrap4": {},
			},
			assetManager: {
				storageType  	: '',
				storeOnChange  : true,
				storeAfterUpload  : true,
				upload: dashboard_url + 'templates/upload_attachments.php',        //for temporary storage
				assets    	: [ ],
				uploadFile: function(e) {
					var files = e.dataTransfer ? e.dataTransfer.files : e.target.files;
					var formData = new FormData();
					for(var i in files){
						formData.append(i, files[i]) //containing all the selected images from local
					}
					$.ajax({
						url: 'upload_attachments.php',
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
	</script>
</body>
</html>