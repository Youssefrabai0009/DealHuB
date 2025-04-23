<?php
require_once __DIR__.'/../config.php';
include __DIR__.'/../Model/offre.php';

class offrecontroller
{
    public function listoffers(){
        $sql = 'SELECT * FROM offres';
        $db = config::getConnexion();
        try {
            $list = $db->query($sql);
            return $list;
        } catch(Exception $e) {
            die('Error :' . $e->getMessage());
        }
    }

    public function addOffer($offer)
    {
        $sql = "INSERT INTO offres (montant, date_offre, statut, id_categorie) 
                VALUES (:montant, :date_offre, :statut, :id_categorie)";
        $db = config::getConnexion();
        $query = $db->prepare($sql);
    
        // Format the DateTime object to string (YYYY-MM-DD)
        $formattedDate = $offer->getDateOffre()->format('Y-m-d');
    
        try {
            $query->execute([
                'montant' => $offer->getMontant(),
                'date_offre' => $formattedDate, // Use the formatted string here
                'statut' => $offer->getStatut(),
                'id_categorie' => $offer->getIdCategorie()
            ]);
        } catch(Exception $e) {
            die('Error :' . $e->getMessage());
        }
    }
    
    public function deleteOffer($id_offre)
    {
        $sql ="DELETE FROM offres WHERE id_offre = :id_offre";
        $db = config::getConnexion();
        $query = $db->prepare($sql);
        try {
            $query->execute(['id_offre' => $id_offre]);
        } catch(Exception $e) {
            die('Error :' . $e->getMessage());
        }
    }

    public function showoffer($id_offre)
    {
        $sql = "SELECT * FROM offres WHERE id_offre = :id_offre";
        $db = config::getConnexion();
        $query = $db->prepare($sql);
        try {
            $query->execute(['id_offre' => $id_offre]); 
            $offer = $query->fetch();
            return $offer; 
        } catch(Exception $e) {
            die('Error :' . $e->getMessage());
        }
    }

    public function updateoffer($offer, $id)
{
    $sql = "UPDATE offres SET montant = :montant, date_offre = :date_offre, 
            statut = :statut, id_categorie = :id_categorie WHERE id_offre = :id_offre";
    
    $db = config::getConnexion();
    $query = $db->prepare($sql);
    
    try {
        $query->execute([
            'montant' => $offer->getMontant(),
            'date_offre' => $offer->getDateOffre()->format('Y-m-d'), 
            'statut' => $offer->getStatut(),
            'id_categorie' => $offer->getIdCategorie(),
            'id_offre' => $id
        ]);
    } catch(Exception $e) {
        die('Error :' . $e->getMessage());
    }
}

}
