<?php

require_once('config.php');

$home_url = 'http://localhost/email-marketing/';
$dashboard_url = 'http://localhost/email-marketing/dashboard/';
$api_url = 'http://localhost/email-marketing/api/';

function display_name($user_id) {
	global $conn;
	if($user_id != '' && $user_id != 0) {
		$query = mysqli_query($conn, "SELECT display_name FROM users WHERE id='$user_id' && delete_status='0'");
		if(mysqli_num_rows($query) > 0) {
			$result = mysqli_fetch_assoc($query);
			return $result['display_name'];
		} else {
			return '';
		}
	} else {
		return '';
	}
}

function user_meta($user_id, $meta_key) {
	global $conn;
	if($user_id != '' && $user_id != 0 && $meta_key != '') {
		$query = mysqli_query($conn, "SELECT meta_value FROM user_meta WHERE user_id='$user_id' && meta_key='$meta_key'");
		if(mysqli_num_rows($query) > 0) {
			$result = mysqli_fetch_assoc($query);
			return $result['meta_value'];
		} else {
			return '';
		}
	}
}

function admin_id($user_id) {

	global $conn;
	if($user_id != '' && $user_id != 0) {
		$admin_id_query = mysqli_query($conn, "SELECT admin_id FROM users WHERE id='$user_id' && active_status='1' && delete_status='0'");
		if(mysqli_num_rows($admin_id_query) > 0) {
			$admin_id_result = mysqli_fetch_assoc($admin_id_query);
			if(is_null($admin_id_result['admin_id'])) {
				return $user_id;
			} else {
				return $admin_id_result['admin_id'];
			}
		} else {
			return $user_id;
		}
	} else {
		return $user_id;
	}

}

function campaign_meta($campaign_id, $meta_key) {
	global $conn;
	if($campaign_id != '' && $campaign_id != 0) {
		$query = mysqli_query($conn, "SELECT meta_value FROM campaign_meta WHERE campaign_id='$campaign_id' && meta_key='$meta_key'");
		if(mysqli_num_rows($query) > 0) {
			$result = mysqli_fetch_assoc($query);
			return $result['meta_value'];
		} else {
			return '';
		}
	} else {
		return '';
	}
}

function inbox_meta($inbox_id, $meta_key) {
	global $conn;
	if($inbox_id != '' && $inbox_id != 0) {
		$query = mysqli_query($conn, "SELECT meta_value FROM inbox_meta WHERE inbox_id='$inbox_id' && meta_key='$meta_key'");
		if(mysqli_num_rows($query) > 0) {
			$result = mysqli_fetch_assoc($query);
			return $result['meta_value'];
		} else {
			return '';
		}
	} else {
		return '';
	}
}

function account_meta($account_id, $meta_key) {
	global $conn;
	if($account_id != '' && $account_id != 0) {
		$query = mysqli_query($conn, "SELECT meta_value FROM account_meta WHERE account_id='$account_id' && meta_key='$meta_key'");
		if(mysqli_num_rows($query) > 0) {
			$result = mysqli_fetch_assoc($query);
			return $result['meta_value'];
		} else {
			return '';
		}
	} else {
		return '';
	}
}

function template_category($category_id) {
	global $conn;
	if($category_id != '' && $category_id != 0 && $category_id != NULL) {
		$query = mysqli_query($conn, "SELECT category_name FROM template_categories WHERE id='$category_id' && delete_status='0'");
		if(mysqli_num_rows($query) > 0) {
			$result = mysqli_fetch_assoc($query);
			return $result['category_name'];
		} else {
			return '';
		}
	} else {
		return '';
	}
}

function time_ago($time_ago) {

	$current_time = time();

	$diff = (int)$current_time - (int)$time_ago;

	$seconds = $diff;
	$mintues = round($seconds / 60);
	$hours = round($seconds / 3600);
	$days = round($seconds / 86400);
	$weeks = round($seconds / 604800);
	$months = round($seconds / 2629440);
	$years = round($seconds / 31553280);

	if($seconds <= 60) {
		return 'Just Now';
	} else if($mintues <= 60) {
		if($mintues == 1) {
			return 'One mintue ago';
		} else {
			return $mintues.' mintues ago';
		}
	} else if($hours <= 24) {
		if($hours == 1) {
			return 'One hour ago';
		} else {
			return $hours.' hours ago';
		}
	} else {
		return date('d-M', $time_ago);
	}

}



function FileSizeConvert($bytes) {
	$result = 0;
    $bytes = floatval($bytes);
        $arBytes = array(
            0 => array(
                "UNIT" => "TB",
                "VALUE" => pow(1024, 4)
            ),
            1 => array(
                "UNIT" => "GB",
                "VALUE" => pow(1024, 3)
            ),
            2 => array(
                "UNIT" => "MB",
                "VALUE" => pow(1024, 2)
            ),
            3 => array(
                "UNIT" => "KB",
                "VALUE" => 1024
            ),
            4 => array(
                "UNIT" => "B",
                "VALUE" => 1
            ),
        );

    foreach($arBytes as $arItem) {
        if($bytes >= $arItem["VALUE"]) {
            $result = $bytes / $arItem["VALUE"];
            $result = str_replace(".", "," , strval(round($result, 2)))." ".$arItem["UNIT"];
            break;
        }
    }
    return $result;
}

function formatSizeUnits($bytes) {
	if ($bytes >= 1073741824) {
		$bytes = number_format($bytes / 1073741824, 2) . ' GB';
	} elseif ($bytes >= 1048576) {
		$bytes = number_format($bytes / 1048576, 2) . ' MB';
	} elseif ($bytes >= 1024) {
		$bytes = number_format($bytes / 1024, 2) . ' KB';
	} elseif ($bytes > 1) {
		$bytes = $bytes . ' bytes';
	} elseif ($bytes == 1) {
		$bytes = $bytes . ' byte';
	} else {
		$bytes = '0 bytes';
	}
	return $bytes;
}

function get_remote_file_info($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, TRUE);
    curl_setopt($ch, CURLOPT_NOBODY, TRUE);
    $data = curl_exec($ch);
    $fileSize = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
    $httpResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return (int)$fileSize;
}

function file_size($url) {
	// $result = get_remote_file_info($url);
	// return FileSizeConvert(get_remote_file_info($url));
	$headers = get_headers($url, 1);
	$size = $headers['Content-Length'];
	return formatSizeUnits($size);
}

// $url = 'http://localhost/email-marketing/api/attachments/info@wirecoder.com/166800943210carddav-info@wirecoder.com.mobileconfig';

function get_file_name($url) {
	$file_name = basename($url);
	return $file_name; // Output: "file.txt"
}

function get_file_name_with_curl($url) {
	header('Content-Type: text/plain');

	$curl = curl_init($url);

	curl_setopt($curl, CURLOPT_HEADER, true);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'HEAD');

	if (($response = curl_exec($curl)) !== false) {
		if (curl_getinfo($curl, CURLINFO_HTTP_CODE) == '200') {
			var_dump($response);

			$reDispo = '/^Content-Disposition: .*?filename=(?<f>[^\s]+|\x22[^\x22]+\x22)\x3B?.*$/m';
			if (preg_match($reDispo, $response, $mDispo)) {
				$filename = trim($mDispo['f'],' ";');

				// echo "Filename Found: $filename";
				return $filename;
			}
		}
	}

	curl_close($curl);
}