<?php include "head.php"; ?>

	<title>AdminLTE 3 | Starter</title>
	<style type="text/css">
		#content {
			width: 100%;
		}
		#content * {
			word-break: break-all !important;
		}
	</style>
</head>
<body class="hold-transition sidebar-mini">
	<div class="wrapper">

		<?php include "nav.php"; ?>

		<?php include "sidebar.php"; ?>

		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<div class="content-header">
				<div class="container-fluid">
					<div class="row mb-2">
						<div class="col-sm-6">
							<h1 class="m-0">Inbox</h1>
						</div><!-- /.col -->
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
								<li class="breadcrumb-item"><a href="accounts.php">Inbox</a></li>
								<li class="breadcrumb-item active">Read Message</li>
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
							<div class="card card-primary card-outline">
								<div class="card-header">
									<h3 class="card-title"></h3>
									<a href="index.php" class="btn btn-tool"><i class="fas fa-arrow-left mr-2"></i>Back</a>
									<div class="card-tools">
										<!-- <a href="#" class="btn btn-tool" title="Previous"><i class="fas fa-chevron-left"></i></a> -->
										<!-- <a href="#" class="btn btn-tool" title="Next"><i class="fas fa-chevron-right"></i></a> -->
									</div>
								</div>
								<!-- /.card-header -->
								<div class="card-body p-0">
									<div class="mailbox-read-info">
										<h5 id="subject"></h5>
										<h6>From: <span id="from_address"></span>
											<span class="mailbox-read-time float-right" id="udate"></span></h6>
										</div>
										<!-- /.mailbox-read-info -->
										<!-- <div class="mailbox-controls with-border text-center"> -->
											<!-- <div class="btn-group"> -->
												<!-- <button type="button" class="btn btn-default btn-sm add_trash_btn" data-container="body" title="Delete"><i class="far fa-trash-alt"></i></button> -->
												<!-- <button type="button" class="btn btn-default btn-sm add_reply_btn" data-container="body" title="Reply"><i class="fas fa-reply"></i></button> -->
												<!-- <button type="button" class="btn btn-default btn-sm forward_btn" data-container="body" title="Forward"><i class="fas fa-share"></i></button> -->
											<!-- </div> -->
											<!-- /.btn-group -->
											<!-- <button type="button" class="btn btn-default btn-sm" title="Print"><i class="fas fa-print"></i></button> -->
										<!-- </div> -->
										<!-- /.mailbox-controls -->
										<div class="mailbox-read-message" id="content">
										</div>
										<!-- /.mailbox-read-message -->
									</div>
								</div>
								<!-- /.card-body -->
								<div class="card-footer bg-white">
									<ul class="mailbox-attachments d-flex align-items-stretch clearfix" id="attachments">
									</ul>
								</div>
								<!-- /.card-footer -->
								<div class="card-footer">
									<div class="float-right">
										<!-- <button type="button" class="btn btn-default add_reply_btn"><i class="fas fa-reply"></i> Reply</button> -->
										<button type="button" class="btn btn-default forward_btn forward_btn"><i class="fas fa-share"></i> Forward</button>
									</div>
									<!-- <button type="button" class="btn btn-default add_trash_btn"><i class="far fa-trash-alt"></i> Delete</button> -->
									<!-- <button type="button" class="btn btn-default"><i class="fas fa-print"></i> Print</button> -->
								</div>
								<!-- /.card-footer -->
							</div>
							<!-- /.card -->
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

			if(user_info.user_role.inbox.read == 0) {
				window.top.location = dashboard_url;
			}

			if(user_info.user_role.inbox.sent == 0) {
				$('.add_reply_btn, .forward_btn').hide();
			}
			if(user_info.user_role.inbox.trash == 0) {
				$('.add_trash_btn').hide();
			}

			var get_message_function = (user_info) => {
				var tmp;
				$.ajax({
					url: api_url,
					type: 'POST',
					async: false,
					data: JSON.stringify({ 'action' : 'get_message', 'id' : '<?php echo strip_tags($_GET['id']); ?>', 'user_id' : user_info.id }),
					success: function(result) {
						tmp = result;
					}
				});
				return tmp;
			}
			get_message = get_message_function(user_info);

			$(document).on('click', '.forward_btn', function(){
				window.top.location = dashboard_url+'compose.php?action=forward&message_id=<?php echo strip_tags($_GET['id']); ?>';
			});

			if(get_message.status_code == '001') {
				$('#subject').html(get_message.display_records.subject);
				$('#from_address').html(get_message.display_records.from_address);
				$('#udate').html(get_message.display_records.udate);
				$('#content').html(get_message.display_records.content);
				if(get_message.display_records.attachments.total_records > 0) {
					var i = 1;
					$.each(get_message.display_records.attachments.attachment_records, function(key, value){
						i++;

						$('#attachments').append(
							'<li>' + 
								'<span class="mailbox-attachment-icon">'+ attachment_file(value.attachment_url, value.attachment_type) +'</span>' + 

								'<div class="mailbox-attachment-info">' + 
									'<a download style="word-break: break-all;" href="'+ value.attachment_url +'" class="mailbox-attachment-name"><i class="fas fa-paperclip mr-2"></i>'+ value.attachment_name +'</a>' + 
									'<span class="mailbox-attachment-size clearfix mt-1">' + 
										'<span>'+ value.attachment_size +'</span>' + 
										'<a download href="'+ value.attachment_url +'" class="btn btn-default btn-sm float-right"><i class="fas fa-cloud-download-alt"></i></a>' + 
									'</span>' + 
								'</div>' + 
							'</li>'
						);
					});
				}
			}

		});
	</script>
</body>
</html>
