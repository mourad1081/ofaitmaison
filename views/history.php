<?php require("shared/header.php"); ?>
<body>
<?php require("shared/menu.php"); ?>

<div id="history" class="container">
    <div class="page-header">
        <img src="img/logo.png" alt="" height="200">
    </div>
    <ul class="timeline">
        <li class="animated zoomIn">
            <div class="timeline-badge danger"><i class="fa fa-star"></i></div>
            <div class="timeline-panel z-depth-2">
                <div class="timeline-heading">
                    <h3 class=" h3-responsive timeline-title">Départ d'une grande aventure</h3>
                    <p><small class="text-muted"><i class="fa fa-calendar"></i> l'An 2014</small></p>
                </div>
                <hr>
                <div class="timeline-body">
                    <img src="img/history-2.jpg" alt="">
                    <p>
                        La passion de la pâtisserie gourmande mélangée à celle de la volonté
                        de garantir un travail artisanal, c’est notre promesse.
                        Ô fait maison, c’est un pâtissier amateur et autodidacte dans le domaine
                        culinaire qui prend conscience qu’aujourd’hui, la "bonne" pâtisserie
                        ne court pas les rues.
                    </p>
                    <br><p>
                        Pour la plupart, les pâtisseries sont aujourd’hui majoritairement
                        industrielles, sans réelle saveur, ni plaisir gustatif, produites
                        sans l’amour d’un pâtissier mais plutôt faite par des machines sans âmes,
                        sans le contact brut d’une main qui malaxe une pâte sablée fraichement
                        produite ; sans le plaisir de suivre la cuisson d’une onctueuse
                        crème pâtissière produite avec des œufs fraichement cassés de la main d’un
                        artisan chevronné, et non avec quelconque alternative synthétique.
                    </p>
                    <hr>
                </div>
            </div>
        </li>
        <li class="timeline-inverted animated zoomIn">
            <div class="timeline-badge danger"><i class="fa fa-birthday-cake"></i></div>
            <div class="timeline-panel z-depth-2">
                <div class="timeline-heading">
                    <h3 class=" h3-responsive timeline-title">Nouveaux horizons</h3>
                </div>
                <hr>
                <div class="timeline-body">
                    <img src="img/history-1.jpg" alt="">
                    <p>Fin 2014, c’est uniquement avec des tartes phare de la pâtisserie que ce jeune
                        artisan se lance dans l’aventure avec un four aménagé et quelques ustensiles
                        culinaires. Le démarrage se fait avec les mises au points de ses tartes en
                        commençant par la tarte citron meringuée, le moelleux au chocolat, la tarte
                        brésilienne, le Cheesecake...  ou des tartes entièrement et spécialement conçues par
                        nos soins comme la délicieuse tarte caramel au beurre salé et noix de pecan...</p>
                    <br><p>Au début de l’aventure, le but n’était que de faire profiter familles et amis,
                        mais très vite, ce cher artisan pris conscience que ces délicieuses productions
                        ne pouvaient rester dans ce chaleureux cadre proche.
                        Et c’est ainsi que dans son petit atelier ménager, l’aventure commence avec
                        certains restaurateurs qui réalisent que fournir à leurs clients un
                        produit frais et de qualité était devenu primordial. </p>
                    <br><p>C’est alors que nous
                        prenons place sur les réseaux sociaux, et très vite l’engouement se fait entendre.
                        De plus en plus de personne se précipitent pour passer commande,
                        une multitude de tartes fraichement produite de manière artisanal et
                        à prix abordable, c’était ce qu’il manquait !</p>
                    <hr>
                </div>
            </div>
        </li>
        <!-- Si l'article suivant est inverted, alors le badge nouvelle année doit l'etre aussi apparemment. -->
        <li class="animated zoomIn">
            <div class="timeline-badge warning small-text">2016</div>
        </li>
        <li class="animated zoomIn">
            <div class="timeline-badge danger"><i class="fa fa-cutlery"></i></div>
            <div class="timeline-panel z-depth-2">
                <div class="timeline-heading">
                    <h3 class=" h3-responsive timeline-title">Nous étendons notre gamme</h3>
                </div>
                <hr>
                <div class="timeline-body">
                    <img src="img/nutela.jpg" alt="">
                    <p>Très vite, en 2016, nous constatons qu’une demande se fait ressentir :
                        celle de pouvoir consommer des produits "fait maison" devient
                        importante, et c’est pourquoi nous avons décidé de rester fidèle au nom de
                        l’enseigne (Ô fait maison). </p>
                    <p>Nous décidons d’agrandir la liste de nos
                        produits et de ne pas rester uniquement dans la vente de tartes sucrées ou
                        salées, et c’est ainsi que la création de différentes pâtisseries
                        tel que les cakes sucrée ou salée, cupcakes, verrines, biscuits, etc.
                        voient le jour.</p>
                    <hr>

                </div>
            </div>
        </li>
        <!-- Si l'article suivant est inverted, alors le badge nouvelle année doit l'etre aussi apparemment. -->
        <li class="timeline-inverted animated zoomIn">
            <div class="timeline-badge warning small-text">2017</div>
        </li>
        <li class="timeline-inverted animated zoomIn">
            <div class="timeline-panel z-depth-2">
                <div class="timeline-heading">
                    <h3 class=" h3-responsive timeline-title">Déploiement sur le Web</h3>
                </div>
                <hr>
                <div class="timeline-body">
                    <img src="img/Capture.PNG" alt="">
                    <p>2017, l’aventure continue sur le web avec la mise en ligne du site officiel
                        qui fait office de vitrine virtuelle et la
                        possibilité d'y passer commande... L'aventure ne fait que commencer !</p>
                    <hr>

                </div>
            </div>
        </li>
    </ul>
</div>

<?php require("shared/footer.php"); ?>
<script>
    /*
    WOW.prototype.addBox = function(element){
        this.boxes.push(element);
    };
    */

    /*
    $('.wow').on('scrollSpy:exit',function(){
        var element = $(this);
        element.css({
            'visibility' : 'hidden',
            'animation-name' : 'none'
        }).removeClass('animated');
        wow.addBox(this);
    });
    $('.wow').scrollSpy();
    */
</script>
</body>
</html>
