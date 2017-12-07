<?php
@session_start();
if(!isset($_SESSION['auth'])) {
	print '<script>document.location.href="./";</script>';
}
else {
	if(isset($_GET['amount']) && isset($_GET['trans_id'])) {
		include('../config.inc');
		$txnref=$_GET['trans_id'];

		$to_hash=$product_id.$txnref.$hash;
		$result_hash=hash('SHA512', $to_hash);
		
		$amount=$_GET['amount'];
		$url="$trans_query_ep?productid=$product_id&transactionreference=$txnref&amount=$amount";
		$headers = array(   
	 		"GET /HTTP/1.1",
		 	"User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.1) Gecko/2008070208 Firefox/3.0.1", 
		 	"Accept-Language: en-us,en;q=0.5",
		 	"Keep-Alive: 300",
		 	"Connection: keep-alive",
		 	"Hash: $result_hash");
 
		$open=curl_init($url);
		curl_setopt($open,CURLOPT_HTTPHEADER, $headers);
		curl_setopt($open, CURLOPT_RETURNTRANSFER, 1);
		$data=curl_exec($open);

		$result=json_decode($data);
		
		$res_code=$result->ResponseCode;
    $ref_code=$result->RetrievalReferenceNumber;
    $res_msg=$result->ResponseDescription;

		mysql_query("UPDATE transaction SET ResponseCode='$res_code', RetrievalReferenceNumber='$ref_code', ResponseDescription='$res_msg', log_date=NOW() WHERE trans_id='$txnref'");
		mysql_close();

		print '	<h1>ReQuery Response</h1>
						<p><strong>New Response: </strong>'.$res_code.'</p>
						<p><strong>Description: </strong>'.$res_msg.'</p><br /><br /><a href="./">Goto Dashboard</a>';
	}
	else {
		print '<script>document.location.href="./";</script>';
	}
}
?>
