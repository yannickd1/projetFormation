function detailMembre(id_membre) {
    location = `detail.php?id_membre=${id_membre}`;
}
function detailMatch(id_match) {
    location = `feuilledematch.php?id_match=${id_match}`;
}

function detailTraining(id_entrainement) {
    location = `participant.php?id_entrainement=${id_entrainement}`;
}

function ajouterMembre(id_membre) {
    location = `editer.php?id_membre=${id_membre}`;
}

function editerMembre(evt, id_membre) {
    evt.stopPropagation();
    location = `ajoutermembre.php?id_membre=${id_membre}`;
}

function supprimerMembre(evt,id_membre) {
    evt.stopPropagation();
    if (confirm("Voulez-vraiment supprimer ce membre ?" )) {
        let url = 'supprimer.php?id_membre='+ id_membre;
        fetch(url)
                .then(response => {
                    if (!response.ok)
                        location.reload();
                })
                .catch(error => console.error(error));
    }

}

function supprimerImage(evt,id_membre){
     evt.stopPropagation();
    if (confirm("Voulez-vous vraiment supprimer cette image ?" )){
        let url = 'supprimerImage.php?id_membre='+ id_membre;
        fetch(url)
                .then(response => {
                    if (!response.ok)
                        location.reload();
                })
                .catch(error => console.error(error));
    }
}
function annuler(id_membre = 0) {
    document.form1.reset();
    document.querySelector('.erreur').innerHTML = '';
    document.querySelector('#vignette').style.backgroundImage = `url(img/memb_${id_membre}_v.jpg)`;
}

function sinscrire(id_membre,id_entrainement){
    location =`sinscrire.php?id_membre=${id_membre}&id_entrainement=${id_entrainement}`;
}



function afficherPhoto(files) {
    var vignette = document.querySelector('#vignette');
    vignette.style.backgroundImage = '';
    if (!files || !files.length)
        return;
    var file = files[0];
    if (!file.size)
        return alert("Error : empty file.");
    if (file.size > MAX_FILE_SIZE)
        return alert("Error : file too big.");

    var ext = file.name.split('.').pop;
    if (TAB_EXT.length && !TAB_EXT.includes(ext))
        return alert("Error : file extension not allowed.");

    if (TAB_MIME.length && !TAB_MIME.includes(file.type))
        return alert("Error : file MIME type not allowed.");

    var reader = new FileReader();
    reader.onload = function () {
        vignette.style.backgroundImage = `url(${this.result})`;

    };
    reader.readAsDataURL(file);
}



function annuler(id_membre = 0) {
    document.form1.reset();
    document.querySelector('.erreur').innerHTML = '';
    document.querySelector('#vignette').style.backgroundImage = `url(img/memb_${id_membre}_v.jpg)`;
}