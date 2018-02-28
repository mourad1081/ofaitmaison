<?php
    session_start();
    require("shared/header.php");

    if(isset($_GET["logout"]) and $_GET["logout"] == "true")
        $_SESSION["auth"] = false;
    else if(isset($_POST["username"]) and ($_POST["username"] === "sachnak")
        and isset($_POST["password"]) and ($_POST["password"] === "Real7325"))
    {
        $_SESSION["auth"] = true;
    }
    if(!isset($_SESSION["auth"]) || ($_SESSION["auth"] == false)) {
        echo '<form action="admin" method="post">
                    <h1>Simple mesure de sécurité. Pouvez-vous vous identifier ?</h1>
                    <input type="text"     name="username" placeholder="Identifiant" />
                    <input type="password" name="password" placeholder="Mot de passe" />
                    <input type="submit" value="S\'authentifier" />
              </form>';
        return;
    }

    /**
     * Get excerpt from string
     *
     * @param String $str String to get an excerpt from
     * @param Integer $startPos Position int string to start excerpt from
     * @param Integer $maxLength Maximum length the excerpt may be
     * @return String excerpt
     */
    function getExcerpt($str, $startPos=0, $maxLength=100) {
        if(strlen($str) > $maxLength) {
            $excerpt   = substr($str, $startPos, $maxLength-3);
            $lastSpace = strrpos($excerpt, ' ');
            $excerpt   = substr($excerpt, 0, $lastSpace);
            $excerpt  .= '...';
        } else {
            $excerpt = $str;
        }

        return $excerpt;
    }
?>


<body>
    <div id="app" class="container-fluid">
        <div class="row">
            <div id="sidebar" class="col-md-2">
                <img src="icon-site-ombre.png" alt="" width="100%">
                <h3 id="sidebar-title" class="h3-responsive" style="color: white;">Administration</h3>
                <ul id="option-items">
                    <li data-show="#widget-recettes" class="active"><i class="fa fa-shopping-bag"></i> Gérer les recettes</li>
                    <li data-show="#widget-articles"><i class="fa fa-newspaper-o"></i> Gérer les news/articles</li>
                    <li data-show="#widget-commandes">
                        <i class="fa fa-shopping-basket"></i> Voir les commandes
                        <?php
                        $commandes = Database::query("SELECT * FROM commandes ORDER BY traitee ASC, date_creation DESC")->fetchAll();

                        $commandes_with_lines = Database::query("SELECT commandes.id, produits.nom_fr, produits.prix, commande_produits.nombre 
                                                FROM commandes
                                                JOIN commande_produits on commandes.id = commande_produits.id_commande
                                                JOIN produits on commande_produits.id_plat = produits.id
                                                ORDER BY date_creation DESC")->fetchAll();
                        $nbTraitees = 0;
                        foreach ($commandes as $c) {
                            if ($c->traitee == "0") $nbTraitees++;
                            else break; // On peut break car c'est trié par traité grâce à la requete sql
                        }
                        ?>
                        <span style="background-color: red;padding: 2px 6px;border-radius: 3px;"><?php echo $nbTraitees; ?></span>
                    </li>
                </ul>
                <h4>Menu rapide</h4>
                <ul class="menu-rapide">
                    <li><a href="accueil" class="nav-link">Accueil</a></li>
                    <li><a href="histoire" class="nav-link">L'histoire</a></li>
                    <li><a href="produits" class="nav-link">Nos produits</a></li>
                    <li><a href="commander" class="nav-link">Commander</a></li>
                    <li><a href="contact" class="nav-link">Contact</a></li>
                </ul>
                <a id="logout" href="admin?logout=true"><i class="fa fa-sign-out"></i> Se déconnecter</a>
            </div>
            <div id="main" class="col-sm-12 col-md-10">



                <!-- WIDGET RECETTES -->
                <div id="widget-recettes" class="row">
                    <h1 class="bg-theme-admin">Gestion des recettes</h1>
                    <div class="container">
                        <p class="alert alert-info">Uploader une image</p>

                        <div id="drop-area-div" style="height:128px;">
                            Glissez et déposez vos images ici OU
                            <input type="file" name="files[]" multiple="multiple" title="Cliquez pour ajouter des images">
                            <br>
                            <img  style="height:95px" src="img/z.gif" alt="">
                        </div>

                        <div class="row">
                            <p class="alert alert-warning">
                                <i style="color:#ff8a00;" class="fa fa-star"></i>
                                 Signifie que le produit sera affiché à l'accueil et
                                 dans la catégorie "A la une", et
                                <i style="color:#ff8a00;" class="fa fa-star-o"></i>
                                signifie que le produit <strong>ne sera pas</strong>
                                affiché à l'accueil ni dans la catégorie "A la une".
                            </p>
                        </div>
                    </div>

                    <!-- CREER RECETTE / CAT -->
                    <div class="row">
                        <button id="create-recette" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Créer une recette
                        </button>

                        <button id="create-cat" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Créer une catégorie
                        </button>
                    </div>

                    <!-- ONGLETS -->
                    <div class="onglets container-fluid">
                        <div id="onglet-produits" role="presentation" class="col-xs-4 onglet active">Produits</div>
                        <div id="onglet-cat" role="presentation" class="col-xs-4 onglet">Catégorie</div>
                        <div id="onglet-img" role="presentation" class="col-xs-4 onglet">Images</div>
                    </div>


                    <!-- TABLE RECETTES -->
                    <table id="table-produits" class="table table-striped table-sm table-bordered table-hover">
                        <thead class="thead-inverse">
                        <tr>
                            <th>Actions</th>
                            <th>Nom</th>
                            <th><i class="fa fa-tag"></i></th>
                            <th><i class="fa fa-tags"></i></th>
                            <th><i class="fa fa-eur"></i></th>
                            <th>Decription</th>
                            <th>Ingrédients</th>
                            <th>Recette</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        global $produits;
                        $produits = Database::query(
                            "SELECT produits.id AS prod_id, nom_fr, descr, recette, img, prix, ingredients, 
                                        cats.id AS id_cat, cats.descr_fr AS cat_descr, produits.id_categorie, 
                                        parentcats.descr_fr AS pcats_descr, parentcats.id AS id_cat_parent, en_avant
                                    FROM produits
                                    LEFT JOIN categories AS cats ON cats.id = produits.id_categorie
                                    LEFT JOIN categories AS parentcats ON parentcats.id = cats.id_parent")->fetchAll();

                        foreach ($produits as $p) { ?>
                            <tr>
                                <td class="actions" data-product-id="<?php echo $p->prod_id; ?>">
                                    <?php
                                    if($p->en_avant == 1)
                                        echo '<i data-en-avant="1" style="color:#ff8a00;" class="en_avant fa fa-star"></i> ';
                                    else
                                        echo '<i data-en-avant="0" style="color:#ff8a00;" class="en_avant fa fa-star-o"></i> ';
                                    ?>
                                    <i class="fa fa-pencil edit-product"></i>
                                    <i class="fa fa-trash delete-product"> </i>
                                    <?php
                                        // On va récupérer les URL de toutes les images de ce produit :
                                        $imgs = Database::query("SELECT * FROM photos WHERE produit_id = :p", ["p" => $p->prod_id])->fetchAll();
                                        foreach ($imgs as $img)
                                            echo "<input class='img-of-prod' type='hidden' value='$img->url' />";
                                    ?>
                                </td>
                                <td class="table-nom"><?php echo $p->nom_fr; ?></td>
                                <td class="table-cat"><?php echo $p->pcats_descr; ?></td>
                                <td class="table-subcat"><?php echo $p->cat_descr; ?></td>
                                <td class="table-prix"><?php echo $p->prix; ?></td>
                                <td class="table-descr"><?php echo $p->descr; ?></td>
                                <td class="table-ingr"><?php echo $p->ingredients; ?></td>
                                <td class="table-recette"><?php echo $p->recette; ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>

                    <div id="table-cat" class="container-fluid">
                        <p class="alert alert-info">
                            Bouger (glisser-déposer) la sous-catégorie dans la catégorie que vous voulez.
                        </p>
                        <?php
                            global $cats, $subcats;
                            $cats = Database::query("SELECT * FROM categories WHERE id_parent = 0")->fetchAll();
                            $subcats = Database::query("SELECT * FROM categories WHERE id_parent != 0")->fetchAll();
                            foreach ($cats as $c) {
                                echo "<p data-id='$c->id' class='cat' ondrop='changeCatParent(event)'>";
                                    echo "<span data-id='$c->id'><i class='fa fa-pencil edit-cat-name'></i> $c->descr_fr</span><br>";
                                    foreach ($subcats as $sc) {
                                        if($sc->id_parent == $c->id)
                                            echo "<span data-id='$sc->id' 
                                                      class='subcat' 
                                                      ondragstart='dragStart(event, $sc->id)' 
                                                      draggable='true'><i class='fa fa-pencil edit-cat-name'></i> $sc->descr_fr</span>";
                                    }
                                echo "</p>";
                            }
                        ?>
                        <p class="alert alert-danger" style="text-align: center;" ondrop="deleteCatSubcat(event)">
                            <i class="fa fa-4x fa-trash"></i> <br>
                            Glisser une catégorie ici pour la supprimer
                        </p>
                    </div>

                    <div id="gestion-img" class="container-fluid">
                        <?php
                        $files = glob("../img/xs/*.{[jJ][pP][gG],[jJ][pP][eE][gG],[pP][nN][gG],[gG][iI][fF],[bB][mM][pP]}", GLOB_BRACE);


                        foreach ($files as $img) {
                            echo '<div>
                                      <i class="fa fa-trash delete-gestion-img" data-path="' . mb_basename($img) . '"></i>
                                      <img class="photo-gallery" alt="' . mb_basename($img) . '" src="img/xs/' . mb_basename($img) . '"/>
                                  </div>';
                        }
                        ?>
                    </div>

                    <!-- Modal edit product -->
                    <div class="modal fade modal-ext" id="modal-product" tabindex="-1"
                         role="dialog" aria-labelledby="myModalLabel"
                         aria-hidden="true" data-product-id="0" data-action="edit">

                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h4 class="modal-title w-100" id="myModalLabel">Editer le produit</h4>
                                </div>
                                <div id="body-edit-product" class="modal-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="modal-nom-produit">Nom du produit</label>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" id="modal-nom-produit" placeholder="Nom">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="modal-prix-produit">Prix</label>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" id="modal-prix-produit" placeholder="0.0">
                                        </div>
                                    </div>
                                    <div>
                                        <h4>Description du produit</h4>
                                        <textarea name="modal-descr-produit" id="modal-descr-produit"> </textarea>
                                    </div>
                                    <div>
                                        <h4>Ingrédients</h4>
                                        <textarea name="modal-ingr-produit" id="modal-ingr-produit"> </textarea>
                                    </div>
                                    <div>
                                        <h4>Recette</h4>
                                        <textarea name="modal-recette-produit" id="modal-recette-produit"> </textarea>
                                    </div>
                                    <div>
                                        <h4>Catégorie</h4>
                                        <select id="modal-select-cat-produit" class="dropdown">
                                            <option value="" disabled selected>Choisissez votre catégorie</option>
                                            <?php
                                            foreach ($subcats as $c) {
                                                 echo "<option value='" . $c->id . "'>" . $c->descr_fr ; "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <h4>Cliquez sur les images correspondant à votre produit</h4>
                                    <p>Cliquez sur le coeur pour choisir la photo principale</p>
                                    <div id="mygallery">
                                        <?php
                                        $files = glob("../img/uploads/*.{[jJ][pP][gG],[jJ][pP][eE][gG],[pP][nN][gG],[gG][iI][fF],[bB][mM][pP]}", GLOB_BRACE);

                                        foreach ($files as $img) {

                                            echo '<a href="' . mb_basename($img) . '">
                                                      <i class="fa fa-heart-o"></i>
                                                      <img class="photo-gallery" data-path="'. mb_basename($img) .'" alt="' . mb_basename($img) . '" src="img/xs/' . mb_basename($img) . '"/>
                                                  </a>';
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button id="btn-modal-edit-product" type="button" class="btn btn-success">Modifier</button>
                                    <button type="button" class="btn btn-warning" data-dismiss="modal">Fermer</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal create cat -->
                    <div class="modal fade modal-ext" id="modal-cat" tabindex="-1"
                         role="dialog" aria-labelledby="mlm"
                         aria-hidden="true" data-cat-id="0" data-action="create">

                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h4 class="modal-title w-100" id="mlm">Créer une catégorie</h4>
                                </div>
                                <div id="body-edit-cat" class="modal-body">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="modal-nom-produit">Nom de la catégorie</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="text" id="modal-nom-cat" placeholder="Nom" />
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="modal-select-cat-cat">Catégorie parent</label>
                                            </div>
                                            <div class="col-md-9">
                                                <select name="parentcat" id="modal-select-cat-cat">
                                                    <option value="0">Pas de catégorie parent</option>
                                                    <?php
                                                    foreach ($cats as $cat) {
                                                        echo "<option value='" . $cat->id . "'>" . $cat->descr_fr ; "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button id="btn-modal-edit-cat" type="button" class="btn btn-success">Créer</button>
                                    <button type="button" class="btn btn-warning" data-dismiss="modal">Fermer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- WIDGET ARTICLES -->
                <div id="widget-articles" class="row" style="display: none;">
                    <h1 class="bg-theme-admin">Gestion des articles</h1>
                    <!-- Create the editor container -->
                    <div class="container">
                        <div class="col-sm-12">
                            <h4 class="h4-responsive">Ecrivez votre news</h4>
                            <textarea id="flash-news"></textarea>

                            <button id="btn-create-flash" class="btn btn-block btn-success">Créer</button>
                            <hr>
                        </div>
                        <div class="col-sm-12">
                            <h4 class="h4-responsive">Autres news</h4>
                            <table id="table-news" class="table table-striped table-sm table-bordered table-hover">
                                <thead class="thead-inverse" >
                                <tr>
                                    <th>Affiché à l'accueil</th>
                                    <th>Texte</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $news = Database::query("SELECT * FROM flash_news")->fetchAll();
                                    foreach ($news as $n)
                                    {
                                        $i = $n->show == '1' ? "<i data-show='1' style='color: gold;' class='fa fa-star'></i>"
                                                             : "<i data-show='0' style='color: gold;' class='fa fa-star-o'></i>";
                                        echo "<tr>
                                                <td class='is-show' data-flash-id='$n->id'> $i </td>
                                                <td>" . getExcerpt($n->content, 0, 100) . "</td>
                                                <td data-flash-id='$n->id'>
                                                    <button class='btn btn-view-flash btn-primary'>Voir</button>
                                                    <button class='btn btn-edit-flash btn-warning'>Modifier</button>
                                                    <button class='btn btn-delete-flash btn-danger'>Supprimer</button>
                                                </td>
                                              </tr>";
                                    }
                                ?>
                                </tbody>
                            </table>

                            <!-- Modal voir flash -->
                            <div class="modal fade modal-ext" id="modal-flash" tabindex="-1"
                                 role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            <h4 class="modal-title w-100" id="myModalLabel">News</h4>
                                        </div>
                                        <div id="body-details-news" class="modal-body">

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-warning" data-dismiss="modal">Fermer</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal edit flash -->
                            <div class="modal fade modal-ext" id="modal-edit-flash" tabindex="-1"
                                 role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            <h4 class="modal-title w-100" id="myModalLabel">Editer news</h4>
                                        </div>
                                        <div id="body-details-news" class="modal-body">
                                            <textarea id="flash-edit-news"></textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button id="btn-edit-flash" data-flash-id="0" type="button" class="btn btn-success" data-dismiss="modal">Modifier</button>
                                            <button type="button" class="btn btn-warning" data-dismiss="modal">Fermer</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- WIDGET COMMANDES -->
                <div id="widget-commandes" class="row" style="display: none;">
                    <h1 class="bg-theme-admin">Gestion des commandes</h1>
                    <div class="container">
                        <div class="row">
                            <p class="alert alert-info">Une ligne bleu signifie que la commande n'a pas été traitée</p>
                            <p class="alert alert-warning">Une ligne jaune signifie que la commande a été traitée</p>
                        </div>
                    </div>

                    <!-- ONGLETS -->
                    <div class="onglets container-fluid">
                        <div id="onglet-comm-now" role="presentation" class="col-xs-6 onglet active">Nouvelles commandes</div>
                        <div id="onglet-comm-passe" role="presentation" class="col-xs-6 onglet">Commandes traitées</div>
                    </div>

                    <table id="table-ocon" class="table table-striped table-sm table-bordered table-hover">
                        <thead class="thead-inverse" >
                        <tr>
                            <th>Traitée</th>
                            <th>Nom Prénom</th>
                            <th>E-mail</th>
                            <th>Téléphone</th>
                            <th>Total</th>
                            <th>Création de la commande</th>
                            <th>Date de livraison</th>
                            <th>Commande</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        // Group by ID commandes
                        $result = array();
                        foreach ($commandes_with_lines as $c) {
                            $id = $c->id;
                            if (isset($result[$id])) {
                                $result[$id][] = $c;
                            } else {
                                $result[$id] = array($c);
                            }
                        }

                        foreach ($commandes as $c) {
                            $isActive = "table-info";
                            if($c->traitee === "0")
                            {
                            ?>
                                <tr class="<?php echo $isActive; ?>" data-commande-id="<?php echo $c->id; ?>">
                                    <td class="traitee" style="width: 250px; text-align: center;">
                                        <?php if($c->traitee == 1) {
                                            echo '<button data-traitee="1" class="a-traiter btn btn-primary btn-block">Renouveler</button>';
                                        } else {
                                            echo '<button data-traitee="0" class="a-traiter btn btn-primary btn-block">Fait</button>';
                                        } ?>
                                    </td>
                                    <td class="table-nom-prenom"><?php echo $c->nom_prenom; ?></td>
                                    <td class="table-email"><?php echo $c->email; ?></td>
                                    <td class="table-telephone"><?php echo $c->telephone; ?></td>
                                    <td class="table-prix-commande"><?php echo number_format($c->prix, 2, ',', ' '); ?> €</td>
                                    <td class="table-date-livraison"><?php echo $c->date_livraison; ?></td>
                                    <td class="table-date-creation"><?php echo $c->date_livraison; ?></td>
                                    <input class="table-comment-commande" type="hidden" value="<?php echo $c->comment; ?>">
                                    <td class="table-actions-commande" style="text-align: center;">
                                        <button class="btn btn-primary"><i class="fa fa-eye"></i> Voir commande </button>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        <?php
                        }
                        ?>
                        </tbody>
                    </table>

                    <table id="table-ocop" class="table table-striped table-sm table-bordered table-hover">
                        <thead class="thead-inverse" >
                        <tr>
                            <th>Traitée</th>
                            <th>Nom Prénom</th>
                            <th>E-mail</th>
                            <th>Téléphone</th>
                            <th>Total</th>
                            <th>Date</th>
                            <th>Commande</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        // Group by ID commandes
                        $result = array();
                        foreach ($commandes_with_lines as $c) {
                            $id = $c->id;
                            if (isset($result[$id])) {
                                $result[$id][] = $c;
                            } else {
                                $result[$id] = array($c);
                            }
                        }

                        foreach ($commandes as $c) {
                            $isActive = "table-warning";
                            if($c->traitee === "1")
                            {
                                ?>
                                <tr class="<?php echo $isActive; ?>" data-commande-id="<?php echo $c->id; ?>">
                                    <td class="traitee" style="width: 250px; text-align: center;">
                                        <?php if($c->traitee == 1) {
                                            echo '<button data-traitee="1" class="a-traiter btn btn-primary btn-block">Renouveler</button>';
                                        } else {
                                            echo '<button data-traitee="0" class="a-traiter btn btn-primary btn-block">Fait</button>';
                                        } ?>
                                    </td>
                                    <td class="table-nom-prenom"><?php echo $c->nom_prenom; ?></td>
                                    <td class="table-email"><?php echo $c->email; ?></td>
                                    <td class="table-telephone"><?php echo $c->telephone; ?></td>
                                    <td class="table-prix-commande"><?php echo $c->prix; ?> €</td>
                                    <td class="table-prix-commande"><?php echo $c->date_creation; ?></td>
                                    <input class="table-comment-commande" type="hidden" value="<?php echo $c->comment; ?>">
                                    <td class="table-actions-commande" style="text-align: center;">
                                        <button class="btn btn-primary"><i class="fa fa-eye"></i> Voir commande </button>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>

                    <!-- Modal details commande -->
                    <div class="modal fade modal-ext" id="modal-commande" tabindex="-1"
                         role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h4 class="modal-title w-100" id="myModalLabel">Détails de la commande</h4>
                                </div>
                                <div id="body-detail-commande" class="modal-body">

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-warning" data-dismiss="modal">Fermer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require("shared/footer.php"); ?>
    <script id="json-produits"  type="application/json"><?php echo json_encode($produits); ?></script>
    <script id="json-produits"  type="application/json"><?php echo json_encode($commandes); ?></script>
    <script id="json-commandes" type="application/json"><?php echo json_encode($result); ?></script>
    <script id="json-news" type="application/json"><?php echo json_encode($news); ?></script>
    <!-- CKEditor library -->
    <script src="https://cdn.ckeditor.com/4.6.2/standard-all/ckeditor.js"></script>
    <!-- Sweet alert -->
    <script src="https://cdn.jsdelivr.net/sweetalert2/6.3.8/sweetalert2.min.js"></script>
    <script src="plugins/dmUploader.js"></script>

    <script>
        $(document).ready(function(e) {
            // $("#mygallery").justifiedGallery();

            var arrayImgSelected = [];

            var produits = JSON.parse($("#json-produits").html());
            var lignesCommandes = JSON.parse($("#json-commandes").html());

            $(document).on("click", "#mygallery img", function (e) {
                e.preventDefault();
                e.stopPropagation();

                var idprod = $('#modal-product').attr('data-product-id');
                var realThis = $(this);

                var datas = {
                    idProduct: idprod,
                    url: realThis.attr('data-path')
                };

                // Si on clicke alors qu'il est selected, alors il faut remove la photo du produit
                if($(this).hasClass("selected"))
                {
                    if($('#modal-product').attr('data-action') === 'create')
                    {
                        arrayImgSelected = arrayImgSelected.filter(function(e) {
                            return e !== datas.url
                        });
                        $(this).removeClass("selected");
                        console.log(arrayImgSelected);
                        return;
                    }

                    $.ajax({
                        method: "POST",
                        url:"controllers/ajaxCalls.php",
                        data: {action: 'removePhoto', data: JSON.stringify(datas)},
                        success: function(data) {
                            if(data === "1")
                                realThis.removeClass("selected");
                            else
                                swal("Oops. une erreur s'est produite");
                        },
                        error: function(xhr, error) { console.log(xhr); console.log(error); }
                    });
                }
                // Sinon il faut l'ajouter
                else
                {
                    if($('#modal-product').attr('data-action') === 'create')
                    {
                        arrayImgSelected.push(datas.url);
                        $(this).addClass("selected");
                        console.log(arrayImgSelected);
                        return;
                    }

                    $.ajax({
                        method: "POST",
                        url:"controllers/ajaxCalls.php",
                        data: {action: 'addPhoto', data: JSON.stringify(datas)},
                        success: function(data) {
                            console.log(data);
                            if(data === "1") {
                                realThis.addClass("selected");
                                console.log(produits);
                            }
                            else
                                swal("Oops. une erreur s'est produite");
                        },
                        error: function(xhr, error) { console.log(xhr); console.log(error); }
                    });
                }
            });

            $(document).on('click', '#mygallery i', function (e) {
                e.preventDefault();
                e.stopPropagation();
                $('#mygallery i.fa-heart').removeClass('fa-heart').addClass('fa-heart-o');
                $(this).removeClass('fa-heart-o').addClass('fa-heart');
            });

            CKEDITOR.replace("modal-recette-produit", {
                // Define the toolbar groups as it is a more accessible solution.
                toolbarGroups: [
                    {"name":"basicstyles","groups":["basicstyles"]},
                    {"name":"links","groups":["links"]},
                    {"name":"paragraph","groups":["list","blocks"]},
                    {"name":"document","groups":["mode"]},
                    {"name":"insert","groups":["insert"]},
                    {"name":"styles","groups":["styles"]},
                    {"name":"about","groups":["about"]}
                ],
                // Remove the redundant buttons from toolbar groups defined above.
                removeButtons: 'Underline,Strike,Subscript,Superscript,Anchor,Styles,Specialchar'
            } );
            CKEDITOR.replace("modal-ingr-produit", {
                // Define the toolbar groups as it is a more accessible solution.
                toolbarGroups: [
                    {"name":"basicstyles","groups":["basicstyles"]},
                    {"name":"links","groups":["links"]},
                    {"name":"paragraph","groups":["list","blocks"]},
                    {"name":"document","groups":["mode"]},
                    {"name":"insert","groups":["insert"]},
                    {"name":"styles","groups":["styles"]},
                    {"name":"about","groups":["about"]}
                ],
                // Remove the redundant buttons from toolbar groups defined above.
                removeButtons: 'Underline,Strike,Subscript,Superscript,Anchor,Styles,Specialchar'
            } );
            CKEDITOR.replace("modal-descr-produit", {
                // Define the toolbar groups as it is a more accessible solution.
                toolbarGroups: [
                    {"name":"basicstyles","groups":["basicstyles"]},
                    {"name":"links","groups":["links"]},
                    {"name":"paragraph","groups":["list","blocks"]},
                    {"name":"document","groups":["mode"]},
                    {"name":"insert","groups":["insert"]},
                    {"name":"styles","groups":["styles"]},
                    {"name":"about","groups":["about"]}
                ],
                // Remove the redundant buttons from toolbar groups defined above.
                removeButtons: 'Underline,Strike,Subscript,Superscript,Anchor,Styles,Specialchar'
            });
            CKEDITOR.replace("flash-news");
            CKEDITOR.replace("flash-edit-news");
            $('#table-cat').hide();
            $('#gestion-img').hide();
            $('#table-ocop').hide();


            // $("#sidebar").css("height", "" + $("html").height());

            $("footer").css("margin-top", 0);

            $("#option-items > li").click(function (e) {
                var widgetAMontrer = this.getAttribute("data-show");
                $("#main>div").hide();
                $(widgetAMontrer).show();
            });

            $("#select-change-img").change(function change() {
                var selected = $("#select-change-img option:selected");
                $("#modal-review-img-produit").attr("src", selected.val());
            });

            $("#modal-change-img-produit").click(function (e) {
                $("#modal-img-produit").attr("src", $("#modal-review-img-produit").attr("src"));
            });

            // Editer produit (apparition de la fenêtre modale)
            $(document).on("click", ".edit-product", function (e) {
                var modalProduct = $('#modal-product');
                var id = this.parentNode.getAttribute("data-product-id");
                var p = produits.filter(function (elem) { return elem.prod_id == id; })[0];
                $("#myModalLabel").text("Editer un produit");
                $("#btn-modal-edit-product").text("Modifier");
                modalProduct.attr("data-action", "edit");
                CKEDITOR.instances["modal-recette-produit"].setData(p.recette);
                CKEDITOR.instances["modal-ingr-produit"].setData(p.ingredients);
                CKEDITOR.instances["modal-descr-produit"].setData(p.descr);
                $("#modal-nom-produit").val(p.nom_fr);
                $("#modal-prix-produit").val(p.prix);
                $("#modal-img-produit").attr("src", p.img);
                modalProduct.attr("data-product-id", id);
                $("#modal-select-cat-produit option[value=" + p.id_categorie + "]").attr("selected", "selected");
                modalProduct.modal('show');

                var imgs = [];

                $(this.parentNode).find('.img-of-prod').each(function (key, img) {
                    imgs.push($(img).val());
                });

                arrayImgSelected = [];
                modalProduct.find('.photo-gallery').removeClass('selected');

                for(var i = 0; i < imgs.length; i++) {
                    console.log(imgs[i]);
                    modalProduct.find('.photo-gallery[data-path="' + imgs[i] + '"]').addClass('selected');
                }

                $('#mygallery i.fa-heart').removeClass('fa-heart').addClass('fa-heart-o');
                $('#mygallery a[href="' + p.img + '"]').find('i').removeClass('fa-heart-o').addClass('fa-heart');
            });

            $(document).on("click", "#create-recette", function () {
                $("#myModalLabel").text("Créer un nouveau un produit");
                $("#btn-modal-edit-product").text("Créer");
                $("#modal-product").attr("data-action", "create");
                CKEDITOR.instances["modal-recette-produit"].setData("");
                CKEDITOR.instances["modal-ingr-produit"].setData("");
                CKEDITOR.instances["modal-descr-produit"].setData("");
                $("#modal-nom-produit").val("");
                $("#modal-prix-produit").val("");
                $("#modal-img-produit").attr("src", "img/z.gif");
                $('#mygallery i.fa-heart').removeClass('fa-heart').addClass('fa-heart-o');
                $('.photo-gallery').removeClass('selected');
                $('#mygallery i').first().removeClass('fa-heart-o').addClass('fa-heart');
                arrayImgSelected = [];
                $('#modal-product').modal('show');
            });


            $(document).on("click", "#create-cat", function (e) {
                $('#modal-cat').modal('show');
            });

            // Editer/créer catégorie (vali...)
            $(document).on("click", "#btn-modal-edit-cat", function (e) {
                var datas = {
                    id: $("#modal-product").attr("data-product-id"),
                    nom: $("#modal-nom-cat").val(),
                    idcat: $("#modal-select-cat-cat").val(),
                };

                var act = $("#modal-cat").attr("data-action") === "create" ? "createCategory" : "editCategory";
                $.ajax({
                    method: "POST",
                    url:"controllers/ajaxCalls.php",
                    data: {action: act, data: JSON.stringify(datas)},
                    success: function(data) {
                        if(data === "1")
                            location.reload()
                    },
                    error: function(xhr, error) {
                        console.log(xhr);
                        console.log(error);
                    }
                });
            });

            // Editer/créer produit (validation des modifications)
            $(document).on("click", "#btn-modal-edit-product", function (e) {
                var datas = {
                    id: $("#modal-product").attr("data-product-id"),
                    recette: CKEDITOR.instances["modal-recette-produit"].getData(),
                    ingr: CKEDITOR.instances["modal-ingr-produit"].getData(),
                    descr: CKEDITOR.instances["modal-descr-produit"].getData(),
                    img: $("#mygallery i.fa-heart").next().attr("data-path"),
                    nom: $("#modal-nom-produit").val(),
                    prix: $("#modal-prix-produit").val(),
                    idcat: $("#modal-select-cat-produit").val(),
                    photos: arrayImgSelected
                };

                var act = $("#modal-product").attr("data-action") === "create" ? "createProduct" : "editProduct";

                if(act === 'createProduct'
                    && (datas.nom === '' || datas.prix === '' ||  datas.idcat === null || isNaN(datas.prix) /* Ceci check si datas.prix est un nombre illégal */)
                    )
                {
                    swal({
                        title: 'Erreur',
                        text: "Veuillez remplir le formulaire convenablement. Le prix (un nombre), le nom et la catégorie doivent être mentionnés.",
                        type: 'warning',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                $.ajax({
                    method: "POST",
                    url:"controllers/ajaxCalls.php",
                    data: {action: act, data: JSON.stringify(datas)},
                    success: function(data) {
                        if(data === '1')
                            swal({
                                type:  'success',
                                title: 'OK',
                                text:  'Le produit a été créé/modifié avec succès. ' +
                                'Veuillez recharger la page pour voir les changements.'
                            }).then(function() {
                                $('#modal-product').modal('hide');
                            });
                        else
                            alert(data);
                    },
                    error: function(xhr, error) {
                        console.log(xhr);
                        console.log(error);
                    }
                });

            });

            // Mettre en avant
            $(document).on("click", ".en_avant", function (e) {
                var id = this.parentNode.getAttribute("data-product-id");
                var self = $(this);
                var mettreALAccueil = "1";
                if(this.getAttribute("data-en-avant") === "1")
                    mettreALAccueil = "0";
                var row = $(this.parentNode.parentNode);
                row.addClass("busy");
                $.ajax({
                    method: "POST",
                    url:"controllers/ajaxCalls.php",
                    data: {action: "enAvant", data: JSON.stringify({avant:mettreALAccueil, id:id})},
                    success: function(xhr, data) {
                        if(mettreALAccueil === "1")
                            self.addClass("fa-star").removeClass("fa-star-o").attr("data-en-avant", "1");
                        else
                            self.removeClass("fa-star").addClass("fa-star-o").attr("data-en-avant", "0");
                        row.removeClass("busy");
                    },
                    error: function(xhr, error) {
                        console.log(xhr);
                        console.log(error);
                        row.removeClass("busy");
                    }
                });
            });

            // Delete product
            $(document).on("click", ".delete-product", function (e) {
                var id = this.parentNode.getAttribute("data-product-id");
                var product = $(this.parentNode.parentNode);
                swal({
                    title: 'Etes-vous sur de vouloir supprimer ce produit ?',
                    text: "Cette action est irréversible.",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Oui',
                    showLoaderOnConfirm: true
                }).then(function () {
                    $.ajax({
                        method: "POST",
                        url:"controllers/ajaxCalls.php",
                        data: {action: "deleteProduct", data: JSON.stringify({id:id})},
                        success: function(xhr, data) {
                            product.fadeOut();
                        },
                        error: function(xhr, error) {
                            console.log(xhr);
                            console.log(error);
                        }
                    });
                })
            });

            // Voir détails commande
            $(document).on("click", ".table-actions-commande>button", function (e) {
                var id = this.parentNode.parentNode.getAttribute("data-commande-id");
                var body = "<ol class='liste-normale'>";
                for(var idCommande in lignesCommandes) {
                    if(idCommande == id) {
                        for(var ligne in lignesCommandes[idCommande]) {
                            body += "<li>"
                                        + lignesCommandes[idCommande][ligne]["nom_fr"] + " x"
                                        + lignesCommandes[idCommande][ligne]["nombre"]
                                        + " - "
                                        + lignesCommandes[idCommande][ligne]["prix"]
                                        + " €/pièce"
                                 + "</li>";
                        }
                    }
                }
                body += "</ol><hr>";
                console.log($(this.parentNode.parentNode).find('.table-comment-commande').val());
                body += "<p>Message facultatif de la personne : </p><p>"
                     + $(this.parentNode.parentNode).find('.table-comment-commande').val()
                     + " </p>";
                $("#body-detail-commande").html(body);
                $('#modal-commande').modal('show');

            });

            // Delete image
            $(document).on('click', '.delete-gestion-img', function (e) {
               e.preventDefault();
               e.stopPropagation();
               var path = this.getAttribute('data-path');
               var $this = $(this.parentNode);
               console.log(this.parentNode);
                swal({
                    title: 'Etes-vous sur de vouloir supprimer cette image ?',
                    text: "Cette action est irréversible.",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Oui',
                    showLoaderOnConfirm: true
                }).then(function () {
                    $.ajax({
                        method: "POST",
                        url:"controllers/ajaxCalls.php",
                        data: {action: "deleteImg", data: JSON.stringify({path:path})},
                        success: function(data) {
                            location.reload();
                        },
                        error: function(xhr, error) {
                            console.log(xhr);
                            console.log(error);
                        }
                    });
                });
            });

            var ocat = $('#onglet-cat');
            var gimg = $('#onglet-img');
            var opro = $('#onglet-produits');
            var ocon = $("#onglet-comm-now");
            var ocop = $("#onglet-comm-passe");

            ocat.click(function (e) {
                ocat.addClass('active');
                $('#table-cat').show();
                $('#table-produits').hide();
                $('#gestion-img').hide();
                opro.removeClass('active');
                gimg.removeClass('active');
            });
            opro.click(function () {
                opro.addClass('active');
                $('#table-cat').hide();
                $('#gestion-img').hide();
                $('#table-produits').show();
                ocat.removeClass('active');
                gimg.removeClass('active');
            });
            gimg.click(function () {
                gimg.addClass('active');
                $('#table-cat').hide();
                $('#gestion-img').show();
                $('#table-produits').hide();
                ocat.removeClass('active');
                opro.removeClass('active');
            });

            ocon.click(function (e) {
                ocon.addClass('active');
                $('#table-ocon').show();
                $('#table-ocop').hide();
                ocop.removeClass('active');
            });

            ocop.click(function () {
                ocop.addClass('active');
                $('#table-ocon').hide();
                $('#table-ocop').show();
                ocon.removeClass('active');
            });

            // Marquer commande comme traitée
            $(document).on("click", ".a-traiter", function (e) {
                var id = this.parentNode.parentNode.getAttribute("data-commande-id");
                var self = $(this);
                var aTraiter = "1";
                if(this.getAttribute("data-traitee") == "1")
                    aTraiter = "0";
                var row = $(this.parentNode.parentNode);
                $.ajax({
                    method: "POST",
                    url:"controllers/ajaxCalls.php",
                    data: {action: "aTraiter", data: JSON.stringify({atraiter:aTraiter, id:id})},
                    success: function(xhr, data) {
                        if(aTraiter == "1") {
                            self.attr("data-traitee", "1").text("Renouveler");
                            row.addClass("table-warning").removeClass("table-info");
                        }
                        else {
                            self.attr("data-traitee", "0").text("Fait");
                            row.removeClass("table-warning").addClass("table-info");
                        }
                    },
                    error: function(xhr, error) {
                        console.log(xhr);
                        console.log(error);
                    }
                });
            });

            // Uploader une image
            $("#drop-area-div").dmUploader({
                url: "controllers/ajaxCalls.php",
                method: "POST",
                extFilter: "jpg;png;bmp;jpeg;gif;JPG;PNG;BMP;JPEG;GIF",
                fileName: "files",
                onUploadSuccess: function(id, data) {
                    swal({
                        title: 'Upload',
                        text: data
                    }).then(function () {
                        // location.reload()
                    });
                }
            });

            var news = JSON.parse($("#json-news").html());
            var modalNews = $('#modal-flash');
            var btnEditFlash = $('#btn-edit-flash');
            $(document).on('click', '.btn-view-flash', function () {
                // On chope l'id
                var id = this.parentNode.getAttribute('data-flash-id');
                // On chope la bonne news
                var goodNews = news.filter(function (element) {
                    return element.id == id;
                })[0];
                // On fout le contenu dans le modal :
                modalNews.find('#body-details-news').html(goodNews.content);
                modalNews.modal('show');
            });

            $(document).on('click', '.btn-delete-flash', function () {
                // On chope l'id
                var id = this.parentNode.getAttribute('data-flash-id');
                var n  = $(this.parentNode.parentNode);
                swal({
                    title: 'Etes-vous sur de vouloir supprimer cette news ?',
                    text: "Cette action est irréversible.",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Oui',
                    showLoaderOnConfirm: true
                }).then(function () {
                    $.ajax({
                        method: "POST",
                        url:"controllers/ajaxCalls.php",
                        data: {action: "deleteNews", data: JSON.stringify({id:id})},
                        success: function(xhr, data) {
                            n.fadeOut(500, function() {
                                n.remove();
                            });
                        },
                        error: function(xhr, error) {
                            console.log(xhr);
                            console.log(error);
                        }
                    });
                })
            });

            $(document).on('click', '.btn-edit-flash', function () {
                var id = this.parentNode.getAttribute('data-flash-id');
                var goodNews = news.filter(function (element) {
                    return element.id == id;
                })[0];
                CKEDITOR.instances['flash-edit-news'].setData(goodNews.content);
                btnEditFlash.attr('data-flash-id', id);
                $('#modal-edit-flash').modal('show');
            });

            $('#btn-create-flash').click(function () {
                var flash = {
                    content: CKEDITOR.instances["flash-news"].getData()
                };
                $.ajax({
                    method: "POST",
                    url:"controllers/ajaxCalls.php",
                    data: {
                        action: "createFlash",
                        data: JSON.stringify(flash)
                    },
                    success: function(data) {
                        if(data === "1")
                            location.reload();
                    },
                    error: function(xhr, error) {
                        console.log(xhr);
                        console.log(error);
                    }
                });
            });

            btnEditFlash.click(function () {
                var id = btnEditFlash.attr('data-flash-id');
                var flash = {
                    id: id,
                    content: CKEDITOR.instances['flash-edit-news'].getData(),
                    show: $('.is-show[data-flash-id=' + id + '] i').attr('data-show')
                };
                $.ajax({
                    method: "POST",
                    url:"controllers/ajaxCalls.php",
                    data: {action: "editFlash", data: JSON.stringify(flash)},
                    success: function(data) {
                        if(data === '1')
                            swal({
                                title: 'Modification faite',
                                text: "Effectuée avec succès.",
                                type: 'success'
                            }).then(function () {
                                location.reload();
                            });
                        else
                            alert('error');
                    },
                    error: function(xhr, error) {
                        console.log(xhr, error);
                    }
                });
            });

            $('.is-show').click(function () {
                var i = $(this).find('i');
                var id = $(this).attr('data-flash-id');
                var newVal = null;
                if(i.attr('data-show') === '1') {
                    i.attr('data-show', '0');
                    i.removeClass('fa-star').addClass('fa-star-o');
                    newVal = '0';
                } else {
                    i.attr('data-show', '1');
                    $('#table-news').find('i.fa-star').removeClass('fa-star').addClass('fa-star-o');
                    i.removeClass('fa-star-o').addClass('fa-star');
                    newVal = '1';
               }
                $.ajax({
                    method: "POST",
                    url:"controllers/ajaxCalls.php",
                    data: {
                        action: "changeFlashVisibility",
                        data: JSON.stringify({
                            idFlash: id,
                            newVal: newVal
                        })
                    },
                    success: function(xhr, data) {
                        console.log(data);
                    },
                    error: function(xhr, error) {
                        console.log(error);
                    }
                });

            });
        });

        function changeCatParent(event) {
            if($(event.target).hasClass('cat'))
            {
                var idsubCat = event.dataTransfer.getData('id');
                $.ajax({
                    method: "POST",
                    url:"controllers/ajaxCalls.php",
                    data: {
                        action: "changeParentCat",
                        data: JSON.stringify({
                            idChanged: idsubCat,
                            newParent: event.target.getAttribute('data-id')
                        })
                    },
                    success: function(xhr, data) {
                        var x = $('.subcat[data-id=' + idsubCat + ']');
                        x.remove();
                        $(event.target).append(x);
                    },
                    error: function(xhr, error) {
                        console.log(xhr);
                        console.log(error);
                    }
                });
            }
        }

        function dragStart(event, idCat) {
            // On rajoute des infos supplémentaire à trasférer
            event.dataTransfer.setData('id', idCat);
        }

        $(document).on('click', '.edit-cat-name', function (e) {
            var id = this.parentNode.getAttribute('data-id');
            var realThis = $(this.parentNode);
            swal({
                title: 'Nouveau nom de la catégorie',
                input: 'text',
                showCancelButton: true,
                confirmButtonText: 'Valider',
                cancelButtonText:'Annuler',
                showLoaderOnConfirm: true,
                preConfirm: function (val) {
                    return new Promise(function (resolve, reject) {
                        $.ajax({
                            method: "POST",
                            url:"controllers/ajaxCalls.php",
                            data: {action: "changeDescrCat", data: JSON.stringify({id:id, newtext: val})},
                            success: function(xhr, data) {
                                resolve();
                            },
                            error: function(xhr, error) {
                                reject();
                            }
                        });
                    })
                },
                allowOutsideClick: false
            }).then(function (val) {
                realThis.html('<i class="fa fa-pencil edit-cat-name"></i> ' + val);
            })
        });




        function deleteCatSubcat(event) {
            var idsubCat = event.dataTransfer.getData('id');
            swal({
                title: 'Etes-vous sur de vouloir supprimer cette catégorie ?',
                text: "Cette action est irréversible.",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Oui',
                showLoaderOnConfirm: true
            }).then(function () {
                $.ajax({
                    method: "POST",
                    url:"controllers/ajaxCalls.php",
                    data: {action: "deleteCat", data: JSON.stringify({id:idsubCat})},
                    success: function(xhr, data) {
                        $('.subcat[data-id=' + idsubCat + ']').fadeOut();
                    },
                    error: function(xhr, error) {
                        console.log(xhr);
                        console.log(error);
                    }
                });
            })
        }
    </script>
</body>
</html>