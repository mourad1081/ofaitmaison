<?php require("shared/header.php"); ?>
<body>
<?php require("shared/menu.php"); ?>
<?php
    global $id, $product, $photos;
    $id = (isset($_GET["id"]) and is_numeric($_GET["id"]))? $_GET["id"] : die();
    $product = Database::query("SELECT * FROM produits WHERE id = :id", ["id" => $id])->fetch();
    $photos  = Database::query("SELECT * FROM photos WHERE produit_id = :id", ["id" => $id])->fetchAll();
?>
<div id="bg-prod" style="background: url('img/uploads/<?php echo $product->img; ?>') center no-repeat">
    <h1 class="h1-responsive">Détail du produit</h1>
</div>

<div id="product" class="container-fluid">
    <div class="row">
        <div class="col-sm-5">
            <img class="wow zoomIn" src="img/uploads/<?php echo $product->img; ?>" alt="#" id="main-img">
        </div>
        <div class="col-sm-6">
            <h1 class="h1-responsive wow fadeInDown" style="font-family: Satisfy, serif; color: #af59a9;">
                <?php echo $product->nom_fr ?>
            </h1>
            <p class="ornement" style="text-align: center">y r z</p>
            <h4 class="h4-responsive">Description</h4>
            <p>
                <?php echo $product->descr ?>
            </p>
            <hr>
            <h4 class="h4-responsive">Prix</h4>
            <p style="text-align: center; font-size:1.7rem; font-family: 'Times New Roman', serif;"><strong><?php echo $product->prix; ?> €</strong></p>
        </div>
        <div class="col-sm-12" style="margin-bottom: 15px;">
            <h4 class="h4-responsive" style="margin-top: 10px;margin-bottom: 10px;">Galerie photo</h4>
            <div id="my-gallery" style="text-align: center;">
                <?php
                $len = count($photos);
                for($i = 0; $i < $len; $i++)
                    echo "<a class='photo-gallery wow zoomIn' href='" . $photos[$i]->url . "' data-target='$i'>
                                <img src='img/uploads/" . $photos[$i]->url . "' style='max-height: 120px;' />
                          </a>";
                ?>
            </div>
        </div>
    </div>



    <div class="modal fade modal-ext" id="modal-galery" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <!--Content-->
            <div class="modal-content">
                <!--Header-->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title w-100" id="myModalLabel">Galerie d'images</h4>
                </div>
                <!--Body-->
                <div class="modal-body">
                    <!-- CAROUSEL -->
                    <div id="carousel-example-4" class="carousel slide carousel-fade" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <?php
                            $len = count($photos);
                            echo '<li class="active" data-target="#carousel-example-4" data-slide-to="0"></li>';
                            for($i = 1; $i < $len; $i++)
                                echo '<li data-target="#carousel-example-4" data-slide-to="' . $i . '"></li>';
                            ?>
                        </ol>

                        <!--Slides-->
                        <div class="carousel-inner" role="listbox">
                            <?php
                            $len = count($photos);
                            echo '<div class="carousel-item active" data-id="0">
                                    <div class="view">
                                        <img src="img/uploads/' . $photos[0]->url . '" style="object-fit: cover;"/>
                                        <div class="full-bg-img">
                                        </div>
                                    </div>
                                    <div class="carousel-caption"></div>
                                   </div>';
                            for($i = 1; $i < $len; $i++)
                            {
                                echo '<div class="carousel-item" data-id="'.$i.'">
                                        <div class="view">
                                            <img src="img/uploads/' . $photos[$i]->url . '" style="object-fit: cover;"/>
                                            <div class="full-bg-img">
                                            </div>
                                        </div>
                                        <div class="carousel-caption"></div>
                                      </div>';
                            }
                            ?>
                        </div>
                        <!--Controls-->
                        <a class="left carousel-control" href="#carousel-example-4" role="button" data-slide="prev">
                            <span class="icon-prev" aria-hidden="true"></span>
                            <span class="sr-only">Précédent</span>
                        </a>
                        <a class="right carousel-control" href="#carousel-example-4" role="button" data-slide="next">
                            <span class="icon-next" aria-hidden="true"></span>
                            <span class="sr-only">Suivant</span>
                        </a>
                    </div>
                </div>
                <!--Footer-->
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require("shared/footer.php"); ?>
<!-- Sweet alert -->
<script src="https://cdn.jsdelivr.net/sweetalert2/6.3.8/sweetalert2.min.js"></script>
<script>
    var bgprod = $("#bg-prod");

    $("#carousel-example-2").carousel({
        interval: 6000
    });

    var modal = $("#modal-galery");
    var li = modal.find(".carousel-indicators li");
    var carouselItems = modal.find('.carousel-item');


    $(document).on('click', '.photo-gallery', function (e) {
        e.preventDefault();
        e.stopPropagation();
        var i = $(this).attr('data-target');
        console.log(i);
        li.removeClass('active');
        li.each(function () {
            if($(this).attr('data-target') === i)
                $(this).addClass('active');
        });
        carouselItems.removeClass('active');
        carouselItems.each(function () {
            if($(this).attr('data-id') === i)
                $(this).addClass('active');
        });
        modal.modal("show");
    });
</script>
</body>
</html>
