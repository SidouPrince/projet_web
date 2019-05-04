$(document).ready(function(){
    $('#btnService').click(function(event) {
    	event.preventDefault();
    	$('#btnService').attr('disabled', 'true');
    	$('#monGif').show();

    	societe = $('#societe option:selected').val();	
    	service = $('#service').val();
    	prix = $('#prix').val();
    	idEtablissement = $('#'+societe).val();

    	const url1 = this.href;

   	// Send a POST request
    	axios.post(url1, {
         societe: idEtablissement,
         service: service,
         prix: prix
  })
  .then(function (response) {
    console.log(response.data.message);
    
    
    $('#alertService').show();
   
    setTimeout(function(args) {
	     $("#monGif").hide('1500');
	     setTimeout(function(args) {
	     
	   	 $('#alertService').fadeOut();
	   	 $('#btnService').attr('disabled', 'false');	 	
	    }, 1500)	 	
    }, 1500)
  })
  .catch(function (error) {
    console.log(error);
  }); 	



});
});    