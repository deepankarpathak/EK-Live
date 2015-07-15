<?php

/*
    Plugin Name: Customize your 404 Error Page for Wordpress
    Plugin URI: http://wpthemegenerator.com
    Description: Create and Customize your Own 404 Error Page in your wordpress installation
    Version: 1.2
    Author: WP Theme Generator
    Author URI: http://wpthemegenerator.com
*/
if ( is_admin() ) {
    register_uninstall_hook(plugin_dir_path(__FILE__).'/custom404.php', 'wptg_er_de_register_settings');
    register_activation_hook(plugin_dir_path(__FILE__).'/custom404.php', 'wptg_er_pre_register_settings');
}


/**
* Define constants
*/
define( CUSTOM_404_PLUGIN_ROOT, dirname( __FILE__ ).  '/' );

/**
* Loads a view
* @param { string } file to retrieve
* @param { arr } variables to pass to the view
* @return { null }
*/
function c_404_load_view( $filename, $data = null  )  {

    if ( is_array( $data ) ) {
        extract( $data );
    }

    require_once( CUSTOM_404_PLUGIN_ROOT . $filename );

}

function wptg_er_pre_register_settings() {
        $opt=get_option('custom404_content_text');
        if(empty($opt)){
            update_option('custom404_content_title', "Enter a text here!");
            update_option('custom404_content_text', "Enter a text here!");
            update_option( 'custom404_link_text_1','' );
            update_option( 'custom404_link_href_1','' );
            update_option( 'custom404_link_text_2','' );
            update_option( 'custom404_link_href_2','' );
            update_option( 'custom404_link_text_3','' );
            update_option( 'custom404_link_href_3','' );
            update_option( 'custom404_link_text_4','' );
            update_option( 'custom404_link_href_4','' );
            update_option( 'custom404_link_text_5','' );
            update_option( 'custom404_link_href_5','' );
            update_option('custom404_selected_img', plugins_url("custom_img/01.png",__FILE__));
            update_option('custom404_selected_pattern', plugins_url("patterns/pattern_001.png",__FILE__));
            update_option('custom404_selected_color', "#c5423c");
            update_option('custom404_selected_textcolor', "#ffffff");
            update_option('custom404_selected_titlecolor', "#ffffff");
            update_option('custom404_text_font', "Alfa Slab One");
            update_option('custom404_text_font_size', "10");
            update_option('custom404_title_font', "Alfa Slab One");
            update_option('custom404_title_font_size', "20");
            update_option('custom404_catid_view', 0);
        }
}

function wptg_er_de_register_settings() {
    delete_option( 'custom404_content_title' );
    delete_option( 'custom404_content_text' );
    delete_option( 'custom404_link_text_1' );
    delete_option( 'custom404_link_href_1' );
    delete_option( 'custom404_link_text_2' );
    delete_option( 'custom404_link_href_2' );
    delete_option( 'custom404_link_text_3' );
    delete_option( 'custom404_link_href_3' );
    delete_option( 'custom404_link_text_4' );
    delete_option( 'custom404_link_href_4' );
    delete_option( 'custom404_link_text_5' );
    delete_option( 'custom404_link_href_5' );
    delete_option( 'custom404_selected_color' );
    delete_option( 'custom404_selected_textcolor' );
    delete_option( 'custom404_selected_titlecolor' );
    delete_option( 'custom404_title_font' );
    delete_option( 'custom404_text_font' );
    delete_option( 'custom404_title_font_size' );
    delete_option( 'custom404_text_font_size' );
    delete_option( 'custom404_selected_img' );
    delete_option( 'custom404_selected_pattern' );
    delete_option( 'custom404_catid_view' );
}
add_action('admin_menu', 'create_404_menu');
function create_404_menu() {
    load_plugin_textdomain('custom404int', false, basename( dirname( __FILE__ ) ) . '/languages' );
    add_menu_page(__('Customize your 404 Error Page - Plugin Options','custom404int'), 'Custom 404 Opt.', 'administrator', __FILE__, 'custom404_settings_page',plugins_url('/img/tg.png', __FILE__));
}
function register_mysettings() {
	    // Register our settings
	    register_setting( 'wptg_custom404-settings-group', 'custom404_selected_color' );
        register_setting( 'wptg_custom404-settings-group', 'custom404_selected_img' );
        register_setting( 'wptg_custom404-settings-group', 'custom404_selected_textcolor' );
        register_setting( 'wptg_custom404-settings-group', 'custom404_selected_titlecolor' );
        register_setting( 'wptg_custom404-settings-group', 'custom404_selected_pattern' );
	    register_setting( 'wptg_custom404-settings-group', 'custom404_content_text' );
        register_setting( 'wptg_custom404-settings-group', 'custom404_content_text' );
        register_setting( 'wptg_custom404-settings-group', 'custom404_link_text_1' );
        register_setting( 'wptg_custom404-settings-group', 'custom404_link_href_1' );
        register_setting( 'wptg_custom404-settings-group', 'custom404_link_text_2' );
        register_setting( 'wptg_custom404-settings-group', 'custom404_link_href_2' );
        register_setting( 'wptg_custom404-settings-group', 'custom404_link_text_3' );
        register_setting( 'wptg_custom404-settings-group', 'custom404_link_href_3' );
        register_setting( 'wptg_custom404-settings-group', 'custom404_link_text_4' );
        register_setting( 'wptg_custom404-settings-group', 'custom404_link_href_4' );
        register_setting( 'wptg_custom404-settings-group', 'custom404_link_text_5' );
        register_setting( 'wptg_custom404-settings-group', 'custom404_link_href_5' );
        register_setting( 'wptg_custom404-settings-group', 'custom404_title_font' );
        register_setting( 'wptg_custom404-settings-group', 'custom404_text_font' );
        register_setting( 'wptg_custom404-settings-group', 'custom404_title_font_size' );
        register_setting( 'wptg_custom404-settings-group', 'custom404_text_font_size' );
        register_setting( 'wptg_custom404-settings-group', 'custom404_catid_view' );
}
function custom404_settings_page() {
    $color_sel=get_option('custom404_selected_color');
?>
<?php
    //font types for titles and content texts
    $fonts=array(
        'Yanone Kaffeesatz'=>'YanoneKaffeesatz'
        ,'Wire One'=>'WireOne'
        ,'Ubuntu'=>'Ubuntu'
        ,'Rokkitt'=>'Rokkitt'
        ,'Righteous'=>'Righteous'
        ,'Raleway'=>'Raleway'
        ,'Quattrocento Sans'=>'QuattrocentoSans'
        ,'PT Sans'=>'PTSans'
        ,'Open Sans'=>'OpenSans'
        ,'Nixie One'=>'NixieOne'
        ,'News Cycle'=>'NewsCycle'
        ,'Acme'=>'Acme'
        ,'Coustard'=>'Coustard'
        ,'Alfa Slab One'=>'AlfaSlabOne'
        ,'Abel'=>'Abel'
        ,'Brawler'=>'Brawler'
        ,'Droid Sans'=>'DroidSans'
        ,'Crushed'=>'Crushed'
        ,'Cabin Condensed'=>'CabinCondensed'
        ,'Federo'=>'Federo'
        ,'Arimo'=>'Arimo'
        ,'Contrail One'=>'ContrailOne'
        ,'Anton'=>'Anton'
        ,'Days One'=>'DaysOne'
        ,'Droid Serif'=>'DroidSerif'
        ,'Abril Fatface'=>'AbrilFatface'
        ,'Allan'=>'Allan'
        ,'Amatic SC'=>'AmaticSC'
        ,'Anonymous Pro'=>'AnonymousPro'
        ,'Bangers'=>'Bangers'
        ,'Baumans'=>'Baumans'
        ,'Boogaloo'=>'Boogaloo'
        ,'Bree Serif'=>'BreeSerif'
        ,'Buda'=>'Buda'
        ,'Cuprum'=>'Cuprum'
        ,'Damion'=>'Damion'
        ,'Dorsa'=>'Dorsa'
        ,'Francois One'=>'FrancoisOne'
        ,'Gruppo'=>'Gruppo'
        ,'Just Another Hand'=>'JustAnotherHand'
        ,'Jockey One'=>'JockeyOne'
        ,'Maiden Orange'=>'MaidenOrange'
        ,'Lobster'=>'Lobster'
        ,'Josefin Slab'=>'JosefinSlab'
        ,'Lobster Two'=>'LobsterTwo'
        ,'Marvel'=>'Marvel'
        ,'Andika'=>'Andika'
        ,'Tahoma'=>'Tahoma'
        ,'Trebuchet MS'=>'TrebuchetMS'
        ,'Georgia'=>'Georgia'
    );
    asort($fonts);
    load_plugin_textdomain('custom404int', false, basename( dirname( __FILE__ ) ) . '/languages' );
?>
<?php foreach($fonts as $name=>$class): ?>
<link href='http://fonts.googleapis.com/css?family=<?php echo urlencode($name); ?>' rel='stylesheet' type='text/css' />
<?php endforeach; ?>
<link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__)."css/colorpicker.css" ?>" />
<?php
    // Get the template with plugin styles
    c_404_load_view( 'css/custom-404-style.php' );
?>
<script type="text/javascript">
    var wp_path="<?php echo get_bloginfo('wpurl'); ?>";
    var cur_color="<?php echo get_option('custom404_selected_color'); ?>";
    var cur_textcolor="<?php echo get_option('custom404_selected_textcolor'); ?>";
    var cur_titlecolor="<?php echo get_option('custom404_selected_titlecolor'); ?>";
    var thefield="<?php echo __("The field",'custom404int') ?>";
    var isrequired="<?php echo __("is required and must be Filled",'custom404int') ?>";
    var thefollowing="<?php echo __("The following errors have occurred:",'custom404int') ?>";
</script>
<script type="text/javascript" src="<?php echo plugin_dir_url(__FILE__)."js/jquery.ddslick.min.js" ?>"></script>
<script type="text/javascript" src="<?php echo plugin_dir_url(__FILE__)."js/colorpicker.js" ?>"></script>
<script type="text/javascript" src="<?php echo plugin_dir_url(__FILE__)."js/custom404.js" ?>"></script>
<form method="post" id="frmcustom" name="frmcustom" onsubmit="saveoptions('#frmcustom');return false;" action="options.php">
    <div class="wrap">
        <h2><img src="<?php echo plugin_dir_url(__FILE__)."img/icon.png"; ?>" />&nbsp;<?php echo __('Customize your 404 Error Page - Plugin Options','custom404int'); ?></h2>
        <div style="height: 20px">
            <div class="updated_custom" id="message_custom001" style="display: none;">&nbsp;</div>
        </div>
        <br />
        <?php do_settings_fields('wptg_custom404-settings-group','custom404_settings_page'); ?>
        <div id="dashboard-widgets-wrap">
            <div id="dashboard-widgets" class="metabox-holder">
                <div id="postbox-container-1" class="postbox-container" style="width:50%;">
                    <div id="normal-sortables" class="meta-box-sortables ui-sortable">
                        <div class="postbox ">
                            <h3 style="cursor: default"><span>Powered by: <a href="http://www.wpthemegenerator.com" target="blank">WordPress Theme Generator</a></span></h3>
                            <div class="inside">
                                <div>
                                    <p>
                                        <?php echo __('One theme, a thousand posibilities: Create amazing and unlimited themes by playing with 1000+ pre-designed elements (or uploading your own designs) and then download in fully working WP or HTML/CSS.','custom404int'); ?>
                                    </p>
                                    <h4>
                                        <a href="">100 Free Sample Themes</a> &nbsp;&nbsp;&nbsp; <a href="">Check 1 Minute Video</a>
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <div class="postbox ">
                            <h3 style="cursor: default"><span><?php echo __('Background decoration Settings:','custom404int');?></span></h3>
                            <div class="inside">
                                <table>
                                    <tr valign="top">
                                        <td><?php echo __('Background Color:','custom404int');?></td>
                                        <td  style="width: 190px">
                                            <select id="color_select" name="color_select">
                                            <?php
                                                $colors=array(__("Red",'custom404int')=>"#c5423c",__("Blue",'custom404int')=>"#009ed4",__("Black",'custom404int')=>"#3a3a3a",__("Grayed Blue",'custom404int')=>"#74a7b0",__("Green",'custom404int')=>"#6d9e7a",__("Gray",'custom404int')=>"#b3c1c2",
                                                            __("Purple",'custom404int')=>"#5f5c82",__("Crimson",'custom404int')=>"#a64c58");
                                                $name1="";
                                                $s=false;
                                                foreach ($colors as $name=>$value) {
                                                    $opt_img=get_option("custom404_selected_color");
                                                    $selec=$value==$opt_img?"selected":"";
                                                    $name1=$selec=='selected'?$name:'';
                                                    if($name1!=''){
                                                        $s=true;
                                                    }
                                                    echo "<option value='$value' data-imagecolor='$value' $selec data-description='$name'>$name</option>";
                                                }
                                                $selec=$s==false?"selected":"";
                                                echo "<option value='#000' data-imagesrc='".plugin_dir_url(__FILE__)."img/select.png' data-imagecolor='#000' $selec data-description='".__("Other",'custom404int')."'>".__("Other",'custom404int')."</option>";
                                            ?>
                                            </select>
                                        </td>
                                        <td>
                                            <?php $n=trim($name1)=="Other"?"style='display: none;'":"";?>
                                            <div id="color_selector" class="colorSelector" <?php echo $n; ?> ><div style="background-color: <?php echo get_option('custom404_selected_color'); ?>">&nbsp;&nbsp;&nbsp;</div></div>
                                            <input type="hidden" name="color_select_value" id="color_select_value" value="<?php echo get_option('custom404_selected_color') ?>" />
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <td><?php echo __('Background Pattern:','custom404int');?></td>
                                        <td colspan="2">
                                            <select id="pattern_select">
                                            <?php
                                                $dir=  plugin_dir_path(__FILE__)."patterns/";
                                                $fold_content=array();
                                                if (@$handle = opendir($dir)) {
                                                    $count_img=0;
                                                    while (false !== ($file = readdir($handle))) {
                                                        $exten=  explode(".", $file);
                                                        if($exten[count($exten)-1]=='png'||$exten[count($exten)-1]=='gif'||$exten[count($exten)-1]=='jpg'||$exten[count($exten)-1]=='bmp'||$exten[count($exten)-1]=='emf'||$exten[count($exten)-1]=='jpeg'){
                                                            $fold_content[]=$file;
                                                            //echo "<label><input type='radio' onclick='select_img(this)' custom-value='$file' name='img_select' $selec id='img_select'><img class='image_box' title='$file' width='95' height='62' src='$file' />&nbsp;</label>";
                                                            /*if($count_img==9){
                                                                $count_img=0;
                                                                echo "<br />";
                                                            }else{
                                                                $count_img++;
                                                            }*/
                                                        }
                                                    }
                                                    closedir($handle);
                                                    sort($fold_content);
                                                    foreach($fold_content as $index=>$file){
                                                       $file2=$file;
                                                       $file= plugins_url("patterns/".$file,__FILE__);
                                                       $file3= plugins_url("patterns_thumb/".$file2,__FILE__);
                                                       $opt_img=get_option("custom404_selected_pattern");
                                                       $selec=$file==$opt_img?"selected":"";
                                                       echo "<option value='$file' $selec data-imagesrc='$file3' data-description='$file2'>$file2</option>";
                                                    }
                                                }
                                            ?>
                                            </select>
                                            <input type="hidden" name="pattern_select_value" id="pattern_select_value" value="<?php echo get_option('custom404_selected_pattern') ?>" />
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <td><?php echo __('Custom bottom image:','custom404int');?></td>
                                        <td colspan="2">
                                            <select id="img_select">
                                            <?php
                                                $dir=  plugin_dir_path(__FILE__)."custom_img/";
                                                if (@$handle = opendir($dir)) {
                                                    $count_img=0;
                                                    $fold_content=array();
                                                    while (false !== ($file = readdir($handle))) {
                                                        $exten=  explode(".", $file);
                                                        if($exten[count($exten)-1]=='png'||$exten[count($exten)-1]=='gif'||$exten[count($exten)-1]=='jpg'||$exten[count($exten)-1]=='bmp'||$exten[count($exten)-1]=='emf'||$exten[count($exten)-1]=='jpeg'){
                                                            $fold_content[]=$file;
                                                            //echo "<label><input type='radio' onclick='select_img(this)' custom-value='$file' name='img_select' $selec id='img_select'><img class='image_box' title='$file' width='95' height='62' src='$file' />&nbsp;</label>";
                                                            /*if($count_img==9){
                                                                $count_img=0;
                                                                echo "<br />";
                                                            }else{
                                                                $count_img++;
                                                            }*/
                                                        }
                                                    }
                                                    closedir($handle);
                                                    sort($fold_content);
                                                    foreach ($fold_content as $index => $file) {
                                                        $file2=$file;
                                                        $file= plugins_url("custom_img/".$file,__FILE__);
                                                        $opt_img=get_option("custom404_selected_img");
                                                        $selec=$file==$opt_img?"selected":"";
                                                        echo "<option value='$file' $selec data-imagesrc='$file' data-description='$file2'>$file2</option>";
                                                    }
                                                }
                                            ?>
                                            </select>
                                            <input type="hidden" name="img_select_value" id="img_select_value" value="<?php echo get_option('custom404_selected_img') ?>" />
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="postbox ">
                            <h3 style="cursor: default"><span><?php echo __('Useful Links Settings:','custom404int');?></span></h3>
                            <div class="inside">
                                <table>
                                    <tr valign="top">
                                        <td><?php echo __("Text for the link 1 (optional):",'custom404int');?></td>
                                        <td colspan="2"><input type="text" name="link_text_1" title="<?php echo __("Text for the link 1 (optional):",'custom404int');?>" maxlength="100" size="40" id="link_text_1" value="<?php echo get_option('custom404_link_text_1'); ?>" /></td>
                                    </tr>
                                    <tr valign="top">
                                        <td><?php echo __("Web page for the link 1 (optional):",'custom404int');?></td>
                                        <td colspan="2"><input type="text" name="link_href_1" title="<?php echo __("Web page for the link 1 (optional):",'custom404int');?>" maxlength="100" size="40" id="link_href_1" value="<?php echo get_option('custom404_link_href_1'); ?>" /></td>
                                    </tr>
                                    <tr valign="top">
                                        <td><?php echo __("Text for the link 2 (optional):",'custom404int');?></td>
                                        <td colspan="2"><input type="text" name="link_text_2" title="<?php echo __("Text for the link 2 (optional):",'custom404int');?>" maxlength="100" size="40" id="link_text_2" value="<?php echo get_option('custom404_link_text_2'); ?>" /></td>
                                    </tr>
                                    <tr valign="top">
                                        <td><?php echo __("Web page for the link 2 (optional):",'custom404int');?></td>
                                        <td colspan="2"><input type="text" name="link_href_2" title="<?php echo __("Web page for the link 2 (optional):",'custom404int');?>" maxlength="100" size="40" id="link_href_2" value="<?php echo get_option('custom404_link_href_2'); ?>" /></td>
                                    </tr>
                                    <tr valign="top">
                                        <td><?php echo __("Text for the link 3 (optional):",'custom404int');?></td>
                                        <td colspan="2"><input type="text" name="link_text_3" title="<?php echo __("Text for the link 3 (optional):",'custom404int');?>" maxlength="100" size="40" id="link_text_3" value="<?php echo get_option('custom404_link_text_3'); ?>" /></td>
                                    </tr>
                                    <tr valign="top">
                                        <td><?php echo __("Web page for the link 3 (optional):",'custom404int');?></td>
                                        <td colspan="2"><input type="text" name="link_href_3" title="<?php echo __("Web page for the link 3 (optional):",'custom404int');?>" maxlength="100" size="40" id="link_href_3" value="<?php echo get_option('custom404_link_href_3'); ?>" /></td>
                                    </tr>
                                    <tr valign="top">
                                        <td><?php echo __("Text for the link 4 (optional):",'custom404int');?></td>
                                        <td colspan="2"><input type="text" name="link_text_4" title="<?php echo __("Text for the link 4 (optional):",'custom404int');?>" maxlength="100" size="40" id="link_text_4" value="<?php echo get_option('custom404_link_text_4'); ?>" /></td>
                                    </tr>
                                    <tr valign="top">
                                        <td><?php echo __("Web page for the link 4 (optional):",'custom404int')?></td>
                                        <td colspan="2"><input type="text" name="link_href_4" title="<?php echo __("Web page for the link 4 (optional):",'custom404int')?>" maxlength="100" size="40" id="link_href_4" value="<?php echo get_option('custom404_link_href_4'); ?>" /></td>
                                    </tr>
                                    <tr valign="top">
                                        <td><?php echo __("Text for the link 5 (optional):",'custom404int')?></td>
                                        <td colspan="2"><input type="text" name="link_text_5" title="<?php echo __("Text for the link 5 (optional):",'custom404int')?>" maxlength="100" size="40" id="link_text_5" value="<?php echo get_option('custom404_link_text_5'); ?>" /></td>
                                    </tr>
                                    <tr valign="top">
                                        <td><?php echo __("Web page for the link 5 (optional):",'custom404int');?></td>
                                        <td colspan="2"><input type="text" name="link_href_5" title="<?php echo __("Web page for the link 5 (optional):",'custom404int');?>" maxlength="100" size="40" id="link_href_5" value="<?php echo get_option('custom404_link_href_5'); ?>" /></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <p>
                            <input type="submit" class="button-primary" value="<?php echo __("Save Changes",'custom404int');?>" />
                        </p>
                    </div>
                </div>
                <div id="postbox-container-2" class="postbox-container" style="width:50%;">
                    <div id="normal-sortables" class="meta-box-sortables ui-sortable">
                        <div class="postbox ">
                            <h3 style="cursor: default"><span><?php echo __("Text and Font Settings:",'custom404int');?></span></h3>
                            <div class="inside">
                                <table>
                                    <tr valign="top">
                                        <td width='250px'><?php echo __("Title Font Type:",'custom404int');?></td>
                                        <td colspan="2">
                                            <select onchange="change_font(this,'#title_font_sel')" name="title_font" id="title_font">
                                                <?php $opt1=get_option('custom404_title_font'); ?>
                                                <?php $font_class=empty($opt1)?'Abel':$fonts[get_option('custom404_title_font')]; ?>
                                                <?php foreach ($fonts as $name=>$text): ?>
                                                <?php $selected=""; ?>
                                                <?php if($name==get_option('custom404_title_font')){$selected="selected";}?>
                                                <option <?php echo $selected; ?> custom-value="<?php echo $text ?>" value="<?php echo $name; ?>"><?php echo $name; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <span class="<?php echo $font_class; ?>" id="title_font_sel"><?php echo __("Font Preview",'custom404int')?></span>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <td width='250px'><?php echo __("Title Font Size (in pixels):",'custom404int')?></td>
                                        <td colspan="2">
                                            <select name="title_font_size" id="title_font_size">
                                                <?php for ($i=20;$i<=40;$i++): ?>
                                                    <?php $selected=$i==get_option("custom404_title_font_size")?"selected":""; ?>
                                                    <option <?php echo $selected; ?> custom-value="<?php echo $name ?>" value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                <?php endfor; ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <td><?php echo __("Title color:",'custom404int');?></td>
                                        <td  style="width: 190px">
                                            <select id="titlecolor_select" name="titlecolor_select">
                                            <?php
                                                $opt_img=get_option("custom404_selected_titlecolor");
                                                $colors=array(__("Default",'custom404int')=>"#ffffff");
                                                $s=false;
                                                $name1="";
                                                foreach ($colors as $name=>$value) {
                                                    $selec=$value==$opt_img?"selected":"";
                                                    $name1=$selec=='selected'?$name:'';
                                                    if($name1!=''){
                                                        $s=true;
                                                    }
                                                    echo "<option value='$value' data-imagecolor='$value' $selec data-description='$name'>$name</option>";
                                                }
                                                $selec=$s==false?"selected":"";
                                                echo "<option value='#000' data-imagesrc='".plugin_dir_url(__FILE__)."img/select.png' data-imagecolor='#000' $selec data-description='".__("Other",'custom404int')."'>".__("Other",'custom404int')."</option>";
                                            ?>
                                            </select>
                                        </td>
                                        <td>
                                            <?php $n=trim($name1)!="Other"?"style='display: none;'":"";?>
                                            <div id="titlecolor_selector" class="colorSelector" <?php echo $n; ?> ><div style="background-color: <?php echo get_option('custom404_selected_titlecolor'); ?>"></div></div>
                                            <input type="hidden" name="titlecolor_select_value" id="titlecolor_select_value" value="<?php echo get_option('custom404_selected_titlecolor') ?>" />
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <td width='250px'><?php echo __("Title Content:",'custom404int')?></td>
                                        <td colspan="2"><input type="text" class="required" title="<?php echo __("Title Content",'custom404int')?>" id="content_title" name="content_title" maxlength="60" size="40" value="<?php echo get_option('custom404_content_title'); ?>" /></td>
                                    </tr>
                                    <tr valign="top">
                                        <td width='250px'><?php echo __("Text Font Type:",'custom404int')?></td>
                                        <td colspan="2">
                                            <select onchange="change_font(this,'#text_font_sel')" name="text_font" id="text_font">
                                                <?php $opt1=get_option('custom404_text_font'); ?>
                                                <?php $font_class=empty($opt1)?'Abel':$fonts[get_option('custom404_text_font')]; ?>
                                                <?php foreach ($fonts as $name=>$text): ?>
                                                <?php $selected=""; ?>
                                                <?php if($name==get_option('custom404_text_font')){$selected="selected";}?>
                                                <option <?php echo $selected; ?> custom-value="<?php echo $text ?>" value="<?php echo $name; ?>"><?php echo $name; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <span class="<?php echo $font_class; ?>" id="text_font_sel"><?php echo __("Font Preview",'custom404int')?></span>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <td width='250px'><?php echo __("Text Font Size (in pixels):",'custom404int')?></td>
                                        <td colspan="2">
                                            <select name="text_font_size" id="text_font_size">
                                                <?php for ($i=10;$i<=25;$i++): ?>
                                                    <?php $selected=$i==  get_option("custom404_text_font_size")?"selected":""; ?>
                                                    <option <?php echo $selected; ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                <?php endfor; ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <td><?php echo __("Text Color:",'custom404int');?></td>
                                        <td  style="width: 190px">
                                            <select id="textcolor_select" name="textcolor_select">
                                            <?php
                                                $opt_img=get_option("custom404_selected_textcolor");
                                                $colors=array(__("Default",'custom404int')=>"#ffffff");
                                                $s=false;
                                                $name1="";
                                                foreach ($colors as $name=>$value) {
                                                    $selec=$value==$opt_img?"selected":"";
                                                    $name1=$selec=='selected'?$name:'';
                                                    if($name1!=''){
                                                        $s=true;
                                                    }
                                                    echo "<option value='$value' data-imagecolor='$value' $selec data-description='$name'>$name</option>";
                                                }
                                                $selec=$s==false?"selected":"";
                                                echo "<option value='#000' data-imagesrc='".plugin_dir_url(__FILE__)."img/select.png' data-imagecolor='#000' $selec data-description='".__("Other",'custom404int')."'>".__("Other",'custom404int')."</option>";
                                            ?>
                                            </select>
                                        </td>
                                        <td>
                                            <?php $n=trim($name1)!="Other"?"style='display: none;'":"";?>
                                            <div id="textcolor_selector" class="colorSelector" <?php echo $n; ?> ><div style="background-color: <?php echo get_option('custom404_selected_textcolor'); ?>"></div></div>
                                            <input type="hidden" name="textcolor_select_value" id="textcolor_select_value" value="<?php echo get_option('custom404_selected_textcolor') ?>" />
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <td><?php echo __("Text Content:",'custom404int');?></td>
                                        <td colspan="2"><input type="text" name="content_text" class="required" title="<?php echo __("Text Content",'custom404int');?>" maxlength="100" size="40" id="content_text" value="<?php echo get_option('custom404_content_text'); ?>" /></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="postbox ">
                            <h3 style="cursor: default"><span><?php echo __("Categories Settings:",'custom404int');?></span></h3>
                            <div class="inside">
                                <table>
                                    <tr valign="top">
                                        <td><?php echo __("Category to view at useful posts:",'custom404int');?></td>
                                        <td colspan="2">
                                            <?php
                                                $args = array(
                                                    'show_option_all'    => __('All Categories','custom404int'),
                                                    'show_option_none'   => '',
                                                    'orderby'            => 'ID',
                                                    'order'              => 'ASC',
                                                    'show_count'         => 0,
                                                    'hide_empty'         => 0,
                                                    'child_of'           => 0,
                                                    'exclude'            => '',
                                                    'echo'               => 1,
                                                    'selected'           => get_option('custom404_catid_view'),
                                                    'hierarchical'       => 0,
                                                    'name'               => 'selected_category',
                                                    'id'                 => 'selected_category',
                                                    'class'              => 'postform',
                                                    'depth'              => 0,
                                                    'tab_index'          => 0,
                                                    'taxonomy'           => 'category',
                                                    'hide_if_empty'      => false
                                                );
                                                wp_dropdown_categories( $args );
                                            ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<div class="loading-spinner"><img src="<?php echo plugin_dir_url(__FILE__)."img/spinner.gif" ?>" title="Loading..." /></div>
<div id="dialog1" style="display: none"></div>
<div id="TS_uploader" style="display: none"></div>
<div id="TS_logo_uploader" style="display: none"></div>
<?php }
function wptg_er_save_plg_options(){
    $status=0;
    $message="";
    load_plugin_textdomain('custom404int', false, basename( dirname( __FILE__ ) ) . '/languages' );
    if(count($_POST)>0){
        update_option('custom404_content_title', stripslashes($_POST['content_title']));
        update_option('custom404_content_text', stripslashes($_POST['content_text']));
        update_option('custom404_selected_img', $_POST['img_select_value']);
        update_option('custom404_selected_pattern', $_POST['pattern_select_value']);
        update_option('custom404_selected_color', $_POST['color_select_value']);
        update_option('custom404_selected_textcolor', $_POST['textcolor_select_value']);
        update_option('custom404_selected_titlecolor', $_POST['titlecolor_select_value']);
        update_option('custom404_catid_view', $_POST['selected_category']);
        update_option( 'custom404_link_text_1', stripslashes($_POST['link_text_1']));
        update_option( 'custom404_link_href_1', stripslashes($_POST['link_href_1']));
        update_option( 'custom404_link_text_2', stripslashes($_POST['link_text_2']));
        update_option( 'custom404_link_href_2', stripslashes($_POST['link_href_2']));
        update_option( 'custom404_link_text_3', stripslashes($_POST['link_text_3']));
        update_option( 'custom404_link_href_3', stripslashes($_POST['link_href_3']));
        update_option( 'custom404_link_text_4', stripslashes($_POST['link_text_4']));
        update_option( 'custom404_link_href_4', stripslashes($_POST['link_href_4']));
        update_option( 'custom404_link_text_5', stripslashes($_POST['link_text_5']));
        update_option( 'custom404_link_href_5', stripslashes($_POST['link_href_5']));
        update_option( 'custom404_title_font', stripslashes($_POST['title_font']));
        update_option( 'custom404_text_font', stripslashes($_POST['text_font']));
        update_option( 'custom404_title_font_size', stripslashes($_POST['title_font_size']));
        update_option( 'custom404_text_font_size', stripslashes($_POST['text_font_size']));
        $message=__("Options Successfully saved!",'custom404int');
        $status=1;
    }else{
        $message=__("Access is denied!",'custom404int');
        $status=0;
    }
    header('Content-type: application/json');
    header('Content-type: text/json');
    die(json_encode(array("status"=>$status,"message"=>$message)));
}
function do_template_redirect()
{
    if(is_404()){
        header("HTTP/1.0 200 OK");
        include plugin_dir_path(__FILE__)."/custom404page.php" ;
        die();
    }
}
add_action('template_redirect', 'do_template_redirect' );
add_action("wp_ajax_saveplg", "wptg_er_save_plg_options");
add_action("wp_ajax_nopriv_saveplg", "wptg_er_save_plg_options");
?>