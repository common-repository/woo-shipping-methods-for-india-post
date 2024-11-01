<?php

/**
 * Plugin Name: WooCommerce Shipping methods for India Post
 * Plugin URI: https://woocommerce.runacorp.com/shippping/indianpost
 * Description: WooCommerce Shipping methods for India Post
 * Version: 1.1.5
 * Author: Run A Corp
 * Author URI: https://runacorp.com
 * License: GPLv2 or later
 */

if(!defined('WPINC')){ die; }
$woo_installed=in_array('woocommerce/woocommerce.php',apply_filters('active_plugins',get_option('active_plugins')));
if($woo_installed){
    function RPCOD_init() {
        if ( ! class_exists( 'RunACorp_India_Post_RPCOD' ) ) {
            class RunACorp_India_Post_RPCOD extends WC_Shipping_Method {
                public function __construct() {
                    $this->id                 = 'rac_woocom_indiapost_rpcod'; 
                    $this->method_title       = __( 'Registered Parcel [COD] Shipping', 'rac_woocom_indiapost_rpcod' );  
                    $this->method_description = __( 'Registered Parcel [COD] Shipping', 'rac_woocom_indiapost_rpcod' ); 
                    $this->availability = 'including';
                    $this->countries = array(
                        'IN', // India
                    );
                    $this->init();
                    $this->enabled = isset( $this->settings['enabled'] ) ? $this->settings['enabled'] : 'yes';
                    $this->title = isset( $this->settings['title'] ) ? $this->settings['title'] : __( 'Registered Parcel [COD] Shipping', 'rac_woocom_indiapost_rpcod' );
                    $this->show_params = isset( $this->settings['show_params'] ) ? $this->settings['show_params'] : 'no';
                    $this->surcharge = isset( $this->settings['surcharge'] ) ? $this->settings['surcharge'] : 0;
                    $this->flat_base_fee = isset( $this->settings['flat_base_fee'] ) ? $this->settings['flat_base_fee'] : 0;
                }
                function init() {
                    $this->init_form_fields();
                    $this->init_settings();
                    add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
                }
 
                function init_form_fields() { 
                    $this->form_fields = array(
                        'enabled' => array(
                            'title' => __( 'Enable', 'rac_woocom_indiapost_rpcod' ),
                            'type' => 'checkbox',
                            'description' => __( 'Enable this shipping.', 'rac_woocom_indiapost_rpcod' ),
                            'default' => 'yes'
                        ),
                        'title' => array(
                            'title' => __( 'Title', 'rac_woocom_indiapost_rpcod' ),
                            'type' => 'text',
                            'description' => __( 'Title to be display on site', 'rac_woocom_indiapost_rpcod' ),
                            'default' => __( 'Registered Parcel [COD]', 'rac_woocom_indiapost_rpcod' )
                        ),
                        'show_params' => array(
                            'title' => __( 'WDP Info', 'rac_woocom_indiapost_rpcod' ),
                            'type' => 'checkbox',
                            'description' => __( 'Enable this to show the calculated weight, calculated distance and pincode information to the end customer.', 'rac_woocom_indiapost_rpcod' ),
                            'default' => 'no'
                        ),
                        'surcharge' => array(
                            'title' => __( 'Surcharge Percentage', 'rac_woocom_indiapost_rpcod' ),
                            'type' => 'text',
                            'description' => __( 'You can apply a surcharge to discourage the use of this shipping method', 'rac_woocom_indiapost_rpcod' ),
                            'default' => '0'
                        ),
                        'flat_base_fee' => array(
                            'title' => __( 'Base Flat Fee', 'rac_woocom_indiapost_rpcod' ),
                            'type' => 'number',
                            'description' => __( 'You can apply a base flat fee to this shipping method.', 'rac_woocom_indiapost_rpcod' ),
                            'default' => 0
                        ),
                    );
                }
 
                /**
                 * This function is used to calculate the shipping cost. Within this function we can check for weights, dimensions and other parameters.
                 *
                 * @access public
                 * @param mixed $package
                 * @return void
                 */
                public function calculate_shipping( $package = array() ) {
                    $weight = 0;
                    $cart_value = 0;
                    foreach ( $package['contents'] as $item_id => $values ) { 
                        $_product = $values['data']; 
                        $weight = $weight + $_product->get_weight() * $values['quantity']; 
                        $cart_value += $_product->get_price() * $values['quantity'];
                    }
                    $weight = wc_get_weight( $weight, 'g' );

                    $cost = 41;

                    if($weight > 500){
                        $cost += ($weight / 500) * 16;
                    }

                    $cost += round( $cart_value / 100 * $this->surcharge );

                    $tempTitle = $this->title;
                    if($this->show_params == "yes")
                        $tempTitle .= " [ $weight g ]";

                    if(is_numeric($this->flat_base_fee))
                        if($this->flat_base_fee > 0)
                            $cost += $this->flat_base_fee;

                    $rate = array(
                        'id' => $this->id,
                        'label' => $tempTitle,
                        'cost' => $cost,
                    );
 
                    if($this->enabled)
                        $this->add_rate( $rate );
                }
            }
        }
    }
    function RP_init() {
        if ( ! class_exists( 'RunACorp_India_Post_RP' ) ) {
            class RunACorp_India_Post_RP extends WC_Shipping_Method {
                public function __construct() {
                    $this->id                 = 'rac_woocom_indiapost_rp'; 
                    $this->method_title       = __( 'Registered Parcel Shipping', 'rac_woocom_indiapost_rp' );  
                    $this->method_description = __( 'Registered Parcel Shipping', 'rac_woocom_indiapost_rp' ); 
                    $this->availability = 'including';
                    $this->countries = array(
                        'IN', // India
                    );
                    $this->init();
                    $this->enabled = isset( $this->settings['enabled'] ) ? $this->settings['enabled'] : 'yes';
                    $this->title = isset( $this->settings['title'] ) ? $this->settings['title'] : __( 'Registered Parcel Shipping', 'rac_woocom_indiapost_rp' );
                    $this->show_params = isset( $this->settings['show_params'] ) ? $this->settings['show_params'] : 'no';
                    $this->flat_base_fee = isset( $this->settings['flat_base_fee'] ) ? $this->settings['flat_base_fee'] : 0;
                }
                function init() {
                    $this->init_form_fields();
                    $this->init_settings();
                    add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
                }
 
                function init_form_fields() { 
                    $this->form_fields = array(
                        'enabled' => array(
                            'title' => __( 'Enable', 'rac_woocom_indiapost_rp' ),
                            'type' => 'checkbox',
                            'description' => __( 'Enable this shipping.', 'rac_woocom_indiapost_rp' ),
                            'default' => 'yes'
                        ),
                        'title' => array(
                            'title' => __( 'Title', 'rac_woocom_indiapost_rp' ),
                            'type' => 'text',
                            'description' => __( 'Title to be display on site', 'rac_woocom_indiapost_rp' ),
                            'default' => __( 'Registered Parcel [COD]', 'rac_woocom_indiapost_rp' )
                        ),
                        'show_params' => array(
                            'title' => __( 'WDP Info', 'rac_woocom_indiapost_rp' ),
                            'type' => 'checkbox',
                            'description' => __( 'Enable this to show the calculated weight, calculated distance and pincode information to the end customer.', 'rac_woocom_indiapost_rp' ),
                            'default' => 'no'
                        ),
                        'flat_base_fee' => array(
                            'title' => __( 'Base Flat Fee', 'rac_woocom_indiapost_rpcod' ),
                            'type' => 'number',
                            'description' => __( 'You can apply a base flat fee to this shipping method.', 'rac_woocom_indiapost_rpcod' ),
                            'default' => 0
                        ),
                    );
                }
                public function calculate_shipping( $package = array() ) {
                    $weight = 0;
                    $cart_value = 0;
                    foreach ( $package['contents'] as $item_id => $values ) { 
                        $_product = $values['data']; 
                        $weight = $weight + $_product->get_weight() * $values['quantity']; 
                        $cart_value += $_product->get_price() * $values['quantity'];
                    }
                    $weight = wc_get_weight( $weight, 'g' );

                    $cost = 36;

                    if($weight > 500){
                        $cost += ($weight / 500) * 16;
                    }

                    $tempTitle = $this->title;
                    if($this->show_params == "yes")
                        $tempTitle .= " [ $weight g ]";


                    if(is_numeric($this->flat_base_fee))
                        if($this->flat_base_fee > 0)
                            $cost += $this->flat_base_fee;

                    $rate = array(
                        'id' => $this->id,
                        'label' => $tempTitle,
                        'cost' => $cost,
                    );

                    if($this->enabled)
                        $this->add_rate( $rate );
                }
            }
        }
    }
    function SP_init() {
        if ( ! class_exists( 'RunACorp_India_Post_SP' ) ) {
            class RunACorp_India_Post_SP extends WC_Shipping_Method {
                public function __construct() {
                    $this->id                 = 'rac_woocom_indiapost_sp'; 
                    $this->method_title       = __( 'Speed Post Shipping', 'rac_woocom_indiapost_sp' );  
                    $this->method_description = __( 'Speed Post Shipping', 'rac_woocom_indiapost_sp' ); 
                    $this->availability = 'including';
                    $this->countries = array(
                        'IN', // India
                    );
                    $this->init();
                    $this->enabled = isset( $this->settings['enabled'] ) ? $this->settings['enabled'] : 'yes';
                    $this->title = isset( $this->settings['title'] ) ? $this->settings['title'] : __( 'Speed Post Shipping', 'rac_woocom_indiapost_sp' );
                    $this->show_params = isset( $this->settings['show_params'] ) ? $this->settings['show_params'] : 'no';
                    $this->surcharge = isset( $this->settings['surcharge'] ) ? $this->settings['surcharge'] : 0;
                    $this->key = isset( $this->settings['key'] ) ? $this->settings['key'] : '';
                    $this->src_pincode = isset( $this->settings['src_pincode'] ) ? $this->settings['src_pincode'] : '682016';
                    $this->flat_base_fee = isset( $this->settings['flat_base_fee'] ) ? $this->settings['flat_base_fee'] : 0;
                    $this->gst_rate = isset( $this->settings['gst_rate'] ) ? $this->settings['gst_rate'] : 18;
                }
                function init() {
                    $this->init_form_fields();
                    $this->init_settings();
                    add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
                }
 
                function init_form_fields() { 
                    $this->form_fields = array(
                        'enabled' => array(
                            'title' => __( 'Enable', 'rac_woocom_indiapost_sp' ),
                            'type' => 'checkbox',
                            'description' => __( 'Enable this shipping.', 'rac_woocom_indiapost_sp' ),
                            'default' => 'yes'
                        ),
                        'title' => array(
                            'title' => __( 'Title', 'rac_woocom_indiapost_sp' ),
                            'type' => 'text',
                            'description' => __( 'Title to be display on site', 'rac_woocom_indiapost_sp' ),
                            'default' => __( 'Speed Post', 'rac_woocom_indiapost_sp' )
                        ),
                        'key' => array(
                            'title' => __( 'GCP Maps Key', 'rac_woocom_indiapost_sp' ),
                            'type' => 'text',
                            'description' => __( 'Google Maps API Key', 'rac_woocom_indiapost_sp' ),
                            'default' => __( '', 'rac_woocom_indiapost_sp' )
                        ),
                        'src_pincode' => array(
                            'title' => __( 'Dispatch Warehouse PINCODE', 'rac_woocom_indiapost_sp' ),
                            'type' => 'text',
                            'description' => __( 'Dispatch Warehouse PINCODE', 'rac_woocom_indiapost_sp' ),
                            'default' => __( '', 'rac_woocom_indiapost_sp' )
                        ),
                        'show_params' => array(
                            'title' => __( 'WDP Info', 'rac_woocom_indiapost_sp' ),
                            'type' => 'checkbox',
                            'description' => __( 'Enable this to show the calculated weight, calculated distance and pincode information to the end customer.', 'rac_woocom_indiapost_sp' ),
                            'default' => 'no'
                        ),
                        'flat_base_fee' => array(
                            'title' => __( 'Base Flat Fee', 'rac_woocom_indiapost_rpcod' ),
                            'type' => 'number',
                            'description' => __( 'You can apply a base flat fee to this shipping method.', 'rac_woocom_indiapost_rpcod' ),
                            'default' => 0
                        ),
                        'gst_rate' => array(
                            'title' => __( 'GST Rate', 'rac_woocom_indiapost_rpcod' ),
                            'type' => 'number',
                            'description' => __( 'GST Rate for your State', 'rac_woocom_indiapost_rpcod' ),
                            'default' => 18
                        ),
                    );
                }
                public function calculate_shipping( $package = array() ) {
                    $weight = 0;
                    foreach ( $package['contents'] as $item_id => $values ) { 
                        $_product = $values['data']; 
                        $weight = $weight + $_product->get_weight() * $values['quantity']; 
                    }
                    $weight = wc_get_weight( $weight, 'g' );
                    $spincode = $this->src_pincode;
                    $dpincode = $package["destination"]["postcode"];
                    $distance = getDistance($spincode, $dpincode, $this->key);

                    $cost = null;

                    $json_data = '{"slabs":[{"min_weight":0,"max_weight":200,"min_distance":0,"max_distance":200,"rate":41},{"min_weight":201,"max_weight":500,"min_distance":0,"max_distance":200,"rate":59},{"min_weight":501,"max_weight":1000,"min_distance":0,"max_distance":200,"rate":77},{"min_weight":1001,"max_weight":1500,"min_distance":0,"max_distance":200,"rate":94},{"min_weight":1501,"max_weight":2000,"min_distance":0,"max_distance":200,"rate":112},{"min_weight":2001,"max_weight":2500,"min_distance":0,"max_distance":200,"rate":130},{"min_weight":2501,"max_weight":3000,"min_distance":0,"max_distance":200,"rate":148},{"min_weight":3001,"max_weight":3500,"min_distance":0,"max_distance":200,"rate":165},{"min_weight":3501,"max_weight":4000,"min_distance":0,"max_distance":200,"rate":183},{"min_weight":4001,"max_weight":4500,"min_distance":0,"max_distance":200,"rate":201},{"min_weight":4501,"max_weight":5000,"min_distance":0,"max_distance":200,"rate":218},{"min_weight":0,"max_weight":200,"min_distance":201,"max_distance":1000,"rate":47},{"min_weight":201,"max_weight":500,"min_distance":201,"max_distance":1000,"rate":71},{"min_weight":501,"max_weight":1000,"min_distance":201,"max_distance":1000,"rate":106},{"min_weight":1001,"max_weight":1500,"min_distance":201,"max_distance":1000,"rate":142},{"min_weight":1501,"max_weight":2000,"min_distance":201,"max_distance":1000,"rate":177},{"min_weight":2001,"max_weight":2500,"min_distance":201,"max_distance":1000,"rate":212},{"min_weight":2501,"max_weight":3000,"min_distance":201,"max_distance":1000,"rate":248},{"min_weight":3001,"max_weight":3500,"min_distance":201,"max_distance":1000,"rate":283},{"min_weight":3501,"max_weight":4000,"min_distance":201,"max_distance":1000,"rate":319},{"min_weight":4001,"max_weight":4500,"min_distance":201,"max_distance":1000,"rate":354},{"min_weight":4501,"max_weight":5000,"min_distance":201,"max_distance":1000,"rate":389},{"min_weight":0,"max_weight":200,"min_distance":1001,"max_distance":2000,"rate":71},{"min_weight":201,"max_weight":500,"min_distance":1001,"max_distance":2000,"rate":94},{"min_weight":501,"max_weight":1000,"min_distance":1001,"max_distance":2000,"rate":142},{"min_weight":1001,"max_weight":1500,"min_distance":1001,"max_distance":2000,"rate":189},{"min_weight":1501,"max_weight":2000,"min_distance":1001,"max_distance":2000,"rate":236},{"min_weight":2001,"max_weight":2500,"min_distance":1001,"max_distance":2000,"rate":283},{"min_weight":2501,"max_weight":3000,"min_distance":1001,"max_distance":2000,"rate":330},{"min_weight":3001,"max_weight":3500,"min_distance":1001,"max_distance":2000,"rate":378},{"min_weight":3501,"max_weight":4000,"min_distance":1001,"max_distance":2000,"rate":425},{"min_weight":4001,"max_weight":4500,"min_distance":1001,"max_distance":2000,"rate":472},{"min_weight":4501,"max_weight":5000,"min_distance":1001,"max_distance":2000,"rate":519},{"min_weight":0,"max_weight":200,"min_distance":2001,"max_distance":5000,"rate":83},{"min_weight":201,"max_weight":500,"min_distance":2001,"max_distance":5000,"rate":106},{"min_weight":501,"max_weight":1000,"min_distance":2001,"max_distance":5000,"rate":165},{"min_weight":1001,"max_weight":1500,"min_distance":2001,"max_distance":5000,"rate":224},{"min_weight":1501,"max_weight":2000,"min_distance":2001,"max_distance":5000,"rate":283},{"min_weight":2001,"max_weight":2500,"min_distance":2001,"max_distance":5000,"rate":342},{"min_weight":2501,"max_weight":3000,"min_distance":2001,"max_distance":5000,"rate":401},{"min_weight":3001,"max_weight":3500,"min_distance":2001,"max_distance":5000,"rate":460},{"min_weight":3501,"max_weight":4000,"min_distance":2001,"max_distance":5000,"rate":519},{"min_weight":4001,"max_weight":4500,"min_distance":2001,"max_distance":5000,"rate":578},{"min_weight":4501,"max_weight":5000,"min_distance":2001,"max_distance":5000,"rate":637}]}';
                    $slabs = json_decode($json_data)->slabs;
                    foreach($slabs as $slab) {
                        if($weight >= $slab->min_weight && $weight <= $slab->max_weight && $distance >= $slab->min_distance && $distance <= $slab->max_distance)
                            if($cost == null || $cost < $slab->rate)
                                $cost = $slab->rate;
                    }

                    // GST
                    if($cost != null) {
                        $cost = round($cost * (1 + ($this->gst_rate / 100)));

                        $tempTitle = $this->title;
                        if($this->show_params == "yes")
                            $tempTitle .= " [ $weight g | $distance Km | $dpincode ]";

                        if(is_numeric($this->flat_base_fee))
                            if($this->flat_base_fee > 0)
                                $cost += $this->flat_base_fee;

                        $rate = array(
                            'id' => $this->id,
                            'label' => $tempTitle,
                            'cost' => $cost,
                        );

                        if($this->enabled)
                            $this->add_rate( $rate );
                    }
                }
            }
        }
    }
    add_action( 'woocommerce_shipping_init', 'SP_init' );
    add_action( 'woocommerce_shipping_init', 'RP_init' );
    add_action( 'woocommerce_shipping_init', 'RPCOD_init' );

    function AddAllClasses( $methods ) {
        $methods[] = 'RunACorp_India_Post_SP';
        $methods[] = 'RunACorp_India_Post_RP';
        $methods[] = 'RunACorp_India_Post_RPCOD';
        return $methods;
    }
    
    add_filter( 'woocommerce_shipping_methods', 'AddAllClasses' );

    //Google Maps Section

    function getDistance($addressFrom, $addressTo, $apiKey){
        $formattedAddrFrom    = str_replace(' ', '+', $addressFrom);
        $formattedAddrTo     = str_replace(' ', '+', $addressTo);
        
        // Geocoding API request with start address
        $geocodeFrom = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddrFrom.'&sensor=false&key='.$apiKey);
        $outputFrom = json_decode($geocodeFrom);
        if(!empty($outputFrom->error_message)){
            return $outputFrom->error_message;
        }
        
        // Geocoding API request with end address
        $geocodeTo = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddrTo.'&sensor=false&key='.$apiKey);
        $outputTo = json_decode($geocodeTo);
        if(!empty($outputTo->error_message)){
            return $outputTo->error_message;
        }
        
        // Get latitude and longitude from the geodata
        $latitudeFrom    = $outputFrom->results[0]->geometry->location->lat;
        $longitudeFrom    = $outputFrom->results[0]->geometry->location->lng;
        $latitudeTo        = $outputTo->results[0]->geometry->location->lat;
        $longitudeTo    = $outputTo->results[0]->geometry->location->lng;
        
        // Calculate distance between latitude and longitude
        $theta    = $longitudeFrom - $longitudeTo;
        $dist    = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
        $dist    = acos($dist);
        $dist    = rad2deg($dist);
        $miles    = $dist * 60 * 1.1515;
        
        // Convert unit and return distance
        return round($miles * 1.609344, 2);
    }
}