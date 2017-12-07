# InterSwitch-WebPay
An open-source implementation of interswitch payment gateway

To use, all you need to do is just to modify the config.inc file bulded in the snippet.

# Demo Card details

Please note, real Credit/Debit cards will not work on the Test Environment. To perform all your Test Transactions, kindly select one of the Test Cards cards below based on the scenario you’re testing for:-

This is the demo card with which you would always get a Approved Successful response
1. Success
6280511000000095
Dec 2026
0000
123

This is the demo card that would decline with ‘Z1′ and a response description of No Card Record. See the response codes page for more details
2. No Card Record
5061030000000000043
Oct 2018
1234
123 

This is the demo card that would decline with ‘Z1′ and a response description of Incorrect PIN. See the response codes page for more details
2. Incorrect PIN Card
5061030000000000035
Sept 2019
0000
123

This is the demo card that would return with Insufficient Funds
3. Insufficient Funds
5061030000000000027
Sept 2019
1234
123

This snippet has two interface.
1. End User
2. Admin User

The end user interface is the base app. the admin interface is inside transLog folder.

Enjoy!
