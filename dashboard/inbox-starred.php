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
							<h1 class="m-0">Dashboard</h1>
						</div><!-- /.col -->
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="#">Dashboard</a></li>
								<li class="breadcrumb-item"><a href="#">Inbox</a></li>
								<li class="breadcrumb-item active">Starred</li>
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
										<h5 class="card-heading d-inline">Inbox</h5>
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
											<button type="button" class="btn btn-default btn-sm bulk_add_archive_btn"><i class="fas fa-archive" title="Add To archive"></i></button>
											<button type="button" class="btn btn-default btn-sm bulk_add_spam_btn"><i class="fas fa-info-circle" title="Add to spam"></i></button>
											<button type="button" class="btn btn-default btn-sm bulk_add_trash_btn"><i class="fas fa-trash" title="Move to trash"></i></button>
											<button type="button" class="btn btn-default btn-sm bulk_mark_read_btn"><i class="fas fa-envelope-open" title="Mark as read"></i></button>
											<button type="button" class="btn btn-default btn-sm bulk_add_important_btn"><i class="far fa-bookmark" title="Mark as important"></i></button>
											<button type="button" class="btn btn-default btn-sm bulk_add_starred_btn" title="Add star"><i class="far fa-star"></i></button>
										</div>
										<!-- /.btn-group -->
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
											<tbody id="display_inbox_emails">
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
											<button type="button" class="btn btn-default btn-sm bulk_add_archive_btn"><i class="fas fa-archive" title="Add To archive"></i></button>
											<button type="button" class="btn btn-default btn-sm bulk_add_spam_btn"><i class="fas fa-info-circle" title="Add to spam"></i></button>
											<button type="button" class="btn btn-default btn-sm bulk_add_trash_btn"><i class="fas fa-trash" title="Move to trash"></i></button>
											<button type="button" class="btn btn-default btn-sm bulk_mark_read_btn"><i class="fas fa-envelope-open" title="Mark as read"></i></button>
											<button type="button" class="btn btn-default btn-sm bulk_add_important_btn"><i class="far fa-bookmark" title="Mark as important"></i></button>
											<button type="button" class="btn btn-default btn-sm bulk_add_starred_btn" title="Add star"><i class="far fa-star"></i></button>
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

			if(user_info.user_role.inbox.trash == 0) {
				$('.add_trash_btn, .bulk_add_trash_btn').hide();
			}

			$('#refresh_inbox').on('click', function(){
				if(user_info.user_role.accounts.read == 1) {
					if(user_info.user_role.inbox.read == 1) {
						display_inbox_emails($('#main_account_select').val());
					} else {
						$('#display_inbox_emails').html("<tr><td colspan='7' class='text-center'>You're not allowed to read mails</td></tr>");
					}
				} else {
					$('#display_inbox_emails').html("<tr><td colspan='7' class='text-center'>You're not allowed to read mails</td></tr>");
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

			if(user_info.user_role.accounts.read == 1) {
				if(user_info.user_role.inbox.read == 1) {
					display_main_account_select();
				} else {
					$('#display_inbox_emails').html("<tr><td colspan='7' class='text-center'>You're not allowed to read mails</td></tr>");
				}
			} else {
				$('#display_inbox_emails').html("<tr><td colspan='7' class='text-center'>You're not allowed to read mails</td></tr>");
			}

			$(document).on('click', '.add_starred_btn', function(){
				var id = $(this).data('id');
				var method = 'add_starred';
				if(id != '' && id != 0) {
					$.ajax({
						url: api_url,
						type: 'POST',beforeSend: function() {
							$('#loader').show();
						},
						data: JSON.stringify({ 'action' : 'starred_label', 'method' : method, 'id' : id }),
						success: function(result) {
							if(result.status_code == '001') {
								if(user_info.user_role.accounts.read == 1) {
									if(user_info.user_role.inbox.read == 1) {
										display_inbox_emails($('#main_account_select').val());
									} else {
										$('#display_inbox_emails').html("<tr><td colspan='7' class='text-center'>You're not allowed to read mails</td></tr>");
									}
								} else {
									$('#display_inbox_emails').html("<tr><td colspan='7' class='text-center'>You're not allowed to read mails</td></tr>");
								}
							} else {
								alert('Please Try Again');
							}
							$('#loader').hide();
						}
					});
				}
			});

			$(document).on('click', '.remove_starred_btn', function(){
				var id = $(this).data('id');
				var method = 'remove_starred';
				if(id != '' && id != 0) {
					$.ajax({
						url: api_url,
						type: 'POST',beforeSend: function() {
							$('#loader').show();
						},
						data: JSON.stringify({ 'action' : 'starred_label', 'method' : method, 'id' : id }),
						success: function(result) {
							if(result.status_code == '001') {
								if(user_info.user_role.accounts.read == 1) {
									if(user_info.user_role.inbox.read == 1) {
										display_inbox_emails($('#main_account_select').val());
									} else {
										$('#display_inbox_emails').html("<tr><td colspan='7' class='text-center'>You're not allowed to read mails</td></tr>");
									}
								} else {
									$('#display_inbox_emails').html("<tr><td colspan='7' class='text-center'>You're not allowed to read mails</td></tr>");
								}
							} else {
								alert('Please Try Again');
							}
							$('#loader').hide();
						}
					});
				}
			});

			$(document).on('click', '.add_important_btn', function(){
				var id = $(this).data('id');
				var method = 'add_important';
				if(id != '' && id != 0) {
					$.ajax({
						url: api_url,
						type: 'POST',beforeSend: function() {
							$('#loader').show();
						},
						data: JSON.stringify({ 'action' : 'important_label', 'method' : method, 'id' : id }),
						success: function(result) {
							if(result.status_code == '001') {
								if(user_info.user_role.accounts.read == 1) {
									if(user_info.user_role.inbox.read == 1) {
										display_inbox_emails($('#main_account_select').val());
									} else {
										$('#display_inbox_emails').html("<tr><td colspan='7' class='text-center'>You're not allowed to read mails</td></tr>");
									}
								} else {
									$('#display_inbox_emails').html("<tr><td colspan='7' class='text-center'>You're not allowed to read mails</td></tr>");
								}
							} else {
								alert('Please Try Again');
							}
							$('#loader').hide();
						}
					});
				}
			});

			$(document).on('click', '.remove_important_btn', function(){
				var id = $(this).data('id');
				var method = 'remove_important';
				if(id != '' && id != 0) {
					$.ajax({
						url: api_url,
						type: 'POST',beforeSend: function() {
							$('#loader').show();
						},
						data: JSON.stringify({ 'action' : 'important_label', 'method' : method, 'id' : id }),
						success: function(result) {
							if(result.status_code == '001') {
								if(user_info.user_role.accounts.read == 1) {
									if(user_info.user_role.inbox.read == 1) {
										display_inbox_emails($('#main_account_select').val());
									} else {
										$('#display_inbox_emails').html("<tr><td colspan='7' class='text-center'>You're not allowed to read mails</td></tr>");
									}
								} else {
									$('#display_inbox_emails').html("<tr><td colspan='7' class='text-center'>You're not allowed to read mails</td></tr>");
								}
							} else {
								alert('Please Try Again');
							}
							$('#loader').hide();
						}
					});
				}
			});

			$(document).on('click', '.add_archive_btn', function(){
				var id = $(this).data('id');
				var mailbox = 'archive';
				var method = 'add_archive';

				if(id != '' && id != 0 && mailbox != '' && method != '') {
					$.ajax({
						url: api_url,
						type: 'POST',beforeSend: function() {
							$('#loader').show();
						},
						data: JSON.stringify({ 'action' : 'move_mailbox', 'method' : method, 'mailbox' : mailbox, id : id }),
						success: function(result) {
							if(result.status_code == '001') {
								if(user_info.user_role.accounts.read == 1) {
									if(user_info.user_role.inbox.read == 1) {
										display_inbox_emails($('#main_account_select').val());
									} else {
										$('#display_inbox_emails').html("<tr><td colspan='7' class='text-center'>You're not allowed to read mails</td></tr>");
									}
								} else {
									$('#display_inbox_emails').html("<tr><td colspan='7' class='text-center'>You're not allowed to read mails</td></tr>");
								}
							} else {
								alert('Please Try Again');
							}
							$('#loader').hide();
						}
					});
				}
			});

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
									if(user_info.user_role.accounts.read == 1) {
										if(user_info.user_role.inbox.read == 1) {
											display_inbox_emails($('#main_account_select').val());
										} else {
											$('#display_inbox_emails').html("<tr><td colspan='7' class='text-center'>You're not allowed to read mails</td></tr>");
										}
									} else {
										$('#display_inbox_emails').html("<tr><td colspan='7' class='text-center'>You're not allowed to read mails</td></tr>");
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

			$(document).on('click', '.add_read_btn, .add_unread_btn', function(){
				var id = $(this).data('id');
				var mailbox = 'read';
				var method = 'add_read';

				if(id != '' && id != 0 && mailbox != '' && method != '') {
					$.ajax({
						url: api_url,
						type: 'POST',
						beforeSend: function() {
							$('#loader').show();
						},
						data: JSON.stringify({ 'action' : 'move_mailbox', 'method' : method, 'mailbox' : mailbox, id : id }),
						success: function(result) {
							if(result.status_code == '001') {
								if(user_info.user_role.accounts.read == 1) {
									if(user_info.user_role.inbox.read == 1) {
										display_inbox_emails($('#main_account_select').val());
									} else {
										$('#display_inbox_emails').html("<tr><td colspan='7' class='text-center'>You're not allowed to read mails</td></tr>");
									}
								} else {
									$('#display_inbox_emails').html("<tr><td colspan='7' class='text-center'>You're not allowed to read mails</td></tr>");
								}
							} else {
								alert('Please Try Again');
							}
							$('#loader').hide();
						}
					});
				}
			});

			$(document).on('click', '.bulk_add_archive_btn', function(){
				var mailbox = 'archive';
				var method = 'add_archive';
				var bulk_action_id = [];
				$('.mailbox-messages input[type=\'checkbox\']:checked').each(function() {
					bulk_action_id.push(this.value);
				});
				if(bulk_action_id.length != 0) {
					$.ajax({
						url: api_url,
						type: 'POST',
						beforeSend: function() {
							$('#loader').show();
						},
						data: JSON.stringify({ 'action' : 'bulk_action_move_mailbox', 'method' : method, 'mailbox' : mailbox, id : bulk_action_id }),
						success: function(result) {
							if(result.status_code == '001') {
								if(user_info.user_role.accounts.read == 1) {
									if(user_info.user_role.inbox.read == 1) {
										display_inbox_emails($('#main_account_select').val());
									} else {
										$('#display_inbox_emails').html("<tr><td colspan='7' class='text-center'>You're not allowed to read mails</td></tr>");
									}
								} else {
									$('#display_inbox_emails').html("<tr><td colspan='7' class='text-center'>You're not allowed to read mails</td></tr>");
								}
							} else {
								alert('Please Try Again');
							}
							$('#loader').hide();
						}
					});
				}
			});

			$(document).on('click', '.bulk_add_spam_btn', function(){
				var mailbox = 'spam';
				var method = 'add_spam';
				var bulk_action_id = [];
				$('.mailbox-messages input[type=\'checkbox\']:checked').each(function() {
					bulk_action_id.push(this.value);
				});
				if(bulk_action_id.length != 0) {
					$.ajax({
						url: api_url,
						type: 'POST',
						beforeSend: function() {
							$('#loader').show();
						},
						data: JSON.stringify({ 'action' : 'bulk_action_move_mailbox', 'method' : method, 'mailbox' : mailbox, id : bulk_action_id }),
						success: function(result) {
							if(result.status_code == '001') {
								if(user_info.user_role.accounts.read == 1) {
									if(user_info.user_role.inbox.read == 1) {
										display_inbox_emails($('#main_account_select').val());
									} else {
										$('#display_inbox_emails').html("<tr><td colspan='7' class='text-center'>You're not allowed to read mails</td></tr>");
									}
								} else {
									$('#display_inbox_emails').html("<tr><td colspan='7' class='text-center'>You're not allowed to read mails</td></tr>");
								}
							} else {
								alert('Please Try Again');
							}
							$('#loader').hide();
						}
					});
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
									if(user_info.user_role.accounts.read == 1) {
										if(user_info.user_role.inbox.read == 1) {
											display_inbox_emails($('#main_account_select').val());
										} else {
											$('#display_inbox_emails').html("<tr><td colspan='7' class='text-center'>You're not allowed to read mails</td></tr>");
										}
									} else {
										$('#display_inbox_emails').html("<tr><td colspan='7' class='text-center'>You're not allowed to read mails</td></tr>");
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

			$(document).on('click', '.bulk_mark_read_btn', function(){
				var mailbox = 'read';
				var method = 'add_read';
				var bulk_action_id = [];
				$('.mailbox-messages input[type=\'checkbox\']:checked').each(function() {
					bulk_action_id.push(this.value);
				});
				if(bulk_action_id.length != 0) {
					$.ajax({
						url: api_url,
						type: 'POST',
						beforeSend: function() {
							$('#loader').show();
						},
						data: JSON.stringify({ 'action' : 'bulk_action_move_mailbox', 'method' : method, 'mailbox' : mailbox, id : bulk_action_id }),
						success: function(result) {
							if(result.status_code == '001') {
								if(user_info.user_role.accounts.read == 1) {
									if(user_info.user_role.inbox.read == 1) {
										display_inbox_emails($('#main_account_select').val());
									} else {
										$('#display_inbox_emails').html("<tr><td colspan='7' class='text-center'>You're not allowed to read mails</td></tr>");
									}
								} else {
									$('#display_inbox_emails').html("<tr><td colspan='7' class='text-center'>You're not allowed to read mails</td></tr>");
								}
							} else {
								alert('Please Try Again');
							}
							$('#loader').hide();
						}
					});
				}
			});

			$(document).on('click', '.bulk_add_important_btn', function(){
				var mailbox = 'inbox';
				var method = 'add_important';
				var bulk_action_id = [];
				$('.mailbox-messages input[type=\'checkbox\']:checked').each(function() {
					bulk_action_id.push(this.value);
				});
				if(bulk_action_id.length != 0) {
					$.ajax({
						url: api_url,
						type: 'POST',
						beforeSend: function() {
							$('#loader').show();
						},
						data: JSON.stringify({ 'action' : 'bulk_action_move_mailbox', 'method' : method, 'mailbox' : mailbox, id : bulk_action_id }),
						success: function(result) {
							if(result.status_code == '001') {
								if(user_info.user_role.accounts.read == 1) {
									if(user_info.user_role.inbox.read == 1) {
										display_inbox_emails($('#main_account_select').val());
									} else {
										$('#display_inbox_emails').html("<tr><td colspan='7' class='text-center'>You're not allowed to read mails</td></tr>");
									}
								} else {
									$('#display_inbox_emails').html("<tr><td colspan='7' class='text-center'>You're not allowed to read mails</td></tr>");
								}
							} else {
								alert('Please Try Again');
							}
							$('#loader').hide();
						}
					});
				}
			});

			$(document).on('click', '.bulk_add_starred_btn', function(){
				var mailbox = 'inbox';
				var method = 'add_starred';
				var bulk_action_id = [];
				$('.mailbox-messages input[type=\'checkbox\']:checked').each(function() {
					bulk_action_id.push(this.value);
				});
				if(bulk_action_id.length != 0) {
					$.ajax({
						url: api_url,
						type: 'POST',
						beforeSend: function() {
							$('#loader').show();
						},
						data: JSON.stringify({ 'action' : 'bulk_action_move_mailbox', 'method' : method, 'mailbox' : mailbox, id : bulk_action_id }),
						success: function(result) {
							if(result.status_code == '001') {
								if(user_info.user_role.accounts.read == 1) {
									if(user_info.user_role.inbox.read == 1) {
										display_inbox_emails($('#main_account_select').val());
									} else {
										$('#display_inbox_emails').html("<tr><td colspan='7' class='text-center'>You're not allowed to read mails</td></tr>");
									}
								} else {
									$('#display_inbox_emails').html("<tr><td colspan='7' class='text-center'>You're not allowed to read mails</td></tr>");
								}
							} else {
								alert('Please Try Again');
							}
							$('#loader').hide();
						}
					});
				}
			});

			$(document).on('click', '.next_btn', function(){
				var current_page = $('#current_page').val();
				++current_page;
				if(current_page <= account_inbox.total_pages) {
					if(user_info.user_role.accounts.read == 1) {
						if(user_info.user_role.inbox.read == 1) {
							display_inbox_emails($('#main_account_select').val(), current_page);
						} else {
							$('#display_inbox_emails').html("<tr><td colspan='7' class='text-center'>You're not allowed to read mails</td></tr>");
						}
					} else {
						$('#display_inbox_emails').html("<tr><td colspan='7' class='text-center'>You're not allowed to read mails</td></tr>");
					}
					$('#current_page').val(current_page);
				}
			});

			$(document).on('click', '.previous_btn', function(){
				var current_page = $('#current_page').val();
				--current_page;
				if(current_page >= 1) {
					if(user_info.user_role.accounts.read == 1) {
						if(user_info.user_role.inbox.read == 1) {
							display_inbox_emails($('#main_account_select').val(), current_page);
						} else {
							$('#display_inbox_emails').html("<tr><td colspan='7' class='text-center'>You're not allowed to read mails</td></tr>");
						}
					} else {
						$('#display_inbox_emails').html("<tr><td colspan='7' class='text-center'>You're not allowed to read mails</td></tr>");
					}
					$('#current_page').val(current_page);
				}
			});

			$('#main_account_select').on('change', function(){
				if($('#main_account_select').val() != '') {
					if(user_info.user_role.accounts.read == 1) {
						if(user_info.user_role.inbox.read == 1) {
							display_inbox_emails($('#main_account_select').val());
						} else {
							$('#display_inbox_emails').html("<tr><td colspan='7' class='text-center'>You're not allowed to read mails</td></tr>");
						}
					} else {
						$('#display_inbox_emails').html("<tr><td colspan='7' class='text-center'>You're not allowed to read mails</td></tr>");
					}
				}
			});

			var account_inbox_function = (account_id, page, per_page_record, q = '') => {
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
							data: JSON.stringify({ 'action' : 'get_starred', 'mailbox' : 'inbox', 'account_id' : account_id, 'user_id' : user_info.id, 'page' : page, 'per_page_record' : per_page_record }),
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
							data: JSON.stringify({ 'action' : 'get_starred', 'mailbox' : 'inbox', 'account_id' : account_id, 'user_id' : user_info.id, 'page' : page, 'per_page_record' : per_page_record, 'q':q }),
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

			function display_inbox_emails(account_id, page = 1, per_page = per_page_record, q = '') {
				account_inbox = account_inbox_function(account_id, page, per_page, q);

				$('#display_inbox_emails').html('');
				if(account_inbox.status_code == '001') {
					$('.total_record').html(account_inbox.total_record);
					$('.display_record').html((account_inbox.offset + 1) +'-'+ (Object.keys(account_inbox.display_records).length + account_inbox.offset));
					if(page == 1) {
						$('.previous_btn').addClass('disabled');
					} else {
						$('.previous_btn').removeClass('disabled');
					}
					if(page == account_inbox.total_pages) {
						$('.next_btn').addClass('disabled');
					} else {
						$('.next_btn').removeClass('disabled');
					}
					$.each(account_inbox.display_records, function(key, value) {

						$('#display_inbox_emails').append(
							'<tr style="cursor: pointer;" class="'+ (value.seen_status == 1 ? "bg-light" : "bg-secondary") +'">' + 
								'<td><input type="checkbox" value="'+ value.id +'" name=""></td>' + 
								'<td><button data-id="'+ value.id +'" class="btn p-0 '+ (value.is_starred == 1 ? "remove_starred_btn" : "add_starred_btn") +'"><i class="bi '+ (value.is_starred == 1 ? "bi-star-fill text-warning" : "bi-star") +'"></i></button</td>' + 
								'<td><button data-id="'+ value.id +'" class="btn p-0 '+ (value.is_important == 1 ? "remove_important_btn" : "add_important_btn") +'"><i class="bi '+ (value.is_important == 1 ? "bi-bookmark-fill text-warning" : "bi-bookmark") +'"></i></button></td>' + 
								'<td style="font-size: 14px; min-width: 150px; max-width: 100%;"><a href="message.php?id='+ value.id +'" class="'+ (value.seen_status == 1 ? "text-dark" : "text-light") +'">'+ (value.from_name != '' ? value.from_name.substr(0, 18) : value.from_address.substr(0, 18)) +'</a></td>' + 
								'<td class="col"><a href="message.php?id='+ value.id +'" class="'+ (value.seen_status == 1 ? "text-dark" : "text-light") +'"><strong class="d-inline">'+ value.subject +'</strong></a></td>' + 
								'<td style="min-width: 100px; max-width: 100%"><small>'+ value.udate +'</small></td>' + 
								'<td>' + 
									'<div class="btn-group mr-1">' + 
										'<button class="btn btn-sm add_archive_btn" role="button" data-id="'+ value.id +'"><i class="fas fa-archive" title="Add to Archive"></i></button>' + 
										(user_info.user_role.inbox.trash == 1 ? '<button class="btn btn-sm add_trash_btn" role="button" data-id="'+ value.id +'"><i class="fas fa-trash-alt" title="Move to Trash"></i></button>' : '') + 
										'<button class="btn btn-sm '+ (value.seen_status == 1 ? "add_unread_btn" : "add_read_btn") +'" role="button" data-id="'+ value.id +'"><i class="far '+ (value.seen_status == 1 ? "fa-envelope" : "fa-envelope-open") +'" title="'+ (value.seen_status == 1 ? "Mark as unread" : "Mark as read") +'"></i></button>' + 
									'</div>' + 
								'</td>' + 
							'</tr>'
						);
					});
				} else {
					$('#display_inbox_emails').html("<tr><td colspan='7' class='text-center'>No Record Found</td></tr>");
				}
			}
			if(user_info.user_role.accounts.read == 1) {
				if(user_info.user_role.inbox.read == 1) {
					display_inbox_emails($('#main_account_select').val());
				} else {
					$('#display_inbox_emails').html("<tr><td colspan='7' class='text-center'>You're not allowed to read mails</td></tr>");
				}
			} else {
				$('#display_inbox_emails').html("<tr><td colspan='7' class='text-center'>You're not allowed to read mails</td></tr>");
			}

			$('#search_inbox').on('keyup', function(){
				var q = $('#search_inbox').val();
				if(q != '') {
					display_inbox_emails($('#main_account_select').val(), page = 1, per_page = per_page_record, q);
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
