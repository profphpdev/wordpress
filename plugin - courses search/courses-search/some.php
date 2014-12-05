<?php
global $wpdb;
$table_posts = $wpdb->prefix.'posts';
$table_postmeta = $wpdb->prefix.'postmeta';
$search_id = $_POST['name'];
$type = $_POST['type'];

$sql_for_post_title = "SELECT * FROM $table_posts WHERE ID = '$search_id'";
$select_post_title_for_popup= $wpdb->get_row($sql_for_post_title);

$sql = "SELECT meta_value FROM $table_postmeta WHERE post_ID = '$search_id'
                      AND meta_key = '_ct_textarea_4fccb0a8a6d44'
              ";
$sql_result = $wpdb->get_row($sql);


    
$sql_for_all = "SELECT meta_value FROM $table_postmeta 
                WHERE post_ID = '$search_id' 
                        AND (meta_key = '_ct_datepicker_4fccaf4084e17' 
                            OR meta_key = '_ct_text_4f951fbf0e3fb' 
                            OR meta_key = '_ct_text_500e6452a98d4'
                            OR meta_key = '_ct_text_4fccaea20e329' 
                            OR meta_key = '_ct_text_4f951fdbb641f' 
                            OR meta_key = '_ct_textarea_4fccb0a8a6d44')";
$sql_result_for_all = $wpdb->get_results($sql_for_all);


switch($type){
    case "hover":
        echo $sql_result->meta_value.' . ';;
        break;
    case "click":{
        $pieces = explode(",", $select_post_title_for_popup->post_title);
        echo '<span style="font-weight: bold; border-bottom: 1px solid #CCC; display: block; padding-bottom: 3px;">'.$pieces[0].'</span>';
        foreach ($sql_result_for_all as $pop_for_all){
            echo($pop_for_all->meta_value).' . ';
        }
        break;}
}

die();
?>