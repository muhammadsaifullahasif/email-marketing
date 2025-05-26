<?php


if(isset($_POST['request']) && $_POST['request'] == 2) {

	$target_dir = 'attachments/';
	$filename = $target_dir.str_replace(' ', '_', $_POST['name']);
	if(unlink($filename)) {
		echo $filename;
	} else {
		echo 'false';
	}

} else {

	if (!empty($_FILES)) {

		$output = '';

		for($i = 0; $i < count($_FILES['file']['name']); $i++) {
			$tempFile = $_FILES['file']['tmp_name'][$i];
			$targetFile = 'attachments/'.str_replace(' ', '_', $_FILES['file']['name'][$i]);

			if(move_uploaded_file($tempFile, $targetFile)) {
				if(count($_FILES['file']['name']) > 1) {
					$output .= $targetFile.', ';
				} else {
					$output .= $targetFile;
				}
			} else {
				echo 'false';
			}
		}

		echo $output;

	}

}


?>