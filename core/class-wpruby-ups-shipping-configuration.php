<?php
namespace WPRuby_Ups\Core;

use WC_Shipping_Method;

class WPRuby_UPS_Shipping_Configuration extends WC_Shipping_Method {

	private $live_mode;
	private $ups_user_id;
	private $ups_password;
	private $ups_access_key;
	private $ups_account_number;
	private $ups_customer_classification;
	private $debug_mode;

	private $customer_classification_options = [
		    'NA' => 'Default',
		    '00' => 'Rates Associated with Shipper Number',
		    '01' => 'Daily Rates',
		    '04' => 'Retail Rates',
		    '05' => 'Regional Rates',
		    '06' => 'General List Rates',
		    '53' => 'Standard List Rates',
    ];

	public function __construct()
    {
		$this->id = 'ups_config';
		$this->method_title = __('UPS (Configuration)','wpruby-ups-shipping-method');
		$this->title = __('UPS (Configuration)','wpruby-ups-shipping-method');

		$this->init_form_fields();
		$this->init_settings();

	    $this->live_mode = $this->get_option('live_mode');
		$this->ups_user_id = $this->get_option('ups_user_id');
		$this->ups_password = $this->get_option('ups_password');
		$this->ups_access_key = $this->get_option('ups_access_key');
		$this->ups_account_number = $this->get_option('ups_account_number');
		$this->ups_customer_classification = $this->get_option('ups_customer_classification');
		$this->debug_mode = $this->get_option('debug_mode');

		add_action('woocommerce_update_options_shipping_'.$this->id, [$this, 'process_admin_options']);

	}

	public function init_form_fields()
    {
		$this->form_fields = [
			'live_mode' => [
				'title' 	=> __('Integration Mode', 'wpruby-ups-shipping-method'),
				'type' 		=> 'select',
				'css' 		=> 'width:50%;',
				'default' 	=> 'test',
				'options' 	=> ['live' => 'Live', 'test' => 'Test'],
			],
			'ups_access_key' => [
				'title'             => __( 'UPS Access Key', 'wpruby-ups-shipping-method' ),
				'type'              => 'text',
				'description'       => __( 'In order to get Access Key, please follow this <a target="_blank" href="https://wpruby.com/knowledgebase/how-to-get-the-ups-access-key/">short guide.</a>', 'wpruby-ups-shipping-method' ),
				'default'           => $this->ups_access_key
			],
			'ups_user_id' => [
				'title'             => __( 'UPS User ID', 'wpruby-ups-shipping-method' ),
				'type'              => 'text',
				'default'           => $this->ups_user_id
			],
			'ups_password' => [
				'title'             => __( 'UPS Password', 'wpruby-ups-shipping-method' ),
				'type'              => 'password',
				'default'           => $this->ups_password
			],
			'ups_account_number' => [
				'title'             => __( 'UPS Account Number', 'wpruby-ups-shipping-method' ),
				'type'              => 'text',
				'description'       => __( 'This was sent to you by email after signup', 'wpruby-ups-shipping-method' ),
				'default'           => $this->ups_account_number
			],
			'ups_customer_classification' => [
				'title'   => __('Customer Classification', 'wpruby-ups-shipping-method'),
				'type' 	  => 'select',
				'css' 	  => 'width:50%;',
				'class'   => 'availability wc-enhanced-select',
				'default' => '06',
				'options' => $this->customer_classification_options,
			],
			'debug_mode' => [
				'title' 		=> __( 'Enable Debug Mode', 'wpruby-ups-shipping-method'),
				'type' 			=> 'checkbox',
				'label' 		=> __( 'Enable ', 'wpruby-ups-shipping-method'),
				'default' 		=> 'no',
				'description'	=> __('If debug mode is enabled, the shipping method will be activated just for the administrator.'),
			],
		];
	}

	public function is_available( $package ) {
		return false;
	}

	/**
	 * Admin Panel Options
	 * - Options for bits like 'title' and availability on a country-by-country basis
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function admin_options()
	{
		?>
		<h3><?php _e('UPS Settings', 'wpruby-ups-shipping-method'); ?></h3>
		<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-2">
				<div id="post-body-content">
					<table class="form-table">
						<?php echo $this->get_admin_options_html(); ?>
					</table><!--/.form-table-->
				</div>
				<div id="postbox-container-1" class="postbox-container">
					<div id="side-sortables" class="meta-box-sortables ui-sortable">
						<div class="postbox shipping-class">
							<h3 class="hndle"><span><i class="fa fa-question-circle"></i>&nbsp;&nbsp;How it works!</span></h3>
                            <hr>
                            <div class="inside">
								<div class="support-widget">
									<p>
										After adding configuration information in this page, please assign <code>UPS Shipping</code> method to a
                                    <a href="<?php echo admin_url('admin.php?page=wc-settings&tab=shipping&section'); ?>">shipping zone</a> and continue setting up other options there for each zone that you like to use UPS with.
									</p>
								</div>
							</div>
						</div>
                        <div class="postbox ">
                            <h3 class="hndle"><span><i class="fa fa-question-circle"></i>&nbsp;&nbsp;Plugin Support</span></h3>
                            <hr>
                            <div class="inside">
                                <div class="support-widget">
                                    <p>
                                        <img style="width:100%;" src="https://wpruby.com/wp-content/uploads/2016/03/wpruby_logo_with_ruby_color-300x88.png">
                                        <br/>
                                        Got a Question, Idea, Problem or Praise?</p>
                                    <ul>
                                        <li>» <a href="https://wpruby.com/knowledgebase/how-to-get-the-ups-access-key/" target="_blank">How to get the UPS Access Key?</a></li>
                                        <li>» <a href="https://wpruby.com/knowledgebase/ups-global-settings/" target="_blank">UPS Global Settings</a></li>
                                        <li>» <a href="https://wpruby.com/knowledgebase/ups-zone-settings/" target="_blank">UPS Zone Settings</a></li>
                                        <li>» <a href="https://wpruby.com/submit-ticket/" target="_blank">Support Request</a></li>
                                        <li>» <a href="https://wpruby.com/knowledgebase_category/woocommerce-ups-shipping-method-pro/" target="_blank">Documentation and Common issues</a></li>
                                        <li>» <a href="https://wpruby.com/plugins/" target="_blank">Our Plugins Shop</a></li>
                                        <li>» If you like the plugin please leave us a <a target="_blank" href="https://wordpress.org/plugins/shipping-method-for-ups-and-wc/?filter=5#postform">★★★★★</a> rating.</li>
                                    </ul>

                                </div>
                            </div>
                        </div>
					</div>
				</div>
			</div>
		</div>
		<div class="clear"></div>
		<style type="text/css">
			#postbox-container-1 .shipping-class{
				background: #ffba00;
				color:#ffffe0;
			}
		</style>
		<?php
	}
}
