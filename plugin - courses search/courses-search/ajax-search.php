<?php 
global $wpdb;

$aColumns = array('cursusnaam', 'thema', 'startdatum', 'aantal_dagen');
$sColumns = array('cursusnaam', 'thema', 'startdatum', 'aantal_dagen');
 
$sIndexColumn = "ID";

/* 
 * Paging
 */
$sLimit = "";
if (isset($_POST['iDisplayStart']) && $_POST['iDisplayLength'] != '-1') {
    $sLimit = "LIMIT " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " .
            mysql_real_escape_string($_POST['iDisplayLength']);
}

/*
 * Ordering
 */
$sOrder = "";
if (isset($_POST['iSortCol_0'])) {
    $sOrder = "ORDER BY  ";
    for ($i = 0; $i < intval($_POST['iSortingCols']); $i++) {
        if ($_POST['bSortable_' . intval($_POST['iSortCol_' . $i])] == "true") {
            $sOrder .= $aColumns[intval($_POST['iSortCol_' . $i])] . "
				 	" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
        }
    }

    $sOrder = substr_replace($sOrder, "", -2);
    if ($sOrder == "ORDER BY") {
        $sOrder = "";
    }
}

/*
 * Filtering
 * NOTE this does not match the built-in DataTables filtering which does it
 * word by word on any field. It's possible to do here, but concerned about efficiency
 * on very large tables, and MySQL's regex functionality is very limited
 */
$sWhere = "";
if (isset($_POST['sSearch']) && $_POST['sSearch'] != "") {
    $sWhere = "AND (";
//    for ($i = 0; $i < count($sColumns); $i++) {
        $sWhere .= $wpdb->prefix."posts.post_title LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' OR  ";
         $sWhere .= $wpdb->prefix."terms.name LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' OR ";
         $sWhere .= "p3.meta_value LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%'";
//          $sWhere .= $wpdb->prefix."postmeta.meta_value LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' ";
//           $sWhere .= $wpdb->prefix."posts.post_title LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%'";
//    }

    $sWhere .= ')';
}

/*
 * SQL queries
 * Get data to display
 */
$thema_name = $_GET['thema'];
$naam_name = $_GET['naam'];
$thema_name_for_all = array();
$post_id = 883;
$post = get_post($post_id);
$description = $post->post_content;
//var_dump($description);exit;

$sql_for_post_title = "SELECT * FROM ".$wpdb->prefix."posts 
                                INNER JOIN ".$wpdb->prefix."postmeta 
                                ON (".$wpdb->prefix."posts.ID = ".$wpdb->prefix."postmeta.post_ID)
                                    WHERE (".$wpdb->prefix."posts.post_title LIKE '".$naam_name.' '."%' 
                                                AND ".$wpdb->prefix."posts.post_type='cursus')
                                            OR (".$wpdb->prefix."postmeta.meta_value LIKE '".$naam_name.' '."%'
                                                AND ".$wpdb->prefix."postmeta.meta_key='_ct_textarea_4fccb0a8a6d44')
                                            OR (".$wpdb->prefix."posts.post_content LIKE '".$naam_name.' '."%')
                                                    
                                            OR (".$wpdb->prefix."posts.post_title LIKE '%".' '.$naam_name."'
                                                AND ".$wpdb->prefix."posts.post_type='cursus')
                                            OR (".$wpdb->prefix."postmeta.meta_value LIKE '%".' '.$naam_name."'
                                                AND ".$wpdb->prefix."postmeta.meta_key='_ct_textarea_4fccb0a8a6d44')
                                            OR (".$wpdb->prefix."posts.post_content LIKE '%".' '.$naam_name."')
                                                    
                                            OR (".$wpdb->prefix."posts.post_title LIKE '%".' '.$naam_name.' '."%'
                                                AND ".$wpdb->prefix."posts.post_type='cursus')
                                            OR (".$wpdb->prefix."postmeta.meta_value LIKE '%".' '.$naam_name.' '."%'
                                                AND ".$wpdb->prefix."postmeta.meta_key='_ct_textarea_4fccb0a8a6d44')
                                            OR (".$wpdb->prefix."posts.post_content LIKE '%".' '.$naam_name.' '."%')
                                            OR (".$wpdb->prefix."posts.post_content LIKE '%".$naam_name."%')
                                                    
                                            OR (".$wpdb->prefix."posts.post_title = '$naam_name'
                                                AND ".$wpdb->prefix."posts.post_type='cursus')
                                            OR (".$wpdb->prefix."postmeta.meta_value = '$naam_name'
                                                AND ".$wpdb->prefix."postmeta.meta_key='_ct_textarea_4fccb0a8a6d44')
                                            OR (".$wpdb->prefix."posts.post_content = '$naam_name')";
//parseKeyword
$result_select_post_title= $wpdb->get_results($sql_for_post_title);
//var_dump($sql_for_post_title);exit;
$y = 0;
$count_title = count($result_select_post_title);
foreach ($result_select_post_title as $rfpt){
    $rfpt = str_replace("'","",$rfpt->post_title);
    $sel_or = $wpdb->prefix."posts.post_title = '$rfpt'";
    $s_or .= $sel_or;
    if($y < $count_title-1){
        $s_or .=' OR ';
        $y++;
    }
}

if($thema_name == 'all_ids'){
    $thema_name_query = "SELECT term_id FROM ".$wpdb->prefix."term_taxonomy WHERE taxonomy='thema'";
    $thema_name_array = $wpdb->get_results($thema_name_query);
    
    foreach ($thema_name_array as $t_n){

        $thema_name_for_all[] = $t_n;
    }

    $i = 0;
    $count = count($thema_name_for_all);
    foreach ($thema_name_for_all as $thfa){
        $join_or = $wpdb->prefix."terms.term_id = '$thfa->term_id'";
        $j_or .= $join_or;
        if($i < $count-1){
            $j_or .= ' OR ';
            $i++;
        }
    }

    if(trim($naam_name) != '' && $s_or != NULL){
    $sQuery = "SELECT ".$wpdb->prefix."posts.post_name as pn,
                    ".$wpdb->prefix."posts.ID as ID,
                    ".$wpdb->prefix."posts.post_title as cursusnaam,
                    ".$wpdb->prefix."terms.name as thema,
                    ".$wpdb->prefix."postmeta.meta_value as startdatum,
                    p2.meta_value as aantal_dagen,
                    p3.meta_value as teazer
              FROM ".$wpdb->prefix."posts 
                  INNER JOIN ".$wpdb->prefix."term_relationships
                      ON (".$wpdb->prefix."term_relationships.object_id=".$wpdb->prefix."posts.ID)
                  INNER JOIN ".$wpdb->prefix."terms 
                      ON (".$wpdb->prefix."terms.term_id = ".$wpdb->prefix."term_relationships.term_taxonomy_id
                            AND ($s_or) 
                            AND  ($j_or)) 
                  INNER JOIN ".$wpdb->prefix."postmeta
                      ON (".$wpdb->prefix."postmeta.post_ID = ".$wpdb->prefix."posts.ID
                            AND ".$wpdb->prefix."postmeta.meta_key = '_ct_datepicker_4fccaf4084e17')
                  INNER JOIN ".$wpdb->prefix."postmeta p2 
                      ON (p2.post_ID = ".$wpdb->prefix."posts.ID
                            AND p2.meta_key = '_ct_text_4fccaea20e329')
                  INNER JOIN ".$wpdb->prefix."postmeta p3 
                      ON (p3.post_ID = ".$wpdb->prefix."posts.ID
                           AND p3.meta_key = '_ct_textarea_4fccb0a8a6d44' $sWhere)
                $sOrder
                $sLimit";
    }
    $rResult = $wpdb->get_results($sQuery);
//var_dump($rResult);exit;
    /* Data set length after filtering */
    $sQuery = "
                    SELECT FOUND_ROWS() AS ct
            ";

    $rResultFilterTotal = $wpdb->get_row($sQuery);
    $iFilteredTotal = $rResultFilterTotal->ct;

/* Total data set length */
    if(trim($naam_name) != '' && $s_or != NULL){
    $sQuery = "SELECT COUNT(" . $sIndexColumn . ") AS ct
               FROM   ".$wpdb->prefix."posts 
                       INNER JOIN ".$wpdb->prefix."term_relationships
                      ON (".$wpdb->prefix."term_relationships.object_id=".$wpdb->prefix."posts.ID
                          AND ".$wpdb->prefix."posts.post_type='cursus'
                           AND ".$wpdb->prefix."posts.post_status='publish')
                  INNER JOIN ".$wpdb->prefix."terms 
                      ON (".$wpdb->prefix."terms.term_id = ".$wpdb->prefix."term_relationships.term_taxonomy_id
                            AND ($s_or) 
                            AND  ($j_or) ) 
                 INNER JOIN ".$wpdb->prefix."postmeta p3 
                     ON (p3.post_ID = ".$wpdb->prefix."posts.ID
                            AND p3.meta_key = '_ct_textarea_4fccb0a8a6d44' $sWhere)
            ";
}}
else{
    if(trim($naam_name) != '' && $s_or != NULL){
    $sQuery = "SELECT ".$wpdb->prefix."posts.post_name as pn,
                        ".$wpdb->prefix."posts.ID as ID,
                        ".$wpdb->prefix."posts.post_title as cursusnaam,
                        ".$wpdb->prefix."terms.name as thema,
                        ".$wpdb->prefix."postmeta.meta_value as startdatum,
                        p2.meta_value as aantal_dagen,
                        p3.meta_value as teazer
               FROM ".$wpdb->prefix."posts
                      INNER JOIN ".$wpdb->prefix."term_relationships
                          ON (".$wpdb->prefix."term_relationships.object_id=".$wpdb->prefix."posts.ID)
                      INNER JOIN ".$wpdb->prefix."terms
                          ON (".$wpdb->prefix."terms.term_id = ".$wpdb->prefix."term_relationships.term_taxonomy_id
                                AND ($s_or)
                                AND  ".$wpdb->prefix."terms.term_id = '$thema_name')
                      INNER JOIN ".$wpdb->prefix."postmeta
                          ON (".$wpdb->prefix."postmeta.post_ID = ".$wpdb->prefix."posts.ID
                                AND ".$wpdb->prefix."postmeta.meta_key = '_ct_datepicker_4fccaf4084e17')
                      INNER JOIN ".$wpdb->prefix."postmeta p2
                          ON (p2.post_ID = ".$wpdb->prefix."posts.ID
                                AND p2.meta_key = '_ct_text_4fccaea20e329')
                      INNER JOIN ".$wpdb->prefix."postmeta p3 
                          ON (p3.post_ID = ".$wpdb->prefix."posts.ID
                               AND p3.meta_key = '_ct_textarea_4fccb0a8a6d44' $sWhere)
                  $sOrder
                  $sLimit";
    }
    $rResult = $wpdb->get_results($sQuery);

    
    /* Data set length after filtering */
    $sQuery = "
                    SELECT FOUND_ROWS() AS ct
            ";

    $rResultFilterTotal = $wpdb->get_row($sQuery);
    $iFilteredTotal = $rResultFilterTotal->ct;

    /* Total data set length */
    if(trim($naam_name) != '' && $s_or != NULL){
    $sQuery = "SELECT COUNT(" . $sIndexColumn . ") AS ct
                    FROM   ".$wpdb->prefix."posts 
                        INNER JOIN ".$wpdb->prefix."term_relationships
                      ON (".$wpdb->prefix."term_relationships.object_id=".$wpdb->prefix."posts.ID
                          AND ".$wpdb->prefix."posts.post_type='cursus'
                           AND ".$wpdb->prefix."posts.post_status='publish') 
                  INNER JOIN ".$wpdb->prefix."terms 
                      ON (".$wpdb->prefix."terms.term_id = ".$wpdb->prefix."term_relationships.term_taxonomy_id
                            AND ($s_or)
                            AND  ".$wpdb->prefix."terms.term_id = '$thema_name')
                INNER JOIN ".$wpdb->prefix."postmeta p3 
                      ON (p3.post_ID = ".$wpdb->prefix."posts.ID
                           AND p3.meta_key = '_ct_textarea_4fccb0a8a6d44' $sWhere)
            ";

}}


$rResultTotal = $wpdb->get_row($sQuery);
$iTotal = $rResultTotal->ct;


/*
 * Output
 */
$output = array(
    "sEcho" => intval($_POST['sEcho']),
    "iTotalRecords" => $iTotal,
    "iTotalDisplayRecords" => $iTotal,
    "aaData" => array()
);


foreach($rResult as $aRow){
    $aRow = (array)$aRow;
    $row = array();

    // Add the row ID and class to the object
    $row['DT_RowId'] = $aRow['ID']."TT".$aRow['pn'];
    for ($i = 0; $i < count($aColumns); $i++) {
       if($aColumns[$i] == 'startdatum' || $aColumns[$i] == 'aantal_dagen'){
            $row[] = substr($aRow[$aColumns[$i]],0,70)."...";
          
        }else
        if ($aColumns[$i] != ' ') {
            /* General output */
            $row[] = $aRow[$aColumns[$i]];
        }
    }
    $output['aaData'][] = $row;
    
}

function parseKeyword($keyword) {
    preg_match_all('/".*?("|$)|((?<=[\\s",+])|^)[^\\s",+]+/', $keyword, $matches);
    $search_items = array_map(create_function('$a', 'return trim($a, "\\"\'\\n\\r ");'), $matches[0]);
    return $search_items;
}

echo json_encode($output); 
die();

?>
