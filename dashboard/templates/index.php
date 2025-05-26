<!doctype html>
<html lang="en">
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
		<script type="text/javascript" src="https://kit.fontawesome.com/d35f256856.js"></script>

		<style type="text/css">
			
			#content {
				border: 1px solid #000;
				min-height: 100px;
				width: 100%;
				position: relative;
			}
			.button-flex-container {
				display: flex;
				align-items: center;
				padding: 10px 0;
			}
			.button-flex-container .divider {
				flex: 1;
/*				height: 1px;*/
/*				background-color: #ff0000; /* Change color of divider line */*/
			}

			#sidebar {
				height: 90vh;
				overflow-y: auto;
			}

			.add_element_btn {
				height: 100px;
				background-repeat: no-repeat;
				float: left;
				margin: 0% 1% 2% 1%;
				text-align: center;
				font-weight: normal;
				font-size: 11px;
				color: #000;
				padding-top: 60px;
				padding-bottom: 7px;
				border: 1px solid #e5e5e5;
				border-radius: 3px;
				background-position: 50% 30%;
				background-size: auto 42px;
				z-index: 100;
				cursor: grab;
				-webkit-box-shadow: 0px 1px 4px 0px rgb(0 0 0 / 5%);
				background-color: #fff;
			}

		</style>
	</head>
	<body>

		<div class="container-fluid">
			
			<ul class="nav bg-light py-2 justify-content-center">
				<li class="nav-item"><a class="nav-link text-dark" href="#"><i class="fas fa-undo"></i></a></li>
				<li class="nav-item"><a class="nav-link text-dark" href="#"><i class="fas fa-redo"></i></a></li>
				<li class="nav-item"><a class="nav-link text-dark" href="#"><i class="fas fa-eye"></i></a></li>
				<li class="nav-item"><a class="nav-link text-dark" href="#"><i class="fas fa-expand-arrows-alt"></i></a></li>
			</ul>

			<div class="row">

				<div class="col-md-9">

					<style type="text/css">
						.mail-row, .edit-row {
							display: flex;
						}
						.edit-row, .edit-col {
							border: 1px dashed grey;
							padding: 10px;
						}
						.edit-col {
							width: 100%;
						}
					</style>

					<div id="content"></div>

				</div>

				<div class="col-md-3">
					<div id="sidebar" class="card card-body">
					</div>
				</div>
			</div>

		</div>

		<!-- Optional JavaScript; choose one of the two! -->
		<script type="text/javascript" src="https://code.jquery.com/jquery-3.6.1.min.js"></script>

		<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

		<script type="text/javascript">

			$(document).ready(function(){

				function get_sidebar(place) {
					$('#sidebar').html(
						'<div class="row">' + 
							
							'<div class="col-6 mb-3">' + 
								'<button data-section="grid_row" data-place="'+ place +'" style="background-image: url(\'libs/builder/icons/grid_row.svg\');" class="btn btn-block card card-body text-center add_element_btn">' + 
									'<small class="m-auto">Grid Row</small>' + 
								'</button>' + 
							'</div>' + 

							'<div class="col-6 mb-3">' + 
								'<button data-section="container" data-place="'+ place +'" style="background-image: url(\'libs/builder/icons/container.svg\');" class="btn btn-block card card-body text-center add_element_btn">' + 
									'<small class="m-auto">Container</small>' + 
								'</button>' + 
							'</div>' + 

							'<div class="col-6 mb-3">' + 
								'<button data-section="button" data-place="'+ place +'" style="background-image: url(\'libs/builder/icons/button.svg\');" class="btn btn-block card card-body text-center add_element_btn">' + 
									'<small class="m-auto">Button</small>' + 
								'</button>' + 
							'</div>' + 

							'<div class="col-6 mb-3">' + 
								'<button data-section="button_group" data-place="'+ place +'" style="background-image: url(\'libs/builder/icons/button_group.svg\');" class="btn btn-block card card-body text-center add_element_btn">' + 
									'<small class="m-auto">Button Group</small>' + 
								'</button>' + 
							'</div>' + 

							'<div class="col-6 mb-3">' + 
								'<button data-section="button_toolbar" data-place="'+ place +'" style="background-image: url(\'libs/builder/icons/button_toolbar.svg\');" class="btn btn-block card card-body text-center add_element_btn">' + 
									'<small class="m-auto">Button Toolbar</small>' + 
								'</button>' + 
							'</div>' + 

							'<div class="col-6 mb-3">' + 
								'<button data-section="heading" data-place="'+ place +'" style="background-image: url(\'libs/builder/icons/heading.svg\');" class="btn btn-block card card-body text-center add_element_btn">' + 
									'<small class="m-auto">Heading</small>' + 
								'</button>' + 
							'</div>' + 

							'<div class="col-6 mb-3">' + 
								'<button data-section="image" data-place="'+ place +'" style="background-image: url(\'libs/builder/icons/image.svg\');" class="btn btn-block card card-body text-center add_element_btn">' + 
									'<small class="m-auto">Image</small>' + 
								'</button>' + 
							'</div>' + 

							'<div class="col-6 mb-3">' + 
								'<button data-section="alert" data-place="'+ place +'" style="background-image: url(\'libs/builder/icons/alert.svg\');" class="btn btn-block card card-body text-center add_element_btn">' + 
									'<small class="m-auto">Alert</small>' + 
								'</button>' + 
							'</div>' + 

							'<div class="col-6 mb-3">' + 
								'<button data-section="card" data-place="'+ place +'" style="background-image: url(\'libs/builder/icons/panel.svg\');" class="btn btn-block card card-body text-center add_element_btn">' + 
									'<small class="m-auto">Card</small>' + 
								'</button>' + 
							'</div>' + 

							'<div class="col-6 mb-3">' + 
								'<button data-section="list_group" data-place="'+ place +'" style="background-image: url(\'libs/builder/icons/list_group.svg\');" class="btn btn-block card card-body text-center add_element_btn">' + 
									'<small class="m-auto">List Group</small>' + 
								'</button>' + 
							'</div>' + 

							'<div class="col-6 mb-3">' + 
								'<button data-section="horizontal_rule" data-place="'+ place +'" style="background-image: url(\'libs/builder/icons/hr.svg\');" class="btn btn-block card card-body text-center add_element_btn">' + 
									'<small class="m-auto">Horizontal Rule</small>' + 
								'</button>' + 
							'</div>' + 

							'<div class="col-6 mb-3">' + 
								'<button data-section="badge" data-place="'+ place +'" style="background-image: url(\'libs/builder/icons/badge.svg\');" class="btn btn-block card card-body text-center add_element_btn">' + 
									'<small class="m-auto">Badge</small>' + 
								'</button>' + 
							'</div>' + 

							'<div class="col-6 mb-3">' + 
								'<button data-section="button_group" data-place="'+ place +'" style="background-image: url(\'libs/builder/icons/button_group.svg\');" class="btn btn-block card card-body text-center add_element_btn">' + 
									'<small class="m-auto">Button Group</small>' + 
								'</button>' + 
							'</div>' + 

							'<div class="col-6 mb-3">' + 
								'<button data-section="table" data-place="'+ place +'" style="background-image: url(\'libs/builder/icons/table.svg\');" class="btn btn-block card card-body text-center add_element_btn">' + 
									'<small class="m-auto">Table</small>' + 
								'</button>' + 
							'</div>' + 

							'<div class="col-6 mb-3">' + 
								'<button data-section="paragraph" data-place="'+ place +'" style="background-image: url(\'libs/builder/icons/paragraph.svg\');" class="btn btn-block card card-body text-center add_element_btn">' + 
									'<small class="m-auto">Paragraph</small>' + 
								'</button>' + 
							'</div>' + 

							'<div class="col-6 mb-3">' + 
								'<button data-section="link" data-place="'+ place +'" style="background-image: url(\'libs/builder/icons/link.svg\');" class="btn btn-block card card-body text-center add_element_btn">' + 
									'<small class="m-auto">Link</small>' + 
								'</button>' + 
							'</div>' + 

						'</div>'
					);
				}

				$(document).on('click', '.add_block_section', function(){

					var element = $(this).data('element');
					var place = $(this).data('place');

					get_sidebar(place);

				});

				if($('#content').html() != '') {
					$('#content').prepend(
						"<div class='button-flex-container'>" + 
							"<span class='divider'></span>" + 
							"<button class='btn btn-primary add_block_section rounded-circle btn-sm' type='button' data-element='content' data-place='prepend'><i class='fas fa-plus'></i></button>" + 
							"<span class='divider'></span>" + 
						"</div>"
					);
					$('#content').append(
						"<div class='button-flex-container'>" + 
							"<span class='divider'></span>" + 
							"<button class='btn btn-primary add_block_section rounded-circle btn-sm' type='button' data-element='content' data-place='append'><i class='fas fa-plus'></i></button>" + 
							"<span class='divider'></span>" + 
						"</div>"
					);
				} else {
					$('#content').prepend(
						"<div class='button-flex-container'>" + 
							"<span class='divider'></span>" + 
							"<button class='btn btn-primary add_block_section rounded-circle btn-sm' type='button' data-element='content' data-place='append'><i class='fas fa-plus'></i></button>" + 
							"<span class='divider'></span>" + 
						"</div>"
					);

					get_sidebar('append');
				}

				$(document).on('click', '.add_element_btn', function(){
					// console.log('This');

					var place = $(this).data('place');
					var section = $(this).data('section');

					if(section == 'grid_row') {
						$('#content').html(
							"<div class='mail-row edit-row'>" + 
								"<div class='mail-col edit-col'>" + 
								"</div>" + 
							"</div>"
						);
					}

				});

			});

		</script>

	</body>
</html>