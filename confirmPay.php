<?php
include('config.inc');
	if(isset($_POST['amount']) && isset($_POST['fname']) && isset($_POST['purpose']) && isset($_POST['guid'])) {
		$amount=str_replace(',','',$_POST['amount']);
		$amount=(($amount+100)*100);
		$name=$_POST['fname'];
		$purpose=$_POST['purpose'];
		$transaction_id=$_POST['guid'];
		
		$to_hash = $transaction_id.$product_id.$pay_item_id.$amount.$site_redirect_url.$mac;
		$hash= hash('SHA512', $to_hash);

		if(mysql_query("INSERT INTO transaction SET trans_id='$transaction_id', full_name='$name', amount='$amount', purpose='$purpose', log_date=NOW()")) {
			@mysql_close();

			print '	<!doctype html>
							<html>
								<head></head>
								<body>
								<form name="webPay" id="webPay" action="'.$payment_ep.'" method="post">
									<input name="product_id" id="product_id" type="hidden" value="'.$product_id.'">
									<input name="pay_item_id" id="pay_item_id" type="hidden" value="'.$pay_item_id.'"/>	
									<input name="amount" id="amount" type="hidden" value="'.$amount.'"/>
									<input name="currency" id="currency" type="hidden" value="'.$currency.'" />
									<input name="site_redirect_url" id="site_redirect_url" type="hidden" value="'.$site_redirect_url.'"/>
									<input name="txn_ref" id="txn_ref" type="hidden" value="'.$transaction_id.'" />
									<input name="cust_id" id="cust_id" type="hidden" value="'.$name.'"/>
									<input name="mackey" id="mac" type="hidden" value="'.$mac.'"/>
									<input name="hash" id="hash" type="hidden" value="'.$hash.'" />
								</form>
								<script type="text/javascript">document.webPay.submit();</script>
							</body>
						</html>';

		}
		else {
			print mysql_error();
		}
	}
	else {
?>
<!doctype html>
<html lang="en-US">
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
			<?php
				$amount=str_replace(',','',$_POST['amount']);
				function GUID() {
					if (function_exists('com_create_guid') === true) {
						return trim(com_create_guid(), '{}');
					}
					return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
				}
				$guid=GUID();
			?>
			<div>
				<form action="confirmPay.php" method="post" class="well">
					<p class="alert alert-info"><i class="glyphicon glyphicon-question-sign"></i> Confirm Payment</p>
					<div class="row">
						<div class="col-lg-7">
							<div class="form-group">
			           <label for="name">Full Name:</label>
			           <input class="form-control" id="data_fname" name="data_fname" value="<?php print $_POST['fname']; ?>" disabled="disabled" type="text">
			         </div>
						</div>
						<div class="clearfix"></div>
						<div class="col-lg-12">
							<div class="form-group">
		         		<label for="name">Purpose Of Payment:</label>
		         		<input class="form-control" id="data_purpose" name="data_purpose" value="<?php print $_POST['purpose']; ?>" disabled="disabled" type="text">
			         </div>
						</div>
						<div class="col-lg-3">
							<div class="form-group">
		         		<label for="name">Amount:</label>
								<div class="input-group">
									<span class="input-group-addon" style="border-top-right-radius:0px; border-bottom-right-radius:0px;">&#8358;</span>
		         			<input class="form-control" id="data_amount" name="data_amount" value="<?php print number_format($amount); ?>.00" disabled="disabled" type="text">
								</div>
			         </div>								
						</div>
						<div class="clearfix"></div>
						<div class="col-lg-8">
							<div class="form-group">
			           <label for="name">Transaction ID:</label>
			           <input class="form-control" id="data_guid" name="data_guid" value="<?php print $guid; ?>" disabled="disabled" type="text">
			         </div>
						</div>
						<div class="clearfix"></div>
						<div class="col-lg-6">
							<div class="form-group">
		         		<label for="name">Transaction Charge:</label>
								<div class="input-group">
									<span class="input-group-addon" style="border-top-right-radius:0px; border-bottom-right-radius:0px;">&#8358;</span>
		         			<input class="form-control" id="tAmount" name="tAmount" value="100.00" disabled="disabled" type="text">
								</div>
			         </div>								
						</div>
						<div class="col-lg-3">
		       		<label for="name">&nbsp;</label>
							<a class="btn btn-block btn-danger" href="./"><i class="glyphicon glyphicon-chevron-left"></i> Back</a>
						</div>
						<div class="col-lg-3">
		       		<label for="name">&nbsp;</label>
							<button class="btn btn-block btn-default" id="confirm" type="submit"><i class="glyphicon glyphicon-ok"></i> Confirm</button>
						</div>
	<input type="hidden" name="fname" value="<?php print $_POST['fname']; ?>" />
	<input type="hidden" name="purpose" value="<?php print $_POST['purpose']; ?>" />
	<input type="hidden" name="amount" value="<?php print number_format($amount); ?>" />
	<input type="hidden" name="guid" value="<?php print $guid; ?>" />
						<div class="clearfix"></div>
					</div>
				</form>
			</div>
		</div>
		<script src="transLog/ui/js/jquery.js"></script>
		<script src="transLog/ui/js/App.js"></script>
	</body>
</html>
<?php
	}
?>
