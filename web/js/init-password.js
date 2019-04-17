$(document).ready(function(){
    $('#btn_password').attr('disabled', true);
    $('#confirmation').hide();

    var terminee = document.getElementById('inscriptionTerminee').value;
    if (terminee == 1) {
        $('#confirmation').show();        
    }

    function verification(){
    //recuperer les deux mot de passes
    var mdp1 = $("#password").val();
    var mdp2 = $("#cpassword").val();
    console.log(mdp1);
        if ( mdp1 != mdp2 ) {
        $("#password").attr('class', 'form-control is-invalid');
        $("#cpassword").attr('class', 'form-control is-invalid');
        $('#btn_password').attr('disabled', true);
    }else{
        $("#password").attr('class', 'form-control is-valid');
        $("#cpassword").attr('class', 'form-control is-valid');
        $('#btn_password').attr('disabled', false);
    }    
    }

    $("#cpassword").change(function(event) {
      verification();
    });
    $("#password").change(function(event) {
      verification();
    });

});