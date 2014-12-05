<?php 
global $wpdb;
$table_posts = $wpdb->prefix.'posts';

//$post_id = 963;
//$post = get_post($post_id);
//$description = $post->post_content;
//$pos1 = strpos($description, 'tag_id');
//if($pos1 != NULL){
//    $pos2 = strpos($description, '='); 
//    $pos_start = $pos2 + 1;
//    $pos_end = strpos($description, ']');
//    $number_for_id = substr($description, $pos_start, $pos_end - $pos_start);
//    var_dump($description);exit;
//    $text = trim($number_for_id);
////    var_dump(trim($number_for_id));
//}
// else {
//    $text = 'all_ids';
//}
// if ($attr['tag_id'] == $text) { 
//        $term = trim($number_for_id); 
//    } 
//    else {
//        $term = "all_ids";
//    } 
if($attr['tag_id'] == '3'){
     $term = '3';
}
else if($attr['tag_id'] == '4'){
    $term = '4';
}
else{
    $term = "all_ids";
}

?>
<?php $plugin_url = plugins_url() . '/courses-search/'; ?>
<style>
    .ppdiv{
        background-image: url('<?php echo $plugin_url ?>css/images/ajax-loader.gif');
        background-repeat: no-repeat;
        background-position:50% 50%;
        width: 20px !important;
        height: 20px;
    }
    #popup_hover{
        background-color:#fff; 
        width:300px; 
        padding:5px;
        border-radius:5px; 
        display:none; 
        position:absolute; 
        border:1px solid #ccc; 
        box-shadow:0px 0px 10px rgba(0,0,0,0.5); 
        z-index: 100;
    }
</style>
<?php //$plugin_url = plugins_url() . '/courses-search/'; ?>
<div id="popup_hover">
</div>

<div id="popup" style="background-color:#fff; width:300px; padding:5px; border-radius:5px; display:none; position:absolute; border:1px solid #ccc; box-shadow:0px 0px 10px rgba(0,0,0,0.5); z-index: 200;">
</div>

<div id="popup_bg" style="position:fixed; width:100%; height: 100%; left:0; top:0; z-index: 150; display:none;"></div>

<div class="data_table_div" style="width: 700px !important;">
    <table id="example" class="display" style="width: 700px !important;">
        <input type="hidden" name="select_value" value="<?php echo $term; ?>" class="value1" />
       
        <thead>
            <tr>
                <th width="28%">Cursusnaam</th>
                <th width="28%">Thema</th>
                <th width="22%">Startdatum</th>
                <th width="22%">Aantal dagen</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td colspan="5" class="dataTables_empty">Loading data from server</td>
            </tr>
        </tbody>

        <tfoot>
            <tr>
                <th>Cursusnaam</th>
                <th>Thema</th>
                <th>Startdatum</th>
                <th>Aantal dagen</th>
            </tr>
        </tfoot>

    </table>
</div>