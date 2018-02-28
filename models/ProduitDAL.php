<?php

require_once("App.php");

class ProduitDal implements  DAL {

    public static function Read($id)
    {
        return Database::query("SELECT * FROM produits WHERE id = :id", ["id" => $id])->fetch();
    }
    public static function ReadAll()
    {
        return Database::query("SELECT * FROM produits")->fetchAll();
    }
    public static function ReadAll2()
    {
        return Database::query("SELECT produits.id, produits.descr, produits.en_avant, 
                                            produits.id_categorie, categories.id_parent, 
                                            produits.nom_fr, produits.prix, produits.ingredients, 
                                            produits.img 
                                     FROM produits
                                     JOIN categories ON produits.id_categorie = categories.id")->fetchAll();
    }
    public static function Delete($id)
    {
        Database::query("DELETE FROM photos WHERE produit_id = :id", ["id" => $id]);
        return Database::query("DELETE FROM produits WHERE id = :id", ["id" => $id]);

    }
    public static function Update($user)
    {

    }
    public static function Create($produit)
    {
        return Database::query("INSERT INTO produit (nom_fr, nom_nl, ingredients, recette, prix, img, id_cat, descr)
                                    VALUES (:nom_fr, :ingredients, :recette, :prix, :img, :id_cat, descr)",
            [
                "nom_fr"      => $produit["nom_fr"],
                "descr"       => $produit["descr"],
                "nom_nl"      => $produit["nom_nl"],
                "ingredients" => $produit["ingredients"],
                "recette"     => $produit["recette"],
                "prix"        => $produit["prix"],
                "img"         => $produit["img"],
                "id_cat"      => $produit["id_cat"]
            ]);
    }
}