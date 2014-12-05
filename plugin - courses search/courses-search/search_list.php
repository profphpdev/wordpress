<?php 
global $wpdb;
$table_posts = $wpdb->prefix.'posts';
$editable_items_search = $_POST['taxonomy'];
$editable = $_POST['textsearch'];

define("COURSES_PATH", plugin_dir_path( __FILE__ ));
$plugin_url = plugins_url() . '/courses-search/';
?>

<?php
//$post_id = 883;
//$post = get_post($post_id);
//$description = $post->post_content;
//var_dump($description);exit

?>
<?php $plugin_url = plugins_url() . '/courses-search/'; ?>
<style>
    .ppdiv{
        background-image: url('<?php echo $plugin_url ?>css/images/ajax-loader.gif');
        background-repeat: no-repeat;
        background-position: center;
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
<div id="popup_hover">
</div>
<!--<form action="<?php //get_admin_url() ?>my-test-page/"> -->
<div id="popup" style="background-color:#fff; width:300px; padding:5px; border-radius:5px; display:none; position:absolute; border:1px solid #ccc; box-shadow:0px 0px 10px rgba(0,0,0,0.5); z-index: 200;">
</div>
<!--</form>-->

<div id="popup_bg" style="position:fixed; width:100%; height: 100%; left:0; top:0; z-index: 150; display:none;"></div>

<div class="data_table_div" style="width: 940px !important;">
    <table id="example" class="display" style="width: 940px !important;">
        <input type="hidden" name="select_value" value="<?php echo $editable_items_search; ?>" class="value1" />
        <input type="hidden" name="select_value" value="<?php echo $editable; ?>" class="value2" />
        
        <thead>
            <tr>
                <th width="35%">Cursusnaam</th>
                <th width="25%">Thema</th>
                <th width="20%">Startdatum</th>
                <th width="20%">Aantal dagen</th>
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