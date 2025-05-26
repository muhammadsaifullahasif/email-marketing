<?php

// $server = '{wirecoder.com:993/ssl}';
// $connection = imap_open($server, 'info@wirecoder.com', '7S@!fullah');
// $mailboxes = imap_list($connection, $server, '*');
// $count = imap_num_msg($connection);
// $uid = 1;

$mailbox = array(
	'inbox' => 'INBOX', 
	'archive' => 'INBOX.Archive', 
	'trash' => 'INBOX.Trash', 
	'sent' => 'INBOX.Sent', 
	'drafts' => 'INBOX.Drafts', 
	'spam' => 'INBOX.spam'
);
$output = array();
$inbox = array();
$i = 0;
foreach($mailbox as $mailbox_key => $mailbox_value) {
	$connection = imap_open('{wirecoder.com:993/ssl}'.$mailbox_value, 'info@wirecoder.com', '7S@!fullah');
	$total_msg = imap_num_msg($connection);
	$inbox[$mailbox_key] = array('inbox' => $mailbox_value, 'total_msg' => $total_msg);
	// echo $mailbox_value.' = '.$total_msg.'<br>';
	$i++;
}

echo '<pre>';
print_r($inbox);
echo '</pre>';

die();
/*
echo '<pre>';
// print_r($mailboxes);
// echo $count;
// echo time();
$account_email = 'info@wirecoder.com';
$account_host = explode('@', $account_email);
echo $account_host[1];
die();
include "classes/class.imap.php";

$IMAP = new IMAP();

// echo '<pre>';

$IMAP->connect('{wirecoder.com:993/ssl}INBOX.Sent', 'info@wirecoder.com', '7S@!fullah');

$imap_fetch_overview = imap_fetch_overview($IMAP->connection(),"1:{$count}",0);
$imap_fetchheader = imap_fetchheader($connection, 2, FT_UID);

print_r($imap_fetchheader);

// echo imap_num_msg($IMAP->connection());
die();

$inbox = $IMAP->getMessages();
foreach($inbox as $v) {
	echo $v['message'].'<br><br><br>';
}
die();

// $result = imap_fetch_overview($connection,"1:{$MC->Nmsgs}",0);
// print_r($result);
// echo imap_num_msg($connection); // Get the total number of messages

// $imap_body = imap_body($connection, 1, FT_UID);
// print_r(trim(quoted_printable_decode(imap_body($connection, $uid, 1))));
$nStart = 0;
$nCnt = 5;
$result = imap_fetch_overview($connection, ($nStart+1).':'.($nStart+$nCnt));
foreach ($result as $overview) {
	echo '<pre>';
	print_r($overview);
	echo '</pre>';
    // echo "#{$overview->msgno} ({$overview->date}) - From: {$overview->from} {$overview->subject}\n";
	echo '<br>---------------------------------------------------------------------------------<br>';
}
die();
// print_r($imap_body);
echo trim(quoted_printable_decode($imap_body));
// echo '</pre>';
die();
/*        
if(isset($structure->parts) && is_array($structure->parts) && isset($structure->parts[1])) {// IF HTML

	
	$part = $structure->parts[1];
	$receivedEmailContent = imap_fetchbody($connection,$email_number,2);
	if($part->encoding == 3) {
		$receivedEmailContent = imap_base64($receivedEmailContent);
		
	} else if($part->encoding == 1) {
		$receivedEmailContent = imap_8bit($receivedEmailContent);
		
	} else {
		$receivedEmailContent = imap_qprint($receivedEmailContent);
	}

}else{//IF NOT HTML
	$receivedEmailContent = imap_fetchbody($connection,$email_number,1);
	
	if($structure->type == 0){
		$receivedEmailContent = imap_base64($receivedEmailContent);
	}else{
		$receivedEmailContent = imap_qprint($receivedEmailContent);
	}
}*/
// echo $receivedEmailContent;
/*
$receivedEmailContent = strip_tags($receivedEmailContent);
$header = imap_headerinfo ( $connection, $email_number);
$receivedEmailTitle = $header->subject;
$receivedEmailSenderName = $header->from[0]->personal;
$receivedEmailSenderEmail = $header->from[0]->mailbox . "@" . $header->from[0]->host;
echo '</pre>';
die();
$result = imap_fetch_overview($connection, ($nStart+1).':'.($nStart+$nCnt));
foreach ($result as $overview) {
	echo '<pre>';
	print_r($overview);
	echo '</pre>';
    // echo "#{$overview->msgno} ({$overview->date}) - From: {$overview->from} {$overview->subject}\n";
	echo '<br>---------------------------------------------------------------------------------<br>';
}
/*for($i = 1; $i <= $count; $i++) {
	// if(!in_array($i, $ignore)) {
		$header = imap_headerinfo($connection, $i);
		$raw_body = imap_body($connection, $i);
		print_r($header);

		echo '<br>---------------------------------------------------------------------------<br>';
	// }
}*/
/*
echo '</pre>';
die();
*/

require_once('classes/imap_original.php');

$email = new Imap('info@wirecoder.com');

$email->connect('{wirecoder.com:993/ssl}INBOX', 'info@wirecoder.com', '7S@!fullah');

$inbox = null;

$inbox = $email->getMessages('html', 'desc');


echo '<pre>';
print_r($inbox);
echo '</pre>';
die();



?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
	<title></title>
</head>
<body>

	<?php

	if($inbox == null) {
		echo "Not Connect";
		exit;
	} else {

	?>

	<table class="table">
		<thead>
			<tr>
				<th>No</th>
				<th>Subject</th>
				<th>Name</th>
				<th>Email</th>
				<th>Date</th>
			</tr>
		</thead>
		<tbody>
			
			<?php

			$html = '';
			$no = 1;

			foreach ($inbox as $v) {

				echo '<pre>';
				print_r($v);
			}
			die();
			foreach ($inbox as $v) {

				$attachment = '';

				if(!empty($v['attachments'])) {
					foreach($v['attachments'] as $a) {
						// $attachment .= "<br><a href='".$a."'>".end(explode('/', $a))."</a>";
						$attachment .= "<br><a href='attachments/info@wirecoder.com/".$a."' target='_blank'>".$a."</a>";
					}
				}
				
				$html .= "<tr><td>".$no."</td>";
				$html .= "<td><a href='#' data-message='".htmlentities($v['message'].(!empty($attachment)? '<hr>attachments:'.$attachment : ''))."' class='single' data-toggle='modal' data-target='#addModal'>".substr($v['subject'], 0, 120).'</a></td>';
				$html .= "<td>".(empty($v['from'][0]['name']) ? '[empty]' : $v['from'][0]['name']).'</td>';
				// $html .= "<td><a href='mailto:".$v['from'][0]['address']."?subject=Re:".$v['subject']."'>".$v['from'][0]['address']."</a></td>";
				$html .= "<td>".date('d-m-Y H:i:s', $v['date'])."</td></tr>";
				$no++;

			}

			echo $html;

			?>

		</tbody>
	</table>

	<?php

	}

	?>

	<!-- Modal -->
	<div class="modal fade" id="addModal" tabindex="-1">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Message</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body" id="ooge">
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary">Save changes</button>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript" src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

	<script type="text/javascript">
		$(document).ready(function() {
			$('body').on('click', '.single', function(){
				var message = $(this).data('message');
				// console.log('This');
				// console.log(message);
				$('#ooge').html(message);
			});
		});
	</script>

</body>
</html>