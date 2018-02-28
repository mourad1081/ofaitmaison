<?php
require_once "../models/Database.php";
require_once "../models/App.php";

require "../vendor/autoload.php";

// import the Intervention Image Manager Class
use Intervention\Image\ImageManager;
ini_set('upload_max_filesize', '500M');
ini_set('post_max_size', '500M');

function random_string($length) {
    $key = '';
    $keys = array_merge(range(0, 9), range('a', 'z'));

    for ($i = 0; $i < $length; $i++) {
        $key .= $keys[array_rand($keys)];
    }

    return $key;
}

// Pour l'upload
if($_FILES)
{
    $target_dir = "../img/uploads/";
    $target_name = utf8_encode(random_string(10) . '_' . basename($_FILES["files"]["name"]));
    $target_file = $target_dir .$target_name;
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["files"]["tmp_name"]);
        if($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }
    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }
    // Check file size
    if ($_FILES["files"]["size"] > 10000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"  && $imageFileType != "gif" && $imageFileType != "bmp"
    && $imageFileType != "JPG" && $imageFileType != "PNG" && $imageFileType != "JPEG"  && $imageFileType != "GIF" && $imageFileType != "BMP") {
        echo "Sorry, only JPG, JPEG, PNG GIF & BMP files are allowed.";
        $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["files"]["tmp_name"], $target_file)) {
            // create an image manager instance with favored driver
            $manager = new ImageManager();
            // Création des images dans les bonnes qualités
            $image = $manager->make($target_file);
            $image->resize(null, 300, function ($constraint) { $constraint->aspectRatio(); });
            $image->save('../img/md/' . $target_name, 80);
            $image->resize(null, 150, function ($constraint) { $constraint->aspectRatio(); });
            $image->save('../img/xs/' . $target_name, 60);
            $image->destroy();
            echo "The file ". basename( $_FILES["files"]["name"]). " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

// Pour le reste
if( isset($_POST["action"]) && isset($_POST["data"]) )
{
    $action = $_POST["action"];
    $data = json_decode($_POST["data"]);
    switch ($action)
    {
        // Edite une valeur d'un champ pour un produit
        case 'editProduct':    echo editProduct($data);   break;
        case 'createProduct':  echo createProduct($data); break;
        case 'editCategory':   echo editCategory($data);   break;
        case 'createCategory': echo createCategory($data);   break;
        case 'addCommand':     echo addCommand($data);    break;
        case 'enAvant':
            Database::query("UPDATE produits SET en_avant = :avant WHERE id = :id", [
                "avant" => $data->avant,
                "id" => $data->id
            ]);
            return "1";
            break;
        case 'aTraiter':
            echo var_export($data, true);
            $x = Database::query("UPDATE commandes SET traitee = :t WHERE id = :id",  [
                "t" => $data->atraiter,
                "id" => $data->id
            ]);
            return var_export($x, true);
            break;
        case 'deleteProduct':  ProduitDAL::Delete($data->id); return "1"; break;
        case 'deleteCat' :
            Database::query('DELETE FROM categories WHERE id = :i', ["i" => $data->id]);
            return '1';
        case 'deleteNews' :
            Database::query('DELETE FROM flash_news WHERE id = :i', ["i" => $data->id]);
            return '1';
        case 'sendMail': echo sendMail($data); break;
        case 'changeParentCat':
            Database::query('UPDATE categories SET id_parent = :p WHERE id = :i', [
                "p" => $data->newParent,
                "i" => $data->idChanged
            ]);
            return '1';
        case 'changeDescrCat':
            Database::query('UPDATE categories SET descr_fr = :p WHERE id = :i', [
                "p" => $data->newtext,
                "i" => $data->id
            ]);
            return '1';
        case 'editFlash':
            $q = Database::$Bdd->prepare('UPDATE flash_news SET flash_news.show = :sh, content = :co WHERE id = :id');
            $q->bindParam("co", $data->content, PDO::PARAM_STR);
            $q->bindParam("sh", $data->show, PDO::PARAM_INT);
            $q->bindParam("id", $data->id, PDO::PARAM_INT);
            echo $q->execute(); break;

        case 'removePhoto':
            Database::query('DELETE FROM photos WHERE url = :u  AND produit_id = :p', [
                "u" => $data->url,
                "p" => $data->idProduct
            ]);
            echo '1'; break;
        case 'addPhoto':
            Database::query("INSERT INTO photos (url, produit_id) VALUES (:u, :p)", [
                "u" => basename($data->url),
                "p" => $data->idProduct
            ]);
            echo '1'; break;
        case 'deleteImg':
            // Il faut supprimer cette photos associées aux produits
            Database::query("DELETE FROM photos WHERE url = :u", [
                "u" => $data->path
            ]);
            return unlink('../img/md/' . $data->path)
                    && unlink('../img/xs/' . $data->path)
                    && unlink('../img/uploads/' . $data->path);

        case 'createPost':
            Database::query("INSERT INTO posts (title, content, is_news) VALUES (:t, :co, :n)", [
                "t" => $data->title,
                "co" => $data->content,
                "n" => $data->isNews
            ]);
            echo '1'; break;
        case 'changeFlashVisibility':
            // 1. on met tout les show à 0
            $query = Database::$Bdd->prepare('UPDATE flash_news SET `show` = :z ');
            $query->bindValue("z", 0, PDO::PARAM_INT);
            $query->execute();
            if($data->newVal == '1') {
                $query = Database::$Bdd->prepare('UPDATE flash_news SET `show` = :u WHERE `id` = :i');
                $query->bindValue("u", 1, PDO::PARAM_INT);
                $query->bindParam("i", $data->idFlash, PDO::PARAM_INT);
                $query->execute();
            }
            echo '1';
            break;
        case 'createFlash':
            Database::query("INSERT INTO flash_news (content) VALUES (:co)", [
                "co" => $data->content
            ]);
            echo '1'; break;

    }
}

function editProduct($data)
{
    $query = Database::$Bdd->prepare("UPDATE produits SET descr = :d, img = :ii, nom_fr = :n, ingredients = :i, recette = :r, prix = :p, id_categorie = :ic  WHERE id= :id");
    $query->bindParam("d",  $data->descr, PDO::PARAM_STR);
    $query->bindParam("n",  $data->nom, PDO::PARAM_STR);
    $query->bindParam("r",  $data->recette, PDO::PARAM_STR);
    $query->bindParam("i",  $data->ingr, PDO::PARAM_STR);
    $query->bindParam("ii", $data->img, PDO::PARAM_STR);
    $query->bindParam("p",  $data->prix, PDO::PARAM_INT);
    $query->bindParam("id", $data->id, PDO::PARAM_INT);
    $query->bindParam("ic", $data->idcat, PDO::PARAM_INT);

    echo $query->execute();
}


function createProduct($data)
{
    $query = Database::$Bdd->prepare("INSERT INTO produits(descr, img, nom_fr, ingredients, recette, prix, id_categorie) 
                                        VALUES (:d, :ii, :n, :i, :r, :p, :ic)");
    $query->bindParam("d",  $data->descr, PDO::PARAM_STR);
    $query->bindParam("n",  $data->nom, PDO::PARAM_STR);
    $query->bindParam("r",  $data->recette, PDO::PARAM_STR);
    $query->bindParam("i",  $data->ingr, PDO::PARAM_STR);
    $query->bindParam("ii", $data->img, PDO::PARAM_STR);
    $query->bindParam("p",  $data->prix, PDO::PARAM_INT);
    $query->bindParam("ic", $data->idcat, PDO::PARAM_INT);

    echo $query->execute();

    if(count($data->photos) > 0) {
        $id = Database::GetDatabase()->lastInsertId();
        $query = "INSERT INTO photos(produit_id, url) VALUES ";
        foreach($data->photos as $photo) {
            $query .= "($id, '$photo'),";
        }
        $query = rtrim($query, ',');
        Database::query($query);
    }
}

function createCategory($data)
{
    $query = Database::$Bdd->prepare("INSERT INTO categories(descr_fr, descr_nl, id_parent) VALUES (:d, :dd, :p)");
    $query->bindParam("d",  $data->nom, PDO::PARAM_STR);
    $query->bindParam("dd",  $data->nom, PDO::PARAM_STR);
    $query->bindParam("p",  $data->idcat, PDO::PARAM_INT);
    echo $query->execute();
}


function addCommand($dataCommand)
{
    // Il faut qu'il y ait au moins le gsm ou l'email pour recontacter la personne et que la commande ne soit pas vide
    if(isset($dataCommand)
        and ( ($dataCommand->gsm != "") or ($dataCommand->email != "") )
        and (count($dataCommand->commande) > 0))
    {
        // On ajoute la commande
        Database::query("INSERT INTO commandes (nom_prenom, email, telephone, comment, prix, origine, date_livraison) VALUES (:n, :e, :g, :co, :p, :o, :d)", [
            "n"  => $dataCommand->nom_prenom,
            "e"  => $dataCommand->email,
            "co" => $dataCommand->comment,
            "g"  => $dataCommand->gsm,
            "p"  => $dataCommand->prix,
            "o"  => $dataCommand->origine,
            "d"  => $dataCommand->dateLivraison
        ]);

        // Ne pas utiliser avec postgresql -- On chope l'ID de la commande qu'on vient d'insérer.
        $inserted_id = Database::GetDatabase()->lastInsertId();

        // On contruit l'ajout dans la table commandes_produit
        $requete = "INSERT INTO commande_produits (id_commande, id_plat, nombre) VALUES ";
        $listIds = "(";
        foreach($dataCommand->commande as $c)
        {
            if(     (filter_var($c->id, FILTER_VALIDATE_INT) !== false)
                and (filter_var($c->nb, FILTER_VALIDATE_INT) !== false))
            {
                $requete = $requete . "($inserted_id, $c->id, $c->nb),";
                $listIds = $listIds . "$c->id,";
            }
        }

        // Retire la dernière virgule
        $requete = substr($requete, 0, -1);
        $listIds = substr($listIds, 0, -1);
        $listIds = $listIds . ")";
        // On rajoute les lignes
        Database::query($requete);

        // On chope tous les produits commandés :
        $products = Database::query("SELECT nom_fr, prix, nombre
                                            FROM commande_produits
                                            JOIN produits ON produits.id = commande_produits.id_plat
                                            WHERE commande_produits.id_commande = $inserted_id")->fetchAll();

        // On en fait une liste :
        $commandeStr = '<ul>';
        foreach ($products as $product)
        {
            $commandeStr .= '<li>' . $product->nombre . ' ' . $product->nom_fr . ' = ' . $product->prix*$product->nombre . ' €</li>';
        }

        $commandeStr .= '</ul>';

        // Ensuite, on envoie un mail :
        $mail = (object) array(
            'name' => $dataCommand->nom_prenom,
            'email' => $dataCommand->email,
            'content' => 'Une commande vient d\'arriver sur ofaitmaison.be de la part de '
                . $dataCommand->nom_prenom
                . '. Vous pouvez le recontacter par email (' . $dataCommand->email
                . ') ou par téléphone (' . $dataCommand->gsm
                . '). <br>'
                . '- Il désire : ' . $commandeStr
                . '- Total : ' . $dataCommand->prix . ' €<br>'
                . '- Il désire sa commande pour le : ' . $dataCommand->dateLivraison . '<br>'
                . '- Son commentaire : ' . $dataCommand->comment . '<br>'
                . "- Comment la personne a connu Ô Fait Maison : " . $dataCommand->origine . '<br>'
                . "Les détails de la commande se trouvent aussi sur votre paneau d'administration ofaitmaison."
        );
        sendMail2($mail, 'Nouvelle commande de ' . $dataCommand->nom_prenom);
        echo "1";
    } else
        echo "0";


}

function sendMail2($data, $title) {
    $to  = "mourad1081@gmail.com"; //'info@ofaitmaison.be';
    // Sujet
    $subject = $title;

    // message
    $message = '
     <html>
      <head>
       <title>Nouvelle commande de ' . $data->name . ' (' . $data->email . ')</title>
       <style>
            div {
                border: solid grey 2px;
                border-radius: 5px;
                background-color: #eee;
                padding: 10px;
            }
            h1 {
                color: #2c3e50;
            }
       </style>
      </head>
      <body>
       <h1>Nouvelle commande de ' . $data->name . ' (' . $data->email . ')</h1>
       <div>' . $data->content . '</div>
      </body>
     </html>
     ';

    // Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8" . "\r\n";

    // En-têtes additionnels
    $headers .= 'To: ' . $to . "\r\n";
    $headers .= 'From: Commande O Fait Maison<noreply@ofaitmaison.be>';
    // Envoi
    return mail($to, $subject, $message, $headers);
}













function sendMail($data) {
    $to  = 'info@ofaitmaison.be';
    // Sujet
    $subject = 'Message entrant de ' . $data->name . ' depuis votre site web';

    // message
    $message = '
     <html>
      <head>
       <title>Message entrant de ' . $data->name . ' (' . $data->email . ')</title>
       <style>
            div {
                border: solid grey 2px;
                border-radius: 5px;
                background-color: #eee;
                padding: 10px;
            }
            h1 {
                color: #2c3e50;
            }
       </style>
      </head>
      <body>
       <h1>Message entrant de ' . $data->name . ' (' . $data->email . ')</h1>
       <div><p><strong>Message : </strong></p>' . $data->content . '</div>
      </body>
     </html>
     ';

    // Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=utf8' . "\r\n";

    // En-têtes additionnels
    $headers .= 'To: ' . $to . "\r\n";
    $headers .= 'From: Page contact O Fait Maison<noreply@ofaitmaison.be>';
    // Envoi
    return mail($to, $subject, $message, $headers);
}