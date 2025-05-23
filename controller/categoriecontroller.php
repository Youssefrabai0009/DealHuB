<?php
require_once __DIR__.'/../config.php';
include __DIR__.'/../model/categorie.php';

class catcontroller
{
    public function listcategories(){
        global $pdo;
        $sql='select * from categories';
        try{
            $list=$pdo->query($sql);
            return $list;
        }catch(Exception $e)
        {
            die('Error :'. $e->getMessage());
        }
    }
    public function addCategorie($categorie)
{
    global $pdo;
    $sql = "INSERT INTO categories (id_categorie, libelle_categorie) VALUES (:id_categorie, :libelle_categorie)";
    try {
        $query = $pdo->prepare($sql);
        $query->execute([
            'id_categorie' => $categorie->getIdCategorie(),
            'libelle_categorie' => $categorie->getlibelle_categorie()
        ]);
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
}

    public function deletecategorie($id_categorie)
{
    global $pdo;
    $sql = "DELETE FROM categories WHERE id_categorie = :id_categorie"; // Utilisation du paramètre lié
    $query = $pdo->prepare($sql);
    $query->bindValue(':id_categorie', $id_categorie, PDO::PARAM_INT);
    
    try {
        // Exécution de la requête avec le paramètre lié
        $query->execute(['id_categorie' => $id_categorie]);
    } catch (Exception $e) {
        die('Error : ' . $e->getMessage());
    }
}

public function showcategorie($id_categorie)
{
    global $pdo;
    $sql = "SELECT * FROM categories WHERE id_categorie = :id_categorie";  // Utilisation de paramètre préparé
    $query = $pdo->prepare($sql);
    try {
        // Lier le paramètre :id_categorie
        $query->execute(['id_categorie' => $id_categorie]);
        $categorie = $query->fetch();  // Récupérer le résultat
        return $categorie;
    } catch (Exception $e) {
        die('Error : ' . $e->getMessage());
    }
}

    public function updateCategorie($categorie, $id_categorie)
{
    global $pdo;
    $sql = "UPDATE categories SET libelle_categorie = :libelle_categorie WHERE id_categorie= :id_categorie";
    try {
        $query = $pdo->prepare($sql);
        $query->execute([
            'libelle_categorie' => $categorie->getlibelle_categorie(),
            'id_categorie' => $id_categorie
        ]);
    } catch (Exception $e) {
        die('Error : ' . $e->getMessage());
    }
}

}
