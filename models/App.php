<?php

/**
 * Initialisation de toute la merde
 */
spl_autoload_register("MonAutoLoader"); // Auto load des classes

function MonAutoLoader($class) { // Fonction chargée d'autoload les classes
    require "$class.php";
}

Database::GetDatabase();

