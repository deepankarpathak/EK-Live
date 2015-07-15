jQuery(document).ready(function(){    
    jQuery('#img_select').ddslick({
        width: 180,
        onSelected: function(data){
             var ddData = jQuery('#img_select').data('ddslick');
             jQuery("#img_select_value").val(ddData.selectedData.value);
        }
    });    
    jQuery('#textcolor_selector').ColorPicker({
        color: cur_textcolor,
        onShow: function (colpkr) {
                jQuery(colpkr).fadeIn(500);
                return false;
        },
        onHide: function (colpkr) {
                jQuery(colpkr).fadeOut(500);
                return false;
        },
        onChange: function (hsb, hex, rgb) {
                jQuery('#textcolor_selector div').css('backgroundColor', '#' + hex);
                jQuery('#textcolor_select_value').val('#'+hex);
        }
    });
    jQuery('#titlecolor_selector').ColorPicker({
        color: cur_titlecolor,
        onShow: function (colpkr) {
                jQuery(colpkr).fadeIn(500);
                return false;
        },
        onHide: function (colpkr) {
                jQuery(colpkr).fadeOut(500);
                return false;
        },
        onChange: function (hsb, hex, rgb) {
                jQuery('#titlecolor_selector div').css('backgroundColor', '#' + hex);
                jQuery('#titlecolor_select_value').val('#'+hex);
        }
    });
    jQuery('#color_selector').ColorPicker({
        color: cur_color,
        onShow: function (colpkr) {
                jQuery(colpkr).fadeIn(500);
                return false;
        },
        onHide: function (colpkr) {
                jQuery(colpkr).fadeOut(500);
                return false;
        },
        onChange: function (hsb, hex, rgb) {
                jQuery('#color_selector div').css('backgroundColor', '#' + hex);
                jQuery('#color_select_value').val('#'+hex);                            
        }
    });
    jQuery('#color_select').ddslick({
        width: 180,
        onSelected: function(data){
             var ddData = jQuery('#color_select').data('ddslick');
             if(ddData.selectedData.description=='Other'){
                 jQuery("#color_selector").show();                 
             }else{
                jQuery("#color_selector").hide();
                jQuery("#color_select_value").val(ddData.selectedData.value);             
             }
        }
    });
    jQuery('#textcolor_select').ddslick({
        width: 180,
        onSelected: function(data){
             var ddData = jQuery('#textcolor_select').data('ddslick');
             if(ddData.selectedData.description=='Other'){                 
                 jQuery("#textcolor_selector").show();                 
             }else{
                jQuery("#textcolor_selector").hide();
                jQuery("#textcolor_select_value").val(ddData.selectedData.value);             
             }
        }
    });
    jQuery('#titlecolor_select').ddslick({
        width: 180,
        onSelected: function(data){
             var ddData = jQuery('#titlecolor_select').data('ddslick');
             if(ddData.selectedData.description=='Other'){                 
                 jQuery("#titlecolor_selector").show();                 
             }else{
                jQuery("#titlecolor_selector").hide();
                jQuery("#titlecolor_select_value").val(ddData.selectedData.value);             
             }
        }
    });
    jQuery('#pattern_select').ddslick({
        width: 180,
        onSelected: function(data){
             var ddData = jQuery('#pattern_select').data('ddslick');             
             jQuery("#pattern_select_value").val(ddData.selectedData.value);
        }
    });
    if(jQuery('input[type=text]:visible,input[type=password]:visible,select:visible,textarea:visible').size()>0){
        jQuery('input[type=text]:visible,input[type=password]:visible,select:visible,textarea:visible').first().focus();
    }
});
validate_forms=function(classn){
    var msg=thefollowing+'&nbsp;';
    var el=new Array();    
    jQuery(classn).each(function(){
        if(jQuery(this).val()==''){
            msg+='<strong> '+thefield+' '+jQuery(this).attr('title')+' '+isrequired+'.</strong>&nbsp;';
            el.push(jQuery(this));
        }
    });    
    if(el.length>0){
        jQuery('#message_custom001').html('<p>'+msg+'</p>');
        jQuery('#message_custom001').fadeIn();
        el[0].focus();
        setTimeout(function(){
            jQuery('#message_custom001').fadeOut();
        }, 3000);
        return false;
    }
    return true;
}
beun=function(){
    return "A Transaction is in Progress.";
}
saveoptions=function(form){    
    jQuery('.loading-spinner').show();    
    if(validate_forms(form+' .required')){        
        jQuery(window).unbind('beforeunload');
        jQuery(window).bind('beforeunload',beun);
        jQuery.ajax(wp_path+'/wp-admin/admin-ajax.php',{
            type: 'post',             
            dataType: 'json',
            data: jQuery(form).serialize()+'&action=saveplg',
            success: function(result){                
                jQuery(window).unbind('beforeunload');
                jQuery('.loading-spinner').hide();
                jQuery('#message_custom001').html('<p>'+result.message+'</p>');
                jQuery('#message_custom001').fadeIn();
                if(jQuery('input[type=text]:visible,input[type=password]:visible,select:visible,textarea:visible').size()>0){
                    jQuery('input[type=text]:visible,input[type=password]:visible,select:visible,textarea:visible').first().focus();
                }
                setTimeout(function(){
                    jQuery('#message_custom001').fadeOut();
                }, 3000);
            }
        });
    }else{
        jQuery(window).unbind('beforeunload');
        jQuery('.loading-spinner').hide();
    }    
    return false;
}
select_img=function(obj){    
    jQuery("#img_select_value").val(jQuery(obj).attr('custom-value'));
}
select_color=function(obj){    
    jQuery("#color_select_value").val(jQuery(obj).attr('custom-value'));
}
change_font=function(obj,id){
    jQuery(id).removeClass();    
    var font_sel=jQuery("#"+jQuery(obj).attr('id')+" option:selected").attr("custom-value");
    jQuery(id).addClass(font_sel);
}