<?php include "head.php"; ?>

	<title>AdminLTE 3 | Starter</title>
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
	</style>
</head>
<body class="hold-transition sidebar-mini">
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
							<h1 class="m-0">Draft</h1>
						</div><!-- /.col -->
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
								<li class="breadcrumb-item active">Draft</li>
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
										<h5 class="card-heading d-inline">Draft</h5>
									</div>
									<div class="col-md-6">
										<select id="main_account_select" class="form-control form-control-border">
											<option>Select Account</option>
										</select>
									</div>
								</div>

								<div class="card-body p-0 dropdown" style="height: 400px; max-height: 100%; overflow-y: auto; position: relative;">
									<div class="mailbox-controls">
										<!-- Check all button -->
										<button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="bi bi-square"></i></button>
										<div class="btn-group">
											<button type="button" class="btn btn-default btn-sm bulk_add_trash_btn"><i class="fas fa-trash" title="Move to trash"></i></button>
										</div>
										<!-- /.btn-group -->
										<button type="button" class="btn btn-default btn-sm refresh_inbox"><i class="fas fa-sync-alt"></i></button>
										<div class="float-right">
											<span><span class="display_record"></span> / <span class="total_record"></span></span>
											<div class="btn-group">
												<button type="button" class="btn btn-default btn-sm previous_btn"><i class="fas fa-chevron-left"></i></button>
												<button type="button" class="btn btn-default btn-sm next_btn"><i class="fas fa-chevron-right"></i></button>
												<input type="hidden" value="1" id="current_page" name="">
											</div>
											<!-- /.btn-group -->
										</div>
										<!-- /.float-right -->
									</div>
									<div class="table-responsive mailbox-messages">
										<table class="table table-hover table-sm">
											<tbody id="display_draft_emails">
											</tbody>
										</table>
									</div>
									
									<!-- /.mail-box-messages -->
								</div>
								<!-- /.card-body -->

								<div class="card-footer p-0">
									<div class="mailbox-controls">
										<!-- Check all button -->
										<button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="bi bi-square"></i></button>
										<div class="btn-group">
											<button type="button" class="btn btn-default btn-sm bulk_add_trash_btn"><i class="fas fa-trash" title="Move to trash"></i></button>
										</div>
										<!-- /.btn-group -->
										<div class="float-right">
											<span><span class="display_record"></span> / <span class="total_record"></span></span>
										</div>
										<!-- /.float-right -->
									</div>
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

	<script type="text/javascript">
		$(document).ready(function(){

			$('#refresh_inbox').on('click', function(){
				if(user_info.user_role.inbox.sent == 1) {
					display_draft_emails($('#main_account_select').val());
				} else {
					$('#display_draft_emails').html("<tr><td colspan='7' class='text-center'>You're not allowed to send mails</td></tr>");
				}
			});

			function display_main_account_select() {
				var account_info = account_info_function(user_info);
				$('#main_account_select').html('');
				if(account_info.status_code == '001') {
					$('#main_account_select').html("<option value=''>Select Account</option>");
					var i = 1;
					$.each(account_info.display_records, function(key, value) {
						if(value.active_status != 1 || value.verified_status != 1)
							return;

						if(i == 1) {
							account_selected = 'selected';
						} else {
							account_selected = '';
						}
						$('#main_account_select').append(
							'<option '+account_selected+' value="'+value.id+'">'+value.account_email+'</option>'
						);
						i++;
					});
				}
			}
			if(user_info.user_role.inbox.sent == 1) {
				display_main_account_select();
			} else {
				$('#main_account_select').html("<option value=''>You're not allowed to read account</option>");
			}

			function refresh_draft() {

				var account_id = $('#main_account_select').val();

				$.ajax({
					url: api_url,
					type: 'POST',
					beforeSend: function() {
						$('#loader').show();
					},
					data: JSON.stringify({ 'action':'download_emails', 'account_id':account_id, 'user_id':user_info.id }),
					success: function(result) {
						if(user_info.user_role.inbox.sent == 1) {
							display_draft_emails(account_id);
						} else {
							$('#display_draft_emails').html("<tr><td colspan='7' class='text-center'>You're not allowed to send mails</td></tr>");
						}
					}
				});

			}

			$(document).on('click', '.add_trash_btn', function(){
				var id = $(this).data('id');
				var mailbox = 'trash';
				var method = 'add_trash';

				if(id != '' && id != 0 && mailbox != '' && method != '') {
					if(user_info.user_role.inbox.trash == 1) {
						$.ajax({
							url: api_url,
							type: 'POST',
							beforeSend: function() {
								$('#loader').show();
							},
							data: JSON.stringify({ 'action' : 'move_mailbox', 'method' : method, 'mailbox' : mailbox, id : id }),
							success: function(result) {
								if(result.status_code == '001') {
									if(user_info.user_role.inbox.sent == 1) {
										display_draft_emails($('#main_account_select').val());
									} else {
										$('#display_draft_emails').html("<tr><td colspan='7' class='text-center'>No Record Found</td></tr>");
									}
								} else {
									alert('Please Try Again');
								}
								$('#loader').hide();
							}
						});
					} else {
						alert("You're not allowed to delete mails");
					}
				}
			});

			$(document).on('click', '.bulk_add_trash_btn', function(){
				var mailbox = 'trash';
				var method = 'add_trash';
				var bulk_action_id = [];
				$('.mailbox-messages input[type=\'checkbox\']:checked').each(function() {
					bulk_action_id.push(this.value);
				});
				if(bulk_action_id.length != 0) {
					if(user_info.user_role.inbox.trash == 1) {
						$.ajax({
							url: api_url,
							type: 'POST',
							beforeSend: function() {
								$('#loader').show();
							},
							data: JSON.stringify({ 'action' : 'bulk_action_move_mailbox', 'method' : method, 'mailbox' : mailbox, id : bulk_action_id }),
							success: function(result) {
								if(result.status_code == '001') {
									if(user_info.user_role.inbox.sent == 1) {
										display_draft_emails($('#main_account_select').val());
									} else {
										$('#display_draft_emails').html("<tr><td colspan='7' class='text-center'>No Record Found</td></tr>");
									}
								} else {
									alert('Please Try Again');
								}
								$('#loader').hide();
							}
						});
					} else {
						alert("You're not allowed to delete mails")
					}
				}
			});

			$(document).on('click', '.next_btn', function(){
				var current_page = $('#current_page').val();
				++current_page;
				if(current_page <= account_draft.total_pages) {
					if(user_info.user_role.inbox.sent == 1) {
						display_draft_emails($('#main_account_select').val(), current_page);
					} else {
						$('#display_draft_emails').html("<tr><td colspan='7' class='text-center'>You're not allowed to send mails</td></tr>");
					}
					$('#current_page').val(current_page);
				}
			});

			$(document).on('click', '.previous_btn', function(){
				var current_page = $('#current_page').val();
				--current_page;
				if(current_page >= 1) {
					if(user_info.user_role.inbox.sent == 1) {
						display_draft_emails($('#main_account_select').val(), current_page);
					} else {
						$('#display_draft_emails').html("<tr><td colspan='7' class='text-center'>You're not allowed to send mails</td></tr>");
					}
					$('#current_page').val(current_page);
				}
			});

			$('#main_account_select').on('change', function(){
				if($('#main_account_select').val() != '') {
					if(user_info.user_role.inbox.sent == 1) {
						display_draft_emails($('#main_account_select').val());
					} else {
						$('#display_draft_emails').html("<tr><td colspan='7' class='text-center'>You're not allowed to send mails</td></tr>");
					}
				}
			});

			var account_draft_function = (account_id, page, per_page_record, q = '') => {
				var inbox;
				if(account_id != '') {
					if(q == '') {
						$.ajax({
							url: api_url,
							type: 'POST',
							async: false,
							beforeSend: function() {
								$('#loader').show();
							},
							data: JSON.stringify({ 'action' : 'get_inbox', 'mailbox' : 'drafts', 'account_id' : account_id, 'user_id' : user_info.id, 'page' : page, 'per_page_record' : per_page_record }),
							success: function(result) {
								inbox = result;
								$('#loader').hide();
							}
						});
					} else {
						$.ajax({
							url: api_url,
							type: 'POST',
							async: false,
							beforeSend: function() {
								$('#loader').show();
							},
							data: JSON.stringify({ 'action' : 'get_inbox', 'mailbox' : 'drafts', 'account_id' : account_id, 'user_id' : user_info.id, 'page' : page, 'per_page_record' : per_page_record, 'q':q }),
							success: function(result) {
								inbox = result;
								$('#loader').hide();
							}
						});
					}
				} else {
					inbox = {status:"error", status_code:"003"};
					$('#loader').hide();
				}
				return inbox;
			}

			function display_draft_emails(account_id, page = 1, per_page = per_page_record, q = '') {
				account_draft = account_draft_function(account_id, page, per_page, q);

				$('#display_inbox_emails').html('');
				if(account_draft.status_code == '001') {
					$('.total_record').html(account_draft.total_record);
					$('.display_record').html((account_draft.offset + 1) +'-'+ (Object.keys(account_draft.display_records).length + account_draft.offset));
					if(page == 1) {
						$('.previous_btn').addClass('disabled');
					} else {
						$('.previous_btn').removeClass('disabled');
					}
					if(page == account_draft.total_pages) {
						$('.next_btn').addClass('disabled');
					} else {
						$('.next_btn').removeClass('disabled');
					}
					$.each(account_draft.display_records, function(key, value) {

						$('#display_draft_emails').append(
							'<tr style="cursor: pointer;" class="'+ (value.seen_status == 1 ? "bg-light" : "bg-secondary") +'">' + 
								'<td><input type="checkbox" value="'+ value.id +'" name=""></td>' + 
								'<td style="font-size: 14px; min-width: 150px; max-width: 100%;"><a href="compose.php?message_id='+ value.id +'" class="'+ (value.seen_status == 1 ? "text-dark" : "text-light") +'">'+ (value.from_name != '' ? value.from_name.substr(0, 18) : value.from_address.substr(0, 18)) +'</a></td>' + 
								'<td class="col"><a href="compose.php?message_id='+ value.id +'" class="'+ (value.seen_status == 1 ? "text-dark" : "text-light") +'"><strong class="d-inline">'+ value.subject +'</strong></a></td>' + 
								'<td style="min-width: 100px; max-width: 100%"><small>'+ value.udate +'</small></td>' + 
								'<td>' + 
									'<div class="btn-group mr-1">' + 
										'<button class="btn btn-sm add_trash_btn" role="button" data-id="'+ value.id +'"><i class="fas fa-trash-alt" title="Move to Trash"></i></button>' + 
									'</div>' + 
								'</td>' + 
							'</tr>'
						);
					});
				} else {
					$('#display_draft_emails').html("<tr><td colspan='7' class='text-center'>No Record Found</td></tr>");
				}
			}
			if(user_info.user_role.inbox.sent == 1) {
				display_draft_emails($('#main_account_select').val());
			} else {
				$('#display_draft_emails').html("<tr><td colspan='7' class='text-center'>You're not allowed to send mails</td></tr>");
			}

			$('#search_inbox').on('keyup', function(){
				var q = $('#search_inbox').val();
				if(q != '') {
					display_draft_emails($('#main_account_select').val(), page = 1, per_page = per_page_record, q);
				}
			});
			
		});

		$(function () {
			//Enable check and uncheck all functionality
			$('.checkbox-toggle').click(function () {
				var clicks = $(this).data('clicks');
				if (clicks) {
					//Uncheck all checkboxes
					$('.mailbox-messages input[type=\'checkbox\']').prop('checked', false);
					$('.checkbox-toggle .bi.bi-check2-square').removeClass('bi-check2-square').addClass('bi-square');
				} else {
					//Check all checkboxes
					$('.mailbox-messages input[type=\'checkbox\']').prop('checked', true);
					$('.checkbox-toggle .bi.bi-square').removeClass('bi-square').addClass('bi-check2-square');
				}
				$(this).data('clicks', !clicks);
			});

			//Handle starring for font awesome
			$('.mailbox-star').click(function (e) {
				e.preventDefault();
				//detect type
				var $this = $(this).find('a > i');
				var fa    = $this.hasClass('fa');

				//Switch states
				if (fa) {
					$this.toggleClass('fa-star');
					$this.toggleClass('fa-star-o');
				}
			});
		});
	</script>
</body>
</html>
