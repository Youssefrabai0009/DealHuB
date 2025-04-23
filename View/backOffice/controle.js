setTimeout(function(){ location.reload(); }, 10000);
function validerFormulaire() {
    let valide = true;

    document.getElementById("erreurId").textContent = "";
    document.getElementById("erreurNom").textContent = "";

    const id = document.getElementById("id_categorie").value.trim();
    const nom = document.getElementById("nom_categorie").value.trim();

    if (id === "") {
      document.getElementById("erreurId").textContent = "L'ID est requis.";
      valide = false;
    }

    if (nom === "") {
      document.getElementById("erreurNom").textContent = "Le nom de la catégorie est requis.";
      valide = false;
    } else if (nom.length < 3) {
      document.getElementById("erreurNom").textContent = "Le nom doit contenir au moins 3 caractères.";
      valide = false;
    }

    return valide;
  }