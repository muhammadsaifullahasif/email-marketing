<?php


foreach($session_token as $key => $value) {
    if($value['session_key'] == '909a0060ca2f5a26e1a16e79a9fc4332') {
        unset($session_token[$key]);
    }
}

?>