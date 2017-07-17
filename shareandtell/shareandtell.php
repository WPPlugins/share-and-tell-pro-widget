<?php
/*
Plugin Name: Share And Tell Pro
Plugin URI: http://pro.shareandtell.com
Description: Harness the power of word-of-mouth marketing over popular social networks. Sign up for Share and Tell Pro at http://pro.shareandtell.com
Version: 1.04
Author: Shareandtell
Author URI: http://shareandtell.com
*/
add_option("shareandtell_application_id", 185);
add_option("shareandtell_color_id", 1);
add_option("shareandtell_btn_id", 1);
add_option("shareandtell_async", true);
add_option("shareandtell_style", "side");

// only add the option if it doesn't already exist, otherwise sat server is hit on every widget load to provide the js
if(get_option("shareandtell_script") == null){
  add_option("shareandtell_script", shareandtell_js_from_sat_server());
}


add_action('get_footer', 'shareandtell_output_js');

// return the script stored as an option
function shareandtell_js_from_option(){
  return get_option("shareandtell_script");
}

// get the javascript from the sat server
function shareandtell_js_from_sat_server(){
  $host = "http://www.shareandtell.com";

  $async = get_option("shareandtell_async");
  $appid = get_option("shareandtell_application_id");
  $btnid = get_option("shareandtell_btn_id");
  $colorid = get_option("shareandtell_color_id");
  $style = get_option("shareandtell_style");

  if($async == "true"){
    $type = "async";
  }else{
    $type = "simple";
  }

  return file_get_contents($host."/widget/widget_javascript?color_id=".$colorid."&app_id=".$appid."&btnid=".$btnid."&style=".$style."&type=".$type);
}

// echo the javascript onto the page
function shareandtell_output_js(){
  echo shareandtell_js_from_option();
  return;
}

// update the script stored in the options
function shareandtell_update_script_option(){
  update_option("shareandtell_script", shareandtell_js_from_sat_server());
  return;
}

// Build the settings menu
add_action('admin_menu', 'shareandtell_menu');

function shareandtell_menu(){
  add_submenu_page('options-general.php', 'Edit ShareAndTell Plugin', 'ShareAndTell Plugin', 'administrator', 'shareandtell-plugin-edit', 'shareandtell_options');
  add_action('admin_init', 'register_options');
}
function register_options(){
  register_setting('shareandtell-plugin-settings', 'shareandtell_application_id');
  register_setting('shareandtell-plugin-settings', 'shareandtell_color_id');
  register_setting('shareandtell-plugin-settings', 'shareandtell_btn_id');
  register_setting('shareandtell-plugin-settings', 'shareandtell_async');
  register_setting('shareandtell-plugin-settings', 'shareandtell_style');
}
function shareandtell_options(){
  // update the javascript on the sat options page
  shareandtell_update_script_option();
  ?>
  <div class="wrap">
    <h2>Share and Tell Plugin Settings</h2>
    <form method='POST' action='options.php'>
      <?php settings_fields('shareandtell-plugin-settings'); ?>
      <style>
        .sat_settings_desc{
          font-size:8pt;
          color:#aaa
        }
        .sat_table_tr{
          border-bottom:1px solid #ddd;
        }
      </style>
      <table class='form-table'>
          <tr valign='top' class="sat_table_tr">
            <th scope='row'>Application ID</th>
            <td><input type='text' name='shareandtell_application_id' value='<?php echo get_option('shareandtell_application_id'); ?>' /></td>
            <td class="sat_settings_desc">Get your application ID from your customer contact.  They should be able to provide this number to you after setting you up in the Share and Tell system.  It is unique to your product / website and will let the widget know what product to show when a user clicks it.</td>
          </tr>
          <tr valign='top' class="sat_table_tr">
            <th scope='row'>Color ID</th>
            <td><input type='text' name='shareandtell_color_id' value='<?php echo get_option('shareandtell_color_id'); ?>' /></td>
            <td class="sat_settings_desc">An up-to-date list of all the color schemes can be found here <a href="http://www.shareandtell.com/color_schemes/index">ShareAndTell Color Schemes</a>.</td>
          </tr>
          <tr valign='top' class="sat_table_tr">
            <th scope='row'>Button ID</th>
            <td><input type='text' name='shareandtell_btn_id' value='<?php echo get_option('shareandtell_btn_id'); ?>' /></td>
            <td class="sat_settings_desc">This should be set to the value 1 in most cases. The only time it should ever be something other than the value of 1 is if you wanted to have more than one widget on a single page, and in that case you would set the ID to 2 for the 2nd widget on the page, 3 for the 3rd, etc.  So, if you want one widget on multiple pages, the button ID is always 1.  If you want multiple widgets on one page, the ID of each widget has to be different from the others on that page.</td>
          </tr>
          <tr valign='top' class="sat_table_tr">
            <th scope='row'>Script Type</th>
            <td>
              <?php if(get_option("shareandtell_async") == "true"){ ?>
                <input type="radio" name="shareandtell_async" value="true" checked> Asynchronous<br/>
                <input type="radio" name="shareandtell_async" value="false"> Simple
              <?php } else { ?>
                <input type="radio" name="shareandtell_async" value="true"> Asynchronous<br/>
                <input type="radio" name="shareandtell_async" value="false" checked> Simple
              <?php } ?>
            </td>
            <td class="sat_settings_desc">We offer an asynchronous version of our widget so that installation of the widget JavaScript file will occur at the same time that the rest of your website is loading.  This will allow you to install the widget without slowing down the load time of your website. If you are having problems getting the widget to work, try using the simple synchronous solution.</td>
          </tr>
          <tr valign='top' class="sat_table_tr">
            <th scope='row'>Position</th>
            <td>
              <?php if(get_option("shareandtell_style") == "bottom"){ ?>
                <input type="radio" name="shareandtell_style" value="bottom" checked> Bottom<br/>
                <input type="radio" name="shareandtell_style" value="side"> Left Side
              <?php } else { ?>
                <input type="radio" name="shareandtell_style" value="bottom"> Bottom<br/>
                <input type="radio" name="shareandtell_style" value="side" checked> Left Side
              <?php } ?>
            </td>
            <td class="sat_settings_desc">Position of the widget on your website.</td>
          </tr>
      </table>
      <p class='submit'>
          <input type='submit' class='button-primary' value='<?php _e('Save Changes') ?>' />
      </p>
    </form>
  </div>
  <?php
}

?>