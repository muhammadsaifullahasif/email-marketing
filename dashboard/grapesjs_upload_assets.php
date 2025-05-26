<?php

include "config.php";

if (!empty($_FILES)) {

	$resultArray = array();

	foreach ( $_FILES as $file){
		$tempFile = $file['tmp_name'];
		$targetFile = 'attachments/'.str_replace(' ', '_', $file['name']);

		if(move_uploaded_file($tempFile, $targetFile)) {
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