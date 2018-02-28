<?php require("shared/header.php"); ?>
<body>
<?php require("shared/menu.php"); ?>

<div id="contact" class="container">
    <div class="row">
        <div class="col-md-12" style="margin-bottom: 30px;">
            <h1 class="h1-responsive" style="font-family: Satisfy; margin-bottom: 20px;">Contactez-nous</h1>
        </div>
        <div class="col-md-6">
            <h4 class="h4-responsive" style="margin-top: 30px; text-align: left; margin-left: 30px">Coordonnées</h4>
            <p style="text-align:left; margin-bottom: 0px; margin-left:30px;"><strong>Téléphone :</strong> +32 488 09 44 24</p>
            <p style="text-align:left; margin-bottom: 0px; margin-left:30px;"><strong>E-mail :</strong> info@ofaitmaison.be</p>
            <div>
                <h4 class="h4-responsive" style="margin-bottom: 30px;margin-top: 30px;">Retrouvez-nous sur vos réseaux sociaux préférés</h4>
            </div>
            <div>
                <a href="https://www.facebook.com/Aufaitmaison1/" class="social-btn bg-fb z-depth-1"><i class="fa fa-facebook"></i></a>
                <a href="https://www.instagram.com/ofaitmaisonbxl/" class="social-btn bg-insta z-depth-1"><i class="fa fa-instagram"></i></a>
                <a href="mailto:info@ofaitmaison.be" class="social-btn bg-mail z-depth-1"><i class="fa fa-envelope"></i></a>
            </div>
        </div>
        <div class="col-md-6">
            <h2 class="h2-responsive" style="margin-bottom: 30px; font-family: Satisfy;">Vous désirez nous envoyer un message ?</h2>
            <form id="form-contact" class="z-depth-3">
                <div class="row">
                    <div class="col-md-6">
                        <div class="md-form">
                            <i class="fa fa-user prefix"></i>
                            <input type="text" id="name" class="form-control">
                            <label for="name">Nom prénom</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="md-form">
                            <i class="fa fa-at prefix"></i>
                            <input type="email" id="mail" class="form-control validate">
                            <label for="mail" data-error="wrong" data-success="right">E-mail</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="md-form">
                            <i class="fa fa-pencil prefix"></i>
                            <textarea type="text" id="msg" class="md-textarea"></textarea>
                            <label for="msg">Votre message</label>
                        </div>
                    </div>
                </div>
                <button id="send-mail" class="btn btn-primary"><i class="fa fa-send"></i> ENVOYER</button>
            </form>
        </div>
        <div class="col-md-12">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d80598.70582421143!2d4.305350454127736!3d50.85506246076264!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47c3a4ed73c76867%3A0xc18b3a66787302a7!2sBruxelles!5e0!3m2!1sfr!2sbe!4v1501941928400"
                    width="100%"
                    height="650"
                    frameborder="0"
                    style="border:0" allowfullscreen></iframe>
        </div>
    </div>
</div>

<?php require("shared/footer.php"); ?>
<!-- Sweet alert -->
<script src="https://cdn.jsdelivr.net/sweetalert2/6.3.8/sweetalert2.min.js"></script>
<script>
    $("body").css("background", "url(img/bgg.jpg) no-repeat center fixed");
    $("body").css("-webkit-background-size", "cover");
    $("body").css("background-size", "cover");

    function validateEmail(email) {
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }

    $("#send-mail").click(function (e) {
        e.preventDefault();
        startLoading($('#send-mail'), 'fa-send');
        var datas = {
            name: $("#name").val(),
            email: $("#mail").val(),
            content: $("#msg").val()
        };
        console.log(datas);
        if(validateEmail(datas.email))
            $.ajax({
                method: "POST",
                url:"controllers/ajaxCalls.php",
                data: {action: "sendMail", data: JSON.stringify(datas)},
                success: function(result) {
                    if(result == "1") {
                        swal({
                            title: 'Mail envoyé avec succès',
                            text: "Merci ! Nous vous recontacterons s'il le faut.",
                            type: 'success'
                        }).then(function (e) {
                            $("#name").val("");
                            $("#mail").val("");
                            $("#msg").val("");
                        });
                        stopLoading($('#send-mail'), 'fa-send');
                    } else {
                        swal({
                            title: 'Ooups !',
                            text: "Ooups ! L'envoi du mail a échoué :-(.",
                            type: 'error'
                        });
                        stopLoading($('#send-mail'), 'fa-send');
                    }
                },
                error: function(xhr, error) {
                    console.log(xhr);
                    console.log(error);
                }
            });
        else
            alert("veuillez indiquer une adresse e-mail valide et remplir tous les autres champs.");
    });

    /**
     * Place un spinner loading sur l'élément reçu en paramètre à la place de l'icone déjà en place
     * @param $HTMLElement
     * @param classToRemove
     */
    function startLoading($HTMLElement, classToRemove)
    {
        $HTMLElement.find('i').removeClass(classToRemove).addClass('fa-spin fa-circle-o-notch');
        $HTMLElement.addClass('disabled');
        $HTMLElement.attr('disabled', true);
    }

    /**
     * Place un spinner loading sur l'élément reçu en paramètre à la place de l'icone déjà en place
     * @param $HTMLElement
     * @param classToAdd
     */
    function stopLoading($HTMLElement, classToAdd)
    {
        $HTMLElement.find('i').addClass(classToAdd).removeClass('fa-spin fa-circle-o-notch');
        $HTMLElement.removeClass('disabled');
        $HTMLElement.removeAttr('disabled');

    }
</script>
</body>
</html>
