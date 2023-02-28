<?php
/*
Plugin Name: Export Database to XML
Description: Exports the WordPress database to XML format.
Author: Antoine
Version: 1.0
*/



add_action('admin_menu', 'export_xml_menu');
add_action( 'admin_menu', 'my_plugin_enqueue_scripts' );

add_action('wp_ajax_download_xml','export_to_xml', 10,2);
add_action('wp_ajax_nopriv_download_xml', 'export_to_xml', 10 ,2);

function export_xml_menu() {
    add_menu_page('Export XML', 'Export XML', 'manage_options', 'export-xml', 'export_xml_page');
}

function my_plugin_enqueue_scripts() {
    wp_enqueue_script('mypluginscript', plugins_url('/myscript.js', __FILE__ ));
    wp_localize_script( 'mypluginscript', 'my_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}

function export_filter()
{
    parse_str($_POST['form'], $test);
    $name = $test['name'] ?: "";
    $reference = $test['reference'] ?: "";
    $orderby = $test['orderby']?: "";
    $acd = $test['asc']? 'DESC':"ASC";

    if(empty($name)&&empty($reference))
    {
        $query = "";
    }

    elseif (empty($name)^empty($reference))
    {
        if($name)
        {
            $query = ("WHERE name LIKE '".$name."%'");
        }
        elseif ($reference)
        {
            $query = ("WHERE reference LIKE '".$reference."%'");
        }
    }

    elseif ($name&&$reference)
    {
        $query = ("WHERE name LIKE '".$name."%' AND reference LIKE '".$reference."%'");
    }
    if($acd&&$orderby)
    {
        $query = $query." ORDER BY $orderby $acd ";
    }

    return $query;
}

function export_to_xml()
{
    $query = export_filter();
    global $wpdb;
    $table_name = $wpdb->prefix . table_name;
    $rows = $wpdb->get_results("SELECT * FROM $table_name ".$query, ARRAY_A);
    $xml = new SimpleXMLElement('<xml/>');

    array_walk_recursive($rows, function ($value, $key) use ($xml) {
        $xml->addChild($key, $value);
    }, ARRAY_FILTER_USE_BOTH);

    echo $xml->asXML();

    exit();
}

function export_xml_page()
{
    ?>
    <h1>Export Database to XML</h1>
    <p id="test-ajax">Click the button below to download the database as XML file.</p>
    <br>

   <form id="myform" method="post">
        <label for="name">Nom</label>
        <input id="name" name="name" type="text" >
        &nbsp;
        <label for="reference">Référence</label>
        <input id="reference" name="reference" type="number">
        &nbsp;
        <label for="orderby"> Trier par </label>
        <select for="orderby" name="orderby"  id="orderby">
            <option value="id">id</option>
            <option value="name">name</option>
            <option value="reference">reference</option>
            <option value="prix">prix</option>
        </select>
        <label for="asc">Ordre descendant</label>
        <input id="asc" name="asc" type="checkbox">

    </form>
        <br>
    <button type="submit" id="download-xml" class="button">Télécharger le fichier XML</button>
        <br>
<?php
}


