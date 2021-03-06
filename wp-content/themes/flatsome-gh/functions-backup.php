<?php
// *** Store Country-Currency list in a global variable which can be accessed from anywhere at the site ***
global $gh_country_currency;
$country_currency_array = array('afghanistan' => 'AFN', 'anguilla' => 'XCD', 'cambodia' => 'KHR', 'equatorial guinea' => 'GQE', 'australia' => 'AUD', 'chad' => 'XAF', 'finland' => 'EUR', 'benin' => 'XOF', 'cuba' => 'CUC', 'bangladesh' => 'BDT', 'brazil' => 'BRL', 'congo' => 'XAF', 'dominica' => 'XCD', 'georgia' => 'GEL', 'grenada' => 'XCD', 'haiti' => 'HTG', 'india' => 'INR', 'israel' => 'ILS', 'kazakhstan' => 'KZT', 'kuwait' => 'KWD', 'lesotho' => 'LSL', 'luxembourg' => 'EUR', 'malaysia' => 'MYR', 'mauritius' => 'MUR', 'mongolia' => 'MNT', 'myanmar' => 'MMK', 'new caledonia' => 'XPF', 'samoa (western)' => 'WST', 'tanzania' => 'TZS', 'norway' => 'NOK', 'serbia' => 'RSD', 'tunisia' => 'TND', 'portugal' => 'EUR', 'spain' => 'EUR', 'uzbekistan' => 'UZS', 'rwanda' => 'RWF', 'sweden' => 'SEK', 'yemen' => 'YER', 'papua new guinea' => 'PGK', 'slovenia' => 'EUR', 'ukraine' => 'UAH', 'algeria' => 'DZD', 'argentina' => 'ARS', 'azerbaijan' => 'AZN', 'belarus' => 'BYR', 'bolivia' => 'BOB', 'bulgaria' => 'BGN', 'canada' => 'CAD', 'china' => 'CNY', 'costa rica' => 'CRC', 'czech republic' => 'CZK', 'ecuador' => 'USD', 'french polynesia' => 'XPF', 'estonia' => 'EEK', 'ghana' => 'GHS', 'guinea' => 'GNF', 'hong kong' => 'HKD', 'iran, islamic republic of' => 'IRR', 'jamaica' => 'JMD', 'kiribati' => 'AUD', 'laos' => 'LAK', 'libya' => 'LYD', 'macedonia (former yug. rep.)' => 'MKD', 'mali' => 'XOF', 'micronesia' => 'USD', 'montserrat' => 'XCD', 'nauru' => 'AUD', 'nicaragua' => 'NIO', 'pakistan' => 'PKR', 'peru' => 'PEN', 'qatar' => 'QAR', 'saint kitts and nevis' => 'XCD', 'sao tome and principe' => 'STD', 'sierra leone' => 'SLL', 'somalia' => 'SOS', 'sudan' => 'SDG', 'syria' => 'SYP', 'togo' => 'XOF', 'turkmenistan' => 'TMM', 'united kingdom' => 'GBP', 'venezuela' => 'VEB', 'andorra' => 'EUR', 'armenia' => 'AMD', 'bahamas' => 'BSD', 'belgium' => 'EUR', 'bosnia-herzegovina' => 'BAM', 'cayman islands' => 'KYD', 'ethiopia' => 'ETB', 'iraq' => 'IQD', 'cÃ´te d\'ivoire' => 'XOF', 'gibraltar' => 'GIP', 'denmark' => 'DKK', 'guinea-bissau' => 'XOF', 'colombia' => 'COP', 'gabon' => 'XAF', 'japan' => 'JPY', 'burkina faso' => 'XOF', 'egypt' => 'EGP', 'hungary' => 'HUF', 'korea north' => 'KPW', 'latvia' => 'LVL', 'liechtenstein' => 'CHF', 'madagascar' => 'MGA', 'malta' => 'EUR', 'moldova' => 'MDL', 'morocco' => 'MAD', 'nepal' => 'NPR', 'niger' => 'XOF', 'palau' => 'USD', 'philippines' => 'PHP', 'romania' => 'RON', 'saint lucia' => 'XCD', 'saudi arabia' => 'SAR', 'singapore' => 'SGD', 'south africa' => 'ZAR', 'suriname' => 'SRD', 'tonga' => 'TOP', 'taiwan' => 'TWD', 'tuvalu' => 'AUD', 'united states of america' => 'USD', 'vietnam' => 'VND', 'austria' => 'EUR', 'barbados' => 'BBD', 'albania' => 'ALL', 'brunei' => 'BND', 'bhutan' => 'BTN', 'chile' => 'CLP', 'antigua and barbuda' => 'XCD', 'cameroon' => 'XAF', 'congo, democratic republic' => 'CDF', 'cyprus' => 'EUR', 'dominican republic' => 'DOP', 'eritrea' => 'ERN', 'france' => 'EUR', 'honduras' => 'HNL', 'germany' => 'EUR', 'guatemala' => 'GTQ', 'indonesia' => 'IDR', 'kenya' => 'KES', 'italy' => 'EUR', 'maldives' => 'MVR', 'mexico' => 'MXN', 'liberia' => 'LRD', 'macau' => 'MOP', 'kyrgyzstan' => 'KGS', 'namibia' => 'NAD', 'montenegro' => 'EUR', 'new zealand' => 'NZD', 'saint helena' => 'SHP', 'puerto rico' => 'USD', 'san marino' => 'EUR', 'paraguay' => 'PYG', 'solomon islands' => 'SBD', 'oman' => 'OMR', 'seychelles' => 'SCR', 'switzerland' => 'CHF', 'sri lanka' => 'LKR', 'thailand' => 'THB', 'turkey' => 'TRY', 'united arab emirates' => 'AED', 'zambia' => 'ZMK', 'vanuatu' => 'VUV', 'angola' => 'AOA', 'aruba' => 'AWG', 'bahrain' => 'BHD', 'belize' => 'BZD', 'botswana' => 'BWP', 'burundi' => 'BIF', 'central african republic' => 'XAF', 'comoros' => 'KMF', 'croatia' => 'HRK', 'djibouti' => 'DJF', 'el salvador' => 'USD', 'fiji' => 'FJD', 'greece' => 'EUR', 'gambia' => 'GMD', 'iceland' => 'ISK', 'guyana' => 'GYD', 'korea south' => 'KRW', 'ireland' => 'EUR', 'jordan' => 'JOD', 'malawi' => 'MWK', 'lithuania' => 'LTL', 'netherlands' => 'EUR', 'monaco' => 'EUR', 'lebanon' => 'LBP', 'nigeria' => 'NGN', 'mauritania' => 'MRO', 'saint vincent and the grenadines' => 'XCD', 'tajikistan' => 'TJS', 'poland' => 'PLN', 'south sudan' => 'SDG', 'uruguay' => 'UYU', 'mozambique' => 'MZM', 'russia' => 'RUB', 'swaziland' => 'SZL', 'wallis and futuna islands' => 'XPF', 'senegal' => 'XOF', 'trinidad and tobago' => 'TTD', 'panama' => 'PAB', 'slovakia' => 'SKK', 'uganda' => 'UGX' );
update_option( 'gh_country_currency', $country_currency_array );
$gh_country_currency = get_option('gh_country_currency');
// ******

/* *** Initiate WP_Session and store the following information of the visitor:
 * 1. IP Address
 * 2. Currency
 * 3. Conversion Rate (INR to visitor's local currency)
 *** */
global $wp_session;
$wp_session = WP_Session::get_instance();
$ip = gh_find_visitor_ip_address();
if($ip != $wp_session['ip']){
    $wp_session['ip'] = $ip;
    
    // $wp_session['ip'] = '54.169.160.23'; // Singapore IP | Temporarily hard-coding IP address for testing. DELETE IT AFTER TESTING
    $wp_session['ip'] = '204.62.114.179'; // USA IP | Temporarily hard-coding IP address for testing. DELETE IT AFTER TESTING
    // $wp_session['ip'] = '122.160.51.238'; // India (GH Office) IP | Temporarily hard-coding IP address for testing. DELETE IT AFTER TESTING
    
    $wp_session['currency'] = ''; 
    $wp_session['conversion_rate'] = ''; 
    $wp_session['country'] = '';
    
    $wp_session['country'] = gh_find_visitor_country($wp_session['ip']);
    if(strtolower( $wp_session['country'] ) != 'india'){
        $wp_session['currency'] = gh_find_visitor_currency($wp_session['country']);
        $wp_session['conversion_rate'] = gh_find_currency_conversion_rate( $wp_session['currency'] );
    }
}
// ******

add_action( 'wp_enqueue_scripts', 'enqueue_parent_theme_style' );
add_action( 'wp_enqueue_scripts', 'gh_enqueue_menu_css' );
add_action( 'wp_enqueue_scripts', 'gh_enqueue_custom_js' );
add_action( 'widgets_init', 'gh_header_contact_no_widget_area_func' );
add_action( 'widgets_init', 'gh_footer_top_courses_widget_area_func' );
add_action( 'widgets_init', 'gh_footer_payment_logos_widget_area_func' );

/*** Changes in WooCommerce Hooks (or Pre-defined hooks in Flatsome Theme) to reflect the given designs. ~ GH ***/
// Following will remove product summary from below the title, to match the given design
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 ); 

// Following will remove social sharing icons from below "Add to Cart" button and will place it just below the product image.
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
add_action( 'woocommerce_before_single_product_summary', 'woocommerce_template_single_sharing', 25 );

add_action( 'woocommerce_single_product_summary', 'gh_hook_template_institution_below_title', 6 );
add_action( 'woocommerce_single_product_summary', 'gh_hook_template_duration_and_provider_below_title', 7 );

// Change "Add To Cart" button text to "Start Application" text
add_filter( 'woocommerce_product_single_add_to_cart_text', 'gh_custom_cart_button_text' ); 

// Remove Order Quantity selectors for ALL products
add_filter( 'woocommerce_is_sold_individually', 'wc_remove_all_quantity_fields', 10, 2 );

// Edit default WooCommerce tabs at Product details/editor page
add_filter( 'woocommerce_product_tabs', 'gh_edit_product_tabs', 98 );

// Remove SKU from front-end site, to match the given design
add_filter( 'wc_product_sku_enabled', 'gh_remove_sku_from_front_end' );

// Remove Cart page from the flow. Now, as soon as someone clicks on "Start Application" (renamed "Add to Cart") button, s/he should reach directly to the Checkout page, bypassing the Cart page.
// add_filter ('add_to_cart_redirect', 'redirect_to_checkout');

add_filter('woocommerce_price_html', 'gh_price', 10, 2);
add_filter('woocommerce_variable_price_html', 'gh_variation_price', 10, 2);
add_filter('woocommerce_variable_sale_price_html', 'gh_variation_sale_price', 10, 2);

add_filter('woocommerce_variation_price_html', 'gh_variation_price_html', 10, 2); 
// Following filters: 'woocommerce_get_price' and 'woocommerce_checkout_update_order_meta' are used to show price in visitor's local currency
// add_filter('woocommerce_get_price', 'gh_return_custom_price', $product, 2);
// add_filter('woocommerce_get_max_variation_price', 'gh_return_custom_max_variation_price', $product, 2);
// add_filter('woocommerce_get_min_variation_price', 'gh_return_custom_min_variation_price', $product, 2);
// add_action( 'woocommerce_checkout_update_order_meta', 'gh_update_meta_data_with_new_currency' );
add_filter('raw_woocommerce_price', 'gh_return_custom_raw_price', 2);
//add_filter('woocommerce_cart_item_price', 'gh_cart_item_price_html', 2);

// add_filter('woocommerce_currency_symbol', 'gh_woocommerce_currency_symbol', 2);


function gh_cart_item_price_html($price){
    echo '<hr>';
    echo $price;
    echo '<hr>';
    return '<span class="amount">Rs.612.15</span>';   
    return '';
}

function gh_variation_price_html($price_html, $product){
    $price_html = '<span class="amount">'.gh_get_local_currency_symbol().' '.gh_get_currency_updated_price($product->price).'</span>';
    return $price_html;
}
// 1. Change the amount
function gh_return_custom_raw_price($price){
    return gh_get_currency_updated_price($price);       
    // return '100';
}

function gh_return_custom_price($price, $product) {       
        return gh_get_currency_updated_price($price);        
}      

function gh_return_custom_max_variation_price($price, $product) {       
        return gh_get_currency_updated_price($price);        
}      

function gh_return_custom_min_variation_price($price, $product) {       
        return gh_get_currency_updated_price($price);        
}      
 
// 2. Update the order meta with currency value and the method used to capture it
function gh_update_meta_data_with_new_currency( $order_id ) {
    // For the record, update the Order Data with local currency and IP address
    global $wp_session;
    update_post_meta( $order_id, 'users_local_currency', $wp_session['currency'] );
    update_post_meta( $order_id, 'currency_method', $wp_session['ip'] );
}

function gh_price( $price, $product ){
    $price = '';

    $price_html = '<span class="price"><span class="amount">'.gh_get_local_currency_symbol().' '.gh_get_currency_updated_price($product->price).'</span></span>';
    // return '';
    return $price_html;
}
function gh_variation_price( $price, $product ) {
	$price = '';
	 
//	if ( !$product->min_variation_price || $product->min_variation_price !== $product->max_variation_price ) $price .= '<span class="from">' . _x('From', 'min_price', 'woocommerce') . ' </span>';
	// $price .= woocommerce_price($product->get_price());
        $min_variation_price = gh_get_currency_updated_price($product->min_variation_price);
        $max_variation_price = gh_get_currency_updated_price($product->max_variation_price);
	$price .= '<span class="gh_min_price_in_loop">'.gh_get_local_currency_symbol().' '.$min_variation_price.'</span>';
	if ( $max_variation_price && $max_variation_price !== $min_variation_price ) {
		$price .= '<span class="to"> ' . _x('-', 'max_price', 'woocommerce') . ' </span>';
		 
		// $price .= woocommerce_price($product->max_variation_price);
		$price .= '<span class="gh_max_price_in_loop">'.gh_get_local_currency_symbol().' '.$max_variation_price.'</span>';
	}
	 
	return $price;
} 

function gh_variation_sale_price( $price, $product ) {
	$price = '';
	 
//	if ( !$product->min_variation_sale_price || $product->min_variation_sale_price !== $product->max_variation_sale_price ) $price .= '<span class="from">' . _x('From', 'min_sale_price', 'woocommerce') . ' </span>';
	// $price .= woocommerce_price($product->get_price());
	$price .= '<span class="gh_min_price_in_loop">'.gh_get_local_currency_symbol().' '.$product->min_variation_sale_price.'</span>';
	if ( $product->max_variation_sale_price && $product->max_variation_sale_price !== $product->min_variation_sale_price ) {
		$price .= '<span class="to"> ' . _x('-', 'max_sale_price', 'woocommerce') . ' </span>';
		 
		// $price .= woocommerce_price($product->max_variation_sale_price);
		$price .= '<span class="gh_max_price_in_loop">'.gh_get_local_currency_symbol().' '.$product->max_variation_sale_price.'</span>';
	}
	
	return $price;
} 

function redirect_to_checkout() {
    global $woocommerce;
    $checkout_url = $woocommerce->cart->get_checkout_url();
    return $checkout_url;
}

function gh_remove_sku_from_front_end(){
    if(!is_admin()){
        return false; // It will hide SKU only from the front-end site
    }else{
        return true;
    }
}

function gh_edit_product_tabs($tabs){
    unset( $tabs['reviews'] ); 			// Remove the reviews tab
    $tabs['description']['title'] = __( 'Course Description' );		// Rename the Course Description tab
    $tabs['additional_information']['callback'] = 'gh_additional_information_tab_content';	// Custom description callback
    
    return $tabs;
}

function gh_additional_information_tab_content(){
    global $product;
    $institution = $product->get_attribute("certifying-institution");
    $duration = $product->get_attribute("duration");
    $provider_obj = wc_get_product_terms($product->id, 'shop_vendor');
    $provider = $provider_obj[0]->name;
    
    $addition_information = '<p class="add_inf_duration">Duration: '.$duration.'</p>';
    $addition_information .= '<p class="add_inf_institution">University/Institution: '.$institution.'</p>';
    $addition_information .= '<p class="add_inf_provider">Provider: '.$provider.'</p>';
    echo $addition_information;
    // echo '<p><span class="duration_below_title">'.$duration.'</span> <span>Provider: '.$provider.'</span></p>';
}

function wc_remove_all_quantity_fields( $return, $product ) {
	return true;
}

function gh_custom_cart_button_text() {
    return "Start Application";
    // return __( 'My Button Text', 'woocommerce' );
} 

/*** Now the list of functions required for any of the above listed hooks ***/

function enqueue_parent_theme_style() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
}

function gh_enqueue_menu_css(){
	wp_enqueue_style( 'tooltipster_styles', get_stylesheet_directory_uri().'/css/tooltipster.css' );
	wp_enqueue_style( 'gh_homepage_mega_menu', get_stylesheet_directory_uri().'/css/nave_responsive.css' );
	wp_enqueue_style( 'gh_change_banner_slider', get_stylesheet_directory_uri().'/css/jquery.bxslider.css' );
}

function gh_enqueue_custom_js(){
        wp_enqueue_script( 'gh_jquery_min', get_stylesheet_directory_uri().'/js/jquery.min.js' );
	wp_enqueue_script( 'gh_homepage_mega_menu', get_stylesheet_directory_uri().'/js/flaunt.js' );
	wp_enqueue_script( 'gh_change_catalog_view', get_stylesheet_directory_uri().'/js/gh_custom.js' );
	wp_enqueue_script( 'tooltip_view', get_stylesheet_directory_uri().'/js/jquery.tooltipster.js' );
	wp_enqueue_script( 'gh_change_banner_slider', get_stylesheet_directory_uri().'/js/jquery.bxslider.js' );
       // wp_enqueue_script( 'gh_change_login_form', get_stylesheet_directory_uri().'/js/edukart.js' );
}

function gh_header_contact_no_widget_area_func(){
	register_sidebar( array(
		'name' => 'Header Contact Widget Sidebar',
		'id' => 'gh_header_contact_widget_area',
		'before_widget' => '<div>',
		'after_widget' => '</div>',
		'before_title' => '',
		'after_title' => '',
	) );
}

function gh_footer_top_courses_widget_area_func(){
	register_sidebar( array(
		'name' => 'Footer Top Courses Widget Sidebar',
		'id' => 'gh_footer_top_courses_widget_area',
		'before_widget' => '<div>',
		'after_widget' => '</div>',
		'before_title' => '',
		'after_title' => '',
	) );
}

function gh_footer_payment_logos_widget_area_func(){
	register_sidebar( array(
		'name' => 'Footer Payment Logos Widget Sidebar',
		'id' => 'gh_footer_payment_logos_widget_area',
		'before_widget' => '<div>',
		'after_widget' => '</div>',
		'before_title' => '',
		'after_title' => '',
	) );
}

function gh_hook_template_institution_below_title(){
    global $product;
    echo "<p>".$product->get_attribute("Certifying Institution")."</p>";
}

function gh_hook_template_duration_below_title(){
    global $product;
    echo $product->get_attribute("duration");
}

function gh_hook_template_duration_and_provider_below_title(){
    global $product;
    $duration = $product->get_attribute("duration");
    $provider_obj = wc_get_product_terms($product->id, 'shop_vendor')[0];
	$university_name = wc_get_product_terms($product->id, 'university')[0];
    $provider = $provider_obj->name;
    $siteurl = get_site_url();
    echo '<p  class="edu-ins-name-detail" > ' .$university_name->name. '</p><p class="duration_below_title"><span class="short-description_sapn">'.$duration.'</span> <span>Provider: <a href="'.$siteurl.'/e/'.$provider_obj->slug.'">'.$provider_obj->name.'</a></span></p>';
}

function gh_inquire_now_feature(){
	$content .= '<span class="single_popup_button button alt" >Inquire Now</span> <div id="edu-enquiry-popup" class="edu-enquiry-popup" style="display:none;" > <h3>Enter your drtails. We will get back to you</h3>
	<div class="popup-pic"></div>
	<div class="popup_form"> 
 	<form>
	<input type="text" name="name" id="name" placeholder="name">
	<input type="text" name="emaill" id="email" placeholder="email">
	<input type="text" name="mobile" id="mobile"  placeholder="mobile">
	<select id="courses" name="courses">
	<option>Select Your Courses</option>
	<option>MBA</option>
	<option>BCA</option>
	<option>MCA</option>
 	</select>
	<button class="single_add_to_cart_button button alt" type="submit">Submit</button>
 	</form>
	</div>
	</div><div class="edu-enquiry-bg"  style="display:none;"> </div> ';
	return $content;
}

function gh_find_visitor_ip_address(){
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP)){
        $ip = $client;
    }elseif(filter_var($forward, FILTER_VALIDATE_IP)){
        $ip = $forward;
    }else{
        $ip = $remote;
    }
    
    return $ip;
}

function gh_find_visitor_country($ip=''){
    if(trim($ip) == ''){
        return;
    }
    $endpoint = "http://www.telize.com/geoip/".$ip;
    $ip_details = json_decode(file_get_contents($endpoint), true);
    
    $country = $ip_details['country'];
    return $country;
}

function gh_find_visitor_currency($country = ''){
    if(trim($country) == ''){ return false; }
    
    global $gh_country_currency;
    $currency = $gh_country_currency[strtolower($country)];
    if(trim($currency) == ''){
        foreach($gh_country_currency as $key => $value){
            if( stristr($key, $country) ){
                $currency = $gh_country_currency[$key];
                return $currency;
            }
        }
    }
    return $currency;
}

function gh_find_currency_conversion_rate( $currency = '' ){
    if( trim($currency) == ''){ return false; }
    $endpoint = "http://www.freecurrencyconverterapi.com/api/v2/convert?q=INR_".$currency."&compact=y";
    
    $data = json_decode(file_get_contents($endpoint), true);
    
    $currency_pair = "INR_".$currency;
//    echo $data[$currency_pair]['val'];
//    exit;
    return $data[$currency_pair]['val'];
}

function gh_get_currency_updated_price($price){
    global $wp_session;
    
    if( $wp_session['currency'] != 'INR' ){
        $new_price = $price * $wp_session['conversion_rate'];
        return $new_price;
    }

    return $price;
}

function gh_get_local_currency_symbol(){
    global $wp_session;
    $symbol = get_woocommerce_currency_symbol($wp_session['currency']);
    if($symbol != ''){
        return $symbol;
    }else{
        return $wp_session['currency'];
    }
}

function gh_validate_email(){
    global $woocommerce;


    // Step #0: First validate email
   if( !filter_var($_POST['username'], FILTER_VALIDATE_EMAIL) ){
       echo false;
       die();
    }
    
    // Step #1: Check if the email exits
    if(isset($_POST['username'])){
        $user = $_POST['username'];
        $result = get_user_by('email', $user);
        if($result){
            echo true; //If user accountt is found, it will return an object, but we need to echo only "true"
        }else{
            echo false;
        }
           $mailto  = get_option('admin_email');
           $subject ="Incomplete Order Notification";
           $date = date_create();
           $msg = "<html><head></head><body><h3 style='background-color:ligh-blue;padding:10px,5px;'>Incomplete Order Notification</h3>";
           $msg .="<p>Server Date And Time[".date_format($date, ' Y-m-d H:i:s')."]</p>"; 
           $msg .="<table border='0' cellspacing ='1' cellpadding: '10' background='light-blue'><tbody><tr><td><strong>Email :<s/trong></td><td>".$user."</td></tr>";
           $msg .="<tr><td><strong>Products :</strong></td><td><ol type= '1'>";
           foreach ($woocommerce->cart->cart_contents as $cart_key => $cart_item_array) {
                 $msg .="<li>".get_the_title($cart_item_array[product_id])."</li>";
            }
            $msg .="</ol></td></tr><tr><td><strong>Cart Total :</strong></td><td>".$woocommerce->cart->get_cart_total()."</td>";
            $msg .= "</tr></tbody></table></body></html>";
            add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));
            wp_mail($mailto, $subject, $msg);
            global $wpdb;
            $table_name = $wpdb->prefix.'incomplete_orders';
            $email = get_magic_quotes_gpc()?stripslashes(trim($user)):trim($user);
            $item = get_magic_quotes_gpc()?stripslashes(trim(get_the_title($cart_item_array[product_id]))):trim(get_the_title($cart_item_array[product_id]));
            $cart_total_price = strip_tags($woocommerce->cart->get_cart_total());
            $price = get_magic_quotes_gpc()?stripslashes(trim($cart_total_price)):trim($cart_total_price);
            $queried_on = date('Y-m-d',time());
            $sql = "INSERT INTO $table_name ( `email`,`items`,`price`,`queried_on`) VALUES ( %s,%s,%s,%s ) ";
            $wpdb->query( $wpdb->prepare( $sql, $email, $item, $price, $queried_on) );
            //echo $rst;
           
    }

    
    die();
}
add_action('wp_ajax_validate_email', 'gh_validate_email');
add_action('wp_ajax_nopriv_validate_email', 'gh_validate_email');//for users that are not logged in.

include 'functions-lk.php';
