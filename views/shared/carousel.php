<div id="carousel-example-2" class="carousel slide carousel-fade" data-ride="carousel" style="box-shadow: 0px 5px 8px -4px black">
    <!--Indicators-->
    <ol class="carousel-indicators">
        <li data-target="#carousel-example-2" data-slide-to="0" class="active"></li>
        <li data-target="#carousel-example-2" data-slide-to="1"></li>
        <li data-target="#carousel-example-2" data-slide-to="2"></li>
    </ol>
    <!--/.Indicators-->

    <!--Slides-->
    <div class="carousel-inner" role="listbox">
        <!--First slide-->
        <div class="carousel-item active">
            <!--Mask color-->
            <div class="view">
                <img src= "<?php echo $img1; ?>" alt="" style="object-fit: cover;">
                <div class="full-bg-img">
                </div>
            </div>
            <!--Caption-->
            <div class="carousel-caption">
                <div class="animated zoomIn">
                    <h1 id="title-o-fait-maison" class="h1-responsive" style="color: #dcc6e0;">Ô fait maison</h1>
                    <p style="font-size: 1.3rem;">Le délice artisanal</p>
                </div>
            </div>
            <!--Caption-->
        </div>
        <!--/First slide-->

        <!--Second slide-->
        <div class="carousel-item">
            <!--Mask color-->
            <div class="view">
                <img src="<?php echo $img2; ?>" alt="" style="object-fit: cover;">
                <div class="full-bg-img">
                </div>
            </div>
            <!--Caption-->
            <div class="carousel-caption">
                <div class="animated fadeInDown">
                    <h1 class="h1-responsive">La rigueur et la fraîcheur de <br> la qualité de nos produits</h1>
                </div>
            </div>
            <!--Caption-->
        </div>
        <!--/Second slide-->

        <!--Third slide-->
        <div class="carousel-item">
            <!--Mask color-->
            <div class="view">
                <img src="<?php echo $img3; ?>" alt="" style="object-fit: cover;">
                <div class="full-bg-img">
                </div>
            </div>
            <!--Caption-->
            <div class="carousel-caption">
                <div class="animated fadeInDown">
                    <h1 class="h1-responsive">La gourmandise sucrée ou salée</h1>
                </div>
            </div>
            <!--Caption-->
        </div>
        <!--/Third slide-->
    </div>
    <!--/.Slides-->

    <!--Controls-->
    <a class="left carousel-control" href="#carousel-example-2" role="button" data-slide="prev">
        <span class="icon-prev" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#carousel-example-2" role="button" data-slide="next">
        <span class="icon-next" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
    <!--/.Controls-->
</div>