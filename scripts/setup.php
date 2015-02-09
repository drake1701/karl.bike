<?php
/**
 * @author		Dennis Rogers
 * @address		www.drogers.net
 */
 
$db = new PDO('sqlite:'.dirname(__FILE__).'/../karl.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$db->exec("DROP TABLE IF EXISTS 'page';");
$db->exec("CREATE TABLE 'page' (
    'id' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    'title' TEXT,
    'content' TEXT,
    'order' INT,
    'status' BOOLEAN DEFAULT 1
)");

$db->exec("DROP TABLE IF EXISTS 'image';");
$db->exec("CREATE TABLE 'image' (
    'id' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 
    'filename' TEXT, 
    'caption' TEXT, 
    'order' INT,
    'status' BOOLEAN DEFAULT 1
)");
