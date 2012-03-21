<?php 
/*
Plugin Name: CJ Custom Content
Plugin URI: http://www.shibulijack.wordpress.com/cj-custom-content
Version: 2.0
Author: <a href="http://www.shibulijack.wordpress.com/">Shibu Lijack</a> a.k.a <strong>CyberJack</strong>
Description: A Simple wp plugin to <strong>add custom content</strong> to wordpress pages. Custom html code can be added to the header, footer, body of all posts & pages, top and bottom of posts. With this plugin installed, it is very easy to insert scripts such as analytics track code, social networking sites integration snippet etc… via <a href="options-general.php?page=cj-custom-content.php">CJ Custom Content Settings</a> page.

  Copyright 2012 Shibu Lijack (email: shibulijack@gmail.com)

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

if (!function_exists("cjPluginSeriesContent_ap")) 
{ 
function cjPluginSeriesContent_ap() {
global $cj_pluginSeries_content;
if (!isset($cj_pluginSeries_content)) {
return; }
if (function_exists('add_options_page')) 
{
add_options_page('CJ Custom Content | Shibu Lijack', 'CJ Custom Content', 9, basename(__FILE__), array(&$cj_pluginSeries_content, 'printAdminPage')); }
} 
}

if (!class_exists("cjPluginSeriesContent")) 
{ 
class cjPluginSeriesContent
{
	var $adminOptionsName = "cjPluginSeriesContentAdminOptions";
	function cjPluginSeriesContent() { 
		}
	function cjPluginSeriesContent_settings_link($links) {
	$links[] = '<a href="'.admin_url('options-general.php?page=cj-custom-content').'">'.__('Settings', 'CJ Custom Content').'</a>';
	return $links;
	}
	function addHeaderCode() { 
	$head = get_option($this->adminOptionsName);
	$opt_head = stripslashes($head['h_code']);
	$condition = $head['show_header'];
	if($condition=='true')
	{
		
		echo($opt_head);
	?>
	<!-- CJ Custom Header Code -->
	<?php }
	}
	function addFooterCode() { 
	$foot = get_option($this->adminOptionsName);
	$opt_foot = stripslashes($foot['f_code']);
	$condition = $foot['show_footer'];
	if($condition=='true')
	{
		echo($opt_foot);
	?>
	<!-- CJ Custom Footer Code -->
	<?php }
	}
	//HTML body code
	function addContent($content = '') {
	$new = get_option($this->adminOptionsName);
	$opt_value = $new['content'];
	$condition = $new['add_content'];
	if($condition=='true')
		$content .= $opt_value; 
	return $content; 
	}
	function addContentPostB($content = '') {
	if (is_single())
	{
	$postb = get_option($this->adminOptionsName);
	$opt_postb = $postb['pb_code'];
	$condition = $postb['show_postb'];
	if($condition=='true')
		$content .= $opt_postb; }
	return $content; 
	}
	function addContentPostT($content = '') {
	if (is_single())
	{
	$postt = get_option($this->adminOptionsName);
	$opt_postt = $postt['pt_code'];
	$condition = $postt['show_postt'];
	if($condition=='true')
		$content = $opt_postt.$content;
		 }
	return $content; 
	}
	function authorUpperCase($author = '') {
	return strtoupper($author);
	}
	//Admin options
	function getAdminOptions() {
	$cjAdminOptions = array('show_header' => 'true', 'show_footer' => 'true', 'show_postb' => 'true', 'show_postt' => 'true', 'add_content' => 'true', 'comment_author' => 'true', 'content' => '' , 'h_code' => '' , 'f_code' => '' , 'pb_code' => '' , 'pt_code' => '');
	$cjOptions = get_option($this->adminOptionsName); 
	if (!empty($cjOptions)) {
	foreach ($cjOptions as $key => $option) $cjAdminOptions[$key] = $option;
	}
	update_option($this->adminOptionsName, $cjAdminOptions); return $cjAdminOptions;
	}
	function init() 
	{ 
	$this->getAdminOptions();
	}
	
	//Print the admin page
	function printAdminPage() {
	$cjOptions = $this->getAdminOptions();
	if (isset($_POST['update_cjPluginSeriesContentSettings'])) 
	{ 
	if (isset($_POST['cjHeader'])) { $cjOptions['show_header'] = $_POST['cjHeader']; }
	if (isset($_POST['cjFooter'])) { $cjOptions['show_footer'] = $_POST['cjFooter']; }
	if (isset($_POST['cjPostT'])) { $cjOptions['show_postt'] = $_POST['cjPostT']; }
	if (isset($_POST['cjPostB'])) { $cjOptions['show_postb'] = $_POST['cjPostB']; }
	if (isset($_POST['cjAddContent'])) { $cjOptions['add_content'] = $_POST['cjAddContent']; }
	if (isset($_POST['cjAuthor'])) { $cjOptions['comment_author'] = $_POST['cjAuthor']; }
	if (isset($_POST['cjContent'])) { $cjOptions['content'] = apply_filters('content_save_pre',$_POST['cjContent']); }
	if (isset($_POST['cjHeadContent'])) { $cjOptions['h_code'] = apply_filters('content_save_pre',$_POST['cjHeadContent']); }
	if (isset($_POST['cjFootContent'])) { $cjOptions['f_code'] = apply_filters('content_save_pre',$_POST['cjFootContent']); }
	if (isset($_POST['cjContentPostT'])) { $cjOptions['pt_code'] = apply_filters('content_save_pre',$_POST['cjContentPostT']); }
	if (isset($_POST['cjContentPostB'])) { $cjOptions['pb_code'] = apply_filters('content_save_pre',$_POST['cjContentPostB']); }
	update_option($this->adminOptionsName, $cjOptions);
	?>
	<div class="updated"><p><strong><?php _e("Settings Updated.","cjPluginSeriesContent");?></strong></p></div>
	<?php } ?>
<div class=wrap>
<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
<?php screen_icon( 'plugins' ); ?><h2>CJ Custom Content</h2>
<br><a href="http://shibulijack.wordpress.com/2012/02/03/cj-custom-content/" target="_blank" class="button-secondary">Visit Plugin Page</a> <a href="https://www.facebook.com/pages/CJ-Custom-Content-wp-Plugin/354642587888416" target="_blank" class="button-secondary">Facebook</a><br>
<br>Plugin developed by <a href="mailto:shibulijack@gmail.com">Shibu Lijack</a></br>
<br>

<table class="widefat">
<thead>
<tr>
<th  >Custom Header Content:</th>
<th>Custom Footer Content:</th>
</tr></thead>
<tr>
<td width="50%"  ><textarea name="cjHeadContent" style="width: 90%; height: 100px;"><?php
_e(apply_filters('format_to_edit',stripslashes($cjOptions['h_code'])),
'cjPluginSeriesContent') ?></textarea><p>Places the code between < head > and < /head ><br><i>Example: Google Analytics Tracking Code</i></p></td>
<td width="50%"><textarea name="cjFootContent" style="width: 90%; height: 100px;"><?php
_e(apply_filters('format_to_edit',stripslashes($cjOptions['f_code'])),
'cjPluginSeriesContent') ?></textarea>
<p>Places the code just above < /body ><br><i>Example: Social Networking sites integration code</i></p></td>
</tr>
<tr><td  ><h3>Enable Custom Header?</h3>
<p><label for="cjHeader_yes"><input type="radio"
id="cjHeader_yes" name="cjHeader" value="true" <?php if ($cjOptions['show_header'] == "true") { _e('checked="checked"', "cjPluginSeriesContent"); }?> /> Yes</label>&nbsp;&nbsp;&nbsp;&nbsp;<label for="cjHeader_no"><input type="radio" id="cjHeader_no" name="cjHeader" value="false" <?php if ($cjOptions['show_header'] == "false") { _e('checked="checked"', "cjPluginSeriesContent"); }?>/> No</label></p>
<p><i>Selecting "No" will disable the custom code inserted in the header</i></p><br></td>
<td><h3>Enable Custom Footer?</h3>
<p><label for="cjFooter_yes"><input type="radio"
id="cjFooter_yes" name="cjFooter" value="true" <?php if ($cjOptions['show_footer'] == "true") { _e('checked="checked"', "cjPluginSeriesContent"); }?> /> Yes</label>&nbsp;&nbsp;&nbsp;&nbsp;<label for="cjFooter_no"><input type="radio" id="cjHeader_no" name="cjFooter" value="false" <?php if ($cjOptions['show_footer'] == "false") { _e('checked="checked"', "cjPluginSeriesContent"); }?>/> No</label></p>
<p><i>Selecting "No" will disable the custom code inserted in the footer</i></p><br></td>
</tr>
</table><br>
<br>

<table class="widefat">
<thead>
<th colspan="2">Custom Body Content:</th>
</thead>
<tr>
<td width="50%"><textarea name="cjContent" style="width: 90%; height: 100px;"><?php
_e(apply_filters('format_to_edit',$cjOptions['content']),
'cjPluginSeriesContent') ?></textarea>
<p>Places the code between < body > and < /body > in all the pages and posts.</p></td>
<td width="50%"><h4>Enable Custom Page/Post Content?</h4>
<p><label for="cjAddContent_yes"><input type="radio" id="cjAddContent_yes" name="cjAddContent" value="true" <?php if ($cjOptions['add_content'] == "true") { _e('checked="checked"', "cjPluginSeriesContent"); }?> /> Yes</label>&nbsp;&nbsp;&nbsp;&nbsp;<label for="cjAddContent_no"><input type="radio" id="cjAddContent_no" name="cjAddContent" value="false" <?php if ($cjOptions['add_content'] == "false") { _e('checked="checked"', "cjPluginSeriesContent"); }?>/> No</label></p>
<p><i>Selecting "No" will disable the content from being added into the end of every post/page.</i></p><br></td>
</tr>
</table><br>
<br>

<table class="widefat">
<thead>
<tr><th  >Custom Post Content[TOP]:</th>
<th>Custom Post Content[BOTTOM]:</th></tr></thead>
<tr>
<td width="50%"  ><textarea name="cjContentPostT" style="width: 90%; height: 100px;"><?php
_e(apply_filters('format_to_edit',$cjOptions['pt_code']),
'cjPluginSeriesContent') ?></textarea>
<p>Places the code at the <strong>top</strong> of every single post but not on pages.</p></td>
<td width="50%"><textarea name="cjContentPostB" style="width: 90%; height: 100px;"><?php
_e(apply_filters('format_to_edit',$cjOptions['pb_code']),
'cjPluginSeriesContent') ?></textarea>
<p>Places the code at the <strong>bottom</strong> of every single post but not on pages.</p>
</td>
</tr>
<tr>
<td  ><h4>Enable Custom Post Content?</h4>
<p><label for="cjAddContentPT_yes"><input type="radio" id="cjAddContentPT_yes" name="cjPostT" value="true" <?php if ($cjOptions['show_postt'] == "true") { _e('checked="checked"', "cjPluginSeriesContent"); }?> /> Yes</label>&nbsp;&nbsp;&nbsp;&nbsp;<label for="cjAddContentPT_no"><input type="radio" id="cjAddContentPT_no" name="cjPostT" value="false" <?php if ($cjOptions['show_postt'] == "false") { _e('checked="checked"', "cjPluginSeriesContent"); }?>/> No</label></p>
<p><i>Selecting "No" will disable the content from being added into the top of every post.</i></p><br></td>
<td><h4>Enable Custom Post Content?</h4>
<p><label for="cjAddContentPB_yes"><input type="radio" id="cjAddContentPB_yes" name="cjPostB" value="true" <?php if ($cjOptions['show_postb'] == "true") { _e('checked="checked"', "cjPluginSeriesContent"); }?> /> Yes</label>&nbsp;&nbsp;&nbsp;&nbsp;<label for="cjAddContentPB_no"><input type="radio" id="cjAddContentPB_no" name="cjPostB" value="false" <?php if ($cjOptions['show_postb'] == "false") { _e('checked="checked"', "cjPluginSeriesContent"); }?>/> No</label></p>
<p><i>Selecting "No" will disable the content from being added into the bottom of every post/page.</i></p><br></td>
</tr>
</table>

<div class="submit">
<input type="submit" class="button-primary" name="update_cjPluginSeriesContentSettings" value="<?php _e('Update Settings', 'cjPluginSeriesContent') ?>" /></div></form></div>
<?php
 }
}
} 

if (class_exists("cjPluginSeriesContent")) 
{ 
	$cj_pluginSeries_content = new cjPluginSeriesContent();
}

//Actions and Filters
if (isset($cj_pluginSeries_content)) {
add_action('activate_cj/cj-custom-content.php', array(&$cj_pluginSeries_content, 'init'));
add_action('admin_menu', 'cjPluginSeriesContent_ap');
add_action('wp_head', array(&$cj_pluginSeries_content, 'addHeaderCode'), 1);
add_action('wp_footer', array(&$cj_pluginSeries_content, 'addFooterCode'), 1);
add_filter('the_content', array(&$cj_pluginSeries_content, 'addContent'));
add_filter('the_content', array(&$cj_pluginSeries_content, 'addContentPostB'));
add_filter('the_content', array(&$cj_pluginSeries_content, 'addContentPostT'));
add_filter('plugin_action_links_'.plugin_basename(__FILE__), array(&$cj_pluginSeries_content, 'cjPluginSeriesContent_settings_link'), 10, 1);
}?>