<?php
require("shared/header.php");
?>
<body>
<?php require("shared/menu.php"); ?>
<?php
    /**
     * @param $val
     * @return bool
     */
    function isMainCat($val) {
        return $val->id_parent == 0;
    }
    global $cats;
    $cats = Database::query("SELECT * FROM categories ORDER BY id_parent")->fetchAll();
    $mainCats = array_filter($cats, 'isMainCat');
    $menu = '<ul class="main-category">';
    $menu .= '<li id="show-all"><h4 class="h4-responsive"><i class="fa fa-arrow-right"></i> Tout afficher</h4></li>';
    // Créer un menu + sous-menu
    // Chaque catégories
    foreach ($mainCats as $mc) {
        $menu .= '<li class="main-category-item" data-maincat-id="' . $mc->id . '"><h4 class="h4-responsive"><i class="fa fa-tag"></i> ' . $mc->descr_fr . '</h4><ul class="sub-cat">';
        // Chaque sous-catégorie
        foreach ($cats as $c) {
            // Est-ce une sous-catégorie de la catégorie courante ?
            if($mc->id == $c->id_parent) {
                $menu .= '<li class="sub-cat-item" data-subcat-id="' . $c->id . '"><i class="fa fa-tags"></i> ' . $c->descr_fr . '</li>';
            }
        }
        $menu .= '</ul></li>';
    }
    $menu .= '</ul>';
?>
<div id="img-container">
    <h1  class="h1-responsive wow zoomIn">Bienvenue dans notre boutique !</h1>
    <h4 class="h4-responsive wow fadeInDown" style="color:black">* Pour les commandes liées à des évènements, veuillez nous contacter par téléphone.</h4>
</div>

<div id="order" class="container-fluid" style="min-height: 60vh;">
    <div class="row">
        <div id="order-menu"   class="col-md-3 col-lg-2">
            <h4 class="h4-responsive"><i class="fa fa-tag"></i> Catégories</h4>
            <button id="a-la-une" class="btn btn-block btn-warning-outline"><i class="fa fa-star"></i> Meilleures ventes</button>
            <?php echo $menu; ?>
        </div>
        <div id="order-panel"  class="col-md-6 col-lg-8">
            <!--Shopping Cart table-->
            <div class="table-responsive">
                <table class="table product-table">
                    <!--Table head-->
                    <thead>
                        <tr>
                            <th></th>
                            <th>Produit</th>
                            <th>Prix</th>
                            <th>Quantité</th>
                            <th class="hidden-md-down">Total</th>
                        </tr>
                    </thead>
                    <!--Table body-->
                    <tbody>
                    <!--First row-->
                    <?php $produits = ProduitDAL::ReadAll2();
                    foreach ($produits as $p)
                    {
                        ?>
                        <tr class="a-product animated zoomIn" data-parent-cat-id="<?php echo $p->id_parent; ?>" data-en-avant="<?php echo $p->en_avant; ?>" data-cat-id="<?php echo $p->id_categorie; ?>">
                            <th scope="row">
                                <img src="img/xs/<?php echo $p->img; ?>" alt="" class="img-fluid">
                            </th>
                            <td>
                                <h5><a style="font-family: 'Times New Roman', serif;" target="_blank" href="produit-<?php echo $p->id ?>"><strong><?php echo $p->nom_fr; ?></strong></a></h5>
                            </td>
                            <td><?php echo $p->prix; ?> €</td>
                            <td>
                                <span class="qty" style="font-weight: 600;"> 0 </span>
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-sm btn-primary sub" data-id="<?php echo $p->id; ?>">
                                        <input type="radio" name="options">&mdash;
                                    </label>
                                    <label class="btn btn-sm btn-primary add" data-id="<?php echo $p->id; ?>">
                                        <input type="radio" name="options">+
                                    </label>
                                </div>
                            </td>
                            <td class="total hidden-md-down">0 €</td>
                        </tr>
                        <?php
                    } ?>
                    </tbody>
                </table>
            </div>

            <!-- Les produits ont cette forme -->

            <!-- ---------------------------- -->
        </div>

        <div id="order-panier" class="col-md-3 col-lg-2" style="margin-bottom: 20px;">
            <h4 class="h4-responsive"><i class="fa fa-shopping-basket"></i> Votre panier</h4>
            <span id="empty-panier">Votre panier est vide</span>
            <div id="achats">
                <!-- Les produits du panier seront ici -->
            </div>
            <!-- -------------------------------- -->
            <h4 class="h4-responsive">Total</h4>
            <p id="prix">0 €</p>
            <button id="btn-commande" class="btn btn-block btn-success text-uppercase" data-toggle="modal">Commander</button>



            <!-- Modal Contact -->
            <div class="modal fade modal-ext" id="modal-contact" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <!--Content-->
                    <div class="modal-content">
                        <!--Header-->
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h4 class="modal-title w-100" id="myModalLabel">Confirmer la commande</h4>
                        </div>
                        <!--Body-->
                        <div class="modal-body">
                            <p class="alert alert-success">
                                Veuillez nous fournir un moyen de vous recontacter s'il vous plaît.
                                <br>
                                (E-mail ou téléphone)
                            </p>
                            <p id="modal-error" class="alert alert-danger">Veuillez remplir complètement le formulaire s'il vous plaît. (Le dernier champ est facultatif)</p>
                            <p id="modal-error-2" class="alert alert-danger">Vérifiez bien que vous nous avez fourni un moyen de vous recontacter ou que vous avez sélectionné des produits à commander.</p>
                            <br>
                            <div class="md-form">
                                <i class="fa fa-user prefix"></i>
                                <input type="text" id="modal-nom-prenom" class="form-control">
                                <label for="modal-nom-prenom">Nom prénom *</label>
                            </div>
                            <div class="md-form">
                                <i class="fa fa-envelope prefix"></i>
                                <input type="text" id="modal-e-mail" class="form-control">
                                <label for="modal-e-mail">Votre e-mail *</label>
                            </div>
                            <label id="mdd" for="modal-date-delivery">Pour quand désirez-vous que la commande soit faite ? </label>
                            <div class="md-form">
                                <i class="fa fa-calendar prefix"></i>
                                <input id="modal-date-delivery" type="text" name="modal-date-delivery"/>
                            </div>
                            <div id="alert-livraison" class="alert alert-warning" style="display: none;">
                                18h étant passé, votre commande ne pourra être livrée qu'à partir d'après-demain.
                            </div>
                            <label id="mtd" for="modal-time-delivery">À quelle heure désirez-vous récupérer la commande ? </label>
                            <div class="md-form">
                                <i class="fa fa-clock-o prefix"></i>
                                <input id="modal-time-delivery" type="text" name="modal-time-delivery"/>
                            </div>
                            <div class="md-form">
                                <i class="fa fa-mobile prefix"></i>
                                <input type="text" id="modal-gsm" class="form-control">
                                <label for="modal-gsm">Votre numéro de téléphone *</label>
                            </div>
                            <div class="md-form">
                                <i class="fa fa-pencil prefix"></i>
                                <textarea type="text" id="modal-origine" class="md-textarea"></textarea>
                                <label for="modal-origine">Comment avez-vous connu Ô Fait Maison ?</label>
                            </div>
                            <div class="md-form">
                                <i class="fa fa-pencil prefix"></i>
                                <textarea type="text" id="modal-comment" class="md-textarea"></textarea>
                                <label for="modal-comment">Désirez-vous nous dire quelque chose en plus ?</label>
                            </div>
                            <div class="text-center">
                                <button id="confirme-commande" class="btn btn-primary btn-large"><i></i> Envoyer la commande</button>
                                <div class="call">
                                    <p>Ou préférez-vous nous appeler ? <span class="cf-phone"><i class="fa fa-phone"> +32 488 09 44 24</i></span></p>
                                    <p>(* : champs nécessaires)</p>
                                </div>
                            </div>
                        </div>
                        <!--Footer-->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-warning" data-dismiss="modal">Fermer</button>
                        </div>
                    </div>
                    <!--/.Content-->
                </div>
            </div>
        </div>
    </div>
</div>

<?php require("shared/footer.php"); ?>
<script id="data-produits" type="application/json"><?php echo json_encode($produits); ?></script>
<script src="https://cdn.jsdelivr.net/sweetalert2/6.3.8/sweetalert2.min.js"></script>
<script src="plugins/pickdate/picker.js"></script>
<script src="plugins/pickdate/picker.date.js"></script>
<script src="plugins/pickdate/picker.time.js"></script>
<script src="plugins/pickdate/translations/fr_FR.js"></script>

<script>
    $(document).ready(function (e) {
        var htmlPanier = '<div class="produit-panier" data-id=""><img src="" /><span class="descr"></span></div>';
        var htmlEmptypanier = '<span id="empty-panier">Votre panier est vide</span>';
        var panier = $("#achats");
        var totalPrix = 0
        var produits = JSON.parse($("#data-produits").html());
        var prix = $("#prix");
        var $produits = $(".a-product");
        $("#modal-error, #modal-error-2").hide();
        var commande = [];
        produits.forEach(function (element) {
            commande.push({
                produit:element,
                nb:0
            });
        });
        var date = null;
        var time = null;
        var now  = new Date();
        now.setDate(now.getDate() + 1);

        var datepicker = $('#modal-date-delivery').pickadate({
            formatSubmit: 'yyyy-mm-dd',
            hiddenPrefix: 'true__',
            min: now
        });
        datepicker = datepicker.pickadate('picker'); // Pour travailler sur l'objet directement

        var timepicker = $('#modal-time-delivery').pickatime({
            formatSubmit: 'HH:i:00',
            hiddenPrefix: 'true__',
            format: 'H:i',
            formatLabel: 'H:i',
            interval:15,
            min:[16,0],
            max:[19,0]
        });
        timepicker = timepicker.pickatime('picker');

        var millisTill18 = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 18, 0, 0, 0) - now;
        if (millisTill18 < 0) {
            // 18H est passé, on active le night mode :
            // Il est 18h ! Interdit de commander avant après-demain
            var tomorrow = new Date(new Date().getTime() + 2*(86400000));
            $('#alert-livraison').show();
            datepicker.set({ min: tomorrow });
        } else {
            // 18h n'est pas passé, on met un timer qui désactivera
            // les commandes pour demain lorsqu'il sera 18H
            setTimeout(function() {
                // Il est 18h ! Interdit de commander avant après-demain
                var tomorrow = new Date(new Date().getTime() + 2*(86400000));
                $('#alert-livraison').show();
                datepicker.set({ min: tomorrow });
            }, millisTill18);
        }


        // Retire une quantité d'un produit donné dans le panier
        $(document).on("click", ".sub", function (e) {
            var id = this.getAttribute("data-id");
            // On chope le produit correspondant
            var p = commande.filter(function (elem) { return elem.produit.id == id })[0];
            // On diminue le nombre de produit de ce type
            if(p.nb > 0) {
                p.nb--;
                $(this.parentNode).prev().text(p.nb);
                totalPrix -= parseFloat(p.produit.prix);
                prix.text(totalPrix.toFixed(2) + " €");
                $(this.parentNode.parentNode).next().text(Number(p.produit.prix * p.nb).toFixed(2) + " €");
            }
            // S'il n'y en n'a plus, on le retire du panier
            if(p.nb == 0) {
                $(".produit-panier[data-id='" + p.produit.id  + "']").remove();
            }
        });

        // Ajoute une quantité donné dans le panier
        $(document).on("click", ".add", function (e) {
            var id = this.getAttribute("data-id");
            // On chope le produit correspondant
            var p = commande.filter(function (elem) { return elem.produit.id == id })[0]
            // S'il n'y en a aucun, on créé le produit dans le panier
            if(p.nb == 0) {
                var ligneCommande = $($.parseHTML(htmlPanier));
                ligneCommande.attr("data-id", p.produit.id);
                ligneCommande.find(".descr").text(p.produit.nom_fr);
                ligneCommande.find("img").attr("src", "img/xs/" + p.produit.img);
                $("#empty-panier").remove();
                panier.append(ligneCommande);
            }
            // On ajoute
            p.nb++;
            totalPrix += parseFloat(p.produit.prix);
            $(this.parentNode).prev().text(p.nb);
            $(this.parentNode.parentNode).next().text(Number(p.produit.prix * p.nb).toFixed(2) + " €");
            prix.text(totalPrix.toFixed(2) + " €");

        });

        // Ouvre la fenêtre de commande
        $("#btn-commande").click(function (e) {
            $("#modal-error, #modal-error-2").hide();
            $("#modal-contact").modal("show");
            $("#confirme-commande i").removeClass("fa fa-spinner fa-spin")
        });

        // Confirmer la commande
        $("#confirme-commande").click(function (e) {
            $("#confirme-commande i").addClass("fa fa-spinner fa-spin")
            var dataCommande = [];
            commande.forEach(function (element) {
                if(element.nb != 0) // On n'ajoute pas ceux qu'il avait électionné puis retiré
                    dataCommande.push({nb: element.nb, id: element.produit.id})
            });
            var datetime = $('input[name="true__modal-date-delivery_submit"]').val()? $('input[name="true__modal-date-delivery_submit"]').val() : "";
            datetime    += $('input[name="true__modal-date-delivery_submit"]').val() && $('input[name="true__modal-time-delivery_submit"]').val()? " " + $('input[name="true__modal-time-delivery_submit"]').val() : "";
            var coordonnees = {
                commande: dataCommande,
                nom_prenom: $("#modal-nom-prenom").val(),
                email: $("#modal-e-mail").val(),
                gsm: $("#modal-gsm").val(),
                prix: totalPrix,
                comment: $("#modal-comment").val(),
                origine: $("#modal-origine").val(),
                dateLivraison: datetime
            };

            // Il faut remplir  le Tel et l'e-mail ET avoir commandé qqch + nom prénom + date livraison
            if( ((coordonnees.gsm   !== "" && coordonnees.gsm   !== null) &&  (coordonnees.email !== "" && coordonnees.email !== null))
                &&  (commande.length > 0)
                &&  (coordonnees.nom_prenom !== "" && coordonnees.nom_prenom !== null)
                &&  (coordonnees.dateLivraison !== ""))
            {
                $.ajax({
                    method: "POST",
                    url:"controllers/ajaxCalls.php",
                    data: {action: "addCommand", data: JSON.stringify(coordonnees)},
                    success: function(data) {
                        if(data === "1") {
                            $("#modal-contact").modal("hide");
                            $("#modal-error, #modal-error-2").hide();
                            swal(
                                'Commande envoyée',
                                'Nous vous recontacterons dans les plus bref délais !',
                                'success'
                            );
                        } else {
                            $("#modal-error-2").show();
                        }
                        $("#confirme-commande i").removeClass("fa fa-spinner fa-spin")
                    },
                    error: function(xhr, error) {
                        console.log(xhr);
                        console.log(error);
                    }
                });
            }
            // Message d'erreur
            else {
                $("#modal-error").show();
                $("#confirme-commande i").removeClass("fa fa-spinner fa-spin")
            }

        });

        // Affiche tous les produits de la sous-catégorie sélectionnée
        $(document).on("click", ".sub-cat-item", function (e) {
            e.stopPropagation();
            var id = this.getAttribute("data-subcat-id");
            var filtered = $produits.filter(function (i) {
                return this.getAttribute("data-cat-id") !== id;
            });
            $produits.show();
            filtered.hide();
        });

        // Affiche tous les produits de la catégorie sélectionnée
        $(document).on("click", ".main-category-item", function (e) {
            e.stopPropagation();
            var id = this.getAttribute('data-maincat-id');
            var f = $produits.filter(function(){
                return $(this).attr('data-parent-cat-id') === id;
            });
            $produits.hide();
            f.show();
        });

        $(document).on("click", "#show-all", function (e) {
            e.stopPropagation();
            $produits.show();
        });

        // Affiche tous les produits mis en avant
        $("#a-la-une").click(function (e) {
            var filtered = $produits.filter(function (i) {
                return this.getAttribute("data-en-avant") === "0";
            });
            $produits.show();
            filtered.hide();
        })
    });
</script>
</body>
</html>