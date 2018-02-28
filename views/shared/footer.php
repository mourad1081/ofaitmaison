<!-- Footer -->
<footer class="page-footer center-on-small-only">
    <!--Footer Links-->
    <div class="container" >
        <div class="row">
            <div class="col-md-12">
                <h4><i class="fa fa-hashtag"></i> S'abonner</h4>
                <span><i class="fa fb fa-facebook"> </i><a href="https://www.facebook.com/Aufaitmaison1/"> Facebook</a></span>
                | <span><i class="fa insta fa-instagram"> </i><a href="https://www.instagram.com/ofaitmaisonbxl/"> Instagram</a>  </span>
                | <span><i class="fa yt fa-youtube"> </i> YouTube</span>
                | <span><i class="fa mail fa-envelope"> </i> News letter</span>
            </div>
        </div>
    </div>
    <div>
        <h4>Ô fait maison, le plaisir de la gourmandise !</h4>
    </div>
    <div class="footer-copyright">
        <div class="container-fluid">
            <p>&copy; ofaitmaison.be Tout droits reservés</p>
        </div>
    </div>

</footer>

<!--/.Footer-->

<!-- SCRIPTS -->

<!-- JQuery -->
<script type="text/javascript" src="js/jquery-2.2.3.min.js"></script>
<!-- Bootstrap tooltips -->
<script type="text/javascript" src="js/tether.min.js"></script>
<!-- Bootstrap core JavaScript -->
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<!-- MDB core JavaScript -->
<script type="text/javascript" src="js/mdb.min.js"></script>

<script src="plugins/justified-galery/jquery.justifiedGallery.min.js"></script>
<!-- Include the Quill library -->
<script src="https://cdn.quilljs.com/1.3.2/quill.js"></script>

<!-- Animations init-->
<script>
    $(document).ready(function (e) {
        var menuMobile = $("#panel-menu-mobile");
        var visible = false;
        new WOW().init();
        $("#carousel-example-2").carousel({
            interval: 3000,
            pause: false
        });
        $("#btn-menu-mobile, #close-menu").click(function (e) {
            e.preventDefault();
            e.stopPropagation();
            !visible ? menuMobile.addClass("est-visible") : menuMobile.removeClass("est-visible");
            visible = !visible;
        })
    });

</script>