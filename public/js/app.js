// Faire disparaître les messages flash après 2 secondes
disappear = function() {
    $("#flashMessages").fadeOut().empty();
}
setTimeout(disappear, 2000);

// Afficher un aperçu de la photo de profil avant de soumettre le formulaire
let loadFile = function (event) {
    let reader = new FileReader();
    reader.onload = function() {
        let output = document.getElementById('output');
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
};