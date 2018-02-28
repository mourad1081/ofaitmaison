<?php
define('__ROOT__', realpath(dirname(__FILE__)) . '/');
require(__ROOT__ . "../../models/Database.php");
require(__ROOT__ . "../../models/App.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <!-- Chrome, Firefox OS and Opera -->
    <meta name="theme-color" content="#af59a9">
    <!-- Windows Phone -->
    <meta name="msapplication-navbutton-color" content="#af59a9">
    <!-- iOS Safari -->
    <meta name="apple-mobile-web-app-status-bar-style" content="#af59a9">

    <title>Ô fait maison</title>
    <meta name="description" content="Plateforme belge de pâtisserie en tout genre à commander et déguster sur Bruxelles." />

    <link rel="icon" href="icon-site.png">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.0/css/font-awesome.min.css">
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Design Bootstrap -->
    <link href="css/mdb.min.css" rel="stylesheet">
    <!-- Satisfy font -->
    <link href="https://fonts.googleapis.com/css?family=Satisfy" rel="stylesheet">
    <!-- Sweet alert CSS -->
    <link href="https://cdn.jsdelivr.net/sweetalert2/6.3.8/sweetalert2.min.css" rel="stylesheet">
    <link href="plugins/justified-galery/justifiedGallery.min.css" rel="stylesheet">
    <link rel="stylesheet" href="plugins/pickdate/themes/classic.css">
    <link rel="stylesheet" href="plugins/pickdate/themes/classic.date.css">
    <link rel="stylesheet" href="plugins/pickdate/themes/classic.time.css">
    <!-- Your custom styles (optional) -->
    <link href="css/style.min.css" rel="stylesheet">
</head>

<?php
setlocale(LC_ALL,'fr_FR.UTF-8');
function mb_basename($file)
{
    $temp = explode('/', $file);
    return end($temp);
}

?>