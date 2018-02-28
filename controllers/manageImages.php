<?php
/**
 * Created by PhpStorm.
 * User: Mourad
 * Date: 18-08-17
 * Time: 03:52
 */

require "../vendor/autoload.php";

ini_set('memory_limit', '256M');

// import the Intervention Image Manager Class
use Intervention\Image\ImageManager;

// create an image manager instance with favored driver
$manager = new ImageManager();


// $files = glob("../img/uploads/*.{[jJ][pP][gG],[jJ][pP][eE][gG],[pP][nN][gG],[gG][iI][fF],[bB][mM][pP]}", GLOB_BRACE);
// Création des images dans les bonnes qualités
$image = $manager->make('../img/uploads/tiramisu.jpg');
$image->resize(null, 300, function ($constraint) { $constraint->aspectRatio(); });
$image->save('../img/md/tiramisu.jpg', 80);
$image->destroy();







