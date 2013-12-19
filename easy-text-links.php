<?php

/*
  Plugin Name: Easy Text Links
  Plugin URI: http://www.thulasidas.com/plugins/easy-text-links
  Description: <em>Lite Version</em>: Make money from your blog by direct text link ad selling, with no complicated setup and no middlemen.
  Version: 1.60
  Author: Manoj Thulasidas
  Author URI: http://www.thulasidas.com
 */

/*
  License: GPL2 or later
  Copyright (C) 2008 www.thulasidas.com
 */

// namespace EzTextLinks;

if (class_exists("EzTextLinks")) {
  // Another version is probably installed. Ask the user to deactivate it.
  die(__("<strong><em>Easy Text Links:</em></strong> Another version of this plugin is active.<br />Please deactivate it before activating <strong><em>Easy Text Links</em></strong>.", "easy-adsenser"));
}
else {

  class EzlBase {

    var $created, $comment, $status, $statusDate, $plgURL;

    function EzlBase() {
      $this->plgURL = plugins_url(basename(dirname(__FILE__)));
      $this->created = EzTextLinks::mkDateString(time());
      $this->status = 'created';
      $this->statusDate = $this->created;
    }

    function mkDateStrings() {
      $this->created = EzTextLinks::mkDateString($this->created);
      $this->statusDate = EzTextLinks::mkDateString($this->statusDate);
    }

    function set($data) {
      foreach ($data as $k => $v) {
        if ($k == 'txn_id' || $k == 'product_code') {
          $this->{$k} = $this->sanitize($v);
          // TODO: also ensure that it is not duplicated -- probabaly in the caller
        }
        else
          $this->{$k} = stripslashes($v);
      }
    }

    function sanitize($id) {
      $ret = trim($id, " \t\n\r\0\x0B");
      $ret = str_replace(' ', '', $ret);
      return $ret;
    }

  }

  class EzlProduct extends EzlBase {

    var $product_price, $expire_hours, $product_code, $product_name;

    function EzlProduct($code) {
      $this->product_code = $this->sanitize($code);
      parent::EzlBase();
    }

    function id() {
      return $this->product_code;
    }

    function className() {
      return "adminProdTable";
    }

    function type() {
      return "products";
    }

    function add($data = false) {
      if ($data) {
        $product = new self($data['product_code']);
        $product->set($data);
      }
      else {
        $product = new self("NewLinkPackage");
        self::form($formName = 'add', $product);
      }
      return $product;
    }

    function delete() {
      echo "<div id='delete_form'>
        This link package (id={$this->product_code}) will be deleted.
        Really want to do that?<br />
        <button id='delete_confirm' class='toolBar'
        style='background-image:url({$this->plgURL}/confirm.png)'></button>
        </div>";
    }

    function hide() {
      echo "<div id='hide_form'>
        This Link Package (id={$this->product_code}) will be suspended.
        Really want to do that?<br />
        <button id='hide_confirm' class='toolBar'
        style='background-image:url({$this->plgURL}/confirm.png)'></button>
        </div>";
    }

    function edit($formName = 'edit') {
      EzlProduct::form($formName, $this);
    }

    static function form($formName, $product) {
      switch ($formName) {
        case 'edit' :
          $disabled = "";
          $noMod = "disabled='disabled'";
          $button = "<button id='{$formName}_confirm'
            style='background-image:url({$product->plgURL}/confirm.png)'
              class='toolBar'></button>";
          break;
        case 'info' :
          $disabled = "disabled='disabled'";
          $noMod = "disabled='disabled'";
          $button = "";
          break;
        case 'add' :
          $disabled = "";
          $noMod = "";
          $button = "<button id='{$formName}_confirm'
            style='background-image:url({$product->plgURL}/confirm.png)'
              class='toolBar'></button>";
          break;
        default :
          echo "Unknown form name!";
          return;
      }
      $created = EzTextLinks::mkDateString($product->created);
      $statusDate = EzTextLinks::mkDateString($product->statusDate);
      echo "<div id='{$formName}_form'>
        <label for='product_code'>ID:<label>
        <input id='dummy' disabled='disabled' style='height:0px;' />
        <input id='product_code' $noMod value='{$product->product_code}' />
        <label for='created'>Creation Date:</label>
        <input id='created' $noMod value='$created'/>
        <label for='product_name'>Package Name:</label>
        <input id='product_name' $disabled value='{$product->product_name}' />
        <label for='product_price'>Price:</label>
        <input id='product_price' $disabled value='{$product->product_price}' />
        <label for='expire_hours'>Default Expiry (hours):</label>
        <input id='expire_hours' $disabled value='{$product->expire_hours}' />
        <label for='status'>Package Status: (live, deleted or suspended)</label>
        <input id='status' $disabled value='{$product->status}' />
        <label for='statusDate'>Package Status Change Date:</label>
        <input id='statusDate' $noMod value='$statusDate' />
        <label for='comment'>Comment:</label>
        <textarea id='comment' $disabled>{$product->comment}</textarea>
        <br />
        $button
        </div>";
    }

    function getText() {
      if (strtolower($this->status) != 'deleted' &&
              strtolower($this->status) != 'hidden')
        return "<span class='ezlinkProdcut' id='{$this->product_code}'>{$this->product_name} for only {$this->product_price}</span>";
      else
        return "";
    }

    function getCols() {
      $lookup = array(
          'product_code' => 'ID',
          'product_name' => 'Name',
          'product_price' => 'Price',
          'expire_hours' => 'Default Expiry (hours)',
          'status' => 'Status');
      $cols = array();
      foreach ($lookup as $k => $v) {
        $cols[$v] = htmlentities($this->$k);
      }
      return $cols;
    }

  }

  class EzlSale extends EzlBase {

    var $txn_id, $created, $customer_name, $customer_email, $purchase_status,
            $expire_date, $product_code;

    function EzlSale($txnId) {
      $txnId = $this->sanitize($txnId);
      $this->txn_id = $txnId;
      parent::EzlBase();
    }

  }

  class EzlLink extends EzlSale {

    var $text;

    function EzlLink($txnId) {
      parent::EzlSale($txnId);
    }

    function setText($text) {
      // TODO: enforce length, trim to have only one <a>
      $this->text = $text;
    }

    function getText() {
      // TODO: A good place to add nofollow, if needed.
      if (EzTextLinks::mkDateInt($this->expire_date) > time() &&
              strtolower($this->status) != 'deleted' &&
              strtolower($this->status) != 'hidden')
        return "<span class='ezlink' id='{$this->txn_id}'>{$this->text}</span>";
      else
        return "";
    }

    function id() {
      return $this->txn_id;
    }

    function className() {
      return "adminLinkTable";
    }

    function type() {
      return "links";
    }

    function email($message) {
      $to = $this->customer_email;
      $from = get_bloginfo('admin_email');
      $blog = get_bloginfo('name');
      $sub = "Regarding your link on $blog";
      $expiry = EzTextLinks::mkDateString($this->expire_date);
      $message = str_replace(array('BLOG', 'EXPIRY'), array($blog, $expiry), $message);
      echo "<div id='email_form'>
        <label for='to'>To:<label>
        <input id='to' disabled='disabled' value='$to' />
        <label for='from'>From:</label>
        <input id='from' value='$from '/>
        <label for='subject'>Subject:</label>
        <input id='subject' disabled='disabled' value='$sub' />
        <label for='message' style='display:block;'>Message:</label>
        <textarea id='message'>$message
        </textarea>
        <br />
        <button id='email_confirm'
        style='background-image:url({$this->plgURL}/confirm.png)'
        class='toolBar'></button>
        </div>";
    }

    function add($data = false) {
      if ($data) {
        $link = new self($data['txn_id']);
        $link->set($data);
        return $link;
      }
      else {
        $link = new self("NewLink");
        self::form($formName = 'add', $link);
      }
    }

    function delete() {
      echo "<div id='delete_form'>
        This link (id={$this->txn_id}) will be deleted.
        Really want to do that?
        <br />
        <button id='delete_confirm' class='toolBar'
        style='background-image:url({$this->plgURL}/confirm.png)'></button>
        </div>";
    }

    function hide() {
      echo "<div id='hide_form'>
        This link (id={$this->txn_id}) will be hidden.
        Really want to do that?
        <br />
        <button id='hide_confirm' class='toolBar'
        style='background-image:url({$this->plgURL}/confirm.png)'></button>
        </div>";
    }

    function expiry() {
      $expiry = EzTextLinks::mkDateString($this->expire_date);
      echo "<div id='expiry_form'>
        <label for='expire_date'>New Expiry:<label>
        <input id='expire_date' value='$expiry' />
        <br />
        <button id='expiry_confirm'  class='toolBar'
        style='background-image:url({$this->plgURL}/confirm.png)'></button>
        </div>";
    }

    function edit($formName = 'edit') {
      self::form($formName, $this);
    }

    static function form($formName, $link) {
      switch ($formName) {
        case 'edit' :
          $disabled = "";
          $noMod = "disabled='disabled'";
          $button = "<button id='{$formName}_confirm' class='toolBar'
            style='background-image:url({$link->plgURL}/confirm.png)'></button>";
          break;
        case 'info' :
          $disabled = "disabled='disabled'";
          $noMod = "disabled='disabled'";
          $button = "";
          break;
        case 'add' :
          $disabled = "";
          $noMod = "";
          $button = "<button id='{$formName}_confirm' class='toolBar'
            style='background-image:url({$link->plgURL}/confirm.png)'></button>";
          break;
        default :
          echo "Unknown form name!";
          return;
      }
      $expiry = EzTextLinks::mkDateString($link->expire_date);
      $created = EzTextLinks::mkDateString($link->created);
      $statusDate = EzTextLinks::mkDateString($link->statusDate);
      echo "<div id='{$formName}_form'>
        <label for='txn_id'>Link Id:<label>
        <input id='dummy' disabled='disabled' style='height:0px;' />
        <input id='txn_id' $noMod value='{$link->txn_id}' />
        <label for='created'>Creation Date:</label>
        <input id='created' $noMod value='$created'/>
        <label for='customer_name'>Advertiser Name:</label>
        <input id='customer_name' $disabled value='{$link->customer_name}' />
        <label for='customer_email'>Advertiser Email:</label>
        <input id='customer_mail' $disabled value='{$link->customer_email}' />
        <label for='expire_date'>Link Expiry Date:</label>
        <input id='expire_date' $disabled value='$expiry' />
        <label for='text'>Link Text:</label>
        <textarea id='text' $disabled>$link->text</textarea>
        <label for='status'>Link Status:</label>
        <input id='status' $disabled value='{$link->status}' />
        <label for='statusDate'>Link Status Change Date:</label>
        <input id='statusDate' $noMod value='$statusDate' />
        <label for='product_code'>Link Package Code:</label>
        <input id='product_code' $disabled value='{$link->product_code}' />
        <label for='comment' style='display:block;'>Comment:</label>
        <textarea id='comment' $disabled>{$link->comment}</textarea>
        <br />
        $button
        </div>";
    }

    function getCols() {
      $lookup = array('txn_id' => 'ID',
          'customer_name' => 'Buyer',
//          'customer_email' => 'Email',
//          'purchase_status' => 'Paypal Status',
          'created' => 'From',
          'expire_date' => 'Expiry',
//          'product_code' => 'Package Name',
          'text' => 'Text',
          'status' => 'Status',
//          'statusDate' => 'Effective'
      );
      $cols = array();
      foreach ($lookup as $k => $v) {
        $cols[$v] = htmlentities($this->$k);
      }
      return $cols;
    }

  }

  class EzTextLinks {

    var $options, $optionName, $plgURL, $actions, $linkToolBar, $popUp;
    private $adminMsg = '';

    const shortCode = 'ezlink';

    static $linkPage = false, $linkToolBarEmpty = true;

    function EzTextLinks() { //constructor
      $this->plgURL = plugins_url(basename(dirname(__FILE__)));
      $this->optionName = "ezTextLinks";
      $savedOptions = get_option($this->optionName);
      if (empty($savedOptions))
        $this->options = $this->mkDefaultOptions();
      else
        $this->options = array_merge($this->mkDefaultOptions(), $savedOptions);
      $this->actions = array("email" => "Email Advertiser",
          "delete" => "Delete this Ad",
          "hide" => "Block this Ad",
          "expiry" => "Change the Expiry Date",
          "edit" => "Edit Link Details",
          "info" => "View Link Details");
      if (is_admin())
        $this->actions = array("add" => "Add a New Ad Link Sale") +
                $this->actions;
      $this->popUp = "<div id='popupContainer' class='hidden'>
<a id='closePopup' class='hidden closePopup' title='close popup'
style='background-image:url({$this->plgURL}/delete.png)'></a>
<h1 id='ezPopupHeader'></h1>
<p id='ezPopupResponse'></p>
</div>
<span id='overlayEffect'></span>";
      if (is_admin())
        $style = "style='display:none;'";
      else
        $style = "style='display:none;background-color:rgba(0,0,0,0.6);'";
      $this->linkToolBar = "<span id='linkToolbar' $style>";
      foreach ($this->actions as $k => $a) {
        $this->linkToolBar .= "<input id='$k' class='toolBar' type='button'
          style='background-image:url({$this->plgURL}/$k.png)' title='$a' value=' '/>";
      }
      $this->linkToolBar .= "</span>";
      $prodActions = array("add" => "Add a New Link Package",
          "delete" => "Delete this Package",
          "hide" => "Suspend this Link Package",
          "edit" => "Edit Package Details",
          "info" => "View Package Details");
      $this->prodToolBar = "<span id='prodToolbar' style='display:none'>";
      foreach ($prodActions as $k => $a) {
        $this->prodToolBar .= "<input id='{$k}_prod' class='toolBar'
          type='button' style='background-image:url({$this->plgURL}/$k.png)'
          title='$a' value=' ' />";
      }
      $this->prodToolBar .= "</span>";
    }

    static function findShortCode($posts) {
      self::$linkPage = false;
      if (empty($posts))
        return $posts;
      foreach ($posts as $post) {
        if (stripos($post->post_content, self::shortCode) !== false) {
          self::$linkPage = true;
          break;
        }
      }
      return $posts;
    }

    function addStyles() {
      if (!self::$linkPage)
        return;
      if (is_admin())
        return;
      wp_register_style('ezTextLinksCSS', "{$this->plgURL}/ezLinks.css");
      wp_enqueue_style('ezTextLinksCSS');
    }

    function addScripts() {
      if (!self::$linkPage && !is_admin())
        return;
      wp_register_script('ezTextLinksJS0', "{$this->plgURL}/ezLinks.js", array('jquery'), '2.0', true);
      wp_enqueue_script('ezTextLinksJS0');
      if (current_user_can('manage_options')) {
        wp_localize_script('ezTextLinksJS0', 'EzlAjax', array('ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('EzlAjaxNonce')));
        wp_register_script('ezTextLinksJS1', "{$this->plgURL}/wz_tooltip.js", array(), '2.0', true);
        wp_enqueue_script('ezTextLinksJS1');
      }
    }

    function mkDefaultOptions() {
      $product = new EzlProduct("package1");
      $product->product_price = 79.95;
      $product->expire_hours = 100;
      $product->product_name = "First Example Link Package";
      $products[$product->product_code] = $product;

      $product = new EzlProduct("package2");
      $product->product_price = 199.95;
      $product->expire_hours = 300;
      $product->product_name = "Second Example Link Package";
      $products[$product->product_code] = $product;

      $links = array();

      $link = new EzlLink("link1");
      $link->customer_name = "Advertiser 1";
      $link->customer_email = "nobody@mail.com";
      $link->purchase_status = "active";
      $link->expire_date = self::mkDateString("2014-03-17 13:17:31");
      $link->product_code = "package1";
      $link->setText("<a href='http://www.example1.com'>First Example Link Sale</a>");
      $links[$link->txn_id] = $link;

      $link = new EzlLink("link2");
      $link->customer_name = "Advertiser 2";
      $link->customer_email = "nobody@mail.com";
      $link->purchase_status = "active";
      $link->expire_date = self::mkDateString("2015-03-17 13:17:31");
      $link->product_code = "package2";
      $link->setText("<a href='http://www.example2.com'>Second Example Link Sale</a>");
      $links[$link->txn_id] = $link;

      $emailTemplate = "Your link on BLOG will perhaps expire on EXPIRY. Would you like to renew it?";

      $adHereTitle = "Advertise Here";
      $adHereURL = "/advertise-here";
      $adHereTemplate = "More information";
      $options = compact('products', 'links', 'emailTemplate', 'adHereTitle', 'adHereURL', 'adHereTemplate');

      return $options;
    }

    function handleSubmits() {
      if (empty($_POST))
        return;
      if (!empty($_POST['reset_ezTextLinks'])) {
        $this->options = $this->mkDefaultOptions();
        $this->adminMsg = '<div class="updated">
          <p><strong>All options reset to defaults.</strong></p> </div>';
      }
      else if (!empty($_POST['update_ezTextLinks'])) {
        $toUpdate = array_intersect_key($this->options, $_POST);
        foreach ($toUpdate as $k => $v) {
          $this->options[$k] = $_POST[$k];
        }
        $this->adminMsg = '<div class="updated"><p>
          <strong>Your options have been updated.</strong></p> </div>';
      }
      update_option($this->optionName, $this->options);
    }

    function printAdminPage() {
      include "adminPage.php";
    }

// Table rendering
    function renderTable($rows, $class) {
      $id = $class;
      $ret = $this->renderTableHeader($rows, $id);
      $alt = "";
      foreach ($rows as $r) {
        $ret .= $this->renderTableRow($r, $alt, $class);
      }
      $ret .= $this->renderTableFooter($rows);
      return $ret;
    }

    function renderTableHeader($rows, $id) {
      $ret = '';
      if (empty($rows) || !is_array($rows))
        return $ret;
      reset($rows);
      $elem = current($rows);
      $cols = $elem->getCols();
      if (empty($cols) || !is_array($cols))
        return $ret;
      if (!empty($id))
        $id = "id='$id'";
      $ret .= sprintf("<table class='ezlTable' $id><tr>");
      foreach ($cols as $k => $v) {
        $ret .= sprintf("<th>%s</th>", $k);
      }
      $ret .= sprintf("</tr>");
      return $ret;
    }

    function renderTableRow($elem, &$alt, $class) {
      $ret = '';
      $cols = $elem->getCols();
      reset($cols);
      $id = current($cols);
      $classes = trim("$alt $class");
      $ret .= sprintf("<tr class='$classes' id='$id'>\n");
      if ($alt == "")
        $alt = "alt";
      else
        $alt = "";
      foreach ($cols as $v) {
        $ret .= sprintf("<td>%s</td>\n", $v);
      }
      $ret .= sprintf("</tr>\n");
      return $ret;
    }

    function renderTableFooter($rows) {
      $ret = '';
      $ret .= sprintf("</table>");
      return $ret;
    }

// AJAX handlers
    function validate() {
      if (!current_user_can('manage_options')) {
        echo "Sorry, you are not authorized to do this!";
        exit;
      }
      $nonce = $_POST['nonce'];
      if (!wp_verify_nonce($nonce, 'EzlAjaxNonce')) {
        echo "Security Check fails. Please try again.";
        exit;
      }
      $elem = $data = false;
      if (!empty($_POST['linkId']))
        $elem = $this->options['links'][$_POST['linkId']];
      else if (!empty($_POST['productId']))
        $elem = $this->options['products'][$_POST['productId']];
      if (!$elem) {
        echo "Link {$_POST['linkId']} or Product {$_POST['productId']} could not be located. Please select one.";
        exit;
      }
      $callers = debug_backtrace();
      $caller = $callers[1]['function'];
      $confirmed = (!empty($_POST['confirm']) &&
              $_POST['confirm'] == "{$caller}_confirm");
      if ($confirmed)
        parse_str($_POST['data'], $data);
      return array($elem, $confirmed, $data);
    }

    function updateOptions($elem, $data, $new = false) {
      $data['statusDate'] = time();
      $elem->set($data);
      $class = $elem->className();
      $id = $elem->id();
      $type = $elem->type();
      if ($new) {
        $this->options[$type][$id] = $elem;
        $alt = "new";
        echo "Added a new element {$id} in $type";
        echo ":new:" . $this->renderTableRow($elem, $alt, $class);
      }
      else {
        echo "{$data['status']}: element {$id} in $type";
        echo ":modified:" . $this->renderTableRow($elem, $alt, $class) .
        ":modified:#" . $elem->id();
      }
      update_option($this->optionName, $this->options);
    }

    function add() {
      list($elem, $confirmed, $data) = $this->validate();
      if ($confirmed) {
        $newElem = $elem->add($data);
        $this->updateOptions($newElem, $data, $new = true);
      }
      else {
        $elem->add();
      }
      exit;
    }

    function delete() {
      list($elem, $confirmed, $data) = $this->validate();
      if ($confirmed) {
        $data['status'] = "Deleted";
        $data['statusDate'] = time();
        $this->updateOptions($elem, $data);
      }
      else {
        $elem->delete();
      }
      exit;
    }

    function email() {
      list($elem, $confirmed, $data) = $this->validate();
      if ($confirmed) {
        extract($data);
        // $headers = "From: $from\r\n";
        if (wp_mail($to, $subject, $message))
          echo "Emailed!";
        else
          echo "Error sending email!";
      }
      else {
        $elem->email($this->options['emailTemplate']);
      }
      exit;
    }

    function hide() {
      list($elem, $confirmed, $data) = $this->validate();
      if ($confirmed) {
        $data['status'] = "Hidden";
        $data['statusDate'] = time();
        $this->updateOptions($elem, $data);
      }
      else {
        $elem->hide();
      }
      exit;
    }

    function expiry() {
      list($elem, $confirmed, $data) = $this->validate();
      if ($confirmed) {
        $data['status'] = "Expiry Modified";
        $this->updateOptions($elem, $data);
      }
      else {
        $elem->expiry();
      }
      exit;
    }

    function edit() {
      list($elem, $confirmed, $data) = $this->validate();
      if ($confirmed) {
        // change the status only if the user has left it empty
        if (empty($data['status']))
          $data['status'] = "Edited";
        $this->updateOptions($elem, $data);
      }
      else {
        $elem->edit();
      }
      exit;
    }

    function info() {
      list($elem, $confirmed, $data) = $this->validate();
      $elem->edit($formName = 'info');
      exit;
    }

// Short code handler
    function handleShortcode($atts, $content = '') {
      extract(shortcode_atts(array("id" => ""), $atts));
      $display = "\n<!-- Easy Text Links - begin -->\n";
      if (self::$linkToolBarEmpty && current_user_can('manage_options')) {
        $display .= $this->linkToolBar;
        foreach ($this->actions as $k => $a) {
          add_action("wp_ajax_$k", "{$k}_callback");
        }
        self::$linkToolBarEmpty = false;
      }
      self::$linkPage = true;

      if (empty($atts) || in_array("links", $atts)) { // show all links
        $links = $this->options['links'];
      }
      else if (array_key_exists("links", $atts)) { // show only selected links
        $linkIds = explode(',', $atts['links']);
        $links = array();
        foreach ($linkIds as $id) {
          if (!empty($this->options['links'][$id]))
            $links[$id] = $this->options['links'][$id];
        }
      }
      else { // no links to be shown
        $links = array();
      }
      if (!empty($links)) {
        $display .= "<ul>";
        foreach ($links as $link) {
          $text = $link->getText();
          if (!empty($text))
            $display .= "<li>$text</li>";
        }
        $display .= "</ul>";
      }
      if (!empty($atts)) {
        if (in_array("invite", $atts) ||
                in_array("advertise", $atts) ||
                in_array("here", $atts)) { // show Advertise Here
          $display .= "<h2>{$this->options['adHereTitle']}</h2>";
          $display .= "<a href='{$this->options['adHereURL']}'>{$this->options['adHereTemplate']}</a>";
        }
        if (in_array("packages", $atts) ||
                array_key_exists("packages", $atts)) { // show packages
          $products = $this->options['products'];
          $display .= "<ul>";
          foreach ($products as $product) {
            $text = $product->getText();
            if (!empty($text))
              $display .= "<li>$text</li>";
          }
          $display .= "</ul>";
        }
        if (array_key_exists("option", $atts) &&
                strtolower($atts['option']) == "nolist") { // kill list
          $display = str_replace(
                  array('<ul>', '<ol>', '<li>', '</li>', '<ol>', '<ul>'),
                  '', $display);
        }
      }
      $display .= "\n<!-- Easy Text Links - end -->\n";
      return $display;
    }

    function addPopup($content) {
      if (current_user_can('manage_options'))
        $content = $this->popUp . $content;
      return $content;
    }

// Date utilities
    static function mkDateString($intOrStr) {
      if (empty($intOrStr))
        return "";
      if (is_int($intOrStr))
        $dateStr = date('Y-m-d H:i:s', $intOrStr);
      else
        $dateStr = date('Y-m-d H:i:s', strtotime($intOrStr));
      return $dateStr;
    }

    static function mkDateInt($intOrStr) {
      if (is_int($intOrStr))
        $dateInt = $intOrStr;
      else
        $dateInt = strtotime($intOrStr);
      return $dateInt;
    }

  }

} //End Class ezTextLinks

if (!function_exists('ezprint')) {

  function ezprint($data) {
    printf("<pre>%s</pre>", print_r($data, true));
  }

}
if (class_exists("EzTextLinks")) {
  $ezTextLinks = new Eztextlinks();
  if (isset($ezTextLinks)) {
    add_shortcode(Eztextlinks::shortCode, array($ezTextLinks, 'handleShortcode'));
    add_action('wp_enqueue_scripts', array($ezTextLinks, 'addStyles'));
    add_action('wp_enqueue_scripts', array($ezTextLinks, 'addScripts'));
    if (is_admin())
      add_action('admin_enqueue_scripts', array($ezTextLinks,
          'addScripts'));
    add_filter('the_posts', array("Eztextlinks", "findShortCode"));
    add_filter('the_content', array($ezTextLinks, 'addPopup'));
    if (is_admin()) {

      function ezTextLinks_ap() {
        global $ezTextLinks;
        if (function_exists('add_options_page')) {
          $mName = 'Easy Text Links';
          add_options_page($mName, $mName, 'activate_plugins',
                  basename(__FILE__), array($ezTextLinks, 'printAdminPage'));
        }
      }

      add_action('admin_menu', 'ezTextLinks_ap');
      foreach ($ezTextLinks->actions as $k => $a) {
        add_action("wp_ajax_$k", array($ezTextLinks, $k));
      }
    }
  }
}
