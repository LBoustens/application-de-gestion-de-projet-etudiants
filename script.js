// Afficher la nouvelle image sélectionnée
document.getElementById('selectImage').addEventListener('change', function(event) {
    var input = event.target;
    var reader = new FileReader();
    reader.onload = function(){
        var image = document.getElementById('profilImage');
        image.src = reader.result;
    };
    reader.readAsDataURL(input.files[0]);
});