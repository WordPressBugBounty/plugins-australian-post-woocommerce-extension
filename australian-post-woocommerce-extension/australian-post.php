<?php
/* @wordpress-plugin
 * Plugin Name:       Australia Post WooCommerce Extension
 * Plugin URI:        https://wpruby.com/plugin/australia-post-woocommerce-extension-pro?utm_source=aupost-lite&utm_medium=pluginuri&utm_campaign=freetopro
 * Description:       WooCommerce Australia Post Shipping Method.
 * Version:           1.10.9
 * WC requires at least: 3.0
 * WC tested up to: 9.4
 * Author:            WPRuby
 * Author URI:        https://wpruby.com
 * Text Domain:       australian-post
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/WPRuby/woocommerce-australian-post-extension
 */

namespace WPRubyAustraliaPost;


define('AUSPOST_LITE_URL', plugin_dir_url(__FILE__));
define('AUSPOST_LITE_DIR', plugin_dir_path(__FILE__));

require_once( dirname( __FILE__ ) . '/includes/autoload.php' );



/**
 *
 * @since 1.5.6
 *
 */
class WPRuby_Australia_Post_Lite{

	public function __construct(){
		//info: if the Pro version active, deactivate it first.
		if ($this->is_plugin_active('woocommerce-australia-post-extension-pro/class-australian-post.php')) {
				add_action('admin_init', array($this, 'deactivate_pro_version'));
		}
		//info: Only add the shipping method actions only if WooCommerce is activated.
		if($this->is_plugin_active('woocommerce/woocommerce.php')){
			add_filter('woocommerce_shipping_methods', array($this, 'add_australia_post_method'));
			add_action('woocommerce_shipping_init', array($this, 'init_australian_post'));

		}

		add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array($this, 'plugin_action_links') );

	}

	// deactivate the pro version
	public function deactivate_pro_version() {
	  deactivate_plugins( 'woocommerce-australia-post-extension-pro/class-australian-post.php' );
	}

	public function add_australia_post_method( $methods ){
		$methods['auspost'] = WC_Australian_Post_Shipping_Method::class;
		return $methods;
	}

	public function init_australian_post()
	{
		require_once( dirname( __FILE__ ) . '/class-australian-post.php' );
	}

	private function is_plugin_active($slug){
		$active_plugins = (array) get_option( 'active_plugins', array() );
		if ( is_multisite() )
			$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
		return in_array( $slug, $active_plugins ) || array_key_exists( $slug, $active_plugins );
	}

	public function plugin_action_links( $links ) {
	   $links[] = '<a href="https://wpruby.com/plugin/australia-post-woocommerce-extension-pro?utm_source=aupost-lite&utm_medium=pluginspage&utm_campaign=freetopro" target="_blank">Get the Pro version</a>';
	   $links[] = '<a href="https://wpruby.com/submit-ticket/" target="_blank">Support</a>';
	   return $links;
	}

}

add_action( 'before_woocommerce_init', function() {
    if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
    }
} );

new WPRuby_Australia_Post_Lite();
