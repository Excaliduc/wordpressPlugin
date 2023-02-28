<?php
/**
 * @package Twig MVC
 * @version 1.0
 */
/*
Plugin Name: Twig MVC
Description: It's just a plugin to get the time and date of the day.
Author: Antoine
Version: 1.0
*/
add_action('admin_menu',array('Controller','my_menu'),0);

class Controller
{
    public static function my_menu()
    {
        add_menu_page('Twig', 'Twig', 'manage_options', 'Twig', array('Controller','date_hour'),'dashicons-carrot',110);
    }
    public static function date_hour()
    {
        require_once(__DIR__.'/vendor/autoload.php');
        $loader = new \Twig\Loader\FilesystemLoader(__DIR__."/templates");
        $twig = new \Twig\Environment($loader);
        echo $twig->render('demo.twig', ['name' => 'Bob razowski']);
    }

}

