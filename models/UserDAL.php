<?php

require_once("App.php");

class UserDal implements  DAL {

    public static function Read($id)
    {
        return Database::query("SELECT * FROM users WHERE id = :id", ["id" => $id])->fetch();
    }
    public static function ReadAll()
    {
        return Database::query("SELECT * FROM users")->fetchAll();
    }
    public static function Delete($id)
    {
        return Database::query("DELETE FROM users WHERE id = :id", ["id" => $id]);
    }

    public static function Update($user)
    {

    }

    /**
     * @param User $user
     * @return mixed
     */
    public static function Create($user)
    {

        return Database::query("INSERT INTO users (username, password, email, role_id)
                                    VALUES (:username, :pass, :email, :role_id)",
                                ["username"   => $user->getChamps()["username"],
                                 "pass"       => $user->getChamps()["password"],
                                 "email"      => $user->getChamps()["email"],
                                 "role_id"    => $user->getChamps()["role_id"]
                                ]);
    }

}