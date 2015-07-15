<?php get_header();

// Use `header` if `http_response_code` doesn't exist
if ( function_exists( 'http_response_code' ) )
    http_response_code( 404 );  ?>

<link href='http://fonts.googleapis.com/css?family=<?php echo $title_font ?>' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=<?php echo $text_font ?>' rel='stylesheet' type='text/css'>

<?php

    // Get the template with plugin styles
    c_404_load_view( 'css/custom-404-style.php' );

?>
<div class="container_full">
    <div class="content_img">
        <div class="content_cusim"></div>
        <div class="title_cu"><?php echo stripslashes(get_option('custom404_content_title'))?></div>
        <div class="custom_text_content">"<?php echo stripslashes(get_option('custom404_content_text'))?>"</div>
        <div class='custom_title'>Check these Useful Posts:</div>
        <div style="margin-top: 5px">
<?php
    global $wpdb;
    $cat_n=get_cat_name(get_option('custom404_catid_view'));
    $args='orderby=rand&showposts=5&cat='.get_option('custom404_catid_view');
    $q=new WP_Query($args);
    if ($q->have_posts()){
        while ($q->have_posts()) {
            $q->the_post();
            ?>
            <div class="text_content"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php  the_title();?></a></div>
            <?php
        }
    }else{
        ?>
        <div class="text_content">No posts!</div>
<?php
    }
?>
        </div>
<?php
    $opt1=get_option('custom404_link_href_1');
    $opt2=get_option('custom404_link_href_2');
    $opt3=get_option('custom404_link_href_3');
    $opt4=get_option('custom404_link_href_4');
    $opt5=get_option('custom404_link_href_5');
    if($opt1!=''||$opt2!=''||$opt3!=''||$opt4!=''||$opt5!=''){
    ?>
        <div class="custom_title2">Check these Useful Links:</div>
        <div class="text_urls">
<?php
    if($opt1!=''){
?>
            <span><a style="<?php echo get_option('custom404_selected_color')=='#efefef'?'color: #aaa':''; ?>" target="blank" href="<?php echo get_option('custom404_link_href_1') ?>" rel="bookmark"><?php echo get_option('custom404_link_text_1') ?></a></span>&nbsp;
<?php
    }
    if($opt2!=''){
?>
            <span><a style="<?php echo get_option('custom404_selected_color')=='#efefef'?'color: #aaa':''; ?>" target="blank" href="<?php echo get_option('custom404_link_href_2') ?>" rel="bookmark"><?php echo get_option('custom404_link_text_2') ?></a></span>&nbsp;
<?php
    }
    if($opt3!=''){
?>
            <span><a style="<?php echo get_option('custom404_selected_color')=='#efefef'?'color: #aaa':''; ?>" target="blank" href="<?php echo get_option('custom404_link_href_3') ?>" rel="bookmark"><?php echo get_option('custom404_link_text_3') ?></a></span>&nbsp;
<?php
    }
    if($opt4!=''){
?>
            <span><a style="<?php echo get_option('custom404_selected_color')=='#efefef'?'color: #aaa':''; ?>" target="blank" href="<?php echo get_option('custom404_link_href_4') ?>" rel="bookmark"><?php echo get_option('custom404_link_text_4') ?></a></span>&nbsp;
<?php
    }
    if($opt5!=''){
?>
            <span><a style="<?php echo get_option('custom404_selected_color')=='#efefef'?'color: #aaa':''; ?>" target="blank" href="<?php echo get_option('custom404_link_href_5') ?>" rel="bookmark"><?php echo get_option('custom404_link_text_5') ?></a></span>&nbsp;
<?php
    }
?>
       </div>
<?php
    }
?>
    </div>
</div>
<div style="height: 80px">&nbsp;</div>
<?php get_footer(); ?>