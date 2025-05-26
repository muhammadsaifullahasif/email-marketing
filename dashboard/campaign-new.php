<?php include "head.php"; ?>

	<title>AdminLTE 3 | Starter</title>
	<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

	<link rel="stylesheet" type="text/css" href="dist/css/grapes.min.css">
	<style type="text/css">
		.content-wrapper {
			position: relative;
		}
		.loader {
			position: absolute;
			background-color: #ffaf00;
			padding: 5px 15px;
			left: 45%;
		}
		#loader.active {
			display: block;
		}
		#content {
			width: 100%;
		}
		#content * {
			word-break: break-all !important;
		}
	</style>
</head>
<body class="hold-transition sidebar-mini sidebar-collapse">
	<div class="wrapper">

		<?php include "nav.php"; ?>

		<?php include "sidebar.php"; ?>

		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
			<div id="loader" class="loader">Loading...</div>
			<!-- Content Header (Page header) -->
			<div class="content-header">
				<div class="container-fluid">
					<div class="row mb-2">
						<div class="col-sm-6">
							<h1 class="m-0 d-inline">Campaign New</h1>
							<a href="campaign-new.php" id="add_campaign_btn" class="btn btn-outline-dark btn-sm mb-3 ml-2">Add New</a>
						</div><!-- /.col -->
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
								<li class="breadcrumb-item"><a href="campaigns.php">Campaigns</a></li>
								<li class="breadcrumb-item active">Campaign New</li>
							</ol>
						</div><!-- /.col -->
					</div><!-- /.row -->
				</div><!-- /.container-fluid -->
			</div>
			<!-- /.content-header -->

			<!-- Main content -->
			<section class="content">
				<div class="container-fluid">

					<!-- Campaigns -->
					<div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-header row align-items-center justify-content-between">
									<div class="col-md-6">
										<h5 class="card-heading d-inline">Campaign New</h5>
									</div>
									<div class="col-md-6">
									</div>
								</div>
								<div class="card-body dropdown" style="height: 400px; max-height: 100%; overflow-y: auto;">

									<form class="form" id="new_campaign_form" method="post">

										<div id="new_campaign_form_msg"></div>
										
										<div class="mb-3">
											<label>Title:</label>
											<input type="text" class="form-control form-control-border" placeholder="Enter Title" id="campaign_title" name="campaign_title">
											<div id="campaign_title_msg"></div>
										</div>

										<div class="mb-3">
											<label>Description:</label>
											<textarea class="form-control form-control-border" id="campaign_description" name="campaign_description" placeholder="Enter Description"></textarea>
										</div>

										<div class="mb-3">
											<label>Contact List:</label>
											<select class="form-control form-control-border" id="contact_list_id" name="contact_list_id">
												<option value="">Select Contact List</option>
											</select>
											<div id="contact_list_id_msg"></div>
										</div>

										<div class="mb-3">
											<label>Select Account:</label>
											<select class="form-control form-control-border" id="account_id" name="account_id">
												<option value="">Select Account</option>
											</select>
											<div id="account_id_msg"></div>
										</div>

										<div class="mb-3">
											<label>Subject:</label>
											<input type="text" class="form-control form-control-border" placeholder="Enter Subject" id="subject" name="subject">
											<div id="subject_msg"></div>
										</div>

										<div class="mb-3">
											<label>Schedule Campaign:</label>
											<div class="custom-control custom-switch">
												<input type="checkbox" class="custom-control-input" id="is_schedule">
												<label class="custom-control-label" for="is_schedule">Is Schedule</label>
											</div>
											<div class="" id="schedule_time_container">
											</div>
										</div>

										<div class="form-group">
											<div class="btn-group textare_btn">
												<button title="Bold" class="btn btn-sm btn-light" data-element="bold"><i class="fas fa-bold"></i></button>
												<button title="Italic" class="btn btn-sm btn-light" data-element="italic"><i class="fas fa-italic"></i></button>
												<button title="Underline" class="btn btn-sm btn-light" data-element="underline"><i class="fas fa-underline"></i></button>
												<button title="Unordered List" class="btn btn-sm btn-light" data-element="insertUnorderedList"><i class="fas fa-list-ul"></i></button>
												<button title="Ordered List" class="btn btn-sm btn-light" data-element="insertOrderedList"><i class="fas fa-list-ol"></i></button>
												<button title="Link" class="btn btn-sm btn-light" data-element="createLink"><i class="fas fa-link"></i></button>
												<button title="Code" id="content_code_btn" class="btn btn-sm btn-light" data-element="code"><i class="fas fa-code"></i></button>
												<button title="Text" id="content_text_btn" class="btn btn-sm btn-light" data-element="text">Visual</button>
												<button title="Template" class="btn btn-sm btn-light" data-element="template">Use Template</button>
												<button title="Clear Content" class="btn btn-sm btn-danger" data-element="clear_content">Clear Content</button>
											</div>
											<input type="hidden" id="content_type" value="" name="">
											<div id="content" contenteditable="true" style="overflow-y: auto; max-height: 300px; min-height: 300px; padding: 10px; border: 1px solid #000;"></div>
										</div>

										<input type="hidden" id="attachments" name="attachments">
										<div class="form-group dropzone">
											<div class="previews"></div>
										</div>

										<button class="btn btn-dark" id="new_campaign_form_btn" name="new_campaign_form_btn" type="submit">Submit</button>

									</form>

									<div class="modal fade" id="get_template_modal">
										<div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title">Template</h5>
													<button type="button" class="close" data-dismiss="modal" aria-label="Close">
														<span aria-hidden="true">&times;</span>
													</button>
												</div>
												<div class="modal-body">
													<div id="display_templates" class="row"></div>
												</div>
												<div class="modal-footer justify-content-between">
													<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
													<button type="button" class="btn btn-primary">Save changes</button>
												</div>
											</div>
											<!-- /.modal-content -->
										</div>
										<!-- /.modal-dialog -->
									</div>
									<!-- /.modal -->

								</div>
							</div>
						</div>
					</div>
					<!-- /.Campaigns -->
				</div>
			</section>
			<!-- /.content -->
		</div>
		<!-- /.content-wrapper -->

		<?php include "footer.php"; ?>
	</div>
	<!-- ./wrapper -->

	<?php include "javascript.php"; ?>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script type="text/javascript" src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
	<script type="text/javascript" src="dist/js/grapes.min.js"></script>
	<script type="text/javascript" src="dist/js/grapesjs-preset-newsletter.min.js"></script>

	<script type="text/javascript">

		$(document).ready(function(){
			$('#loader').hide();
		});

		if(user_info.user_role.campaigns.add == 0) {
			window.top.location = dashboard_url +'campaigns.php';
			$('#add_campaign_btn').hide();
		}

		function display_contact_lists() {
			contact_lists = contact_lists_function(user_info);
			$('#contact_list_id').html('');
			if(contact_lists.status_code == '001') {
				$('#contact_list_id').html('<option value="">Select Contact List</option>');
				$.each(contact_lists.display_records, function(key, value) {

					$('#contact_list_id').append(
						'<option value="'+ value.id +'">'+ value.contact_list_title +' ('+ value.total_contacts +')</option>'
					);
				});
			} else {
				$('#contact_list_id').html("<option value=''>No Contact List Found</option>");
			}
		}

		display_contact_lists();

		function display_main_account_select() {
			var account_info = account_info_function(user_info);
			$('#account_id').html('');
			if(account_info.status_code == '001') {
				$('#account_id').html("<option value=''>Select Account</option>");
				var i = 1;
				$.each(account_info.display_records, function(key, value) {
					if(value.active_status != 1 || value.verified_status != 1)
						return;

					if(i == 1) {
						account_selected = 'selected';
					} else {
						account_selected = '';
					}
					$('#account_id').append(
						'<option '+account_selected+' value="'+value.id+'">'+value.account_email+'</option>'
					);
					i++;
				});
			}
		}
		display_main_account_select();

		$('#is_schedule').on('change', function(){
			if($('#is_schedule').is(':checked')) {
				$('#schedule_time_container').html(
					'<label>Schedule Time:</label>' + 
					'<input type="text" class="form-control form-control-border" placeholder="Select Schedule Date" id="schedule_time" name="schedule_time">' + 
					'<div id="schedule_time_msg"></div>'
				);

				$("#schedule_time").flatpickr({
					enableTime: true,
					dateFormat: "d-m-Y H:i",
					minDate: "today",
					time_24hr: true
				});
			} else {
				$('#schedule_time_container').html('');
			}
		});

		let attached_files;

		Dropzone.autoDiscover = false;
		const dropzone = new Dropzone("div.dropzone", {
			url: "upload_attachments.php",
			parallelUploads: 10,
			uploadMultiple: true,
			maxFilesize: 25,
			addRemoveLinks: true,
			removedfile: function(file) {
				var name = file.name;
				$.ajax({
					url: 'upload_attachments.php',
					type: 'POST',
					data: { name:name, request:2 },
					success: function(data) {
						data = dashboard_url + data;
						let attached_files = $('#attachments').val();
						attached_files = attached_files.replace(data, '');
						attached_files = attached_files.replace(', ,', ',');
						if(attached_files.charAt(attached_files.length-1) === ',') {
							attached_files = attached_files.slice(0, -1);
						} else if(attached_files.charAt(attached_files.length-2) == ',') {
							attached_files = attached_files.slice(0, -2);
						} else if(attached_files.charAt(0) === ',') {
							attached_files = attached_files.slice(1);
						} else if(attached_files.charAt(1) === ',') {
							attached_files = attached_files.slice(2);
						}
						attached_files = attached_files.trim();
						$('#attachments').val(attached_files);
					}
				});
				var _ref;
				return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
			},
			success: function(file, response) {
				attached_files = $('#attachments').val();
				if(attached_files != '') {
					attached_files = attached_files + ', ' + response;
				} else {
					attached_files = response;
				}
				$('#attachments').val(attached_files);
			}
		});

		$(document).on('click', '.textare_btn button', function(e){
			e.preventDefault();
			$(this).each(element => {
				let command = $(this).data('element');
				if(command == 'createLink') {
					let url = prompt('Enter the link here', 'https://');
					document.execCommand(command, false, url);
				} else if(command == 'code') {
					$('#content').text($('#content').html());
				} else if(command == 'text') {
					$('#content').html($('#content').text());
				} else if(command == 'template') {
					$('#get_template_modal').modal('show');
				} else if(command == 'clear_content') {
					$('#content').html('');
					$('#content_type').val('');
					$('#content_code_btn, #content_text_btn').show();
				} else {
					document.execCommand(command, false, null);
				}
			});
		});

		$('#get_template_modal').on('shown.bs.modal', function () {
			$('#display_templates').html('');
			templates = templates_function(user_info);

			if(templates.status_code == '001') {
				$.each(templates.display_records, function(key, value) {

					$('#display_templates').append(
						'<div class="col-md-2 mb-3">' + 
							'<div class="card">' + 
								'<img src="images/no_image.jpg" class="card-img-top">' + 
								'<div class="card-body">' + 
									'<h5 class="card-title">'+ value.template_name +'</h5>' + 
									'<p class="card-text">'+ value.template_category_name +'</p>' + 
									'<button data-id="'+ value.id +'" class="btn btn-primary btn-sm use_template_btn">Use Template</button>' + 
								'</div>' + 
							'</div>' + 
						'</div>'
					);

				});
			} else {
				$('#display_templates').html("<tr><td colspan='5' class='text-center'>No Record Found</td></tr>");
			}
		});

		$(document).on('click', '.use_template_btn', function(){
			var id = $(this).data('id');

			if(id != '' && id != 0) {
				$.ajax({
					url: api_url,
					type: 'POST',
					data: JSON.stringify({ action: 'single_template', template_id: id, user_id: user_info.id }),
					success: function(result) {
						if(result.status_code == '001') {
							$('#get_template_modal').modal('hide');
							$('#content').html(result.display_records.content);
							$('#content_type').val(result.display_records.content);
							$('#content_code_btn, #content_text_btn').hide();

							const editor = grapesjs.init({
								container: '#content',
								allowScripts: 1,
								jsInHtml: false,
								fromElement: true,
								width: 'auto',
								storageManager: false,
								plugins: [
									"gjs-preset-newsletter", 
								],
								pluginsOpts: {
									"gjs-preset-newsletter": {
										modalTitleImport: 'Import template',
									},
								},
								assetManager: {
									storageType  	: '',
									storeOnChange  : true,
									storeAfterUpload  : true,
									upload: dashboard_url + 'grapesjs_upload_assets.php', 
									assets    	: [
									],
									uploadFile: function(e) {
										var files = e.dataTransfer ? e.dataTransfer.files : e.target.files;
										var formData = new FormData();
										for(var i in files){
											formData.append(i, files[i])
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
												editor.AssetManager.add(images);
											}
										});
									},
								},
							});
						}
					}
				});
			}
		});

		$('#content').on('change', function(){
			$('#content_type').val($('#content').html());
		});

		$('#new_campaign_form').on('submit', function(e){
			e.preventDefault();

			var campaign_title = $('#campaign_title').val();
			var campaign_description = $('#campaign_description').val();
			var contact_list_id = $('#contact_list_id').val();
			var account_id = $('#account_id').val();
			var subject = $('#subject').val();
			if($('#is_schedule').is(':checked')) {
				var is_schedule = 1;
				var schedule_time = $('#schedule_time').val();
			} else {
				var is_schedule = 0;
				var schedule_time = '';
			}
			var content = $('#content_type').val();
			var attachments = $('#attachments').val();
			var href_links = '', j = 0, href_output, input_content = content.split(' ');

			for(var i = 0; i < input_content.length; i++) {

				if(input_content[i].startsWith('href="') || input_content[i].startsWith("href='")) {
					href_output = input_content[i].substr(6);
					href_output = href_output.slice(0, -1);
					if(j == 0) {
						href_links += href_output;
					} else {
						href_links += ','+href_output;
					}
					j++;
				}

			}
			var bool = 0;

			if(campaign_title == '') {
				$('#campaign_title').removeClass('is-valid').addClass('is-invalid');
				$('#campaign_title_msg').removeClass('valid-feedback').addClass('invalid-feedback').text('Please Enter Campaign Title');
				bool = 1;
			} else {
				$('#campaign_title').removeClass('is-valid is-invalid');
				$('#campaign_title_msg').removeClass('valid-feedback invalid-feedback').text('');
				bool = 0;
			}

			if(contact_list_id == '') {
				$('#contact_list_id').removeClass('is-valid').addClass('is-invalid');
				$('#contact_list_id_msg').removeClass('valid-feedback').addClass('invalid-feedback').text('Please Select Contact List');
				bool = 1;
			} else {
				$('#contact_list_id').removeClass('is-valid is-invalid');
				$('#contact_list_id_msg').removeClass('valid-feedback invalid-feedback').text('');
				bool = 0;
			}

			if(account_id == '') {
				$('#account_id').removeClass('is-valid').addClass('is-invalid');
				$('#account_id_msg').removeClass('valid-feedback').addClass('invalid-feedback').text('Please Select Account ID');
				bool = 1;
			} else {
				$('#account_id').removeClass('is-valid is-invalid');
				$('#account_id_msg').removeClass('valid-feedback invalid-feedback').text('');
				bool = 0;
			}

			if(subject == '') {
				$('#subject').removeClass('is-valid').addClass('is-invalid');
				$('#subject_msg').removeClass('valid-feedback').addClass('invalid-feedback').text('Please Enter Subject');
				bool = 1;
			} else {
				$('#subject').removeClass('is-valid is-invalid');
				$('#subject_msg').removeClass('valid-feedback invalid-feedback').text('');
				bool = 0;
			}

			if(is_schedule == 1) {

				if(schedule_time == '') {
					$('#schedule_time').removeClass('is-valid').addClass('is-invalid');
					$('#schedule_time_msg').removeClass('valid-feedback').addClass('invalid-feedback').text('Please Select Schedule Time');
					bool = 1;
				} else {
					$('#schedule_time').removeClass('is-valid is-invalid');
					$('#schedule_time_msg').removeClass('valid-feedback invalid-feedback').text('');
					bool = 0;
				}

			}

			if(bool == 0) {
				if(user_info.user_role.campaigns.add == 1) {
					$.ajax({
						url: api_url, 
						type: 'POST', 
						beforeSend: function() {
							$('#new_campaign_form_msg').removeClass('alert-danger').addClass('alert alert-success').text('Please Wait...');
							$('#new_campaign_form_btn').addClass('disabled');
						},
						data: JSON.stringify({ action:'new_campaign', user_id:user_info.id, campaign_title:campaign_title, campaign_description:campaign_description, contact_list_id:contact_list_id, account_id:account_id, subject:subject, content:content, href_links:href_links, attachments:attachments, is_schedule:is_schedule, schedule_time:schedule_time }), 
						success: function(result) {
							if(result.status_code == '001') {
								$('#new_campaign_form_msg').removeClass('alert-danger').addClass('alert alert-success').text('Campaign Successfully Created');
								setTimeout(function(){
									window.location.href = dashboard_url+'campaigns.php';
								}, 1000);
							} else if(result.status_code == '002') {
								$('#new_campaign_form_msg').removeClass('alert-success').addClass('alert alert-danger').text('Please Try Again');
							} else if(result.status_code == '003') {
								$('#new_campaign_form_msg').removeClass('alert-success').addClass('alert alert-danger').text('Please Fill Required Fields');
							}
							$('#new_campaign_form_btn').removeClass('disabled');
						}
					});
				} else {
					alert("You're not allowed to add campaign");
					window.top.location = dashboard_url +'campaigns.php';
				}
			}
		});

	</script>
</body>
</html>
