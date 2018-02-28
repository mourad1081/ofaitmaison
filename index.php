<?php

/*
require("views/shared/header.php");
 <body>
    <?php
// Menu
require("views/shared/menu.php");
// --- Carousel --- //
// Il faut obligatoirement déclarer les variables img1, 2 et 3 pour indiquer le path vers les images
// (à terme, ce sera pour changer les images directement depuis un board)
$img1 = "img/pp.jpg";
$img2 = "img/f2.gif";
$img3 = "img/saumon.jpg";
require("views/shared/carousel.php");
?>
    <!-- Fait avec amour -->
<div id="accueil-page" class="container-fluid white block-presentation" style="background-color: #DCC6E0 !important; padding: 0;">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 wow zoomIn">
                <div>
                    <h1 class="h1-responsive bg-theme">Fait pour vous</h1>
                    <h1 class="h1-responsive">Avec amour</h1>
                    <p class="ornement">y r z</p>
                    <p style="font-family: 'Times New Roman';">Un péché mignon cuisiné avec tendresse et à base de produits de qualité. <br> <i class="fa fa-heart-o"></i></p>
                </div>
            </div>
            <div class="col-sm-6 wow zoomInDown" data-wow-delay="0.4s">
                <img  src="img/nutela.jpg" width="100%" alt="">
            </div>
        </div>
    </div>
    <!-- Vous désirez nous contacter ? -->
    <div class="container-fluid bg-img" style="height: 300px;

                                                   -moz-box-shadow:    inset  0  8px 15px -8px #696868,
                                                                       inset  0 -8px 15px -8px #696868;
                                                   -webkit-box-shadow: inset  0  8px 15px -8px #696868,
                                                                       inset  0 -8px 15px -8px #696868;
                                                   box-shadow:         inset  0  8px 15px -8px #696868,
                                                                       inset  0 -8px 15px -8px #696868;"">

</div>
<!-- Nos produits mis en avant -->
<div class="container-fluid team-section" style="padding: 0;">
    <!--Section heading-->
    <h1 class="section-heading wow zoomInDown">Nos produits phares</h1>
    <p class="ornement wow fadeIn">y r z</p>
    <!--Section description-->
    <!--Card-->
    <div id="produits-en-avant" style="padding-left: 15px;padding-right: 15px;">
        <?php
        $produits = ProduitDAL::ReadAll();
        $cpt = 0;
        foreach ($produits as $p) {

            if($p->en_avant == 1)
            {
                if($cpt % 3 == 0)
                    echo '<div class="row">';

                echo '<div data-product-id="' . $p->id . '" 
                                   data-product-cat-id="' . $p->id_categorie . '"
                                   class="prod col-xs-12 col-col-sm-6 col-md-4 wow fadeIn">
                                <div class="card">
                                    <!--Card image-->
                                    <div class="view overlay hm-white-slight"  
                                         style="background: url(\'img/uploads/' . $p->img . '\') no-repeat center center scroll; 
                                                background-size: cover !important; ">
                                                
                                        <a href="produit-' . $p->id . '">
                                            <div class="mask waves-effect waves-light"></div>
                                        </a>
                                    </div>
                                    <div class="card-block">
                                         <h4 class="card-title h4-responsive">' . $p->nom_fr . '</h4>
                                    </div>
                                </div>
                            </div>';


                $cpt++;
                if($cpt % 3 == 0)
                    echo '</div>';

            }
        }
        ?>
    </div>
</div>


<!-- Ce qu'en pense nos clients -->
<div id="carousel-example-3" class="carousel slide carousel-fade" data-ride="carousel" style="padding-left: 15px;padding-right: 15px;">
    <!--Indicators-->
    <ol class="carousel-indicators">
        <li data-target="#carousel-example-3" data-slide-to="0" class="active"></li>
        <li data-target="#carousel-example-3" data-slide-to="1"></li>
        <li data-target="#carousel-example-3" data-slide-to="2"></li>
        <li data-target="#carousel-example-3" data-slide-to="3"></li>
    </ol>
    <!--/.Indicators-->

    <!--Slides-->
    <div class="carousel-inner" role="listbox">
        <!--First slide-->
        <div class="carousel-item active">
            <!--Mask color-->
            <div class="view">
                <img src= "img/blank.png" alt="" style="margin-top: -20px;">
                <div class="full-bg-img">
                </div>
            </div>
            <!--Caption-->
            <div class="carousel-caption">
                <div class="animated zoomIn">
                    <div class="testimonial">
                        <!--Content-->
                        <h4>Anonyme</h4>
                        <h5>Bruxelles</h5>
                        <p>
                            <i class="fa fa-quote-left"></i>
                            Merci pour les tartes, c'était un délice, je n'hésiterai pas à re-commander.
                            <i class="fa fa-quote-right"></i>
                        </p>
                        <!--Review-->
                        <div>
                            <i class="fa fa-star"> </i>
                            <i class="fa fa-star"> </i>
                            <i class="fa fa-star"> </i>
                            <i class="fa fa-star"> </i>
                            <i class="fa fa-star"> </i>
                        </div>
                    </div>
                </div>
            </div>
            <!--Caption-->
        </div>
        <!--/First slide-->

        <!--Second slide-->
        <div class="carousel-item">
            <!--Mask color-->
            <div class="view">
                <img src="img/blank.png" alt="">
                <div class="full-bg-img">
                </div>
            </div>
            <!--Caption-->
            <div class="carousel-caption">
                <div class="animated fadeInDown">
                    <div class="testimonial">
                        <!--Content-->
                        <h4>Tikay Y.</h4>
                        <h5>Anderlecht (Bruxelles)</h5>
                        <p><i class="fa fa-quote-left"></i>
                            Je suis en famille, devant <em>#leMeilleurPatissier</em> avec une pâtisserie de chez <em>#oFaitMaison</em>.
                            <i class="fa fa-quote-right"></i>
                        </p>
                        <!--Review-->
                        <div>
                            <i class="fa fa-star"> </i>
                            <i class="fa fa-star"> </i>
                            <i class="fa fa-star"> </i>
                            <i class="fa fa-star"> </i>
                            <i class="fa fa-star-half-full"> </i>
                        </div>
                    </div>
                </div>
            </div>
            <!--Caption-->
        </div>
        <!--/Second slide-->

        <!--Third slide-->
        <div class="carousel-item">
            <!--Mask color-->
            <div class="view">
                <img src="img/blank.png" alt="">
                <div class="full-bg-img">
                </div>
            </div>
            <!--Caption-->
            <div class="carousel-caption">
                <div class="animated fadeInDown">
                    <div class="testimonial">
                        <!--Content-->
                        <h4>AbdelKarim </h4>
                        <h5>Vilvoorde (Bruxelles)</h5>
                        <p>
                            <i class="fa fa-quote-left"></i>
                            C'était trop bon. :)
                            <i class="fa fa-quote-right"></i>
                        </p>
                        <!--Review-->
                        <div>
                            <i class="fa fa-star"> </i>
                            <i class="fa fa-star"> </i>
                            <i class="fa fa-star"> </i>
                            <i class="fa fa-star"> </i>
                            <i class="fa fa-star-o"> </i>
                        </div>
                    </div>
                </div>
            </div>
            <!--Caption-->
        </div>

        <div class="carousel-item">
            <!--Mask color-->
            <div class="view">
                <img src="img/blank.png" alt="">
                <div class="full-bg-img">
                </div>
            </div>
            <!--Caption-->
            <div class="carousel-caption">
                <div class="animated fadeInDown">
                    <div class="testimonial">
                        <!--Content-->
                        <h4>Anonyme</h4>
                        <h5>Bruxelles</h5>
                        <p><i class="fa fa-quote-left"></i>
                            Merci beaucoup pour ma commande. Très frais et un délice. Mes invités ont adorés.
                            Vous avez contribué à rendre ma soirée top !!!!! <br>
                            À une prochaine commande sûrement.
                            <i class="fa fa-quote-right"></i>
                        </p>
                        <!--Review-->
                        <div>
                            <i class="fa fa-star"> </i>
                            <i class="fa fa-star"> </i>
                            <i class="fa fa-star"> </i>
                            <i class="fa fa-star"> </i>
                            <i class="fa fa-star"> </i>
                        </div>
                    </div>
                </div>
            </div>
            <!--Caption-->
        </div>
        <!--/Third slide-->
    </div>
    <!--/.Slides-->

    <!--Controls-->
    <a class="left carousel-control" href="#carousel-example-3" role="button" data-slide="prev">
        <span class="icon-prev" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#carousel-example-3" role="button" data-slide="next">
        <span class="icon-next" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
    <!--/.Controls-->
</div>
</div>

<!-- Big news flash -->
<div class="modal fade modal-ext" id="modal-flash" tabindex="0" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <!--Content-->
        <div class="modal-content">
            <!--Header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title w-100" id="myModalLabel">News</h4>
            </div>
            <!--Body-->
            <div class="modal-body">
                <?php
                $flash = Database::query('SELECT * 
                                                    FROM flash_news 
                                                    WHERE flash_news.show = 1 
                                                    LIMIT 1')->fetchAll();
                if($flash) {
                    $flash = $flash[0];
                    echo $flash->content;
                    echo '<script>
                                setTimeout(function() {
                                  $("#modal-flash").modal("show");
                                }, 2000);
                              </script>';
                }
                ?>
            </div>
            <!--Footer-->
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Fermer</button>
            </div>
        </div>
        <!--/.Content-->
    </div>
</div>



<?php require("views/shared/footer.php"); ?>

<script>
    $("#carousel-example-2").carousel({
        interval: 6000
    });
    $("#carousel-example-3").carousel({
        interval: 6000
    });
</script>
</body>

*/?>

<!doctype html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
        <link rel="icon" type="image/jpeg" href="img/newlogo.jpeg" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.0/css/font-awesome.min.css">
        <style>
        </style>
        <title>Ô fait maison</title>
    </head>
    <body style="text-align: center; background: white;padding: 20px; font-family: 'Raleway', sans-serif; color: #8B254C">
        <div style="padding: 20px;">
            <img style="height: 200px;" src="img/newlogo.jpeg" alt="">
        </div>

        <div style="margin-bottom: 20px; font-weight: 600;">
            Ô fait maison est en construction, nous serons de retour le plus rapidement possible !
        </div>
        <div style="margin: 10px;">
            Retrouvez-nous sur vos réseaux sociaux préférés <br><br>
            <span style="margin-left: 5px;margin-right: 5px;"><i class="fa fb fa-facebook"> </i><a style="color:#34495e;" href="https://www.facebook.com/Aufaitmaison1/"> Facebook</a></span>
            | <span style="margin-left: 5px;margin-right: 5px;"><i class="fa insta fa-instagram"> </i><a style="color:#34495e;" href="https://www.instagram.com/ofaitmaisonbxl/"> Instagram</a>  </span>
            | <span style="margin-left: 5px;margin-right: 5px;"><i class="fa yt fa-envelope"> </i> <a style="color:#34495e;" href="mailto:info@ofaitmaison.be">Message</a></span>
            | <span style="margin-left: 5px;margin-right: 5px;"><i class="fa yt fa-snapchat"> </i> SnapChat : ofaitmaisonbxl</span>
            | <span style="margin-left: 5px;margin-right: 5px;"><i class="fa yt fa-mobile"> </i> Tel : 0488/91.26.93</span>
        </div>
        <hr style="   border: 0;
    height: 1px;
    background-image: linear-gradient(to right, rgba(0, 0, 0, 0), rgba(116,0,102,0.75), rgba(0, 0, 0, 0));">
        <div style="margin-bottom: 20px; font-weight: 600;">En attendant, venez dégustez nos saveurs !</div>
        <div style="border: solid #8B254C 2px;">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3561.5234382447584!2d4.32985127079677!3d50.86121304813352!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x5a410324d4dce923!2s%C3%94+fait+maison!5e0!3m2!1sfr!2sbe!4v1514804757511"
                    width="100%"
                    height="450"
                    frameborder="0"
                    style="border:0"
                    allowfullscreen></iframe>
        </div>
    </body>
</html>