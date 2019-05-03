$(document).ready(function(){
    $('#btnService').click(function(event) {
    	$('#btnService').attr('disabled', 'true');
    	$('#monGif').show();
    	societe = $('#selection').val();
    	service = $('#service').val();
    	prix = $('#prix').val();
    	
    	return false;
    });
});