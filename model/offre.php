<?php

class offer
{
    private ?int $id_offre;
    private ?int $montant;
    private ?DateTime $date_offre;
    private ?string $statut;
    private ?int $id_categorie;
    private ?int $user_id;
    public function __construct(?int $id_offre, ?int $montant, ?DateTime $date_offre, ?string $statut, ?int $id_categorie,?int $user_id)


    {
        $this->id_offre=$id_offre;
        $this->montant=$montant;
        $this->date_offre=$date_offre;
        $this->statut=$statut;
        $this->id_categorie=$id_categorie;
        $this->user_id=$user_id;

    }
    public function getIdOffre(): ?int
    {
        return $this->id_offre;
    }
    public function getIduser(): ?int
    {
        return $this->user_id;
    }

    public function getIdCategorie(): ?int
    {
        return $this->id_categorie;
    }

    public function getDateOffre(): ?DateTime
    {
        return $this->date_offre;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    
    public function setIdOffre(?int $id_offre): void
    {
        $this->id_offre = $id_offre;
    }
    public function setIduser(?int $user_id): void
    {
        $this->user_id = $user_id;
    }
    public function setIdCategorie(?int $id_categorie): void
    {
        $this->id_categorie = $id_categorie;
    }

    public function setDateOffre(?DateTime $date_offre): void
    {
        $this->date_offre = $date_offre;
    }

    public function setMontant(?int $montant): void
    {
        $this->montant = $montant;
    }

    public function setStatut(?string $statut): void
    {
        $this->statut = $statut;
    }
}







?>