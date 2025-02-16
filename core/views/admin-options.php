<h3><?php _e('UPS Settings', 'woocommerce');?></h3>
<?php if ($this->debug_mode == 'yes'): ?>
	<div class="updated woocommerce-message">
		<p><?php _e('UPS debug mode is activated, only administrators can use it.', 'wpruby-ups-shipping-method');?></p>
	</div>
<?php endif;?>
<div id="poststuff">
	<div id="post-body" class="metabox-holder columns-2">
		<div id="post-body-content">
			<table class="form-table">
				<?php echo $this->get_admin_options_html(); ?>
			</table><!--/.form-table-->
		</div>
        <div id="postbox-container-1" class="postbox-container">
            <div id="side-sortables" class="meta-box-sortables ui-sortable">
                <div class="postbox ">
                    <h3 class="hndle"><span><i class="dashicons dashicons-update"></i>&nbsp;&nbsp;Upgrade to Pro</span></h3>
                    <hr>
                    <div class="inside">
                        <div class="support-widget">
                            <ul>
                                <li>» SurePost® Options</li>
                                <li>» Negotiated Rates</li>
                                <li>» Packages Insurance</li>
                                <li>» Handling Fees and Discounts</li>
                                <li>» Custom Boxes</li>
                                <li>» Auto Hassle-Free Updates</li>
                                <li>» High Priority Customer Support</li>
                            </ul>
                            <a href="https://wpruby.com/plugin/woocommerce-ups-shipping-method-pro/?utm_source=aupost-lite&utm_medium=widget&utm_campaign=freetopro" class="button wpruby_button" target="_blank"><span class="dashicons dashicons-star-filled"></span> Upgrade Now</a>
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

                <div class="postbox rss-postbox">
                    <h3 class="hndle"><span><i class="fa fa-wordpress"></i>&nbsp;&nbsp;WPRuby Blog</span></h3>
                    <hr>
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
    .wpruby_button{
        background-color:#4CAF50 !important;
        border-color:#4CAF50 !important;
        color:#ffffff !important;
        width:100%;
        padding:5px !important;
        text-align:center;
        height:35px !important;
        font-size:12pt !important;
        line-height: 22px !important;
    }
</style>


