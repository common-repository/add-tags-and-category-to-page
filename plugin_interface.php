<?php
// create custom plugin settings menu
add_action('admin_menu', 'tcp_create_menu');

function tcp_create_menu() {
	add_options_page('Add Tags And Category', 'Add Tags And Category', 'administrator', __FILE__, 'tcp_settings_page');
}

function tcp_settings_page() {
	include('includes/tcp_header.php');
	include('includes/tcp_post_type_select.php');
	include('includes/tcp_footer.php');
}

add_action('admin_notices', 'wpa_notifications');
if (isset($_GET['wpa_hide_notifications']) == 1){
    update_option('wpa_hide_notifications','yes_13');
}

if (!function_exists('wpa_notifications')){
	function wpa_notifications(){
	    if (get_option('wpa_hide_notifications') != 'yes_13'){
	        if (!is_plugin_active('honeypot/wp-armour.php')){
	            $wpa_supported_plugins   =  array(
	                    'gravityforms.php' => 'Gravity Forms',
	                    'bbpress.php'       => 'BBPress Forum',
	                    'wp-contact-form-7.php'=> 'Contact Form 7',
	                    'ninja-forms.php' => 'Ninja Forms',
	                    'wpforms.php' => 'WP Forms',
	                    'formidable.php' => 'Formidable Forms'
	            );

	            $active_plugins = get_option('active_plugins');
	            foreach ($active_plugins as $key => $plugin) {
	                $pluginNameExplode = explode('/', $plugin);
	                $pluginnames[$pluginNameExplode[1]]       = $pluginNameExplode[1];
	            }
	            
	            $supportedActivePlugins = array_intersect_key($wpa_supported_plugins, $pluginnames);

	            if (!empty($supportedActivePlugins )){

	                if (get_option('users_can_register') == 1 ){
	                    $supportedActivePlugins['wpregister'] = 'Registration';
	                }

	                if (get_option('default_comment_status') == 'open' ){
	                    $supportedActivePlugins['wpcomments'] = 'Comments';
	                }
	                
	                $plugin_href = admin_url('plugin-install.php?tab=plugin-information&plugin=honeypot&TB_iframe=true&width=800&height=500');
	                $plugin_install_url = admin_url('plugin-install.php?s=WP+Armour&tab=search');

	                 echo '<div class="notice notice-info" style="">
	                        <p>Are you getting Spam in <strong>'.join(", ",$supportedActivePlugins).'</strong> ? With <a class="thickbox" href="'.$plugin_href.'">WP Armour</a> plugin you can block spam without using Captcha or Akismet.</p>
	                        <p style="font-weight:bold;">
	                            <a href="'.$plugin_install_url.'">Install WP Armour</a> | 
	                            <a class="thickbox" href="'.$plugin_href.'">View Details</a> | 
	                            <a href="?wpa_hide_notifications=1">Hide Message</a></p>
	                 </div>';
	            }
	        }
	    }
	}
}