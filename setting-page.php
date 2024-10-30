<?php

$options = get_option('ips_options');

$header_script = $options && isset($options['header_script'])?$options['header_script']:'';
$footer_script = $options && isset($options['footer_script'])?$options['footer_script']:'';

$order_received_header_script = $options && isset($options['or_header_script'])?$options['or_header_script']:'';
$order_received_footer_script = $options && isset($options['or_footer_script'])?$options['or_footer_script']:'';

?>

<div class="wrap">
    <h2>In Page Script Options</h2>
    <form method="post" action="options.php">
        <?php settings_fields('ips_options_group'); ?>

        <div class="ips-general">
            <p>These scripts will be displayed on all pages/posts.</p>
            <p>
                <label><?php echo __('Header Script', IPS_DOMAIN) ?></label>
                <textarea name="ips_options[header_script]" class="widefat" cols="20" rows="10"><?php echo htmlentities($header_script) ?></textarea>
            </p>
            <p>
                <label><?php echo __('Footer Script', IPS_DOMAIN) ?></label>
                <textarea name="ips_options[footer_script]" class="widefat" cols="20" rows="10"><?php echo htmlentities($footer_script) ?></textarea>
            </p>
        </div>

        <div class="ips-woocommerce" style="<?php echo class_exists('Woocommerce')?'':'display:none;' ?>">
            <h2>Order Received Page</h2>
            <p>These scripts will be displayed on order received page, after the order completed.</p>
            <p>
                <label><?php echo __('Header Script', IPS_DOMAIN) ?></label>
                <textarea name="ips_options[or_header_script]" class="widefat" cols="20" rows="10"><?php echo htmlentities($order_received_header_script) ?></textarea>
            </p>
            <p>
                <label><?php echo __('Footer Script', IPS_DOMAIN) ?></label>
                <textarea name="ips_options[or_footer_script]" class="widefat" cols="20" rows="10"><?php echo htmlentities($order_received_footer_script) ?></textarea>
            </p>
        </div>

        <p class="submit">
            <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
        </p>
    </form>
</div>