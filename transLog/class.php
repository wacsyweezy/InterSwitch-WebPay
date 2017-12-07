<?php
	@session_start();
	include('../config.inc');
	class App {
		# HEADER
		public static function uiHeader() {
			print '	<!doctype html>
							<html>
								<head>
									<link href="ui/css/bootstrap.min.css" rel="stylesheet" />
									<link href="ui/css/App.css" rel="stylesheet" />';
									self::page_title();
								print'
								</head>
								<body>';
		}
		# FOOTER
		public static function uiFooter() {
			print '			<script src="ui/js/jquery.js"></script>
									<script src="ui/js/bootstrap.min.js"></script>
									<script src="ui/js/App.js"></script>
								</body>
							</html>';
		}
		# PAGE TITLE
		public static function page_title() {
			if(!isset($_SESSION['auth']) && !isset($_GET['page'])) {
				print '<title>Payment Trace | Login</title>';
			}
			if(isset($_SESSION['auth']) && !isset($_GET['page'])) {
				print '<title>Payment Trace | Dashboard</title>';
			}
			if(isset($_SESSION['auth']) && isset($_GET['page']) && $_GET['page']=="aTransaction") {
				print '<title>Payment Trace | All Transactions</title>';
			}
			if(isset($_SESSION['auth']) && isset($_GET['page']) && $_GET['page']=="sTransaction") {
				print '<title>Payment Trace | Successful Transactions</title>';
			}
			if(isset($_SESSION['auth']) && isset($_GET['page']) && $_GET['page']=="uTransaction") {
				print '<title>Payment Trace | Unsuccessful Transactions</title>';
			}
			if(isset($_SESSION['auth']) && isset($_GET['page']) && $_GET['page']=="pTransaction") {
				print '<title>Payment Trace | Pending Transactions</title>';
			}
		}
		# APP MENU
		public static function uiMenu() {
			print '
<nav class="navbar navbar-inverse">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#"><i class="glyphicon glyphicon-credit-card"></i> Pay Trace</a>
    </div>
    <div id="navbar" class="collapse navbar-collapse">
      <ul class="nav navbar-nav pull-right">
        <li><a href="./"><i class="glyphicon glyphicon-dashboard"></i> Dashboard</a></li>
        <li><a href=".?page=aTransaction"><i class="glyphicon glyphicon-th"></i> All Transaction</a></li>
        <li><a href=".?page=sTransaction"><i class="glyphicon glyphicon-ok"></i> Successful Transaction</a></li>
        <li><a href=".?page=uTransaction"><i class="glyphicon glyphicon-warning-sign"></i> Unsuccessful Transaction</a></li>
        <li><a href=".?page=pTransaction"><i class="glyphicon glyphicon-time"></i> Pending Transaction</a></li>
        <li><a href=".?page=logout"><i class="glyphicon glyphicon-off"></i> Logout</a></li>
      </ul>
    </div><!--/.nav-collapse -->
  </div>
</nav>
<div class="container">
	<h1 >Pay Trace</h1>
</div>';
		}
		# LOGIN PAGE
		public static function propmtLogin() {
			@session_start();
			if(isset($_SESSION['auth'])) {
				print '	<script>document.location.href="./";</script>';
			}
			self::uiHeader();
			print '
<style>
body {
  padding-top: 40px;
  padding-bottom: 40px;
  background-color: #fff;
}

.form-signin {
  max-width: 330px;
  padding: 15px;
  margin: 0 auto;
}
.form-signin .form-signin-heading,
.form-signin .checkbox {
  margin-bottom: 10px;
}
.form-signin .checkbox {
  font-weight: normal;
}
.form-signin .form-control {
  position: relative;
  height: auto;
  -webkit-box-sizing: border-box;
     -moz-box-sizing: border-box;
          box-sizing: border-box;
  padding: 10px;
  font-size: 16px;
}
.form-signin .form-control:focus {
  z-index: 2;
}
</style>
<div class="container">
	<form class="form-signin" action="" method="POST">
		<h1 class="page-header"><i class="text-muted glyphicon glyphicon-credit-card"></i> Pay Trace</h1>
		<p class="lead text-muted"><i class="glyphicon glyphicon-lock"></i> Please login</p>
		';
		if(isset($_POST['username']) && isset($_POST['password'])) {
			$username=$_POST['username'];
			$password=$_POST['password'];
			if($username=="admin" && $password=="admin") {
				$_SESSION['auth']="logged";
				print '<script>document.location.href="./";</script>';
			}
			else {
				print '	<div class="alert alert-danger">Invalid login credential</div>';
			}
		}
		print '
		<label for="inputEmail" class="sr-only">Email address</label>
		<div class="input-group">
			<div class="input-group-addon"><i class="glyphicon glyphicon-user"></i></div>
			<input type="text" name="username" id="username" class="form-control" placeholder="Username" required autofocus>
		</div>
		<label for="inputPassword" class="sr-only">Password</label>
		<div class="input-group">
			<div class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></div>
			<input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
		</div>
		<br />
		<button class="btn btn-primary btn-lg" type="submit"><i class="glyphicon glyphicon-lock"></i> Sign in</button>
	</form>
</div> <!-- /container -->';
			self::uiFooter();
		}
		# DASHBOARD PAGE
		public static function dashboard() {
			self::uiHeader();
			self::uiMenu();
			print '	<div class="container">
								<h2><i class="glyphicon glyphicon-dashboard"></i> Dashboard</h2>';
			self::reUse("widget", "all");
			self::reUse("transaction", "latest-success");
			print '	</div>';
			self::uiFooter();
		}

		# REUSEABLE CLASS
		public static function reUse($type, $param) {
			# LIST TRANSACTION
			if($type=="transaction") {
				
				// all transaction
				if($param=="all") {
					print '	<div class="panel panel-default">
										<div class="panel-heading">
											<strong><i class="glyphicon glyphicon-th"></i> All Transactions</strong>
										</div>
										<div class="panel-body">';
					$get_all=mysql_query("SELECT * FROM transaction");
					if($get_all && mysql_num_rows($get_all)>=1) {
						if(!isset($page)) {
							$page=1;
						}
						// number of rows to display
						$page_rows=10;
						$last=ceil(mysql_num_rows($get_all)/$page_rows);
						if(isset($_GET['p'])) {
							$page=$_GET['p'];
						}
						if($page<1) {
							$page=1;
						}
						else if($page>$last) {
							$page=$last;
						}
						$max="LIMIT ".($page-1)*$page_rows.",".$page_rows;
						print '	<div class="table table-responsive">
											<table class="table table-responsive table-border table-striped table-hover">
												<thead>
													<th>Name & Purpose</th>
													<th>Transaction ID</th>
													<th>Transaction Ref</th>
													<th>Transaction Date</th>
													<th>Amount</th>
													<th>Transaction Response</th>
												<thead>
												<tbody>';
						$all_transaction=mysql_query("SELECT * FROM transaction ORDER BY id DESC $max");
						while($all=mysql_fetch_array($all_transaction)) {
							print '	<tr>
												<td>
													<i class="glyphicon glyphicon-user"></i> '.stripslashes($all['full_name']).'
													<br />
													<small><i class="glyphicon glyphicon-pushpin"></i> '.stripslashes($all['purpose']).'</small>
												</td>
												<td>'.$all['trans_id'].'</td>
												<td>'.$all['RetrievalReferenceNumber'].'</td>
												<td>'.$all['log_date'].'</td>
												<td>&#8358;'.number_format($all['amount']/100).'</td>
												<td>'.$all['ResponseDescription'].'</td>
											</tr>';
						}
						print '			</tbody>
											</table>
										</div>';
					}
					else {
						print '	<blockquote>
											<i class="glyphicon glyphicon-warning-sign text-warning"></i> No transaction found
										</blockquote>';
					}
										print '
										</div>';
					if(mysql_num_rows($get_all)>=1) {
										print '
										<div class="panel-footer">
											<strong class="pull-left"><small>Page '.$page.' of '.$last.'</small></strong>
											<ul class="pull-right pagination" style="padding:0px; margin:0px;">';
											if($page==1){}
											else {
												print '<li style="border-radius:0px;"><a style="border-radius:0px;" title="Goto First Page" href=".?page=aTransaction"><i class="glyphicon glyphicon-menu-left"></i><i class="glyphicon glyphicon-menu-left"></i></a></li>';
												$previous=$page-1;
												print '<li style="border-radius:0px;"><a style="border-radius:0px;" title="Goto Previous Page" href=".?page=aTransaction&p='.$previous.'"><i class="glyphicon glyphicon-menu-left"></i></a>';
											}
											if($page==$last){}
											else {
												$next=$page+1;
												print '<li style="border-radius:0px;"><a style="border-radius:0px;" title="Goto Next Page" href=".?page=aTransaction&p='.$next.'"><i class="glyphicon glyphicon-menu-right"></i></a></li>';
												print '<li style="border-radius:0px;"><a style="border-radius:0px;" title="Goto Last Page" href=".?page=aTransaction&p='.$last.'"><i class="glyphicon glyphicon-menu-right"></i><i class="glyphicon glyphicon-menu-right"></i></a>';
											}
					print '			</ul>
											<div class="clearfix"></div>
										</div> ';
					}
					print '
									</div>';
				}
				// all successful transaction
				if($param=="all-success") {
					print '	<div class="panel panel-default">
										<div class="panel-heading">
											<strong><i class="glyphicon glyphicon-th"></i> All Successful Transactions</strong>
										</div>
										<div class="panel-body">';
					$get_all_success=mysql_query("SELECT * FROM transaction WHERE ResponseCode='00'");
					if($get_all_success && mysql_num_rows($get_all_success)>=1) {
						if(!isset($page)) {
							$page=1;
						}
						// number of rows to display
						$page_rows=10;
						$last=ceil(mysql_num_rows($get_all_success)/$page_rows);
						if(isset($_GET['p'])) {
							$page=$_GET['p'];
						}
						if($page<1) {
							$page=1;
						}
						else if($page>$last) {
							$page=$last;
						}
						$max="LIMIT ".($page-1)*$page_rows.",".$page_rows;
						print '	<div class="table table-responsive">
											<table class="table table-responsive table-border table-striped table-hover">
												<thead>
													<th>Name & Purpose</th>
<th>Transaction ID</th>
													<th>Transaction Ref</th>
													<th>Transaction Date</th>
													<th>Amount</th>
													<th>Transaction Response</th>
												<thead>
												<tbody>';
						$get_success=mysql_query("SELECT * FROM transaction WHERE ResponseCode='00' ORDER BY id DESC $max");
						while($success=mysql_fetch_array($get_success)) {
							print '	<tr>
												<td>
													<i class="glyphicon glyphicon-user"></i> '.stripslashes($success['full_name']).'
													<br />
													<small><i class="glyphicon glyphicon-pushpin"></i> '.stripslashes($success['purpose']).'</small>
												</td>
<td>'.$success['trans_id'].'</td>
												<td>'.$success['RetrievalReferenceNumber'].'</td>
												<td>'.$success['log_date'].'</td>
												<td>&#8358;'.number_format($success['amount']/100).'</td>
												<td>'.$success['ResponseDescription'].'</td>
											</tr>';
						}
						print '			</tbody>
											</table>
										</div>';
					}
					else {
						print '	<blockquote>
											<i class="glyphicon glyphicon-warning-sign text-warning"></i> No successful transaction found
										</blockquote>';
					}
										print '
										</div>';
					if(mysql_num_rows($get_all_success)>=1) {
										print '
										<div class="panel-footer">
											<strong class="pull-left"><small>Page '.$page.' of '.$last.'</small></strong>
											<ul class="pull-right pagination" style="padding:0px; margin:0px;">';
											if($page==1){}
											else {
												print '<li style="border-radius:0px;"><a style="border-radius:0px;" title="Goto First Page" href=".?page=sTransaction"><i class="glyphicon glyphicon-menu-left"></i><i class="glyphicon glyphicon-menu-left"></i></a></li>';
												$previous=$page-1;
												print '<li style="border-radius:0px;"><a style="border-radius:0px;" title="Goto Previous Page" href=".?page=sTransaction&p='.$previous.'"><i class="glyphicon glyphicon-menu-left"></i></a>';
											}
											if($page==$last){}
											else {
												$next=$page+1;
												print '<li style="border-radius:0px;"><a style="border-radius:0px;" title="Goto Next Page" href=".?page=sTransaction&p='.$next.'"><i class="glyphicon glyphicon-menu-right"></i></a></li>';
												print '<li style="border-radius:0px;"><a style="border-radius:0px;" title="Goto Last Page" href=".?page=sTransaction&p='.$last.'"><i class="glyphicon glyphicon-menu-right"></i><i class="glyphicon glyphicon-menu-right"></i></a>';
											}
					print '			</ul>
											<div class="clearfix"></div>
										</div> ';
					}
					print '
									</div>';
				}
				// all unsuccessful transaction
				if($param=="unsuccessful") {
					print '	<div class="panel panel-default">
										<div class="panel-heading">
											<strong><i class="glyphicon glyphicon-th"></i> All Unsuccessful Transactions</strong>
										</div>
										<div class="panel-body">';
					$get_all_unsuccess=mysql_query("SELECT * FROM transaction WHERE ResponseCode='Z1'");
					if($get_all_unsuccess && mysql_num_rows($get_all_unsuccess)>=1) {
						if(!isset($page)) {
							$page=1;
						}
						// number of rows to display
						$page_rows=10;
						$last=ceil(mysql_num_rows($get_all_unsuccess)/$page_rows);
						if(isset($_GET['p'])) {
							$page=$_GET['p'];
						}
						if($page<1) {
							$page=1;
						}
						else if($page>$last) {
							$page=$last;
						}
						$max="LIMIT ".($page-1)*$page_rows.",".$page_rows;
						print '	<div class="table table-responsive">
											<table class="table table-responsive table-border table-striped table-hover">
												<thead>
													<th>Name & Purpose</th>
<th>Transaction ID</th>
													<th>Transaction Ref</th>
													<th>Transaction Date</th>
													<th>Amount</th>
													<th>Transaction Response</th>
												<thead>
												<tbody>';
						$get_unsuccess=mysql_query("SELECT * FROM transaction WHERE ResponseCode='Z1' ORDER BY id DESC $max");
						while($unsuccess=mysql_fetch_array($get_unsuccess)) {
							print '	<tr>
												<td>
													<i class="glyphicon glyphicon-user"></i> '.stripslashes($unsuccess['full_name']).'
													<br />
													<small><i class="glyphicon glyphicon-pushpin"></i> '.stripslashes($unsuccess['purpose']).'</small>
												</td>
<td>'.$unsuccess['trans_id'].'</td>
												<td>'.$unsuccess['RetrievalReferenceNumber'].'</td>
												<td>'.$unsuccess['log_date'].'</td>
												<td>&#8358;'.number_format($unsuccess['amount']/100).'</td>
												<td>'.$unsuccess['ResponseDescription'].'</td>
											</tr>';
						}
						print '			</tbody>
											</table>
										</div>';
					}
					else {
						print '	<blockquote>
											<i class="glyphicon glyphicon-warning-sign text-warning"></i> No unsuccessful transaction found
										</blockquote>';
					}
										print '
										</div>';
					if(mysql_num_rows($get_all_unsuccess)>=1) {
										print '
										<div class="panel-footer">
											<strong class="pull-left"><small>Page '.$page.' of '.$last.'</small></strong>
											<ul class="pull-right pagination" style="padding:0px; margin:0px;">';
											if($page==1){}
											else {
												print '<li style="border-radius:0px;"><a style="border-radius:0px;" title="Goto First Page" href=".?page=uTransaction"><i class="glyphicon glyphicon-menu-left"></i><i class="glyphicon glyphicon-menu-left"></i></a></li>';
												$previous=$page-1;
												print '<li style="border-radius:0px;"><a style="border-radius:0px;" title="Goto Previous Page" href=".?page=uTransaction&p='.$previous.'"><i class="glyphicon glyphicon-menu-left"></i></a>';
											}
											if($page==$last){}
											else {
												$next=$page+1;
												print '<li style="border-radius:0px;"><a style="border-radius:0px;" title="Goto Next Page" href=".?page=uTransaction&p='.$next.'"><i class="glyphicon glyphicon-menu-right"></i></a></li>';
												print '<li style="border-radius:0px;"><a style="border-radius:0px;" title="Goto Last Page" href=".?page=uTransaction&p='.$last.'"><i class="glyphicon glyphicon-menu-right"></i><i class="glyphicon glyphicon-menu-right"></i></a>';
											}
					print '			</ul>
											<div class="clearfix"></div>
										</div> ';
					}
					print '
									</div>';
				}
				// all pending transaction
				if($param=="pending") {
					print '	<div class="panel panel-default">
										<div class="panel-heading">
											<strong><i class="glyphicon glyphicon-th"></i> All Pending Transactions</strong>
										</div>
										<div class="panel-body">';
					$get_all_pending=mysql_query("SELECT * FROM transaction WHERE ResponseCode<>'Z1' AND ResponseCode<>'00'");
					if($get_all_pending && mysql_num_rows($get_all_pending)>=1) {
						if(!isset($page)) {
							$page=1;
						}
						// number of rows to display
						$page_rows=10;
						$last=ceil(mysql_num_rows($get_all_pending)/$page_rows);
						if(isset($_GET['p'])) {
							$page=$_GET['p'];
						}
						if($page<1) {
							$page=1;
						}
						else if($page>$last) {
							$page=$last;
						}
						$max="LIMIT ".($page-1)*$page_rows.",".$page_rows;
						print '	<div class="table table-responsive">
											<table class="table table-responsive table-border table-striped table-hover">
												<thead>
													<th>Name & Purpose</th>
<th>Transaction ID</th>
													<th>Transaction Ref</th>
													<th>Transaction Date</th>
													<th>Amount</th>
													<th>Transaction Response</th>
													<th class="text-right">Task</th>
												<thead>
												<tbody>';
						$all_pending_transaction=mysql_query("SELECT * FROM transaction WHERE ResponseCode<>'Z1' AND ResponseCode<>'00' ORDER BY id DESC $max");
						while($all_pending=mysql_fetch_array($all_pending_transaction)) {
							$query_string=$all_pending['amount'].'&trans_id='.$all_pending['trans_id'];
							print '	<tr>
												<td>
													<i class="glyphicon glyphicon-user"></i> '.stripslashes($all_pending['full_name']).'
													<br />
													<small><i class="glyphicon glyphicon-pushpin"></i> '.stripslashes($all_pending['purpose']).'</small>
												</td>
<td>'.$all_pending['trans_id'].'</td>
												<td>'.$all_pending['RetrievalReferenceNumber'].'</td>
												<td>'.$all_pending['log_date'].'</td>
												<td>&#8358;'.number_format($all_pending['amount']/100).'</td>
												<td>'.$all_pending['ResponseDescription'].'</td>
												<td class="text-right"><a target="_new" href="requery.php?amount='.$query_string.'" class="btn-sm btn btn-primary"><i class="glyphicon glyphicon-transfer"></i> ReQuery</a></td>
											</tr>';
						}
						print '			</tbody>
											</table>
										</div>';
					}
					else {
						print '	<blockquote>
											<i class="glyphicon glyphicon-warning-sign text-warning"></i> No transaction found
										</blockquote>';
					}
										print '
										</div>';
					if(mysql_num_rows($get_all_pending)>=1) {
										print '
										<div class="panel-footer">
											<strong class="pull-left"><small>Page '.$page.' of '.$last.'</small></strong>
											<ul class="pull-right pagination" style="padding:0px; margin:0px;">';
											if($page==1){}
											else {
												print '<li style="border-radius:0px;"><a style="border-radius:0px;" title="Goto First Page" href=".?page=pTransaction"><i class="glyphicon glyphicon-menu-left"></i><i class="glyphicon glyphicon-menu-left"></i></a></li>';
												$previous=$page-1;
												print '<li style="border-radius:0px;"><a style="border-radius:0px;" title="Goto Previous Page" href=".?page=pTransaction&p='.$previous.'"><i class="glyphicon glyphicon-menu-left"></i></a>';
											}
											if($page==$last){}
											else {
												$next=$page+1;
												print '<li style="border-radius:0px;"><a style="border-radius:0px;" title="Goto Next Page" href=".?page=pTransaction&p='.$next.'"><i class="glyphicon glyphicon-menu-right"></i></a></li>';
												print '<li style="border-radius:0px;"><a style="border-radius:0px;" title="Goto Last Page" href=".?page=pTransaction&p='.$last.'"><i class="glyphicon glyphicon-menu-right"></i><i class="glyphicon glyphicon-menu-right"></i></a>';
											}
					print '			</ul>
											<div class="clearfix"></div>
										</div> ';
					}
					print '
									</div>';
				}
				// latest successful transaction
				if($param=="latest-success") {
					print '	<h3><i class="glyphicon glyphicon-indent-left"></i> Payment Summary</h3>
									<div class="panel panel-default">
										<div class="panel-heading">
											<strong><i class="glyphicon glyphicon-ok"></i> Last 10 Successful Transactions</strong>
										</div>
										<div class="panel-body">';
					$get_success=mysql_query("SELECT * FROM transaction WHERE ResponseCode='00' ORDER BY id DESC LIMIT 10");
					if($get_success && mysql_num_rows($get_success)>=1) {
						print '	<div class="table table-responsive">
											<table class="table table-responsive table-border table-striped table-hover">
												<thead>
													<th>Name & Purpose</th>
													<th>Transaction Ref</th>
													<th>Transaction Date</th>
													<th>Amount</th>
													<th>Transaction Response</th>
												<thead>
												<tbody>';
						while($success=mysql_fetch_array($get_success)) {
							print '	<tr>
												<td>
													<i class="glyphicon glyphicon-user"></i> '.stripslashes($success['full_name']).'
													<br />
													<small><i class="glyphicon glyphicon-pushpin"></i> '.stripslashes($success['purpose']).'</small>
												</td>
												<td>'.$success['RetrievalReferenceNumber'].'</td>
												<td>'.$success['log_date'].'</td>
												<td>&#8358;'.number_format($success['amount']/100).'</td>
												<td>'.$success['ResponseDescription'].'</td>
											</tr>';
						}
						print '			</tbody>
											</table>
										</div>';
					}
					else {
						print '	<blockquote>
											<i class="glyphicon glyphicon-warning-sign text-warning"></i> No successful transaction found
										</blockquote>';
					}
										print '
										</div>
										<div class="panel-footer text-right">
											<a href=".?page=sTransaction" class="btn btn-default"><i class="glyphicon glyphicon-share-alt"></i> View All</a>
										</div>
									</div>';
				}
			}
			# DASHBOARD WIDGET
			if($type=="widget") {
				
				print '<legend class="text-right">Transactions</legend>';
				if($param=="all") {
					$get_all=mysql_num_rows(mysql_query("SELECT * FROM transaction"));
					$get_s=mysql_num_rows(mysql_query("SELECT * FROM transaction WHERE ResponseCode='00'"));
					$get_u=mysql_num_rows(mysql_query("SELECT * FROM transaction WHERE ResponseCode='Z1'"));
					$get_p=mysql_num_rows(mysql_query("SELECT * FROM transaction WHERE ResponseCode<>'00'"));
					print '	<div class="row">
										<div class="col-md-3 col-xs-6 col-sm-3">
											<div class="widget widget-darkblue">
												<div class="widget-content padding">
													<div class="widget-icon">
														<i class="glyphicon glyphicon-credit-card"></i>
													</div>
													<div class="text-box">
														<p class="maindata hidden-xs"><b>All Transactions</b></p>
														<h2>
															<span class="animate-number" data-value="4000" data-duration="3000">'.number_format($get_all).'</span>
														</h2>
														<div class="clearfix"></div>
													</div>
												</div>
												<div class="widget-footer">
													All Transaction<div class="clearfix"></div>
												</div>
											</div>
										</div>
										<div class="col-md-3 col-xs-6 col-sm-3">
											<div class="widget widget-darkblue">
												<div class="widget-content padding">
													<div class="widget-icon">
														<i class="glyphicon glyphicon-ok"></i>
													</div>
													<div class="text-box">
														<p class="maindata hidden-xs"><b>Successful Transactions</b></p>
														<h2>
															<span class="animate-number" data-value="4000" data-duration="3000">'.number_format($get_s).'</span>
														</h2>
														<div class="clearfix"></div>
													</div>
												</div>
												<div class="widget-footer">
													Successful<div class="clearfix"></div>
												</div>
											</div>
										</div>
										<div class="col-md-3 col-xs-6 col-sm-3">
											<div class="widget widget-darkblue">
												<div class="widget-content padding">
													<div class="widget-icon">
														<i class="glyphicon glyphicon-warning-sign"></i>
													</div>
													<div class="text-box">
														<p class="maindata hidden-xs"><b>Unsuccessful Transactions</b></p>
														<h2>
															<span class="animate-number" data-value="4000" data-duration="3000">'.number_format($get_u).'</span>
														</h2>
														<div class="clearfix"></div>
													</div>
												</div>
												<div class="widget-footer">
													Unsuccessful<div class="clearfix"></div>
												</div>
											</div>
										</div>
										<div class="col-md-3 col-xs-6 col-sm-3">
											<div class="widget widget-darkblue">
												<div class="widget-content padding">
													<div class="widget-icon">
														<i class="glyphicon glyphicon-time"></i>
													</div>
													<div class="text-box">
														<p class="maindata hidden-xs"><b>Pending Transactions</b></p>
														<h2>
															<span class="animate-number" data-value="4000" data-duration="3000">'.number_format($get_p).'</span>
														</h2>
														<div class="clearfix"></div>
													</div>
												</div>
												<div class="widget-footer">
													Pending<div class="clearfix"></div>
												</div>
											</div>
										</div>
									</div>';
				}
			}
		}
		# ALL TRANSACTION PAGE
		public static function all() {
			self::uiHeader();
			self::uiMenu();
			print '	<div class="container">
								<h2><i class="glyphicon glyphicon-credit-card"></i> All Transactions</h2>';
			self::reUse("transaction", "all");
			print '	</div>';
			self::uiFooter();
		}
		# SUCCESSFUL TRANSACTION PAGE
		public static function successful() {
			self::uiHeader();
			self::uiMenu();
			print '	<div class="container">
								<h2><i class="glyphicon glyphicon-ok"></i> Successful Transactions</h2>';
			self::reUse("transaction", "all-success");
			print '	</div>';
			self::uiFooter();
		}
		# UNSUCCESSFUL TRANSACTION PAGE
		public static function unsuccessful() {
			self::uiHeader();
			self::uiMenu();
			print '	<div class="container">
								<h2><i class="glyphicon glyphicon-warning-sign"></i> Unsuccessful Transactions</h2>';
			self::reUse("transaction", "unsuccessful");
			print '	</div>';
			self::uiFooter();
		}
		# PENDING TRANSACTION PAGE
		public static function pending() {
			self::uiHeader();
			self::uiMenu();
			print '	<div class="container">
								<h2><i class="glyphicon glyphicon-time"></i> Pending Transactions</h2>';
			self::reUse("transaction", "pending");
			print '	</div>';
			self::uiFooter();
		}
	}
?>
