window.onload = function() {
    const rows = document.querySelectorAll(".offres-table tbody tr");

    rows.forEach(row => {
        const statusCell = row.querySelector("td:last-child"); // la dernière cellule est le statut
        const status = statusCell.textContent.trim().toLowerCase();

        if (status === "acceptée") {
            row.classList.add("status-accepte");
        } else if (status === "rejetée") {
            row.classList.add("status-rejete");
        }
    });
};
    function choisirVideo(src, nom, montant, equity) {
        document.getElementById("videoPlayer").src = src;
        document.getElementById("offreDetails").textContent = `${nom} propose ${montant} DT pour ${equity}% de parts.`;
    }
    
    function accepterOffre() {
        alert("Offre acceptée avec succès !");
    }
    
   
    
    function envoyerNouvelleOffre() {
        let montant = document.getElementById("newMontant").value;
        let equity = document.getElementById("newEquity").value;
        alert(`Nouvelle offre envoyée : ${montant} DT pour ${equity}% de parts.`);
    }
    
    function envoyerMessage() {
        let message = document.getElementById("messageEntrepreneur").value;
        alert(`Message envoyé : ${message}`);
    }
    
    function retourVideos() {
        document.getElementById("videoPlayer").src = "";
        document.getElementById("offreDetails").textContent = "Sélectionnez une vidéo pour voir l'offre.";
    }
    
    function rejeterOffre() {
        if (confirm("Êtes-vous sûr de vouloir rejeter cette offre ?")) {
            alert("Offre rejetée.");
        }
    }