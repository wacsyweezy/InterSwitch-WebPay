<?php
#********* Open Source Web Based InterSwitch webPay Application in PHP ********** # 
# In order to use this snippet, there's a need for a database. In the bundled     #
# snippet, there's an sql file that contact a the transaction schema              #
###################################################################################

# Database Configuration
$db = mysql_connect('localhost', 'root', 'web');
mysql_select_db('webPay', $db);


# InterSwitch Compulsory Variables Setting.

#product ID, to be provided by interswitch when successfuly registarted as a merchant
$product_id = "6205";

#Pay Item ID, to be provided by interswitch when successfuly registarted as a merchant
$pay_item_id = "101";

# Site Redirect URL, to be provided by you for interswitch, used to recieve payment response from interswitch
$site_redirect_url = "http://localhost/webPay/webpay.php";

# Currency, For naira, it's 566, there are other currency codes, it's all available on interswitch website
$currency = "566";

# MAC, to be provided by interswitch when successfuly registarted as a merchant
$mac = "D3D1D05AFE42AD50818167EAC73C109168A0F108F32645C8B59E897FA930DA44F9230910DAC9E20641823799A107A02068F7BC0F4CC41D2952E249552255710F";

# Payment API EP, below is the test end point. When on prod, live endpoint should be used. 
$payment_ep = "https://sandbox.interswitchng.com/webpay/pay";

# Transaction Query API EP, below is the test end point. When on prod, live endpoint should be used.
$trans_query_ep = "https://sandbox.interswitchng.com/webpay/api/v1/gettransaction.json";
?>
