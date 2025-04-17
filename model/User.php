<?php
class User {
    private $nom;
    private $prenom;
    private $email;
    private $role;

    public function __construct($nom, $prenom, $email, $role) {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->role = $role;
    }

    public function getNom() {
        return $this->nom;
    }

    public function getPrenom() {
        return $this->prenom;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getRole() {
        return $this->role;
    }
}
