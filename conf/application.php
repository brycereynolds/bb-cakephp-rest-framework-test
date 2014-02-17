<?php
include_once ROOT."/../conf/FLCF.php";
$FlConfigs = array();
$FLConfigs["default"] = array(

FLCF::SERVER_URL => 'local_bb.catalog.com',

FLCF::CORE_DEBUG => 1,

//Path of static content
FLCF::STATIC_PATH => "/",

//master
FLCF::MYSQL_MASTER_USER => "root",
FLCF::MYSQL_MASTER_PASSWORD => 'password',
FLCF::MYSQL_MASTER_SERVER => '127.0.0.1',
FLCF::MYSQL_MASTER_DB => 'bb_catalog',
FLCF::MYSQL_MASTER_ROOT_USER => "root",
FLCF::MYSQL_MASTER_ROOT_PASSWORD => "password",

//unit-testing db - db should be empty
FLCF::MYSQL_TEST_USER => "root",
FLCF::MYSQL_TEST_PASSWORD => 'password',
FLCF::MYSQL_TEST_SERVER => '127.0.0.1',
FLCF::MYSQL_TEST_DB => 'bb_catalog_test',
FLCF::MYSQL_TEST_ROOT_USER => "root",
FLCF::MYSQL_TEST_ROOT_PASSWORD => "password",

//fixture database - generates fixtures from this db
FLCF::MYSQL_FIXTURE_USER => "root",
FLCF::MYSQL_FIXTURE_PASSWORD => 'password',
FLCF::MYSQL_FIXTURE_SERVER => '127.0.0.1',
FLCF::MYSQL_FIXTURE_DB => 'bb_catalog_fixture',
FLCF::MYSQL_FIXTURE_ROOT_USER => "root",
FLCF::MYSQL_FIXTURE_ROOT_PASSWORD => "password",

);

FLCF::set($FLConfigs);