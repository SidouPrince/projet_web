$(document).ready(function(){
    
    //cacher la div dalerte si un nom etablissement existe déja
    $('#etablissementExiste').hide();
    /*
        on recupere la valeur de la div si dejaExiste = 0 on laisse la div cachée
        veut dire que linsertion est reussie sinon on la montre
    */
    
    var etablissementExiste = document.getElementById('etablExiste').value;
    console.log(etablissementExiste);
    if (etablissementExiste == 1) {
        $('#etablissementExiste').show();
    } 
});