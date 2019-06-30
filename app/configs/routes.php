<?php

/*
 * Routes
 *
 * Syntax is:
 * route => array attributes[module, action]
 *
 * placeholder in route with ":"
 */

$routes = array(
    "" => array("module" => "news", "action" => "list"),
    "news" => array("module" => "news", "action" => "list"),
    "news/full/:id" => array("module" => "news", "action" => "full"),
    "comments/insert" => array("module" => "comments", "action" => "insert"),
    "aboutme" => array("module" => "aboutme", "action" => "show"),
    "projects" => array("module" => "projects", "action" => "list"),
    "contact" => array("module" => "contact", "action" => "show"),
    "contact/insert" => array("module" => "contact", "action" => "insert"),
    "impressum" => array("module" => "impressum", "action" => "show"),
    "register" => array("module" => "user", "action" => "register"),
    "user/insert" => array("module" => "user", "action" => "insert"),
    "login" => array("module" => "user", "action" => "login"),
    "logout" => array("module" => "user", "action" => "logout"),
    "profile" => array("module" => "user", "action" => "profile"),
    "profile/:id" => array("module" => "user", "action" => "profile"),
    "admin" => array("module" => "admin", "action" => "overview"),
    "news/add" => array("module" => "news", "action" => "add"),
    "news/manage" => array("module" => "news", "action" => "manage"),
    "news/edit/:id" => array("module" => "news", "action" => "edit"),
    "news/delete/:id" => array("module" => "news", "action" => "delete"),
    "contact/manage" => array("module" => "contact", "action" => "manage")
);

return $routes;

?>