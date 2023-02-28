<?php
/**
 * @package Date_Heure
 * @version 1.0
 */
/*
Plugin Name: Date Heure
Description: It's just a plugin to get the time and date of the day.
Author: Antoine
Version: 1.0
*/

function date_hour()
{
    date_default_timezone_set('Europe/Paris');
    $time = getdate();
    $date = new DateTime();
    printf('<p>Il est '.$date->format('H:i').' Nous sommes le '.$date->format('d/m/Y').'</p>');
    // // echo ($date->format('d/m/Y H:i'));
}
add_action('admin_notices','date_hour');
add_action( 'wp_enqueue_scripts','date_hour' );

