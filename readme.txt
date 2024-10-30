=== In Page Script ===
Contributors: svincoll4
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=QZUJY7DR3JYWS&lc=VN&item_name=In%20Page%20Script%20Plugin&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
Tags: header script, footer script, google analytics script, adwords script
Requires at least: 3.0.1
Tested up to: 4.3
Stable tag: 0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin helps to add scripts into the header (before close tag &lt;/HEAD&gt;) or the footer (before close tag &lt;/BODY&gt;).

== Description ==

This plugin helps to add scripts into the header (before close tag &lt;/HEAD&gt;) or the footer (before close tag &lt;/BODY&gt;).

* Add header/footer scripts for all pages
* Add header/footer scripts for individual page
* Add header/footer scripts for Order Received page (Woocommerce)
* Allows filter to support custom post types


Although this plugin has been created to add these scripts only, there is no limit to add other things on the page such as META tags, STYLESHEET/CSS tags, HTML tags...

== Installation ==

1. Download and unzip the plugin into your WordPress plugins directory (usually /wp-content/plugins/).
2. Activate the plugin through the 'Plugins' menu in your WordPress Admin.
3. Edit pages to input the header/footer script. You can also input the script under Settings -> In Page Script.
4. Support Woocommerce Order Received page.


== Frequently Asked Questions ==

= How to use this plugin for custom post types =

You may want to use this filter from functions.php: **ips_post_type**

For ex:

    add_action('init', 'init_ips_plugin');
    function init_ips_plugin(){
        add_filter('ips_post_type', 'filter_ips_post_type');
    }
    function filter_ips_post_type($post_types){
        // replace custom_post_type with yours
        return array('page', 'custom_post_type');
    }



== Screenshots ==

1. Screenshot

== Changelog ==

= 0.1 =
* Add header/footer scripts for all pages
* Add header/footer scripts for individual page
* Add header/footer scripts for Order Received page (Woocommerce)
* Allows filter to support custom post types

== Upgrade Notice ==

Nothing.