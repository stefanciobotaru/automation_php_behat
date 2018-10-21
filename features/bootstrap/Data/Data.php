<?php
/**
 * Created by PhpStorm.
 * User: stefan.ciobotaru
 * Date: 10/19/2018
 * Time: 8:50 PM
 */

namespace Data;

class Data
{

    /**
     * Returns data for adding new repository
     *
     * @param $name
     *
     * @return array
     */
    public static function generateRandomDataForNewRepository($name)
    {

        $data = [
            "name"         => $name . self::generateRandomString(10),
            "description"  => "This is a test repo",
            "homepage"     => "https://github.com/stefanciobotaru",
            "private"      => false,
            "has_issues"   => true,
            "has_projects" => true,
            "has_wiki"     => true,
        ];

        return json_encode($data);

    }

    /**
     * @param null $length
     *
     * @return string
     */
    public static function generateRandomString($length = null)
    {
        if ($length === null) {
            $length = 6;
        }
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        return substr(str_shuffle($chars), 0, $length);
    }

}