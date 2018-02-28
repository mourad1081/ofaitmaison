<?php
require_once("App.php");

class User {

    private $champs = array();

    /**
     * @return array
     */
    public function getChamps()
    {
        return $this->champs;
    }

    /**
     * @param array $champs
     */
    public function setChamps($champs = array())
    {
        if(!is_array($champs))
            throw new InvalidArgumentException("Erreur : Il faut un tableau");

        $this->champs = $champs;
    }

    /**
     * @param array $args
     */
    function __construct($args = array())
    {
        if (isset($args))
        {
            $this->champs = $args;
        }
    }

    function __toString()
    {
        return var_export($this, true);
    }
}