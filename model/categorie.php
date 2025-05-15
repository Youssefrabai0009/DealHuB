<?php

class categorie
{
    private ?int $id_categorie;
    private ?string $libelle_categorie;
    public function __construct(?int $id_categorie,?string $libelle_categorie)
    {
        $this->id_categorie=$id_categorie;
        $this->libelle_categorie=$libelle_categorie;
       

    }

   public function getIdCategorie(): ?int
    {
        return $this->id_categorie;
    }


    public function getlibelle_categorie(): ?string
    {
        return $this->libelle_categorie;
    }


    public function setIdCategorie(?int $id_categorie): void
    {
        $this->id_categorie = $id_categorie;
    }

    public function setlibelle_categorie(?string $libelle_categorie): void
    {
        $this->libelle_categorie = $libelle_categorie;
    }
}







?>