<?php
/*
Plugin Name: [CR]PostWarning
Plugin URI: http://bayu.freelancer.web.id/oss/crpost-warning/
Description: Plugin that show warning / reminder message whenever publish / update button pushed
Author: Arief Bayu Purwanto
Version: 0.0.2
Author URI: http://bayu.freelancer.web.id/
*/



add_action('admin_head', 'cr_postwarning_admin_hook');

function cr_postwarning_admin_hook(){

	$uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : NULL ;

	$message = false;

	if ($uri AND strpos($uri,'edit.php'))
	{
		$message = true;
	}
	elseif ($uri AND strpos($uri,'post-new.php'))
	{
		$message = true;
	}

	elseif ($uri AND strpos($uri,'post.php'))
	{
		$message = true;
	}

	if ($message)
	{
?>
<script language="javascript" type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#post').submit(function() {
		//alert('Handler for .submit() called.');
		//return false;
		var cfm = confirm("<?php echo cr_postwarning_get_fixed_warning_message(); ?>");
		if(cfm)
		{
			return true;
		}
		
		jQuery('#ajax-loading').hide();
		jQuery('#publish').removeClass('button-primary-disabled');
		return false;
	});
});
</script>
<?php
	}
}

add_action('admin_menu', 'cr_postwarning_config_admin');
function cr_postwarning_config_admin()
{
	add_submenu_page( 'options-general.php', '[CR]Post Warning', '[CR]Post Warning', 8, 'cr_postwarning_config_admin_form', 'cr_postwarning_config_admin_form');
}

function cr_postwarning_config_admin_form()
{
?>
<div class="wrap">
<h2>[CR]Post Warning</h2>
<p>Here you can set your custom message on post publish / update.</p>
<form method="post" action="options.php">
<?php wp_nonce_field('update-options'); ?>



<h4>Warning Message</h4>
<textarea name="cr_postwarning__custom_message" style="width: 80%; height: 100px;"><?php echo get_option('cr_postwarning__custom_message'); ?></textarea>
<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />

<input type="hidden" name="action" value="update" />
<input type="hidden" name="page_options" value="cr_postwarning__custom_message" />
</p>

</form>
</div>
<?php
}


function cr_postwarning_get_fixed_warning_message()
{
	$warning_message = get_option('cr_postwarning__custom_message');
	
	$array_broken = array("\r\n", "\t", "\n", "\x82");
	$array_fixed = array("\\n", "\\t", "\\n", "\\n");
	
	return str_replace($array_broken, $array_fixed, $warning_message);
	
}

function cr_postwarning_del_bad_chars($text)
{
	$arrText = explode("\n", $text);
	foreach ($arrText as $key => $value)
	{
		$arrText[$key] = preg_replace("/[^\x20-\x7e]/", "", $value);
	}
	$text = implode("\n", $arrText);
	
	return $text;
}
