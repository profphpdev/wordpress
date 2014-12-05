jQuery(document).ready(function() {

    var table = jQuery('#example').dataTable({
        "sPaginationType": "full_numbers",
        "bProcessing": true,
        "bServerSide": true,
        "iDisplayLength": 10,
        "aaSorting":[[2, "asc"]],
        "sAjaxSource":ajax_object.ajaxurl+'?thema='+jQuery('.value1').val(),
        "sServerMethod": "POST",
        "fnServerParams": function ( aoData ) {
            aoData.push( { name: "action", value: "getShortJournalList" } );
        },

        "bDestroy": true
    });
    
    var request;
    jQuery("#example tbody tr>td:first-child").live({
        mouseenter:
            function (ev) {
                if(jQuery("#example tbody tr>td:first-child").html() != 'Geen cursus voor gevonden.'){
                 var obj_id = jQuery(this).parent().attr('id');
                 var tmpName = obj_id.split('TT');
                 var id = tmpName[0];
                 var pn = tmpName[1];
                jQuery(this).addClass("ui-state-hover"); 
                jQuery('#popup_hover').addClass("ppdiv").html('');
                jQuery('#popup_hover').show(); 
                jQuery('#popup_hover').css({'left':800 ,'top':ev.pageY});
                request = jQuery.ajax({
                  type: "POST",
                  url: ajax_object.ajaxurl2,
                  data: { name: id, action: 'updateShortJournalItem', type: 'hover'},
                  success:function(data)
                  {
                      jQuery('#popup_hover').removeClass("ppdiv");
                      jQuery('#popup_hover').html(data);
                  }
                });
                }
            },
        mouseleave:
            function (ev) {
                jQuery(this).removeClass("ui-state-hover");
                jQuery('#popup_hover').hide();
                request.abort();
            }
            
    });

//    jQuery('#example tbody tr>td:first-child').live('click', function (e) {
//        if(jQuery("#example tbody tr>td:first-child").html() != 'Geen cursus voor gevonden.'){
//            var obj_id = jQuery(this).parent().attr('id');
//            jQuery('#popup_bg').show();
//            jQuery('#popup').show(); 
//            jQuery('#popup').css({'left':e.pageX,'top':e.pageY});
//            jQuery.ajax({
//              type: "POST",
//              url: ajax_object.ajaxurl2,
//              data: { name: obj_id, action: 'updateShortJournalItem', type: 'click'},
//              success:function(data)
//              {
//                  jQuery('#popup').html(data);
//              }
//            });
//        } 
//    } ); 
// 
//    jQuery('#popup_bg').click(function(){
//        jQuery('#popup_bg').hide(); 
//        jQuery('#popup').hide();
//    });
                jQuery('#example tbody tr>td:first-child').live('click', function () {
                    if(jQuery("#example tbody tr>td:first-child").html() != 'Geen cursus voor gevonden.'){
                        var obj_id = jQuery(this).parent().attr('id');  
                        var tmpName = obj_id.split('TT');
                        var id = tmpName[0];
                        var pn = tmpName[1];
                        document.location.href=pn;
                    } 
                });
} );