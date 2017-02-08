$(function() {
	$('.input-daterange input').each(function() {
	    $(this).datepicker({
	    	todayHighlight: true,
	    	format: 'yyyy-mm-dd',
	    	autoclose: true
	    });
	});
});