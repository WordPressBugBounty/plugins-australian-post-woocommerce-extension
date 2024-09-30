<?php

namespace WPRubyAustraliaPost;


use WC_Product;
use WC_Shipping_Method;
use WPRuby_PackedBox;
use WPRubyAustraliaPost\Deps\DVDoug\BoxPacker\Box;
use WPRubyAustraliaPost\Deps\DVDoug\BoxPacker\PackedBox;
use WPRubyAustraliaPost\Deps\DVDoug\BoxPacker\Packer;
use WPRubyAustraliaPost\Includes\WPRuby_AusPost_Box;
use WPRubyAustraliaPost\Includes\WPRuby_AusPost_Item;

class WC_Australian_Post_Shipping_Method extends WC_Shipping_Method
{

	public $postageParcelURL = 'https://digitalapi.auspost.com.au/postage/parcel/domestic/calculate.json';
	public $api_key = '20b5d076-5948-448f-9be4-f2fd20d4c258';
	public $supported_services = array(
		'AUS_PARCEL_REGULAR' => 'Parcel Post',
		'AUS_PARCEL_EXPRESS' => 'Express Post'
	);

    private $shop_post_code;
    private $default_weight;
    private $default_width;
    private $default_length;
    private $default_height;
    private $show_duration;
    private $debug_mode;
	private $enable_stripping_tax;

	public function __construct( $instance_id = 0 )
    {
		$this->id = 'auspost';
		$this->instance_id = absint( $instance_id );
		$this->method_title = __('Australia Post','australian-post');
		$this->title = __('Australia Post','australian-post');
        $this->method_description = 'Australia Post WooCommerce Extension integrates Australia Post with WooCommerce, calculating shipping costs and delivery times for customers.';

		$this->supports  = array(
			'shipping-zones',
			'shipping-zones',
			'instance-settings',
		);
		$this->init_form_fields();
		$this->init_settings();
		$this->tax_status = 'taxable';

		$this->enabled = $this->get_option('enabled');
		$this->title = $this->get_option('title');
		$this->api_key = $this->get_option('api_key');
		$this->shop_post_code = $this->get_option('shop_post_code');
		$this->enabled_services = $this->get_option('enabled_services');

		$this->default_weight = $this->get_option('default_weight');
		$this->default_width = $this->get_option('default_width');
		$this->default_length = $this->get_option('default_length');
		$this->default_height = $this->get_option('default_height');
		$this->show_duration = $this->get_option( 'show_duration' );
		$this->enable_stripping_tax = $this->get_option('enable_stripping_tax');

		$this->debug_mode = $this->get_option('debug_mode');

		add_action('woocommerce_update_options_shipping_'.$this->id, array($this, 'process_admin_options'));

	}


	public function init_form_fields(){

		$dimensions_unit = strtolower( get_option( 'woocommerce_dimension_unit' ) );
		$weight_unit = strtolower( get_option( 'woocommerce_weight_unit' ) );

		$this->instance_form_fields = array(
			'title' => array(
				'title' 		=> __( 'Method Title', 'australian-post' ),
				'type' 			=> 'text',
				'description' 	=> __( 'This controls the title', 'australian-post' ),
				'default'		=> __( 'Australia Post Shipping', 'australian-post' ),
				'desc_tip'		=> true,
			),
			'api_key' => array(
					'title'             => __( 'API Key', 'australian-post' ),
					'type'              => 'text',
					'description'       => __( 'Get your key from <a target="_blank" href="https://developers.auspost.com.au/apis/pacpcs-registration">https://developers.auspost.com.au/apis/pacpcs-registration</a>', 'australian-post' ),
					'default'           => $this->api_key
			),
			'enabled_services' => [
				'title' => __('Enabled Services', 'australian-post'),
				'type' => 'multiselect',
				'default' => ['AUS_PARCEL_REGULAR', 'AUS_PARCEL_EXPRESS'],
				'class' => 'availability wc-enhanced-select',
				'options' => $this->supported_services,
			],
			'shop_post_code' => array(
					'title'             => __( 'Shop Origin Postcode', 'australian-post' ),
					'type'              => 'text',
					'description'       => __( 'Enter your Shop postcode.', 'australian-post' ),
					'default'           => '2000',
					'css'				=> 'width:100px;',
			),
			'default_weight' => array(
					'title'             => __( 'Default Package Weight', 'australian-post' ),
					'type'              => 'text',
					'default'           => '0.5',
					'description'       => __( $weight_unit , 'australian-post' ),
					'css'				=> 'width:100px;',
			),
			'default_width' => array(
					'title'             => __( 'Default Package Width', 'australian-post' ),
					'type'              => 'text',
					'default'           => '5',
					'description'       => __( $dimensions_unit, 'australian-post' ),
					'css'				=> 'width:100px;',
			),
			'default_height' => array(
					'title'             => __( 'Default Package Height', 'australian-post' ),
					'type'              => 'text',
					'default'           => '5',
					'description'       => __( $dimensions_unit, 'australian-post' ),
					'css'				=> 'width:100px;',
			),
			'default_length' => array(
					'title'             => __( 'Default Package Length', 'australian-post' ),
					'type'              => 'text',
					'default'           => '10',
					'description'       => __( $dimensions_unit, 'australian-post' ),
					'css'				=> 'width:100px;',
			),
			'debug_mode' => array(
				'title' 		=> __( 'Enable Debug Mode', 'australian-post' ),
				'type' 			=> 'checkbox',
				'label' 		=> __( 'Enable ', 'australian-post' ),
				'default' 		=> 'no',
				'description'	=> __('If debug mode is enabled, the shipping method will be activated just for the administrator.', 'australian-post'),
			),
			'show_duration' => array(
				'title' 		=> __( 'Delivery Time', 'australian-post' ),
				'type' 			=> 'checkbox',
				'label' 		=> __( 'Enable ', 'australian-post' ),
				'default' 		=> 'yes',
				'description'	=> __( 'Show Delivery Time Estimation in the Checkout page.', 'australian-post' ),
			),
			'enable_stripping_tax' => array(
				'title' => __('Remove GST', 'australian-post'),
				'type' => 'checkbox',
				'default' => 'no',
				'label' => __('Enable', 'australian-post'),
				'description' => __('Hint: Enabling this option will strip the GST(tax) value (10%) from the shipping prices coming from Australia Post.', 'australian-post'),
			),
	 );
	}

	/**
	 * Admin Panel Options
	 * - Options for bits like 'title' and availability on a country-by-country basis
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function admin_options() {
		include "views/admin-options.php";
	}

	public function is_available( $package ){
		// The lite version doesn't support international shipping
		if($package['destination']['country'] != 'AU') return false;

		// Debug mode
		if($this->debug_mode === 'yes'){
			return current_user_can('administrator');
		}
		return true;
	}

	public function calculate_shipping( $package = array() ){

		$package_details = $this->get_package_details_by_boxpacker($package);

		if ($package_details === false) {
			$package_details  =  $this->get_package_details( $package );
		}

		$this->rates = [];
		// since 1.4.2 enhancing the debug mode.
		$this->debug('Packing Details: <pre>' . print_r($package_details, true) . '</pre>');
		$rates = array();
		foreach($package_details as  $pack){
			$weight = $pack['weight'];
			$height = $pack['height'];
			$width 	= $pack['width'];
			$length = $pack['length'];
			$rates = $this->get_rates($rates, $weight, $height, $width, $length, $package['destination']['postcode'] );
		}

		if(!empty($rates)){
			uasort( $rates, array( $this, 'sort_rates' ) );
			foreach ($rates as $key => $rate) {
				if(is_array($rate)){
					$rate['package'] = $package;
				}
				//info @since 1.5.6 Adding shipping rate filter to allow users to modify the shipping price.
				if(isset($rates[$key]['cost'])){
				    $cost = $this->strip_shipping_tax($rates[$key]['cost']);
					$rates[$key]['cost'] = apply_filters('australia_post_shipping_rate', $cost);
				}
				$this->add_rate($rates[$key]);
			}
		}


	}


	/** sort rates based on cost *
	 *
	 * @param $a
	 * @param $b
	 *
	 * @return int
	 */
	public function sort_rates( $a, $b ) {
		if ( $a['cost'] == $b['cost'] ) return 0;
		return ( $a['cost'] < $b['cost'] ) ? -1 : 1;
	}

	private function get_rates( $old_rates, $weight, $height, $width, $length, $destination )
	{

		if (!$this->enabled_services) {
			return [];
		}

		$rates = [];
		$query_params['from_postcode'] = $this->shop_post_code;
		$query_params['to_postcode'] = $destination;
		$query_params['length'] = $length;
		$query_params['width'] = $width;
		$query_params['height'] = $height;
		$query_params['weight'] = $weight;



		foreach($this->enabled_services as $service_key):
            $query_params['service_code'] = $service_key;
            $this->debug('Packing Request: <pre>' . print_r($this->postageParcelURL.'?'.http_build_query($query_params), true) . '</pre>');

            $response = wp_remote_get( $this->postageParcelURL.'?'.http_build_query($query_params),array('headers' => array('AUTH-KEY'=> $this->api_key)));
            if(is_wp_error( $response )){
                return array('error' => 'Unknown Problem. Please Contact the admin');
            }

            $aus_response = json_decode(wp_remote_retrieve_body($response));
            // since 1.4.2 enhancing the debug mode.
            $this->debug('Australia Post RESPONSE: <pre>' . print_r($aus_response, true) . '</pre>');

            if(!isset($aus_response->error) && $aus_response != null){
                $duration = '';
                if($this->show_duration === 'yes'){
                    $duration = ' ('. $aus_response->postage_result->delivery_time .')';
                }

                $old_rate = (isset($old_rates[$service_key]['cost']))?$old_rates[$service_key]['cost']:0;
                // add the rate if the API request succeeded

                $rates[$service_key] = array(
                        'id' => $this->id . '_' . $service_key,
                        'label' => $this->title. ' ' . $aus_response->postage_result->service.' ' . $duration,
                        'cost' =>  ($aus_response->postage_result->total_cost ) + $old_rate,

                );

            // if the API returned any error, show it to the user
            }else{
                return array('error' => $aus_response->error->errorMessage);

            }
        endforeach;

		return $rates;
	}
    /**
     * get_package_details function.
     *
     * @access private
     * @param mixed $package
     * @return mixed
     */
    private function get_package_details( $package )
    {
	    $max_weight = $this->get_max_weight($package);
    	$weight   = 0;
    	$volume   = 0;
    	$value    = 0;
    	$products = [];
    	// Get weight of order
    	foreach ( $package['contents'] as $item_id => $values ) {
    	    /** @var WC_Product $_product */
            $_product = $values['data'];

            if (wc_get_weight($_product->get_weight(), 'kg') > $max_weight) {
            	return [];
            }

    		$weight += wc_get_weight( (floatval($_product->get_weight())<=0  )?$this->default_weight:$_product->get_weight(), 'kg' ) * $values['quantity'];
    		$value  += $_product->get_price() * $values['quantity'];

    		$length = wc_get_dimension( ($_product->get_length()=='')?$this->default_length:$_product->get_length(), 'cm' );
    		$height = wc_get_dimension( ($_product->get_height()=='')?$this->default_height:$_product->get_height(), 'cm' );
    		$width = wc_get_dimension( ($_product->get_width()=='')?$this->default_width:$_product->get_width(), 'cm' );

            if ($length > 105 || $width > 105 || $height > 105) {
                return [];
            }

            $min_dimension = $this->get_min_dimension($length, $width, $height);
		    $products[] = array('weight'=> wc_get_weight( (floatval($_product->get_weight())<=0  )?$this->default_weight:$_product->get_weight(), 'kg' ),
    							'quantity'=> $values['quantity'],
    							'length'=> $length,
    							'height'=> $height,
    							'width'=> $width,
    							'item_id'=> $item_id,
                                'min_dimension' => $min_dimension
    						);
    		$volume += ( $length * $height * $width );
    	}


        $pack = array();
        $packs_count = 1;
        $pack[$packs_count]['weight'] = 0;
        $pack[$packs_count]['length'] = 0;
        $pack[$packs_count]['height'] = 0;
        $pack[$packs_count]['width'] = 0;
        foreach ($products as $product) {
            while ($product['quantity'] != 0) {
                if(!isset($pack[$packs_count]['weight'])){
                    $pack[$packs_count]['weight'] = 0;
                }
                $pack[$packs_count]['weight'] += $product['weight'];
	            $pack[$packs_count]['length'] = ('length' == $product['min_dimension']) ? $pack[$packs_count]['length'] + $product['length'] : $product['length'];
	            $pack[$packs_count]['width'] = ('width' == $product['min_dimension']) ? $pack[$packs_count]['width'] + $product['width'] : $product['width'];
	            $pack[$packs_count]['height'] = ('height' == $product['min_dimension']) ? $pack[$packs_count]['height'] + $product['height'] : $product['height'];
                $pack[$packs_count]['item_id'] =  $product['item_id'];

                if(
					$this->cubicMeters($pack[$packs_count]) > 0.25 ||
                    $pack[$packs_count]['weight'] > $max_weight ||
                    $pack[$packs_count]['length'] > 105 ||
                    $pack[$packs_count]['width'] > 105 ||
                    $pack[$packs_count]['height'] > 105
                ){
	                $pack[$packs_count]['length'] = ('length' == $product['min_dimension']) ? $pack[$packs_count]['length'] - $product['length'] : $product['length'];
	                $pack[$packs_count]['height'] = ('height' == $product['min_dimension']) ? $pack[$packs_count]['height'] - $product['height'] : $product['height'];
	                $pack[$packs_count]['width'] = ('width' == $product['min_dimension']) ? $pack[$packs_count]['width'] - $product['width'] : $product['width'];

	                $pack[$packs_count]['weight'] -=  $product['weight'];
                    $packs_count++;
                    $pack[$packs_count]['weight'] = $product['weight'];
                    $pack[$packs_count]['length'] =1;
                    $pack[$packs_count]['height'] =1;
                    $pack[$packs_count]['width'] =1;
                    $pack[$packs_count]['item_id'] =  $product['item_id'];

                }
                $product['quantity']--;
            }
        }

    	return $pack;
    }


	/**
	 * @param $package
	 *
	 * @return float|int
	 */
	private function get_max_weight( $package){
    	return ( $package['destination']['country'] == 'AU' )? 22:20;
    }

	/**
	 * get_min_dimension function.
	 * get the minimum dimension of the package, so we multiply it with the quantity
	 * @access private
	 * @param number $width
	 * @param number $length
	 * @param number $height
	 * @return string $result
	 */
	private function get_min_dimension($length, $width, $height)
	{
		$dimensions = array('width' => $width, 'length' => $length, 'height' => $height);
		$result = array_keys($dimensions, min($dimensions));
		return $result[0];
	}

	/**
	 * Output a message
	 *
	 * @param $message
	 * @param string $type
	 */
	public function debug($message, $type = 'notice') {
		if ($this->debug_mode == 'yes' && current_user_can('manage_options')) {
		    wc_add_notice($message, $type);
		}
	}

	/**
	 * @param $rate_cost
	 *
	 * @return float
	 */
	private function strip_shipping_tax($rate_cost){

		if ( 'yes' !== $this->enable_stripping_tax) {
			return $rate_cost;
		}
		return $rate_cost / 1.1;
	}


		/**
		 * get_package_details_by_boxpacker function.
		 *
		 * @access private
		 * @param mixed $package
		 * @return mixed
		 */
		private function get_package_details_by_boxpacker($package)
		{
			$default_length = ($this->default_length > 0)?$this->default_length:1;
			$default_width = ($this->default_width > 0)?$this->default_width:1;
			$default_height = ($this->default_height > 0)?$this->default_height:1;

			$packer = new Packer();
            $packer->setMaxBoxesToBalanceWeight(0);
			foreach ($this->add_boxes_iterations() as $box) {
				$packer->addBox($box);
			}

			// Get weight of order
			foreach ($package['contents'] as $item_id => $values) {
				/** @var WC_Product $_product */
				$_product = $values['data'];
				if ($_product->is_virtual()) {
					continue;
				}

				$weight = wc_get_weight((floatval($_product->get_weight()) <= 0) ? $this->default_weight : $_product->get_weight(), 'g');
				$length = wc_get_dimension((floatval($_product->get_length()) <= 0) ? $default_length : $_product->get_length(), 'mm');
				$height = wc_get_dimension((floatval($_product->get_height()) <= 0) ? $default_height : $_product->get_height(), 'mm');
				$width = wc_get_dimension((floatval($_product->get_width()) <= 0) ? $default_width : $_product->get_width(), 'mm');
				//adding the packer code
				//2. adding items
					$item = (new WPRuby_AusPost_Item())
					->setLength($length)
					->setWidth($width)
					->setDepth($height)
					->setWeight($weight)
					->setDescription($_product->get_name())->setKeepFlat(false);
					$packer->addItem($item, intval($values['quantity']));
				//end of the packer code
			}
			//adding the packer code
			//3. packing
			try {
				$packedBoxes = $packer->pack();
			} catch (\Exception $e) {
				return false;
			}

			$pack = array();
			$packs_count = 1;
			$pack[$packs_count]['weight'] = 0;
			$pack[$packs_count]['length'] = 0;
			$pack[$packs_count]['height'] = 0;
			$pack[$packs_count]['width'] = 0;
			$pack[$packs_count]['quantity'] = 0;
			/** @var PackedBox $packedBox */
			foreach ($packedBoxes as $packedBox) {
				/** @var Box $boxType */
				$pack[$packs_count]['name'] = $packedBox->getBox()->getReference();
				$pack[$packs_count]['weight'] = $packedBox->getWeight() / 1000;
				$pack[$packs_count]['length'] = $packedBox->getUsedLength() / 10;
				$pack[$packs_count]['width'] =  $packedBox->getUsedWidth() / 10;
				$pack[$packs_count]['height'] = $packedBox->getUsedDepth() / 10;
				$pack[$packs_count]['quantity'] = count($packedBox->getItems()->asArray());
				$pack[$packs_count]['postcode'] = $package['destination']['postcode'];
				$packs_count++;
			}
			return $pack;
		}

	/**
	 * @return array
	 */
	private function add_boxes_iterations()
	{
        $boxesData = include "includes/boxes.php";
        $boxes = [];
        foreach ($boxesData as $box) {
            $boxes[] = (new WPRuby_AusPost_Box())
                ->setEmptyWeight(0)
                ->setInnerDepth($box['inner_height'])
                ->setInnerLength($box['inner_length'])
                ->setInnerWidth($box['inner_width'])
                ->setMaxWeight($box['max_weight'])
                ->setOuterLength($box['outer_length'])
                ->setOuterWidth($box['outer_width'])
                ->setOuterDepth($box['outer_height'])
                ->setReference($box['name']);
            }
        return $boxes;
	}

	private function cubicMeters($pack) {
		return round(($pack['length'] * $pack['width'] * $pack['height']) / 1000000, 2);
	}

}

