
// Afficher la nouvelle image de profl sélectionnée
document.getElementById('image').addEventListener('change', function(event) {
    var input = event.target;
    var reader = new FileReader();
    reader.onload = function(){
        var image = document.getElementById('projimage');
        image.src = reader.result;
    };
    reader.readAsDataURL(input.files[0]);
});