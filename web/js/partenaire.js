$(document).ready(function(){
	var div_50 = document.getElementById('confirmation_partenaire');
    $('#confirmation_partenaire').hide();

    $("#btn_etablissement").attr("disabled",true);
    function bonjour(){
    	alert('bonjour');
    }
    var s = document.getElementById('hid').value;
    console.log(s);
    if (s == 11) {
		$("#btn_etablissement").attr("disabled", false);
		$("#btn_partenaire").attr("disabled",true);
		$('#confirmation_partenaire').show();
    }
    if (s == 14) {
    	//ce code sera execué si on insere un nom établissement
    	bonjour();
    }
    
});