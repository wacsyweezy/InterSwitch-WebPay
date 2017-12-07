<!doctype html>
<html dir="ltr" lang="en-US">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<title>Interswitch WebPay ProtoType</title>
		<link href="transLog/ui/css/bootstrap.min.css" rel="stylesheet" />	
		<link href="transLog/ui/css/App.css" rel="stylesheet" />	
	</head>
	<body>
		<div class="container" style="max-width:650px;">
			<h1 class="page-header"><i class="glyphicon glyphicon-credit-card"></i> Pay Portal</h1>
			<div class="row">
				<div class="col-lg-12">
<?php
	include('config.inc');
	if(isset($_POST['txnref'])) {
		$txnref=$_POST['txnref'];
		$get_trans=mysql_fetch_array(mysql_query("SELECT * FROM transaction WHERE trans_id='$txnref' LIMIT 1"));
		$to_hash=$product_id.$txnref.$mac;
		$result_hash=hash('SHA512', $to_hash);
		$amount=$get_trans['amount'];
		$url="$trans_query_ep?productid=$product_id&txn_ref=$txnref&transactionreference=$txnref&amount=$amount";
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
		@mysql_close();
	}
?>
						<div class="well">
							<div class="row">
								<div class="col-md-8">
									<p class="lead"><small class="text-muted"><i class="glyphicon glyphicon-user"></i> Full Name</small><br /><?php print stripslashes($get_trans['full_name']); ?></p>
								</div>
								<div class="col-md-4">
									<p class="lead"><small class="text-muted"><i class="glyphicon glyphicon-credit-card"></i> Amount</small><br />&#8358;<?php print number_format($get_trans['amount']/100); ?>.00</p>
								</div>
								<div class="clearfix"></div>
								<div class="col-md-12">
									<p class="lead"><small class="text-muted"><i class="glyphicon glyphicon-blackboard"></i> Payment Purpose</small><br /><?php print stripslashes($get_trans['purpose']); ?></p>
								</div>
								<div class="clearfix"></div>
								<div class="col-md-12">
									<p class="lead"><small class="text-muted"><i class="glyphicon glyphicon-barcode"></i> Transaction ID</small><br /><?php print $get_trans['trans_id']; ?></p>
								</div>
								<div class="clearfix"></div>
								<div class="col-md-12">
									<p class="lead"><small class="text-muted"><i class="glyphicon glyphicon-pushpin"></i> Payment Status</small><br /><?php print $res_msg; ?></p>
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
						<div class="row" style="margin-top:30px;">
							<div class="col-sm-6 col-xs-6 col-md-6">
								<a href="./" class="btn btn-success"><i class="glyphicon glyphicon-home"></i> Home</a>
							</div>
							<div class="text-right col-sm-6 col-xs-6 col-md-6">
								<a href="./" class="btn btn-default"><i class="glyphicon glyphicon-ok-sign"></i> Pay Another</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
