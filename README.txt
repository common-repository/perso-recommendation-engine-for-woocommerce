# Perso Recommendation Engine Plugin for Woocommerce
Contributors: hi@getperso.com

Tags: recommendation engine, woocommerce, others also viewed, other customers also buy, personalized e-coommerce, personalized ecommerce, perso recommendation engine, online shop, toko online

Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=rahadian.bayu.permadi@gmail.com&item_name=Donation+for+Perso

Requires at least: 4.1

Tested up to: 4.4.2

Stable tag: 2.1.1

License: GPLv2

License URI: http://www.gnu.org/licenses/gpl-2.0.html

Perso Recommendation Engine Plugin - Perso Plugin <b>Recommendation Engine</b>.   

== Description ==

Your visitor will enjoy recommendation that are formed by other visitors interacting your web site. Your visitors are unique. They have their own tastes, needs and backgrounds. Perso is aimed at providing personalized feeling when they visit your site. Perso allows your very own customers help each other to discover the best product in your websites for themselves.

This plugin is providing product recommendation service for woocommerce sites. The recommendation is served automatically according to the visitors actions (Collaborative Filtering). Those actions are viewing product and buying the products.

THe recommendations are served on the product detail age. There are two places where the recommendations, on the widget on sidebar or on the footer of the detail page after product description. More details on the screenshot.


For example:

Visitor A is viewing the product detail page of product 1, product 2, and product 3. This visitor A actions will make those three product to have products recommendation. For other visitors who visit your site, when they view the product detail age for product 1, product 2, or product 3. The recommendation based on the previous visitor, visitor A, will be displayed. In this case, if detail page of product 1 is viewed, the recommendation contains product 2 and product 3. When product 2 detail page is viewed, the recommendation contains product 1 and product 3. When the detail age of product 3 is viewed the recommendation contains product 1 and product 3.

The same case when the products are bought together. 

The process is carried on by using Ajax mechanism to make it faster and lighter.

A Running example of this plugin in action can be seen on our sample site [our sample site] http://www.getperso.com/sample

This plugin is developed by Perso. copyright (c) 2016 by[Perso](http://www.getperso.com)

= Strength & flexibility =

This plugin is built using WordPress best practises both on the front and the back end. This results in an efficient, robust and intuitive plugin. Currently this plugin supports latest woocommerce and latest wordpress.

= Customizable =

Your business is unique, you may modify this plugin to meet your business requirement. However, re-distribution after modification is prohibited. You may refer to woocommerce plugin customization page and wordpress plugin development page to do it. This free version of Perso Recommendation Engine will enable two widgets that display product recommendations on the product detail page. More advanced features will be available in the near future.

== Installation ==

= Minimum Requirement =

* Wordpress 4.0
* Woocommerce version 2.3.5
* PHP version 5.2.4 or greater
* MySQL version 5.0 or greater

= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don’t need to leave your web browser. To do an automatic install of Perso Recommendation Engine, log in to your WordPress dashboard, navigate to the Plugins menu, search for Perso Recommendation Engine and click Add New. You can activate the plugin afterwards in the list of Plugins.


= Manual installation =

The manual installation method involves downloading our Perso Recommendation Engine plugin and uploading it to your webserver via your favourite FTP application. The WordPress codex contains [instructions on how to do this here](http://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

= Updating =

Automatic updates should work like a charm; as always though, ensure you backup your site just in case.

= Configuration =

Once plugin is installed please activate it from activate link in the wordpress plugin page. After that, go to woocommerce wp-admin dashboard menu and click Settings. There is a sub-menu called Perso Settings. In there you can find settings that will limit the number of products displayed in the recommendation widgets.


== Screenshots ==
1. 'Customer who viewed this also viewed' Recommendations on the footer of product detail page
2. 'Customer who bought this also bought' Recommendations on the footer of product detail page
3. Sidebar widget recommendation that other visitors viewed together with product being viewed
4. Sidebar widget recommendation that other visitors purchased together with product being viewed
5. Setting panel for Perso (Upper part)
6. Setting panel for Perso (Lower part)

== Change Log ==

= 1.0 =
Initial Upload

= 1.5 =
Changed the mechanism (input and output) using Ajax to make the process lighter 

= 1.5.1 =
Fixing the bug on the CSS of product recommendation list displayed on product detail page 

= 1.5.2 =
Fixing the bug on the price formatting on the recommended products 

= 1.5.3 =
Fixing the bug on not diplaying recommendation for wordpress 4.4.1 

= 1.5.4 =
Fixing bug for the case where the recommendation are more than the maximum items for recommendation defined on settings

= 1.5.5 =
Fixing bug for the widget display in all types of themes

= 1.6 =
Fixing bug for dislay multiple 'others also bought' recommendation. Clean up some unused codes.

= 1.7 =
Prettify the widget display in case no products to be recommend, it will not display empty widget.

= 2.0 =
Add recommendation on the footer of the product detail page

= 2.0.1=
add link for rating

= 2.0.2=
Fixing bug when dislaying footer recommendatiopn in some themes, e.g. wootique

= 2.1.0 =
Ugrade recommendation others also bought. Where it will take fropm the oprder table and not from separate table. This way the others also bought will be recognized right after installation, and not only log down the purchase after this lugin installation.

= 2.1.1=
Fixing bug number of item displayed for 'customers also bought' based on the settings
