<?php
require("shared/header.php");
?>
<body>
<?php require("shared/menu.php"); ?>
    <div id="products" class="container-fluid" style="margin-top: 100px; padding-left: 0;padding-right: 0;">
        <!-- Select category -->
        <div id="select-cat">
            <h1 class="h1-responsive">Choisissez votre catégorie</h1>
            <p class="ornement">y r z</p>
            <div id="select-cat-container" class="container">
                <div class="row">
                    <?php global $cats;
                    $cats = Database::query("SELECT * FROM categories")->fetchAll();
                    foreach ($cats as $cat) {
                        if($cat->id_parent == "0")
                            echo '<div class="cat col-xs-4" data-cat-id="' . $cat->id . '">' . $cat->descr_fr . '</div>';
                    }
                    ?>
                </div>
            </div>
            <div id="select-subcat-container" class="no-shown">
                <h3 class="h3-responsive">~ Et éventuellement, une sous-catégorie ~</h3>

                <?php
                foreach ($cats as $cat)
                    if($cat->id_parent != "0")
                        echo '<div class="subcat animated bounceIn" data-cat-parent-id="' . $cat->id_parent . '" data-subcat-id="' . $cat->id . '">' . $cat->descr_fr . '</div>';
                ?>
            </div>
        </div>
        <div id="product-list" class="row" style="display: none;">
            <div class="container">
                    <?php $produits = ProduitDAL::ReadAll();
                    $cpt = 0;
                    $border = "";
                    foreach ($produits as $prod) {
                        if($cpt % 3 == 0) {
                            echo '<div>';
                        }
                        echo '<div data-product-id="' . $prod->id . '" 
                                   data-product-cat-id="' . $prod->id_categorie . '"
                                   data-image-url="' . $prod->img . '"
                                   class="prod col-xs-6 col-md-4 animated zoomIn">
                                <div class="card">
                                    <!--Card image-->
                                    <div class="view overlay hm-white-slight"  
                                    style="background: url(\'img/md/' . mb_basename($prod->img) . '\') no-repeat center center scroll; background-size: cover !important; ">
                                        <a href="produit-' . $prod->id . '">
                                            <div class="mask waves-effect waves-light"></div>
                                        </a>
                                    </div>
                                    <div class="card-block">
                                        <h4 class="card-title h4-responsive">' . $prod->nom_fr . '</h4>
                                    </div>
                                </div>
                            </div>';
                        $cpt++;
                        if($cpt % 3 == 0) {
                            echo '</div>';
                        }
                    }
                    ?>
            </div>
        </div>
    </div>

<div id="wtf" style="height: 25vh;"></div>


<?php require("shared/footer.php"); ?>
<script id="data-produits" type="application/json"><?php echo json_encode($produits); ?></script>
<script src="https://cdn.jsdelivr.net/sweetalert2/6.3.8/sweetalert2.min.js"></script>

<script>

    $(document).ready(function (e) {

        var prods = $('.prod');
        var subcats = $('.subcat');
        var subcatContainer = $('#select-subcat-container');
        var productList = $('#product-list');

        $(document).on("click", ".subcat", function (e) {
            // Lorsqu'on clique sur une sous-catégorie, on affiche les
            // les produits ayant ces catégories.
            var id = this.getAttribute("data-subcat-id");
            prods.hide();
            $('html, body').animate({
                scrollTop: subcatContainer.position().top
            }, 'slow');
            // On n'affiche que les produits qui sont de la bonne catégorie.
            // C'est-à-dire, ceux dont le cat-id ==  le .subcat cliqué
            productList.show();
            prods.filter('[data-product-cat-id="' + id + '"]').show();

        });

        $(document).on("click", ".cat", function (e) {
            // Lorsqu'on clique sur une catégorie, on affiche les
            // sous-catégories correspondantes
            subcatContainer.animate({
                opacity:1
            }, 400);
            var idCat = this.getAttribute("data-cat-id");
            prods.hide();
            subcats.hide();
            subcats.filter('[data-cat-parent-id="' + idCat + '"]').show();
        });
    });
</script>

</body>
</html>