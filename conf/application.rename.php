<?php

include_once ROOT."/../conf/FLCF.php";
$FlConfigs = array();
$FLConfigs["default"] = array(

FLCF::SERVER_URL => 'local_bb.catalog.com',

//Production(and staging) should be 0 (unless debugging), for development use 2
FLCF::CORE_DEBUG => 1,

//Path of static content
FLCF::STATIC_PATH => "/",

//master
FLCF::MYSQL_MASTER_USER => "user",
FLCF::MYSQL_MASTER_PASSWORD => 'password',
FLCF::MYSQL_MASTER_SERVER => '127.0.0.1',
FLCF::MYSQL_MASTER_DB => 'Proficiency',
FLCF::MYSQL_MASTER_ROOT_USER => "user",
FLCF::MYSQL_MASTER_ROOT_PASSWORD => "password",

//unit-testing db - db should be empty
FLCF::MYSQL_TEST_USER => "user",
FLCF::MYSQL_TEST_PASSWORD => 'password',
FLCF::MYSQL_TEST_SERVER => '127.0.0.1',
FLCF::MYSQL_TEST_DB => 'test_proficiency',
FLCF::MYSQL_TEST_ROOT_USER => "user",
FLCF::MYSQL_TEST_ROOT_PASSWORD => "password",

//fixture database - generates fixtures from this db
FLCF::MYSQL_FIXTURE_USER => "user",
FLCF::MYSQL_FIXTURE_PASSWORD => 'password',
FLCF::MYSQL_FIXTURE_SERVER => '127.0.0.1',
FLCF::MYSQL_FIXTURE_DB => 'Proficiency_fixture',
FLCF::MYSQL_FIXTURE_ROOT_USER => "user",
FLCF::MYSQL_FIXTURE_ROOT_PASSWORD => "password",

);

FLCF::set($FLConfigs);