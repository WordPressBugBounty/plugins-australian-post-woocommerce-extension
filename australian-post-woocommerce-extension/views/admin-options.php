<?php
// Check if the notice should be shown (not dismissed within the last 2 weeks)
$current_user_id = get_current_user_id();
$dismissed_time = get_user_meta($current_user_id, 'rulehook_promo_dismissed', true);
$show_rulehook_notice = empty($dismissed_time) || (time() - intval($dismissed_time) > 14 * DAY_IN_SECONDS);
?>


<h3><?php _e('Australia Post Settings', 'woocommerce');?></h3>
<?php if ($this->debug_mode == 'yes'): ?>
    <div class="updated woocommerce-message">
        <p><?php _e('Australia Post debug mode is activated, only administrators can use it.', 'australian-post');?></p>
    </div>
<?php endif;?>

<!-- New RuleHook Promotion Banner -->
<?php if ($show_rulehook_notice): ?>
    <div class="notice notice-info rulehook-promo is-dismissible" id="rulehook-promo-notice">
        <div class="rulehook-promo-content">
            <span class="rulehook-icon">🚀</span>
            <div class="rulehook-message">
                <strong><?php _e('New from WPRuby', 'australian-post'); ?></strong>
                <p><?php _e('Meet RuleHook — create flexible shipping rules in Shopify and WooCommerce by weight, postcode, cart total, and product tags. No code required.', 'australian-post'); ?></p>
            </div>
            <a href="https://rulehook.com/woocommerce?utm_source=auspost-plugin&utm_medium=plugin-banner&utm_campaign=cross-promo" class="button rulehook-button" target="_blank"><?php _e('Learn More →', 'australian-post'); ?></a>
        </div>
    </div>
<?php endif; ?>


<div id="poststuff">
    <div id="post-body" class="metabox-holder columns-2">
        <div id="post-body-content">
            <table class="form-table">
                <?php echo $this->get_admin_options_html();?>
            </table><!--/.form-table-->
            <p class="submit">
                <button name="save" disabled="" class="woocommerce-save-button components-button is-primary" type="submit" value="Save changes">Save changes</button>
            </p>

        </div>
        <div id="postbox-container-1" class="postbox-container">
            <div id="side-sortables" class="meta-box-sortables ui-sortable">

                <!-- New RuleHook Promo Widget -->
                <div class="postbox rulehook-widget">
                    <h3 class="hndle"><span class="dashicons dashicons-superhero"></span> <?php _e('Introducing RuleHook', 'australian-post'); ?></h3>
                    <div class="inside">
                        <div class="rulehook-widget-content">
                            <div class="rulehook-logo">
                                <span class="rulehook-emoji">🚀</span>
                                <h4><?php _e('RuleHook', 'australian-post'); ?></h4>
                            </div>
                            <p><?php _e('Create flexible shipping rules in Shopify and WooCommerce by:', 'australian-post'); ?></p>
                            <ul class="rulehook-features">
                                <li><span class="dashicons dashicons-yes-alt"></span> <?php _e('Weight & dimensions', 'australian-post'); ?></li>
                                <li><span class="dashicons dashicons-yes-alt"></span> <?php _e('Postcodes & regions', 'australian-post'); ?></li>
                                <li><span class="dashicons dashicons-yes-alt"></span> <?php _e('Cart total', 'australian-post'); ?></li>
                                <li><span class="dashicons dashicons-yes-alt"></span> <?php _e('Product tags & categories', 'australian-post'); ?></li>
                            </ul>
                            <p class="rulehook-tagline"><?php _e('No code required!', 'australian-post'); ?></p>
                            <a href="https://rulehook.com/woocommerce?utm_source=auspost-plugin&utm_medium=plugin-widget&utm_campaign=cross-promo" class="button rulehook-widget-button" target="_blank"><?php _e('Learn More →', 'australian-post'); ?></a>
                        </div>
                    </div>
                </div>

                <div class="postbox">
                    <h3 class="hndle"><span class="dashicons dashicons-star-filled"></span> <?php _e('Upgrade to Pro', 'australian-post'); ?></h3>
                    <div class="inside">
                        <div class="support-widget">
                            <ul class="feature-list">
                                <li><span class="dashicons dashicons-yes"></span> MyPost Business Support <span class="new-feature">NEW</span></li>
                                <li><span class="dashicons dashicons-yes"></span> eParcel Support <span class="new-feature">NEW</span></li>
                                <li><span class="dashicons dashicons-yes"></span> International Shipping</li>
                                <li><span class="dashicons dashicons-yes"></span> Customizable Domestic Shipping</li>
                                <li><span class="dashicons dashicons-yes"></span> Pre-Paid Domestic Satchels</li>
                                <li><span class="dashicons dashicons-yes"></span> Letters Shipping</li>
                                <li><span class="dashicons dashicons-yes"></span> Courier Shipping</li>
                                <li><span class="dashicons dashicons-yes"></span> Handling Fees and Discounts</li>
                                <li><span class="dashicons dashicons-yes"></span> Extra Cover</li>
                                <li><span class="dashicons dashicons-yes"></span> Signature On Delivery</li>
                                <li><span class="dashicons dashicons-yes"></span> Display the Cheapest option</li>
                                <li><span class="dashicons dashicons-yes"></span> Fallback Price</li>
                                <li><span class="dashicons dashicons-yes"></span> Renaming Shipping Options</li>
                                <li><span class="dashicons dashicons-yes"></span> Custom Boxes</li>
                                <li><span class="dashicons dashicons-yes"></span> Australia Post Tracking</li>
                                <li><span class="dashicons dashicons-yes"></span> Contracted Prices</li>
                                <li><span class="dashicons dashicons-yes"></span> Label Printing</li>
                                <li><span class="dashicons dashicons-yes"></span> Live Tracking</li>
                                <li><span class="dashicons dashicons-yes"></span> Auto Hassle-Free Updates</li>
                                <li><span class="dashicons dashicons-yes"></span> High Priority Customer Support</li>
                            </ul>
                            <a href="https://wpruby.com/plugin/australia-post-woocommerce-extension-pro?utm_source=aupost-lite&utm_medium=widget&utm_campaign=freetopro" class="button wpruby_button" target="_blank"><span class="dashicons dashicons-star-filled"></span> <?php _e('Upgrade Now', 'australian-post'); ?></a>
                        </div>
                    </div>
                </div>
                <div class="postbox">
                    <h3 class="hndle"><span class="dashicons dashicons-editor-help"></span> <?php _e('Plugin Support', 'australian-post'); ?></h3>
                    <div class="inside">
                        <div class="support-widget">
                            <div class="wpruby-logo">
                                <img src="https://cdn.wpruby.com/wp-content/uploads/2024/12/01122141/wpruby-logo-wide-1.png" alt="WPRuby Logo">
                            </div>
                            <p><?php _e('Got a Question, Idea, Problem or Praise?', 'australian-post'); ?></p>
                            <ul class="support-links">
                                <li><span class="dashicons dashicons-admin-site"></span> <a target="_blank" href="http://auspost.com.au/parcels-mail/size-and-weight-guidelines.html"><?php _e('Weight and Size Guidelines', 'australian-post'); ?></a></li>
                                <li><span class="dashicons dashicons-sos"></span> <a href="https://wpruby.com/submit-ticket/" target="_blank"><?php _e('Support Request', 'australian-post'); ?></a></li>
                                <li><span class="dashicons dashicons-book"></span> <a href="https://wpruby.com/knowledgebase_category/woocommerce-australia-post-shipping-method-pro/" target="_blank"><?php _e('Documentation and Common issues', 'australian-post'); ?></a></li>
                                <li><span class="dashicons dashicons-cart"></span> <a href="https://wpruby.com/plugins/" target="_blank"><?php _e('Our Plugins Shop', 'australian-post'); ?></a></li>
                                <li><span class="dashicons dashicons-star-filled"></span> <span><?php _e('If you like the plugin please leave us a', 'australian-post'); ?> <a style="display: inline" target="_blank" href="https://wordpress.org/support/view/plugin-reviews/australian-post-woocommerce-extension?filter=5#postform"><?php _e('★★★★★', 'australian-post'); ?></a> <?php _e('rating.', 'australian-post'); ?></span></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="postbox">
                    <h3 class="hndle"><span class="dashicons dashicons-wordpress"></span> <?php _e('WPRuby Blog', 'australian-post'); ?></h3>
                    <div class="inside">
                        <div class="rss-widget">
                            <?php
                            wp_widget_rss_output(array(
                                    'url' => 'https://wpruby.com/feed/',
                                    'title' => 'WPRuby Blog',
                                    'items' => 3,
                                    'show_summary' => 0,
                                    'show_author' => 0,
                                    'show_date' => 1,
                            ));
                            ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<div class="clear"></div>

<style type="text/css">
    /* General Styles */
    #poststuff .postbox {
        border-radius: 5px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e5e5e5;
        margin-bottom: 20px;
    }
    #poststuff .postbox .hndle {
        border-bottom: 1px solid #eee;
        padding: 12px 15px;
        display: flex;
        align-items: center;
    }
    #poststuff .postbox .hndle span.dashicons {
        margin-right: 8px;
        color: #0073aa;
    }
    #poststuff .postbox .inside {
        padding: 15px;
    }

    /* RuleHook Banner Promotion */
    .rulehook-promo {
        border-left-color: #2271b1;
        padding: 0;
        margin: 20px 0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .rulehook-promo-content {
        display: flex;
        align-items: center;
        padding: 15px;
    }
    .rulehook-icon {
        font-size: 24px;
        margin-right: 15px;
    }
    .rulehook-message {
        flex: 1;
    }
    .rulehook-message p {
        margin: 5px 0 0;
    }
    .rulehook-button {
        background-color: #2271b1 !important;
        color: #ffffff !important;
        border-color: #2271b1 !important;
        text-decoration: none;
        padding: 5px 12px;
        font-weight: 500;
        margin-left: 15px;
        white-space: nowrap;
    }
    .rulehook-button:hover {
        background-color: #135e96 !important;
        border-color: #135e96 !important;
    }

    /* RuleHook Widget */
    .rulehook-widget {
        border-left: 4px solid #2271b1;
    }
    .rulehook-widget .hndle span.dashicons {
        color: #2271b1;
    }
    .rulehook-widget-content {
        text-align: center;
    }
    .rulehook-logo {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
    }
    .rulehook-emoji {
        font-size: 24px;
        margin-right: 8px;
    }
    .rulehook-logo h4 {
        margin: 0;
        font-size: 18px;
        color: #2271b1;
    }
    .rulehook-widget-content p {
        margin-top: 0;
        font-size: 14px;
    }
    .rulehook-features {
        text-align: left;
        margin: 15px 0;
        padding: 0;
    }
    .rulehook-features li {
        margin: 0 0 8px;
        padding: 0;
        list-style: none;
        display: flex;
        align-items: center;
    }
    .rulehook-features .dashicons {
        color: #2271b1;
        margin-right: 8px;
    }
    .rulehook-tagline {
        font-weight: bold;
        font-style: italic;
        margin: 15px 0;
    }
    .rulehook-widget-button {
        background-color: #2271b1 !important;
        color: #ffffff !important;
        border-color: #2271b1 !important;
        width: 100%;
        text-align: center;
        padding: 8px 12px !important;
        font-weight: 500;
    }
    .rulehook-widget-button:hover {
        background-color: #135e96 !important;
        border-color: #135e96 !important;
    }

    /* Pro Features List */
    .feature-list {
        margin: 0;
        padding: 0;
    }
    .feature-list li {
        margin: 0 0 10px;
        padding: 0 0 10px;
        border-bottom: 1px solid #f5f5f5;
        list-style: none;
        display: flex;
        align-items: center;
    }
    .feature-list li:last-child {
        border-bottom: 0;
        margin-bottom: 15px;
    }
    .feature-list .dashicons {
        color: #4CAF50;
        margin-right: 8px;
        font-size: 16px;
    }
    .new-feature {
        background-color: #e53935;
        color: white;
        font-size: 10px;
        padding: 2px 6px;
        border-radius: 3px;
        margin-left: 8px;
        text-transform: uppercase;
        font-weight: bold;
    }

    /* Support Widget */
    .wpruby-logo {
        text-align: center;
        margin-bottom: 15px;
    }
    .wpruby-logo img {
        max-width: 180px;
        height: auto;
    }
    .support-widget p {
        text-align: center;
        font-size: 14px;
        margin-bottom: 15px;
        font-weight: 500;
    }
    .support-links {
        margin: 0;
        padding: 0;
    }
    .support-links li {
        margin: 0 0 10px;
        padding: 0 0 10px;
        border-bottom: 1px solid #f5f5f5;
        list-style: none;
        display: flex;
        align-items: center;
    }
    .support-links li:last-child {
        border-bottom: 0;
    }
    .support-links .dashicons {
        color: #0073aa;
        margin-right: 8px;
    }
    .support-links a {
        text-decoration: none;
    }

    /* Upgrade Button */
    .wpruby_button {
        background-color: #4CAF50 !important;
        border-color: #43A047 !important;
        color: #ffffff !important;
        width: 100%;
        padding: 10px !important;
        text-align: center;
        height: auto !important;
        font-size: 14px !important;
        line-height: 1.5 !important;
        font-weight: 600;
        text-transform: uppercase;
        border-radius: 4px;
        margin-top: 10px;
        transition: all 0.2s ease;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .wpruby_button:hover {
        background-color: #43A047 !important;
        box-shadow: 0 3px 8px rgba(0,0,0,0.15);
    }
    .wpruby_button .dashicons {
        margin-right: 5px;
        font-size: 16px;
        line-height: 1.5;
    }

    /* RSS Widget */
    .rss-widget ul li {
        margin-bottom: 12px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f5f5f5;
    }
    .rss-widget ul li:last-child {
        border-bottom: 0;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    .rss-widget a {
        font-weight: 600;
        text-decoration: none;
        font-size: 13px;
    }
    .rss-widget .rss-date {
        display: block;
        color: #777;
        font-size: 12px;
        margin-top: 3px;
    }
</style>


<script type="text/javascript">
    jQuery(document).ready(function($) {
        // Handle the dismiss button click for RuleHook promo
        $(document).on('click', '#rulehook-promo-notice .notice-dismiss', function() {
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'dismiss_rulehook_promo',
                    security: '<?php echo wp_create_nonce('rulehook_dismiss_nonce'); ?>'
                },
                success: function(response) {
                    $('#rulehook-promo-notice').fadeOut(300, function() { $(this).remove(); });
                }
            });
        });

        $('.submit .woocommerce-save-button').eq(1).remove();
    });
</script>


