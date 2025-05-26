<?php

include "head.php";

?>
	
	<title>AdminLTE 3 | Starter</title>
	<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
		.to_address li {
			background-color: silver;
			border-radius: 50px;
			display: inline-block;
			font-size: 14px;
			margin-right: 20px;
			padding: 2px 5px;
			padding-left: 0px;
			list-style: none;
		}
		.to_address i {
			background-color: grey;
			border-radius: 50px;
			color: white;
			padding: 5px 7px;
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
							<h1 class="m-0">Compose</h1>
						</div><!-- /.col -->
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
								<li class="breadcrumb-item active">Compose</li>
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
										<h5 class="card-heading d-inline">Compose</h5>
									</div>
									<div class="col-md-6">
									</div>
								</div>
								<div class="card-body dropdown" style="height: 400px; max-height: 100%; overflow-y: auto;">

									<form method="post" id="compose_form" class="">
										<input type="hidden" value="" id="message_id" name="">
										<div id="from_address_container">
											<div class="form-group">
												<select class="form-control" id="from_address">
													<option value="">Select Account</option>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label>
												<a href="#" id="cc_address_btn" class="mr-2">Cc</a>
												<a href="#" id="bcc_address_btn">Bcc</a>
											</label>
											<input type="text" class="form-control to_address" id="to_address" placeholder="To Address: (Seperate with , )" name="to_address">
										</div>
										<div id="cc_address_container"><input type="hidden" id="cc_address" name=""></div>
										<div id="bcc_address_container"><input type="hidden" id="bcc_address" name=""></div>
										<div class="form-group">
											<input class="form-control" id="subject" placeholder="Subject:">
										</div>
										<div class="form-group">
											<div class="btn-group textare_btn">
												<button title="Bold" class="btn btn-sm btn-light" data-element="bold"><i class="fas fa-bold"></i></button>
												<button title="Italic" class="btn btn-sm btn-light" data-element="italic"><i class="fas fa-italic"></i></button>
												<button title="Underline" class="btn btn-sm btn-light" data-element="underline"><i class="fas fa-underline"></i></button>
												<button title="Unordered List" class="btn btn-sm btn-light" data-element="insertUnorderedList"><i class="fas fa-list-ul"></i></button>
												<button title="Ordered List" class="btn btn-sm btn-light" data-element="insertOrderedList"><i class="fas fa-list-ol"></i></button>
												<button title="Link" class="btn btn-sm btn-light" data-element="createLink"><i class="fas fa-link"></i></button>
												<button title="Code" class="btn btn-sm btn-light" data-element="code"><i class="fas fa-code"></i></button>
												<button title="Text" class="btn btn-sm btn-light" data-element="text">Visual</button>
												<button title="Template" class="btn btn-sm btn-light" data-element="template">Use Template</button>
											</div>
											<div id="content" contenteditable="true" style="overflow-y: auto; max-height: 300px; min-height: 300px; padding: 10px; border: 1px solid #000;">
											</div>
										</div>
										<div class="bg-white">
											<ul class="mailbox-attachments d-flex align-items-stretch clearfix" id="attachments_container">
											</ul>
										</div>
										<input type="hidden" id="attachments" name="attachments">
										<div class="form-group dropzone">
											<div class="previews"></div>
										</div>
										<div class="mb-3" id="is_important_container"></div>
										<div class="mb-3" id="is_tracked_container"></div>
										<div id="schedule_time_block"></div>
										<button type="button" id="draft_btn" class="btn btn-default"><i class="fas fa-pencil-alt"></i> Draft</button>
										<button type="submit" id="send_btn" class="btn btn-primary"><i class="far fa-envelope"></i> Send</button>
									</form>
									<!-- /.card -->

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
	<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/grapesjs/0.16.34/grapes.min.js"></script>
	<script type="text/javascript" src="dist/js/grapesjs-preset-newsletter.min.js"></script>
	<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
	<script type="text/javascript">

		if(user_info.user_role.inbox.sent == 0) {
			// window.top.location == dashboard_url;
		}

		if(user_info.user_role.inbox.schedule == 0) {
			$('#schedule_time_block').html("<input type='hidden' value='0' id='inbox_schedule'>");
		} else {
			$('#schedule_time_block').html(
				"<input type='hidden' value='1' id='inbox_schedule'>" + 
				'<div class="mb-3">' + 
					'<label>Schedule Message:</label>' + 
					'<div class="custom-control custom-switch">' + 
						'<input type="checkbox" class="custom-control-input" id="is_schedule">' + 
						'<label class="custom-control-label" for="is_schedule">Is Schedule</label>' + 
					'</div>' + 
					'<div id="schedule_time_container">' + 
					'</div>' + 
				'</div>'
			);
		}

		$(document).ready(function(){
			$('#to_address').focus();
			$('#loader').hide();

			if((subscription_info.features.urgent_email.limit != false && (subscription_info.features.urgent_email.limit > 0 || subscription_info.features.urgent_email.limit === '-1')) && (user_info.user_role.inbox.urgent_email == 1)) {
				if(user_info.user_role.inbox.urgent_email == 1) {
					$('#is_important_container').html(
						'<label>Urgent Message:</label>' + 
						'<div class="custom-control custom-switch">' + 
							'<input type="checkbox" class="custom-control-input" value="1" id="is_important">' + 
							'<label class="custom-control-label" for="is_important">Is Urgent</label>' + 
						'</div>'
					);
					var urgent_email_info = (user_info) => {
						var account_id = $('#from_address').val();
						var tmp;
						$.ajax({
							url: api_url,
							type: 'POST',
							async: false,
							data: JSON.stringify({ 'action' : 'get_urgent_email_info', 'user_id' : user_info.id, 'account_id':account_id }),
							success: function(result) {
								tmp = result;
								if(result.status_code === '002') {
									if(result.status_subcode === '2001') {
										$('#is_important_container').html("<a href='"+home_url+"'>Upgrade to Premium<i class='fas fa-crown ml-1'></i> Version, to use Urgent Email Feature</a>");
									} else if(result.status_subcode === '2002') {
										$('#is_important_container').html("Your package is expired, Please update your package to use Urgent Email Feature");
									}
								}
							}
						});
						return tmp;
					}
					urgent_email_info = urgent_email_info(user_info);
					$('#is_important').on('change', function(){
						if($('#is_important').is(':checked')) {
							if(urgent_email_info.status_code == '001') {
								$('#is_important').prop("checked", true);
							} else {
								$('#is_important').prop("checked", false);
							}
						}
					});
				} else {
					$('#is_important_container').html('<input type="hidden" value="0" id="is_important">');
				}
			} else {
				$('#is_important_container').html('<input type="hidden" value="0" id="is_important">');
			}

			if((subscription_info.features.tracking_email.limit != false && (subscription_info.features.tracking_email.limit > 0 || subscription_info.features.tracking_email.limit === '-1')) && (user_info.user_role.inbox.tracking_email == 1)) {
				if(user_info.user_role.inbox.tracking_email == 1) {
					$('#is_tracked_container').html(
						'<label>Track Message:</label>' + 
						'<div class="custom-control custom-switch">' + 
							'<input type="checkbox" class="custom-control-input" value="1" id="is_tracked">' + 
							'<label class="custom-control-label" for="is_tracked">Is Track</label>' + 
						'</div>'
					);
					var track_email_info = (user_info) => {
						var account_id = $('#from_address').val();
						var tmp;
						$.ajax({
							url: api_url,
							type: 'POST',
							async: false,
							data: JSON.stringify({ 'action' : 'get_track_email_info', 'user_id' : user_info.id, 'account_id':account_id }),
							success: function(result) {
								tmp = result;
								if(result.status_code == '002') {
									if(result.status_subcode == '2001') {
										$('#is_tracked_container').html("<a href='"+home_url+"'>Upgrade to Premium<i class='fas fa-crown ml-1'></i> Version, to use Tracking Email Feature</a>");
									} else if(result.status_subcode == '2002') {
										$('#is_tracked_container').html("Your package is expired, Please update your package to use Tracking Email Feature");
									}
								}
							}
						});
						return tmp;
					}
					track_email_info = track_email_info(user_info);
					console.log(track_email_info);
					$('#is_tracked').on('change', function(){
						if($('#is_tracked').is(':checked')) {
							if(track_email_info.status_code == '001') {
								$('#is_tracked').prop("checked", true);
							} else {
								$('#is_tracked').prop("checked", false);
							}
						}
					});
				} else {
					$('#is_tracked_container').html('<input type="hidden" value="0" id="is_tracked">');
				}
			} else {
				$('#is_tracked_container').html('<input type="hidden" value="0" id="is_tracked">');
			}


			$('#is_schedule').on('change', function(){
				if(user_info.user_role.inbox.schedule == 1) {
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
				} else {
					$('#schedule_time_container').html('');
				}
			});
		});

		$('#send_btn').on('click', function(e){
			e.preventDefault();
			var from_address = $('#from_address').val();
			var to_address = $('#to_address').val();
			var cc_address = $('#cc_address').val();
			var bcc_address = $('#bcc_address').val();
			var subject = $('#subject').val();
			var message = $('#content').html();
			var attachments = $('#attachments').val();
			if($('#is_important').is(':checked')) {
				if(user_info.user_role.inbox.urgent_email == 1) {
					var is_important = 1
				} else {
					var is_important = 0;
				}
			} else {
				var is_important = 0;
			}
			if($('#is_tracked').is(':checked')) {
				if(user_info.user_role.inbox.tracking_email == 1) {
					var is_tracked = 1;
				} else {
					var is_tracked = 0;
				}
			} else {
				var is_tracked = 0;
			}
			if($('#inbox_schedule').val() == 1) {
				if($('#is_schedule').is(':checked')) {
					if(user_info.user_role.inbox.schedule == 1) {
						var is_schedule = 1;
						var schedule_time = $('#schedule_time').val();
					} else {
						var is_schedule = 0;
						var schedule_time = '';
					}
				} else {
					var is_schedule = 0;
					var schedule_time = '';
				}
			} else {
				var is_schedule = 0;
				var schedule_time = '';
			}
			var bool = 0;

			if(from_address == '') {
				alert('Please specify any sender account.');
				bool = 1;
			}
			if(to_address == '') {
				alert('Please specify at least one recipient.');
				bool = 1;
			}
			if(subject == '') {
				alert('Please specify subject.');
				bool = 1;
			}
			if(is_schedule == 1) {
				if(schedule_time == '') {
					alert('Please Select Schedule Time.');
					bool = 1;
				}
			}

			if(bool == 0) {
				if(user_info.user_role.inbox.sent == 1) {
					$.ajax({
						url: api_url,
						type: 'POST',
						beforeSend: function() {
							$('#loader').show();
						},
						data: JSON.stringify({"action":"compose", "user_id":user_info.id, "from_address":from_address, "to_address":to_address, "cc_address":cc_address, "bcc_address":bcc_address, "subject":subject, "message":message, "attachments":attachments, 'is_important':is_important, 'is_tracked':is_tracked, 'is_schedule':is_schedule, 'schedule_time':schedule_time}),
						success: function(result) {
							if(result.status_code == '001') {
								$('#loader').html('Email send Successfully');
								window.top.location = dashboard_url;
							} else {
								$('#send_btn, #draft_btn').removeAttr('disabled');
								$('#loader').hide();
								alert('Please Try Again');
							}
						}
					});
				} else {
					alert("You're not allowed to send mail");
				}
			}
		});

		$('#draft_btn').on('click', function(e){
			e.preventDefault();
			var message_id = $('#message_id').val();
			var from_address = $('#from_address').val();
			var to_address = $('#to_address').val();
			var cc_address = $('#cc_address').val();
			var bcc_address = $('#bcc_address').val();
			var subject = $('#subject').val();
			var message = $('#content').html();
			var attachments = $('#attachments').val();
			if($('#is_important').is(':checked')) {
				var is_important = 1;
			} else {
				var is_important = 0;
			}
			if($('#is_tracked').is(':checked')) {
				var is_tracked = 1;
			} else {
				var is_tracked = 0;
			}
			if($('#is_schedule').is(':checked')) {
				var is_schedule = 1;
				var schedule_time = $('#schedule_time').val();
			} else {
				var is_schedule = 0;
				var schedule_time = '';
			}
			var bool = 0;

			if(from_address == '') {
				alert('Please specify any sender account');
				bool = 1;
			}
			if(to_address == '') {
				alert('Please specify at least one recipient');
				bool = 1;
			}

			if(bool == 0) {
				if(user_info.user_role.inbox.sent == 1) {
					$.ajax({
						url: api_url,
						type: 'POST',
						beforeSend: function() {
							$('#loader').show();
						},
						data: JSON.stringify({"action":"save_draft", "user_id":user_info.id, "message_id":message_id, "from_address":from_address, "to_address":to_address, "cc_address":cc_address, "bcc_address":bcc_address, "subject":subject, "message":message, "attachments":attachments, 'is_important':is_important, 'is_tracked':is_tracked, 'is_schedule':is_schedule, 'schedule_time':schedule_time}),
						success: function(result) {
							if(result.status_code == '001') {
								$('#loader').html('Email save Successfully');
								setTimeout(function(){
									window.top.location = dashboard_url;
								},1000);
							} else {
								$('#send_btn, #draft_btn').removeAttr('disabled');
								$('#loader').hide();
								alert('Please Try Again');
							}
						}
					});
				} else {
					alert("You're not allowed to send mail");
				}
			}
		});

		$('#cc_address_btn').on('click', function(){
			$('#cc_address_container').html(
				'<div class="form-group">' + 
					'<input class="form-control" placeholder="Cc: (Seperate with , )" id="cc_address" name="cc_address">' + 
				'</div>'
			);
		});

		$('#bcc_address_btn').on('click', function(){
			$('#bcc_address_container').html(
				'<div class="form-group">' + 
					'<input class="form-control" placeholder="Bcc: (Seperate with , )" id="bcc_address" name="bcc_address">' + 
				'</div>'
			);
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

		<?php

		if(isset($_GET['action']) && $_GET['action'] == 'forward') {
		?>

			var get_message_function = (user_info) => {
				var tmp;
				$.ajax({
					url: api_url,
					type: 'POST',
					async: false,
					data: JSON.stringify({ 'action' : 'get_message', 'id' : '<?php echo strip_tags($_GET['message_id']); ?>', 'user_id' : user_info.id }),
					success: function(result) {
						tmp = result;
					}
				});
				return tmp;
			}
			get_message = get_message_function(user_info);
			single_account_info = single_account_info_function(get_message.display_records.account_id, user_info);

			if(get_message.status_code == '001') {
				if(get_message.display_records.cc_address != '') {
					var cc_address = "Cc: \""+ get_message.display_records.cc_address +"\"";
				} else {
					var cc_address = '';
				}
				$('#subject').val('Fwd: '+ get_message.display_records.subject).attr('readonly', 'readonly');
				$('#content').html(
					"<br><br>---------- Forwarded message ---------" + 
					"<br>From: \""+ get_message.display_records.from_address +"\"" + 
					"<br>Date: " + get_message.display_records.udate + 
					"<br>Subject: " + get_message.display_records.subject + 
					"<br>To: "+ single_account_info.display_records.account_title +" \""+ single_account_info.display_records.account_email +"\"" + 
					"<br>"+ cc_address + 
					"<br><br><br>" + 
					get_message.display_records.content
				);

				if(get_message.display_records.attachments.total_records > 0) {
					var i = 1;
					$.each(get_message.display_records.attachments.attachment_records, function(key, value){
						i++;

						attached_files = $('#attachments').val();
						if(attached_files != '') {
							attached_files = attached_files + ', ' + value.attachment_url;
						} else {
							attached_files = value.attachment_url;
						}
						$('#attachments').val(attached_files);
					});
				}
			}
		<?php
		} else if(isset($_GET['message_id'])) {
		?>
			var get_message_function = (user_info) => {
				var tmp;
				$.ajax({
					url: api_url,
					type: 'POST',
					async: false,
					data: JSON.stringify({ 'action' : 'get_message', 'id' : '<?php echo strip_tags($_GET['message_id']); ?>', 'user_id' : user_info.id }),
					success: function(result) {
						tmp = result;
					}
				});
				return tmp;
			}
			get_message = get_message_function(user_info);
			single_account_info = single_account_info_function(get_message.display_records.account_id, user_info);

			if(get_message.status_code == '001') {
				$('#message_id').val(<?php echo strip_tags($_GET['message_id']); ?>);
				if(get_message.display_records.cc_address != '') {
					var cc_address = "Cc: \""+ get_message.display_records.cc_address +"\"";
				} else {
					var cc_address = '';
				}
				$('#to_address').val(get_message.display_records.to_address);
				$('#subject').val(get_message.display_records.subject);
				$('#content').html(get_message.display_records.content);

				if(get_message.display_records.attachments.total_records > 0) {
					var i = 1;
					$.each(get_message.display_records.attachments.attachment_records, function(key, value){
						i++;

						attached_files = $('#attachments').val();
						if(attached_files != '') {
							attached_files = attached_files + ', ' + value.attachment_url;
						} else {
							attached_files = value.attachment_url;
						}
						$('#attachments').val(attached_files);
					});
				}
			}
		<?php
		}

		?>

		function display_from_address_select() {
			var account_info = account_info_function(user_info);
			$('#from_address').html('');
			if(account_info.status_code == '001') {
				$('#from_address').html("<option value=''>Select Account</option>");
				var i = 1;
				$.each(account_info.display_records, function(key, value) {
					if(value.active_status != 1 || value.verified_status != 1)
						return;

					if(user_info.id == value.user_id) {

						if(i == 1) {
							account_selected = 'selected';
						} else {
							account_selected = '';
						}
						$('#from_address').append(
							'<option '+account_selected+' value="'+value.id+'">'+value.account_email+'</option>'
						);
						i++;
					}
				});
			}
		}
		display_from_address_select();

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

					const editor = grapesjs.init({
						// Indicate where to init the editor. You can also pass an HTMLElement
						container: '#content',
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
						}
					}
				});
			}
		});
		
	</script>
</body>
</html>
