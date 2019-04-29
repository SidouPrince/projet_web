$(document).ready(function(){
    //cacher la div dalerte si un email existe déja
    $('#authentificationError').hide();
    /*
        on recupere la valeur de la div si dejaExiste = 0 on laisse la div cachée
        veut dire que linsertion est reussie sinon on la montre
    */
    var Existe = document.getElementById('dejaExiste').value;
    console.log(Existe)
    if (Existe == 1) {
        $('#authentificationError').show();
    } 
});