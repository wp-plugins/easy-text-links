=== Easy Text Links ===
Contributors: manojtd
Donate link: http://buy.thulasidas.com/easy-text-links
Tags: text links, advertising, revenue, ezlinks, easy text links
Requires at least: 3.5
Tested up to: 3.6
Stable tag: 1.40
License: GPLv2 or later

A robust and modern plugin to help you sell and manage text links on your blog.

== Description ==

*Easy Text Links* is a modern plugin to help you make extra revenue from your blog by selling text links. Text link advertising can be significantly more lucrative than contextual ads. This plugin automates the insertion and expiration of the links, and helps you with quick reminder emails to your advertisers.

Other plugins that do similar text link advertising do exist in the repository, but they are typically front ends to services that provide such links (such as Text Link Ads, for instance.) They take a significant cut of your advertising revenue (50% in the case of Text Link Ads) acting as the middlemen between you and your advertisers. *Easy Text Links*, on the other hand, is for those who want to interact directly with their advertisers, and keep the whole revenue for themselves, cutting the middlemen.

*Easy Text Links* keeps track of the expiry dates of the text links on your page, and removes them when expired. It also gives you easy means of sending a reminder email to the advertiser, block or delete the links all from your blog page itself, without having to go to the admin page. Of course, it is smart and secure so that only the blog admin with the right privileges will ever see the options to modify the links. It does all this magic using a modern AJAX/jQuery framework with an attractive lightbox effect.

If you have a popular blog, text links can potentially bring in much more revenue than any contextual advertising. In my case, the difference was easily a factor of 100. Keep in mind, however, that most contextual ad providers (such as AdSense) do not like paid link placement, for obvious reasons. Your blog may get penalized in terms page rank or search listing placement. Please weigh the pros and cons carefully before embarking on text link advertising. If you do decide to go for it, this plugin will be able to help.

= Features =

1. Automatic removal of your text links upon expiry.
2. Ability to send a reminder email to your advertiser with one click.
3. Ability to delete and block your links and modify their expiries.
4. Convenience of managing your links (delete, block, set expiry, email advertiser etc.) right from your blog post where the link appears, without going to the plugin admin page.
5. Attractive and modern interface with lightbox effect.
6. Highly secure and robust against potential hacker attacks and attempts to manipulate links.
7. Now available in your own language using machine translation curtsey of Google and Microsoft.

= Pro Version =

A [pro version](http://buy.thulasidas.com/easy-text-links "Pro Version of Eazy Text Links for $7.95") of this plugin is available with the following added features:

1. Fully automated link sales and expiry (with the help of the [Easy PayPal](http://wordpress.org/extend/plugins/easy-paypal-lte/ "Easy PayPal Plugin") plugin available at WordPress.
2. Your advertisers will see "Buy Now" buttons to purchase links.
3. Basic widget support.
4. Automatic email reminders to you and your advertiser when the links are about to expire. (WIP)
5. Choice of several attractive "Advertise Here" images. (WIP)
6. Advanced widget support with a dedicated widget. (WIP)

== Upgrade Notice ==

Compatibility with WP3.6.

== Installation ==

You can install it using the WordPress Plugins -> Add New interface.

Or,

1. Upload the Easy Text Links plugin (the whole `easy-text-link` folder) to the '/wp-content/plugins/' directory.
2. Activate the plugin through the 'Plugins' menu in your blog.

To use the plugin, insert the shortcode `[ezlink]` in a post. See the FAQ for more information.

== Screenshots ==

1. Links inserted by Easy Links Pro. Note the floating tool bar that appears when you hover over the link (if you are logged in as the admin to your blog) giving you easy access to the actions you can take.

2. The Admin Page. The tool bar appears as you hover over a row in the Links Packages or Sold Links table.

== Frequently Asked Questions ==

= What are "Link Packages" and "Sold Links"? =

Link Packages describe what you offer to your advertisers. They specify your rates and other relevant information to entice advertisers to divert some of the marketing budget in the direction of your blog. For instance, you may have these packages: (1) "AboveFold" with Price=$100, Expiry=720 (which is a month in hours) (2) "AboveFold (3mo)" with Price=$250, Expiry=2160 (three months in hours) (3) "Footer"  with Price=$50, Expiry=720 and so on. You can list them wherever you want by giving the shortcode `[ezlink packages]`. You will also see all your packages listed on the *Easy Text Links* admin page in a neat table, where you can inspect, modify or delete them.

Sold Links are, naturally, the links you have sold to your advertisers. You can display them on your posts or pages by the shortcode `[ezlink links]`. If you want to display only a particular link (whose ID is, say, link1), you would give the shortcode `[ezlink links=link1]`.  For multiple links, you give the shortcode as `[ezlink links=link1,link2]`. Again, the sold links will appear on the plugin admin page where you can manipulate them.

The links and products, when listed using the shortcode `[ezlink]`, will be formatted as an unordered list (`<ul>...</ul>`).

= I don't want the links to be an unordered list. What can I do? =

You can specify an option in the shortcode as `[ezlink option=nolist]`. This option can be given with any combination of `[ezlink packages]`, `[ezlink packages]` or `[ezlink links=link1,link2,link3...]`.

= How do I specify a "Advertise Here" link pointing to a blog page? =

You insert the shortcode `[ezlink invite]` (or `[ezlink advertise]` or `[ezlink here]`) to display such an invitation.

Note that you have to create the target page/post with a title like "Advertise Here" or something similar and point to it on the plugin admin page (under the *"Advertise Here" Target:"* option). Once that is done, your `[ezlink invite]` shortcode will point to that page/post. In that page, you can use `[ezlink packages]` shortcode to list your link packages, and give any contact details so that your advertisers can get in touch with you.

= Can I have a full list of shortcode keywords and syntax? =

1. List packages (within `<ul>...</ul>`) : `[ezlink packages]`.
2. List all links (within `<ul>...</ul>`) : `[ezlink links]`
3. List specific links (within `<ul>...</ul>`) : `[ezlink links=link1,link2,link3...]`.
4. Suppress `<ul><li>...</li></ul>` around any of the lists above : `[ezlink option=nolist ...]`
5. Advertise Here display : `[ezlink invite]` or `[ezlink advertise]` or `[ezlink here]`

= How can I get the links or packages in a widget? =

The [Pro version](http://buy.thulasidas.com/easy-text-links "Pro Version of Eazy Text Links for $7.95") version of *Easy Text Links* gives you basic support for widgets. You can use the WordPress default Text widget to insert the shortcode and it will be rendered as expected.  A dedicated and multi-insertable widget with fully configurable text links will be released later.

The lite version does not offer a widget. The work around is to install the [Shortcode Widget](http://wordpress.org/extend/plugins/shortcode-widget/ "Shortcode Widget plugin from WordPress directory"). You can then place the `[ezlink]` shortcodes in the widgets provided by Shortcode Widget.

= I deleted/blocked a link by mistake. Now it has disappeared. How do I get it back? =

Go to the admin page of the plugin, and edit the Sold Link by clicking on the edit icon (green pencil) on the floating toolbar. You can then set the status to anything other than Deleted or Hidden.

== Change Log ==

* V1.40: Compatibility with WP3.6. [Aug 8, 2013]
* V1.30: Now available in your own language using machine translation curtsey of Google and Microsoft. [May 22, 2013]
* V1.21: Bug fix on displaying the link toolbar. Documentation and help changes. [May 15, 2013]
* V1.20: Handling empty attributes in shortcode, and hidden and deleted packages. [May 3, 2013]
* V1.11: Minor bug fix. [May 2, 2013]
* V1.10: Correcting W3C markup validation errors on the admin page. [May 1, 2013]
* V1.01: Handling blocked and deleted links. [Apr 23, 2013]
* V1.00: Initial release. [Apr 20, 2012]

= Future Plans =

1. Templating package listing and "Advertise Here" page and link.
2. More fields in templates (BUYER, PACKAGENAME etc.)
3. Making package and link expiry specification flexible (today, tomorrow, +-nd/w/m/y)
4. Widget support (Pro version).
5. Use JSON in AJAX-jQuery communication for standard compliance.
6. Style the admin tables to match WordPress admin themes.

