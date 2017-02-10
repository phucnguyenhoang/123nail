$(function() {
	$('.input-daterange input').each(function() {
	    $(this).datepicker({
	    	todayHighlight: true,
	    	format: 'yyyy-mm-dd',
	    	autoclose: true
	    });
	});

	var employeeUrl = $('#hidEmployeeUrl').val();
	$('#cboShop').change(function() {
		$('#cboEmployee').addClass('ctr-loading').prop('disabled', true);
		$.ajax({
			url: employeeUrl + $(this).val(),
			method: 'get',
			type: 'json',
			dataType: 'json',
			success: function(data) {
				$('#cboEmployee').html(_makeEmployeeList(data));
				$('#cboEmployee').removeClass('ctr-loading').prop('disabled', false);
			}
		});
	});
});

function _makeEmployeeList(data) {
	var html = '<option value="0">All</option>';
	$.each(data, function(key, name) {
		html += '<option value="' + key + '">' + name + '</option>';
	});

	return html;
}