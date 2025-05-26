<!-- Main Sidebar Container -->
		<aside class="main-sidebar sidebar-light-primary elevation-1">
			<!-- Brand Logo -->
			<a href="index3.html" class="brand-link">
				<img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
				<span class="brand-text font-weight-light">Email Marketing</span>
			</a>

			<!-- Sidebar -->
			<div class="sidebar">
				<!-- Sidebar user panel (optional) -->
				<div class="user-panel mt-3 pb-3 mb-3 d-flex">
					<div class="image">
						<img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
					</div>
					<div class="info">
						<a href="#" class="d-block" id="sidebar_display_name"></a>
					</div>
				</div>

				<!-- Sidebar Menu -->
				<nav class="mt-2">
					<ul class="nav nav-pills nav-sidebar flex-column nav-legacy nav-compact nav-child-indent nav-flat" data-widget="treeview" role="menu" data-accordion="false">
						<!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
						<li class="nav-item py-2 btn-dark" id="compose_btn">
							<a href="compose.php" class="nav-link text-white">
								<i class="nav-icon bi bi-pen"></i>
								<p>Compose</p>
							</a>
						</li>
						<li class="nav-item" id="sidebar_inbox_btn">
							<a href="index.php" class="nav-link">
								<i class="bi bi-inbox-fill nav-icon"></i>
								<p>Inbox<i class="right fas fa-angle-left"></i></p>
							</a>
							<ul class="nav nav-treeview">
								<li class="nav-item" id="sidebar_read_inbox_btn">
									<a href="index.php" class="nav-link">
										<i class="bi bi-inbox nav-icon"></i>
										<p>All Mails</p>
									</a>
								</li>
								<li class="nav-item" id="sidebar_starred_inbox_btn">
									<a href="inbox-starred.php" class="nav-link">
										<i class="bi bi-star nav-icon"></i>
										<p>Starred</p>
									</a>
								</li>
								<li class="nav-item" id="sidebar_sent_inbox_btn">
									<a href="sent.php" class="nav-link">
										<i class="bi bi-send nav-icon"></i>
										<p>Sent</p>
									</a>
								</li>
								<li class="nav-item" id="sidebar_draft_inbox_btn">
									<a href="draft.php" class="nav-link">
										<i class="bi bi-file-earmark nav-icon"></i>
										<p>Drafts</p>
									</a>
								</li>
								<li class="nav-item" id="sidebar_important_inbox_btn">
									<a href="inbox-important.php" class="nav-link">
										<i class="bi bi-tag nav-icon"></i>
										<p>Important</p>
									</a>
								</li>
								<li class="nav-item" id="sidebar_scheduled_inbox_btn">
									<a href="inbox-schedule.php" class="nav-link">
										<i class="bi bi-alarm nav-icon"></i>
										<p>Scheduled</p>
									</a>
								</li>
								<li class="nav-item" id="sidebar_spam_inbox_btn">
									<a href="inbox-spam.php" class="nav-link">
										<i class="bi bi-info-circle nav-icon"></i>
										<p>Spam</p>
									</a>
								</li>
								<li class="nav-item" id="sidebar_bin_inbox_btn">
									<a href="inbox-trash.php" class="nav-link">
										<i class="bi bi-trash nav-icon"></i>
										<p>Bin</p>
									</a>
								</li>
							</ul>
						</li>
						<li class="nav-item" id="sidebar_read_contact_list_btn">
							<a href="#" class="nav-link">
								<i class="nav-icon bi bi-person-badge-fill"></i>
								<p>Contact Lists<i class="right fas fa-angle-left"></i></p>
							</a>
							<ul class="nav nav-treeview">
								<li class="nav-item" id="sidebar_contact_list_btn">
									<a href="contacts.php" class="nav-link">
										<i class="bi bi-person-badge nav-icon"></i>
										<p>All Contact Lists</p>
									</a>
								</li>
								<li class="nav-item" id="sidebar_add_contact_list_btn">
									<a href="contact-new.php" class="nav-link">
										<i class="bi bi-plus-circle nav-icon"></i>
										<p>Add New</p>
									</a>
								</li>
								<li class="nav-item" id="sidebar_contact_email_list_btn">
									<a href="contact-emails.php" class="nav-link active">
										<i class="bi bi-envelope nav-icon"></i>
										<p>Contact Emails</p>
									</a>
								</li>
							</ul>
						</li>
						<li class="nav-item" id="sidebar_read_campaign_btn">
							<a href="#" class="nav-link">
								<i class="nav-icon bi bi-megaphone-fill"></i>
								<p>Campaigns<i class="right fas fa-angle-left"></i></p>
							</a>
							<ul class="nav nav-treeview">
								<li class="nav-item" id="sidebar_campaign_btn">
									<a href="campaigns.php" class="nav-link">
										<i class="bi bi-megaphone nav-icon"></i>
										<p>All Campaigns</p>
									</a>
								</li>
								<li class="nav-item" id="sidebar_add_campaign_btn">
									<a href="campaign-new.php" class="nav-link">
										<i class="bi bi-plus-circle nav-icon"></i>
										<p>Add New</p>
									</a>
								</li>
								<li class="nav-item" id="sidebar_scheduled_campaign_btn">
									<a href="campaign-schedule.php" class="nav-link">
										<i class="bi bi-alarm nav-icon"></i>
										<p>Scheduled Campaigns</p>
									</a>
								</li>
								<li class="nav-item" id="sidebar_analysis_campaign_btn">
									<a href="campaign-analysis.php" class="nav-link">
										<i class="bi bi-bar-chart nav-icon"></i>
										<p>Analysis</p>
									</a>
								</li>
							</ul>
						</li>
						<li class="nav-item" id="sidebar_read_template_btn">
							<a href="#" class="nav-link">
								<i class="nav-icon bi bi-grid-1x2-fill"></i>
								<p>Templates<i class="right fas fa-angle-left"></i></p>
							</a>
							<ul class="nav nav-treeview">
								<li class="nav-item" id="sidebar_template_btn">
									<a href="templates.php" class="nav-link">
										<i class="bi bi-grid-1x2 nav-icon"></i>
										<p>All Templates</p>
									</a>
								</li>
								<li class="nav-item" id="sidebar_add_template_btn">
									<a href="template-new.php" class="nav-link">
										<i class="bi bi-plus-circle nav-icon"></i>
										<p>Add New</p>
									</a>
								</li>
							</ul>
						</li>
						<li class="nav-item" id="sidebar_account_btn">
							<a href="#" class="nav-link">
								<i class="nav-icon bi bi-person-badge-fill"></i>
								<p>Accounts<i class="right fas fa-angle-left"></i></p>
							</a>
							<ul class="nav nav-treeview">
								<li class="nav-item" id="sidebar_read_account_btn">
									<a href="accounts.php" class="nav-link">
										<i class="bi bi-person-badge nav-icon"></i>
										<p>All Accounts</p>
									</a>
								</li>
								<li class="nav-item" id="sidebar_add_account_btn">
									<a href="account-new.php" class="nav-link">
										<i class="bi bi-plus-circle nav-icon"></i>
										<p>Add New</p>
									</a>
								</li>
							</ul>
						</li>
						<li class="nav-item" id="sidebar_read_staff_btn">
							<a href="#" class="nav-link">
								<i class="nav-icon bi bi-people-fill"></i>
								<p>Staff<i class="right fas fa-angle-left"></i></p>
							</a>
							<ul class="nav nav-treeview">
								<li class="nav-item" id="sidebar_staff_btn">
									<a href="staffs.php" class="nav-link">
										<i class="bi bi-people nav-icon"></i>
										<p>All Staff</p>
									</a>
								</li>
								<li class="nav-item" id="sidebar_add_staff_btn">
									<a href="staff-new.php" class="nav-link">
										<i class="bi bi-plus-circle nav-icon"></i>
										<p>Add New</p>
									</a>
								</li>
							</ul>
						</li>
						<li class="nav-item">
							<a href="setting.php" class="nav-link">
								<i class="nav-icon bi bi-gear"></i>
								<p>Settings</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="logout.php" class="nav-link">
								<i class="nav-icon bi bi-box-arrow-right"></i>
								<p>Signout</p>
							</a>
						</li>
					</ul>
				</nav>
				<!-- /.sidebar-menu -->
			</div>
			<!-- /.sidebar -->
		</aside>