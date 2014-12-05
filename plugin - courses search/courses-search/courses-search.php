<?php
/*
  Plugin Name: Courses_search
  Description: 
  Author:
  Version: 1.0
 */

define("COURSES_PATH", plugin_dir_path( __FILE__ ));

function search_load_scripts() {
    wp_register_style('datatable-css', plugins_url('datatable/media/css/demo_table.css', __FILE__), array(), '1.85', 'all');
    wp_enqueue_style('datatable-css');
    wp_register_style('ui-css', plugins_url('css/jquery-ui-1.8.18.custom.css', __FILE__), array(), '1.8.18', 'all');
    wp_enqueue_style('ui-css');
wp_register_script('jquery', plugins_url('js/jquery-1.7.1.min.js', __FILE__), '1.7.1', true);
wp_enqueue_script('jquery');
    wp_register_script('ui', plugins_url('js/jquery-ui-1.8.18.custom.min.js', __FILE__), '1.8.18', true);
    wp_enqueue_script('ui');
    wp_register_script('datatable-js', plugins_url('datatable/media/js/jquery.dataTables.js', __FILE__), '1.85', true);
    wp_enqueue_script('datatable-js');
    wp_register_script( 'ajax-script', plugins_url('js/script.js', __FILE__),'1.078', true);
    wp_enqueue_script( 'ajax-script');
    wp_localize_script( 'ajax-script', 'ajax_object', array( 'ajaxurl' =>get_admin_url(). 'admin-ajax.php','ajaxurl2' => get_admin_url(). 'admin-ajax.php') );
}

function getSearchList() {

        require_once COURSES_PATH . 'ajax-search.php';
}
add_action( 'wp_ajax_nopriv_getSearchList', 'getSearchList' );
add_action( 'wp_ajax_getSearchList', 'getSearchList' );

function updateJournalItem() {
    
        require_once COURSES_PATH . 'some.php';
}
add_action( 'wp_ajax_nopriv_updateJournalItem', 'updateJournalItem' );
add_action( 'wp_ajax_updateJournalItem', 'updateJournalItem' );

function short_search_load_scripts() {
    wp_register_style('datatable-css', plugins_url('datatable/media/css/demo_table.css', __FILE__), array(), '1.85', 'all');
    wp_enqueue_style('datatable-css');
    wp_register_style('ui-css', plugins_url('css/jquery-ui-1.8.18.custom.css', __FILE__), array(), '1.8.18', 'all');
    wp_enqueue_style('ui-css');
wp_register_script('jquery', plugins_url('js/jquery-1.7.1.min.js', __FILE__), '1.7.1', true);
wp_enqueue_script('jquery');
    wp_register_script('ui', plugins_url('js/jquery-ui-1.8.18.custom.min.js', __FILE__), '1.8.18', true);
    wp_enqueue_script('ui');
    wp_register_script('datatable-js', plugins_url('datatable/media/js/jquery.dataTables.js', __FILE__), '1.85', true);
    wp_enqueue_script('datatable-js');
    wp_register_script( 'journal-script', plugins_url('js/journal.js', __FILE__),'1.078', true);
    wp_enqueue_script( 'journal-script');
    wp_localize_script( 'journal-script', 'ajax_object', array( 'ajaxurl' =>get_admin_url(). 'admin-ajax.php','ajaxurl2' => get_admin_url(). 'admin-ajax.php') );
}
function getShortJournalList() {
     
        require_once COURSES_PATH . 'ajax-shortJournal.php';
}
add_action( 'wp_ajax_nopriv_getShortJournalList', 'getShortJournalList' );
add_action( 'wp_ajax_getShortJournalList', 'getShortJournalList' );

function updateShortJournalItem() {
    
        require_once COURSES_PATH . 'ajax-shortSome.php';
}
add_action( 'wp_ajax_nopriv_updateShortJournalItem', 'updateShortJournalItem' );
add_action( 'wp_ajax_updateShortJournalItem', 'updateShortJournalItem' );

function Zoeken_Data(){
    global $wpdb;
        $table_taxonomy = $wpdb->prefix.'term_taxonomy';
        $table_terms = $wpdb->prefix.'terms';

        $sql_for_taxonomy_id = "SELECT * FROM $table_taxonomy WHERE taxonomy='thema' ORDER BY term_id ASC";
        $result_select_taxonomy_id = $wpdb->get_results($sql_for_taxonomy_id);
        ?>

        <?php $plugin_url = plugins_url() . '/courses-search/'; ?>
        <form method="post" style="overflow: auto;" action="<?php get_admin_url() ?>/cursus-zoeken/">

            Op thema <br />
            <select name="taxonomy" style="margin-bottom: 10px; width:183px; padding:4px 3px;">
            <?php
                foreach($result_select_taxonomy_id as $taxonomy_id){
                    $sql_for_taxonomy = "SELECT * FROM $table_terms WHERE term_id='$taxonomy_id->term_id' ORDER BY term_id ASC";

                    $result_select_taxonomy= $wpdb->get_results($sql_for_taxonomy);
                    foreach($result_select_taxonomy as $taxonomy){
                        echo '<option style="width: 155px;" value="'.$taxonomy->term_id.'">'.$taxonomy->name.'</option>';
                    }
                }
                foreach ($ids_for_thema as $ift){
                    $all_id[] = $ift;
                }
                echo '<option style="width: 155px;" value="all_ids" selected>Alle cursussen</option>';
            ?>
            </select><br />

            Op naam <br />
            <label>
                <input type='text' name="textsearch" value='' style='width: 170px;'/>
            </label><br />

            <label>
                <input id='input' type='submit' name='search' value='ZOEK' style='margin:15px auto;width:85px;display:block; float: left'/>
            </label>
        </form>
        <?php
    include 'search_list.php'; ?> <br /><br /><br /><br /> <?php
}

function Cursus_zoeken_short() {
    Zoeken_Data();
    search_load_scripts();
}
add_shortcode('cursus_zoeken', 'Cursus_zoeken_short');

class ZoekCursusWidget extends WP_Widget {
    /** constructor */
    function ZoekCursusWidget() {
        parent::WP_Widget(false, $name = 'Zoek Cursus', array('description' => "Zoek Cursus."));	
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
        
        global $wpdb;
        $table_taxonomy = $wpdb->prefix.'term_taxonomy';
        $table_terms = $wpdb->prefix.'terms';

        $sql_for_taxonomy_id = "SELECT * FROM $table_taxonomy WHERE taxonomy='thema' ORDER BY term_id ASC";
        $result_select_taxonomy_id = $wpdb->get_results($sql_for_taxonomy_id);

        echo $title;?>

        <?php $plugin_url = plugins_url() . '/courses-search/'; ?>
        <form method="post" style="overflow: auto;" action="<?php get_admin_url() ?>/cursus-zoeken/">
            <h2 style="font-weight:bold; margin-bottom: 10px">Zoek cursus</h2>

            Op thema <br />
            <select name="taxonomy" style="margin-bottom: 10px; width:183px; padding:4px 3px;">
            <?php
                foreach($result_select_taxonomy_id as $taxonomy_id){
                    $sql_for_taxonomy = "SELECT * FROM $table_terms WHERE term_id='$taxonomy_id->term_id' ORDER BY term_id ASC";

                    $result_select_taxonomy= $wpdb->get_results($sql_for_taxonomy);
                    foreach($result_select_taxonomy as $taxonomy){
                        echo '<option style="width: 155px;" value="'.$taxonomy->term_id.'">'.$taxonomy->name.'</option>';
                    }
                }
                foreach ($ids_for_thema as $ift){
                    $all_id[] = $ift;
                }
                echo '<option style="width: 155px;" value="all_ids" selected>Alle cursussen</option>';
            ?>
            </select><br />

            Op naam <br />
            <label>
                <input type='text' name="textsearch" value='' style='width: 170px;'/>
            </label>

            <label>
                <input id='input' type='submit' name='search' value='ZOEK' style='margin:15px auto;width:85px;display:block'/>
            </label>
        </form>
        <?php
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {				
        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {
	
    }
} // class ZoekCursusWidget

function my_shortcode_func($attr) {
    short_search_load_scripts();
    include 'short_search_list.php';?> <br /><br /><br /><br /> <?php
}
add_shortcode('course_list', 'my_shortcode_func');    



// register ZoekCursusWidget widget
add_action('widgets_init', create_function('', 'return register_widget("ZoekCursusWidget");'));
?>