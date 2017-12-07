function number_format(number, decimals, dec_point, thousands_sep) {
	number = (number + '')
	 .replace(/[^0-9+\-Ee.]/g, '');
	var n = !isFinite(+number) ? 0 : +number,
	 prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
	 sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
	 dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
	 s = '',
	 toFixedFix = function(n, prec) {
		 var k = Math.pow(10, prec);
		 return '' + (Math.round(n * k) / k)
			 .toFixed(prec);
	 };
	// Fix for IE parseFloat(0.55).toFixed(0) = 0;
	s = (prec ? toFixedFix(n, prec) : '' + Math.round(n))
	 .split('.');
	if (s[0].length > 3) {
	 s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
	}
	if ((s[1] || '')
	 .length < prec) {
	 s[1] = s[1] || '';
	 s[1] += new Array(prec - s[1].length + 1)
		 .join('0');
	}
	return s.join(dec);
}

$("input#amount").keydown(function (e) {
	// Allow: backspace, delete, tab, escape, enter and .
	if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
		// Allow: Ctrl+A, Command+A
		(e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) || 
		// Allow: home, end, left, right, down, up
		(e.keyCode >= 35 && e.keyCode <= 40)) {
		 // let it happen, don't do anything
 		return;
	}
	// Ensure that it is a number and stop the keypress
	if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
		e.preventDefault();
	}
});
$("input#amount").blur(function() {
	var amount=$.trim($('input#amount').val());	
	$(this).val(number_format(amount));		
});
$('button#give_now').click(function() {
	var name=$.trim($('input#fname').val());
	var purpose=$.trim($('input#purpose').val());
	var amount=$.trim($('input#amount').val());
	if(name.length<3) {
		alert("Please enter your name");
		return false;
	}
	if(purpose.length<3) {
		alert("Please enter your purpose of giving");
		return false;
	}
	if(amount==0) {
		alert("Invalid amount entered");
		return false;
	}
	else {
		return true;
	}
});
