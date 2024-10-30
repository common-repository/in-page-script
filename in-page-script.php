<?php
/*
Plugin Name: In Page Script
Plugin URI: https://wordpress.org/plugins/in-page-script/
Description: This plugin helps to add scripts into the header (before close tag &lt;/HEAD&gt;) or the footer (before close tag &lt;/BODY&gt;).
Author: Phuc Pham
Version: 0.1
Author URI: http://facebook.com/svincoll4
License: GPL2
*/

/*  Copyright 2015  Phuc Pham  (email : svincoll4@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define('IPS_DOMAIN', 'ips');

class In_Page_Script{

    var $post_types = array('page');

    function __construct(){

        add_action('init', array($this, 'init'), 30);

        if(is_admin()){
            add_action( 'admin_init', array($this, 'admin_init'));
            add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
            add_action( 'save_post', array( $this, 'save_post' ) );
            add_action( 'admin_menu', array($this, 'admin_menu'));
        }else{
            add_action('wp_head', array($this, 'print_header_script'), 30);
            add_action('wp_footer', array($this, 'print_footer_script'), 30);
        }
    }

    function init(){
        $this->post_types = apply_filters('ips_post_type', $this->post_types);
    }

    function admin_init(){
        register_setting( 'ips_options_group', 'ips_options' );
    }

    function admin_menu(){
        add_options_page('In Page Script', 'In Page Script', 'manage_options', 'in-page-script', array($this, 'setting_page'));

    }

    function setting_page(){
        include 'setting-page.php';
    }

    function add_meta_box($post_type){

        if ( in_array( $post_type, $this->post_types )) {
            add_meta_box(
                'ips_meta_box'
                ,__( 'In Page Script', IPS_DOMAIN )
                ,array( $this, 'render_meta_box_content' )
                ,$post_type
                ,'advanced'
                ,'low'
            );
        }
    }

    function render_meta_box_content($post){
        // Add an nonce field so we can check for it later.
        wp_nonce_field( 'ips_action', 'ips_nonce' );

        // Use get_post_meta to retrieve an existing value from the database.
        $header_script = get_post_meta( $post->ID, '_header_script', true );
        $footer_script = get_post_meta( $post->ID, '_footer_script', true );

        ?>

        <p>
            <label><?php echo __('Header Script', IPS_DOMAIN) ?></label>
            <textarea name="ips[header_script]" class="widefat" cols="20" rows="10"><?php echo htmlentities($header_script) ?></textarea>
        </p>
        <p>
            <label><?php echo __('Footer Script', IPS_DOMAIN) ?></label>
            <textarea name="ips[footer_script]" class="widefat" cols="20" rows="10"><?php echo htmlentities($footer_script) ?></textarea>
        </p>

        <?php
    }

    function save_post($post_id){

        // Check if our nonce is set.
        if ( ! isset( $_POST['ips_nonce'] ) )
            return $post_id;

        $nonce = $_POST['ips_nonce'];

        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $nonce, 'ips_action' ) )
            return $post_id;

        // If this is an autosave, our form has not been submitted,
        //     so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
            return $post_id;


        // Check the user's permissions.
        if ( 'page' == $_POST['post_type'] ) {

            if ( ! current_user_can( 'edit_page', $post_id ) )
                return $post_id;

        } else {

            if ( ! current_user_can( 'edit_post', $post_id ) )
                return $post_id;
        }

        /* OK, its safe for us to save the data now. */

        // Sanitize the user input.
        $data = $_POST['ips'];

        // Update the meta field.
        update_post_meta( $post_id, '_header_script', $data['header_script']);
        update_post_meta( $post_id, '_footer_script', $data['footer_script']);
    }

    function print_header_script(){
        global $post;

        $header_script = '';

        $options = get_option('ips_options');
        if($options){

            if(isset($options['header_script']) && apply_filters('ips_allow_header_script', true)){
                echo $options['header_script'];
            }

            if(isset($options['or_header_script']) && function_exists('is_wc_endpoint_url')){
                if(is_wc_endpoint_url('order-received')){
                    echo $options['or_header_script'];
                }
            }

        }


        if(is_singular($this->post_types)){
            $header_script = get_post_meta($post->ID, '_header_script', true);
        }elseif(in_array('page', $this->post_types)){
            $post_id = false;
            if(is_front_page()){
                $post_id = get_option('page_on_front');
            }elseif(is_home()){
                $post_id = get_option('page_for_posts');
            }

            if($post_id){
                $header_script = get_post_meta($post_id, '_header_script', true);
            }
        }

        if($header_script){
            echo $header_script;
        }
    }

    function print_footer_script(){
        global $post;

        wp_reset_postdata();

        $footer_script = '';

        if(is_singular($this->post_types)){
            $footer_script = get_post_meta($post->ID, '_footer_script', true);
        }elseif(in_array('page', $this->post_types)){
            $post_id = false;
            if(is_front_page()){
                $post_id = get_option('page_on_front');
            }elseif(is_home()){
                $post_id = get_option('page_for_posts');
            }

            if($post_id){
                $footer_script = get_post_meta($post_id, '_footer_script', true);
            }
        }

        if($footer_script){
            echo $footer_script;
        }

        $options = get_option('ips_options');
        if($options){
            if(isset($options['or_footer_script']) && function_exists('is_wc_endpoint_url')){
                if(is_wc_endpoint_url('order-received')){
                    echo $options['or_footer_script'];
                }
            }

            if(isset($options['footer_script']) && apply_filters('ips_allow_footer_script', true)){
                echo $options['footer_script'];
            }


        }

    }
}

new In_Page_Script();