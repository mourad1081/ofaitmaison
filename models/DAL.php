<?php
require_once("App.php");

interface DAL {
    public static function Read($id);
    public static function Delete($id);
    public static function Update($entity);
    public static function Create($entity);
}
