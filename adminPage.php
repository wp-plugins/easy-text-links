<?php

/*
  Copyright (C) 2008 www.ads-ez.com

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

// if translating, print translation interface
if ($this->ezTran->printAdminPage()) {
  return;
}
require_once($this->plgDir . '/myPlugins.php');
$slug = $this->slug;
$plgURL = $this->plgURL;
$plg = $this->myPlugins[$slug];
require_once($this->plgDir . '/EzAdmin.php');
$this->ezAdmin = new EzAdmin($plg, $slug, $plgURL);
$this->ezAdmin->domain = $this->domain;
$ez = $this->ezAdmin;

$this->handleSubmits();
echo $this->adminMsg;
$adminProdTable = $this->renderTable($this->options['products'], "adminProdTable");
$adminLinkTable = $this->renderTable($this->options['links'], "adminLinkTable");
echo <<<EOF1
<div id="loading">
  <p><img src="{$this->plgURL}/loading.gif" alt="loading"/> Please Wait. Loading...</p>
</div>
{$this->linkToolBar}
{$this->prodToolBar}
{$this->popUp}
<link rel='stylesheet' id='ezTextLinksCSS-css'  href='{$this->plgURL}/ezLinks.css' type='text/css' media='all' />
<div class="wrap" id="ezlWrap" style="width:1000px;">
<h2>Easy Text Links Setup </h2>
<form method="post" action='{$_SERVER["REQUEST_URI"]}'>
<table>
<tr><td>
<!--  Help Info here -->
<ul style="padding-left:10px;list-style-type:circle; list-style-position:inside;" >
<li>
<a href="#" title="Click for help" onclick="TagToTip('help0',WIDTH, 450, TITLE, 'What is this plugn for', STICKY, 1, CLOSEBTN, true, CLICKCLOSE, true, FIX, [this, 15, 5])">
What is this plugin for?
</a>
</li>
<li>
<a href="#" title="Click for help" onclick="TagToTip('help1',WIDTH, 450, TITLE, 'How to use this plugin', STICKY, 1, CLOSEBTN, true, CLICKCLOSE, true, FIX, [this, 15, 5])">
How to use this plugin?
</a>
</li>
<li>
<a href="#" title="Click for help" onclick="TagToTip('help2',WIDTH, 450, TITLE, 'List of shortcodes', STICKY, 1, CLOSEBTN, true, CLICKCLOSE, true, FIX, [this, 15, 5])">
How to use the shortcodes with this plugin?
</a>
</li>
<li>
<a href="#" title="Click for help" onclick="TagToTip('help3',WIDTH, 450, TITLE, 'How to modify a sold link or a link package', STICKY, 1, CLOSEBTN, true, CLICKCLOSE, true, FIX, [this, 15, 5])">
How to modify a sold link or a link package?
</a>
</li>
</ul>
</td>
EOF1;
@include ($this->plgDir . '/head-text.php');
echo <<<EOF2
</tr>
<tr><th scope="row" colspan=3>
Link Packages
</th></tr>
<tr>
<td colspan="3">
$adminProdTable
</td>
</tr>
<tr><th scope="row" colspan=3>
Sold Links
</th></tr>
<tr>
<td colspan="3">
$adminLinkTable
</td>
</tr>
<tr>
<td colspan="3">
<table style="width:100%;padding:10px;">
<tr>
<th scope="row" style="width:50%">
Email Template
</th>
<th scope="row" style="width:50%">
Other Options
</th>
</tr>
<tr>
  <td>
    <small>These UPPERCSE words will be replaced by their values:<br /> BLOG, EXPIRY</small><br />
    <textarea id="emailTemplate" name="emailTemplate" style="width:95%;height:70px;">{$this->options['emailTemplate']}
    </textarea>
  </td>
  <td>
    Title for "Advertise Here" invite:<br />
    <input id="adHereTitle" name="adHereTitle" style="width:95%;"
      value="{$this->options['adHereTitle']}" />
     "Advertise Here" Target:<br />
    <input id="adHereURL" name="adHereURL" style="width:95%;"
      value="{$this->options['adHereURL']}" />
    "Advertise Here" Anchor Text:<br />
    <input id="adHereTemplate" name="adHereTemplate" style="width:95%;"
      value="{$this->options['adHereTemplate']}" />
  </td>
</tr>
</table>
</td>
</tr>
</table>
<div class="submit">
<input type="submit" name="update_ezTextLinks" value="Save Changes" title="Save the changes as specified above" onmouseover="Tip('Save the changes as specified above',WIDTH, 240, TITLE, 'Save Changes')" onmouseout="UnTip()"/>
<input type="submit" id="resetOptions" name="reset_ezTextLinks" value="Reset to Defaults" title="Discard all the options and reset them to the default values" onmouseover="TagToTip('reset',WIDTH, 240, TITLE, 'DANGER!', BGCOLOR, '#ffcccc', FONTCOLOR, '#800000',BORDERCOLOR, '#c00000')" onmouseout="UnTip()" />
</div>
</form>
<hr />
<!-- Help text for ToolTips -->
<div id="help0" style='display:none;'>
This plugin helps you manage your text link ads, which are links you place on your blog in exchange for money. If you have a popular blog, text links can potentially bring in much more revenue than any contextual advertising. In my case, the difference was easily a factor of 100. <br/><br/>Keep in mind that most contextual ad providers (such as AdSense) do not like paid link placement, for obvious reasons. Your blog may get penalized in terms page rank or search listing placement. Please weigh the pros and cons carefully before embarking on text link advertising. If you do decide to go for it, this plugin will be able to help.
</div>
<div id="help1" style='display:none;'>
This plugin uses shortcodes to place your links and your "Advertise Here" invitation wherever you want on your blog. Please see the <a href='http://wordpress.org/extend/plugins/easy-text-links/faq/' target='_blank'>FAQ at WordPress</a> for details. A quick guide with all the shortcode comibinations and keywords is in the next help item.<br /><br />You may want to create an enticing "Advertise Here" post or page. Include in this post or page the shortcodes `[ezlink packages]` to list your link packages and rates. Once done, please specify its URL as the target for your "Advertise Here" link (under <b>Other Optons</b> below). You can also change the anchor text from its default "Advertise Here" to something more compelling. You can then use the short code `[ezlink invite]` anywhere on your blog where you want to display an "Advertise Here" invitation.
</div>
<div id="help2" style='display:none;'>
Please use the following shortcode and keywords to display your links on your pages and posts.
<ol>
<li>List packages (within <code>&lt;ul&gt;...&lt;/ul&gt;</code>) : <code>[ezlink packages]</code>.</li>
<li>List all links (within <code>&lt;ul&gt;...&lt;/ul&gt;</code>) : <code>[ezlink links]</code></li>
<li>List specific links (within <code>&lt;ul&gt;...&lt;/ul&gt;</code>) : <code>[ezlink links=link1,link2,link3...]</code>.</li>
<li>Suppress <code>&lt;ul&gt;&lt;li&gt;...&lt;/li&gt;&lt;/ul&gt;</code> around any of the lists above : <code>[ezlink option=nolist ...]</code></li>
<li>Advertise Here display : <code>[ezlink invite]</code> or <code>[ezlink advertise]</code> or <code>[ezlink here]</code></li>
</ol>
</div>
<div id="help3" style='display:none;'>
Hover over any line item in a row on the tables below. You will get a toolbar to modify the line item. Depending on whether you are pointing to a Link Package or a Sold Link, you will get different options.<br /><br />You can also edit a sold link where it is displayed on your blog, provided you are logged in and have the right access privileges.
</div>
<span id="reset" style='display:none;'>
<span style="font-color=#f00;">This <b>Reset Options</b> button discards all your changes and loads the default options. This is your only warning!</span><br />
<b>Discard all your changes and load defaults. (Are you quite sure?)</b></span>
EOF2;
echo "<form method='post'>";
$ez->renderTranslator();
echo "</form><br />";
echo '<div style="background-color:#fcf;padding:5px;border: solid 1px;margin:5px;">';
$ez->renderSupport();
echo '</div>';
include ($this->plgDir . '/tail-text.php');
echo <<<EOF3
<h3>Credits</h3>
<table style="width:100%;padding:10px;">
<tr><td>
<ul style="padding-left:10px;list-style-type:circle; list-style-position:inside;" >
<li>
<b>Easy Text Links</b> uses the excellent Javascript/DHTML tooltips by <a href="http://www.walterzorn.com" target="_blank" title="Javascript, DTML Tooltips"> Walter Zorn</a>.
</li>
<li>
It also uses the Must Have Icons by <a href="http://www.visualpharm.com" target="_blank" title="Must Have Icons">Visual Pharm</a>.
</li>
</ul>
</td>
</tr>
</table>
</div>
EOF3;
