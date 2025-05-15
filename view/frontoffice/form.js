document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector("form");

  form.addEventListener("submit", function (e) {
    let hasErrors = false;

    const montant = document.getElementById("montant");
    const dateOffre = document.getElementById("date_offre");
    const statut = document.getElementById("statut");

    // Spans d'erreur
    const errorMontant = document.getElementById("error-montant");
    const errorDate = document.getElementById("error-date");
    const errorStatut = document.getElementById("error-statut");

    // Réinitialiser les erreurs
    errorMontant.textContent = "";
    errorDate.textContent = "";
    errorStatut.textContent = "";

    // Contrôle montant
    if (montant.value <= 0 || isNaN(montant.value)) {
      errorMontant.textContent = "Le montant doit être positif.";
      hasErrors = true;
    }

    // Contrôle date
    const dateNow = new Date().toISOString().split("T")[0];
    if (!dateOffre.value) {
      errorDate.textContent = "Veuillez entrer une date.";
      hasErrors = true;
    } else if (dateOffre.value > dateNow) {
      errorDate.textContent = "La date ne peut pas être dans le futur.";
      hasErrors = true;
    }

    // Contrôle statut
    if (!statut.value) {
      errorStatut.textContent = "Veuillez sélectionner un statut.";
      hasErrors = true;
    }

    if (hasErrors) e.preventDefault(); // annule l'envoi
  });
});

