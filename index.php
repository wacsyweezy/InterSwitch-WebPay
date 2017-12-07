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
			<form action="confirmPay.php" method="post" class="well">
				<div class="row">
					<div class="col-lg-7">
						<div class="form-group">
		           <label for="name">Full Name:</label>
		           <input class="form-control" id="fname" name="fname" placeholder="Full Name" type="text">
		         </div>
					</div>
					<div class="clearfix"></div>
					<div class="col-lg-12">
						<div class="form-group">
		       		<label for="name">Purpose Of Payment:</label>
		       		<input class="form-control" id="purpose" name="purpose" placeholder="Purpose" type="text">
		         </div>
					</div>
					<div class="col-lg-4">
						<div class="form-group">
		       		<label for="name">Amount:</label>
							<div class="input-group">
								<span class="input-group-addon" style="border-top-right-radius:0px; border-bottom-right-radius:0px;">&#8358;</span>
		       			<input class="form-control" id="amount" name="amount" placeholder="Amount" type="text">
							</div>
		         </div>								
					</div>
					<div class="col-lg-3 pull-right">
	       		<label for="name">&nbsp;</label>
						<button class="btn btn-block btn-primary" id="give_now" type="submit"><i class="glyphicon glyphicon-play-circle"></i> Pay Now</button>
					</div>
					<div class="clearfix"></div>
				</div>
			</form>
		</div> 
		<script src="transLog/ui/js/jquery.js"></script>
		<script src="transLog/ui/js/App.js"></script>
	</body>
</html>
