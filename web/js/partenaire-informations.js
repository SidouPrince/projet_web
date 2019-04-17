$(document).ready(function(){
    

    //cacher la div dalerte si un email existe déja
    $('#existe').hide();

    
    /*
        on recupere la valeur de la div si dejaExiste = 0 on laisse la div cachée
        veut dire que linsertion est reussie sinon on la montre
    */
    var dejaExiste = document.getElementById('dejaExiste').value;

    if (dejaExiste == 1) {
        $('#existe').show();
    } 
});