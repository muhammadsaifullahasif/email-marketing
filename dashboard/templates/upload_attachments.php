<?php

include "../config.php";

if (!empty($_FILES)) {

	// echo '<pre>';
	// print_r($_FILES);
	// die();

	$resultArray = array();

	// for($i = 0; $i < count($_FILES['file']['name']); $i++) {
	foreach ( $_FILES as $file){
		$tempFile = $file['tmp_name'];
		$targetFile = 'attachments/'.str_replace(' ', '_', $file['name']);

		if(move_uploaded_file($tempFile, '../'.$targetFile)) {
			// $query = mysqli_query($conn, "INSERT INTO media_assets(user_id, asset_name, asset_url, asset_type, asset_size, time_created)")
			// $output[$i] = $targetFile;
			$result=array(
				'name'=>$file['name'],
				'type'=>'image',
				'src'=>$dashboard_url.$targetFile,
				'height'=>350,
				'width'=>250,
			);
			array_push($resultArray,$result);
		} else {
			echo 'false';
		}

	}

	$response = array( 'data' => $resultArray );
	echo json_encode($response);

}


?>