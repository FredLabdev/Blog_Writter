//**************************************************************************************
//                         Interactions pour la deconnexion             
//**************************************************************************************

function deconnect_confirm() {
    if (confirm("Voulez-vous vraiment vous déconnecter ?")) {
        document.location.href = "index.php?action=deconnexion";
        return true;
    } else {
        alert("Je me disais aussi...");
        return false;
    }
}

document.getElementById("deconnexion").addEventListener('click', function (e) {
    e.preventDefault();
    deconnect_confirm();
});

//**************************************************************************************
//                         Interactions pour la saisie WYSIWYG             
//**************************************************************************************

function commande(nom, argument) {
    if (typeof argument === 'undefined') {
        argument = '';
    }
    switch (nom) {
        case "createLink":
            argument = prompt("Quelle est l'adresse du lien ?");
            break;
        case "insertImage":
            argument = prompt("Quelle est l'adresse de l'image ?");
            break;
    }
    document.execCommand(nom, false, argument);
}

// Création d'un nouveau billet
function getNewPostInForm() {
    var newPostInForm = document.getElementById("newPostInForm"); // valeur de saisie récupérée
    var newPostPlainText = document.getElementById("newPostPlainText"); // qui sera placée, en texte brut
    newPostPlainText.value = newPostInForm.innerText; // passé en valeur, pour céation de l'extrait,
    var newPostHTML = document.getElementById("newPostHTML"); // et en HTML,
    newPostHTML.value = newPostInForm.innerHTML; // pour publicatoin complète mise en forme
}

// Modification d'un billet
function getModifPostInForm() {
    var modifPostInForm = document.getElementById("modifPostInForm"); // valeur de saisie récupérée
    var modifPostPlainText = document.getElementById("modifPostPlainText"); // qui sera placée, en texte brut
    modifPostPlainText.value = modifPostInForm.innerText; // passé en valeur, pour nouvel extrait,
    var modifPostHTML = document.getElementById("modifPostHTML"); // et en HTML,
    modifPostHTML.value = modifPostInForm.innerHTML; // pour nouvelle publication complète mise en forme
}

//**************************************************************************************
//                    Interactions pour la modification d'un compte             
//**************************************************************************************

var form = document.getElementById('form_modif');
var champ = document.getElementById('champ');
var missChamp = document.getElementById('modif_champ');
var missChampConfirm = document.getElementById('modif_champ_confirm');
var errorChamp = document.getElementById('error1');
var errorChampConfirm = document.getElementById('error2');

if (missChamp.validity.valueMissing) {
    if (champ.value == "1") {
        missChamp.setCustomValidity("Veuillez entrer votre nouvelle adresse mail.");
    } else {
        missChamp.setCustomValidity("Veuillez entrer votre nouveau mot de passe.");
    }
} else {
    missChamp.setCustomValidity("");
    if (missChampConfirm.validity.valueMissing) {
        if (champ.value == "1") {
            missChampConfirm.setCustomValidity("Veuillez confirmer cette nouvelle adresse mail.");
        } else {
            missChampConfirm.setCustomValidity("Veuillez confirmer ce nouveau mot de passe.");
        }
    } else {
        missChampConfirm.setCustomValidity("");
    }
}

// On place un message d'erreur

missChamp.addEventListener("input", function (event) {
    // Chaque fois que l'utilisateur saisit quelque chose dans le 1er champ
    // S'il y a un message d'erreur affiché et que le champ est valide, on retire l'erreur
    errorChamp.innerHTML = ""; // On réinitialise le contenu
    errorChamp.className = "error"; // On réinitialise l'état visuel du message
    errorChampConfirm.innerHTML = ""; // On réinitialise le contenu
    errorChampConfirm.className = "error"; // On réinitialise l'état visuel du message
    missChampConfirm.setAttribute("required", ""); // on rend le 2nd champ obligatoire
    if (!missChamp.value) { // si le champ est vide.
        missChampConfirm.removeAttribute("required"); // on désactive le 2nd champ obligatoire
    }
}, false);

missChampConfirm.addEventListener("input", function (event) {
    // Chaque fois que l'utilisateur saisit quelque chose dans le 2nd champ
    errorChampConfirm.innerHTML = ""; // On réinitialise le contenu
    errorChampConfirm.className = "error"; // On réinitialise l'état visuel du message
}, false);

form.addEventListener("submit", function (event) {
    if (errorChamp.innerHTML !== "") {
        errorChamp.className = "error active";
    }
    if (errorChampConfirm.innerHTML !== "") {
        errorChampConfirm.className = "error active";
    }
}, false);
