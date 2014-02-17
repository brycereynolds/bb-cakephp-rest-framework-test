<?php

class FLCF {
    private static $cfgObject = null;
    private static $K = "default";

    const SERVER_URL = 'SERVER_URL';

    const CORE_DEBUG = 'CORE_DEBUG';

    const STATIC_PATH = 'STATIC_PATH';

    const MYSQL_MASTER_USER = 'MYSQL_MASTER_USER';
    const MYSQL_MASTER_PASSWORD = 'MYSQL_MASTER_PASSWORD';
    const MYSQL_MASTER_SERVER = 'MYSQL_MASTER_SERVER';
    const MYSQL_MASTER_DB = 'MYSQL_MASTER_DB';
    const MYSQL_MASTER_ROOT_USER = 'MYSQL_MASTER_ROOT_USER';
    const MYSQL_MASTER_ROOT_PASSWORD = 'MYSQL_MASTER_ROOT_PASSWORD';

    const MYSQL_TEST_USER = 'MYSQL_TEST_USER';
    const MYSQL_TEST_PASSWORD = 'MYSQL_TEST_PASSWORD';
    const MYSQL_TEST_SERVER = 'MYSQL_TEST_SERVER';
    const MYSQL_TEST_DB = 'MYSQL_TEST_DB';
    const MYSQL_TEST_ROOT_USER = 'MYSQL_TEST_ROOT_USER';
    const MYSQL_TEST_ROOT_PASSWORD = 'MYSQL_TEST_ROOT_PASSWORD';

    const MYSQL_FIXTURE_USER = 'MYSQL_FIXTURE_USER';
    const MYSQL_FIXTURE_PASSWORD = 'MYSQL_FIXTURE_PASSWORD';
    const MYSQL_FIXTURE_SERVER = 'MYSQL_FIXTURE_SERVER';
    const MYSQL_FIXTURE_DB = 'MYSQL_FIXTURE_DB';
    const MYSQL_FIXTURE_ROOT_USER = 'MYSQL_FIXTURE_ROOT_USER';
    const MYSQL_FIXTURE_ROOT_PASSWORD = 'MYSQL_FIXTURE_ROOT_PASSWORD';

    const CDN_AWS_STATIC = 'CDN_AWS_STATIC';
    const CDN_USER_IMAGES_VENDOR = 'CDN_USER_IMAGES_VENDOR';
    const CDN_CONTENT_VENDOR = 'CDN_CONTENT_VENDOR';
    const CDN_WEB_VENDOR = 'CDN_WEB_VENDOR';
    const CDN_WEBROOT = 'CDN_WEBROOT';
    const SLAVE_DB = 'SLAVE_DB';

    public static function set($array){
        self::$cfgObject = $array;
    }

    public static function get($key, $defaultValue = false){
        if(!isset(self::$cfgObject[self::$K][$key])){
            return $defaultValue;
        }else{
            return self::$cfgObject[self::$K][$key];
        }
    }

    public static function setcfg($newkey){
        self::$K = $newkey;
    }

    public static function dbpw_master(){
        return self::get(self::MYSQL_MASTER_PASSWORD);
    }
    public static function dbuser_master(){
        return self::get(self::MYSQL_MASTER_USER);
    }
    public static function dbserver_master(){
        return self::get(self::MYSQL_MASTER_SERVER);
    }
    public static function dbname_master(){
        return self::get(self::MYSQL_MASTER_DB);
    }


    public static function dbpw_test(){
        return self::get(self::MYSQL_TEST_PASSWORD);
    }
    public static function dbuser_test(){
        return self::get(self::MYSQL_TEST_USER);
    }
    public static function dbserver_test(){
        return self::get(self::MYSQL_TEST_SERVER);
    }
    public static function dbname_test(){
        return self::get(self::MYSQL_TEST_DB);
    }


    public static function dbpw_fixture(){
        return self::get(self::MYSQL_FIXTURE_PASSWORD);
    }
    public static function dbuser_fixture(){
        return self::get(self::MYSQL_FIXTURE_USER);
    }
    public static function dbserver_fixture(){
        return self::get(self::MYSQL_FIXTURE_SERVER);
    }
    public static function dbname_fixture(){
        return self::get(self::MYSQL_FIXTURE_DB);
    }

    public static function static_path(){
        return self::get(self::STATIC_PATH);;
    }
}
