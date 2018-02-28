<?php
class Database {
    /**
     * @var PDO Représente la connexion
     */
    static $Bdd = null;

    public static function GetDatabase() {
        if(self::$Bdd != null)
            return self::$Bdd;
        else {
            try {
                self::$Bdd = new PDO(
                    'mysql:host=localhost;dbname=ofaitmaison;charset=utf8',
                    'root',
                    '');
                self::$Bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$Bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

                return self::$Bdd;
            }
            catch (Exception $e) {
                die('Erreur : ' . $e->getMessage());
            }
        }
    }
    /**
     * @param string $query La requête
     * @param array $params Paramètres de la requête
     * @return mixed
     */
    public static function query($query, $params = []) {
        $statement = self::$Bdd->prepare($query);
        $statement->execute($params);
        return $statement;
    }

}