=== Hexagonal Reviews ===
Contributors: warguns86
Tags: reviews, review, hexagonal, clean
Requires at least: 5.6
Tested up to: 5.7
Requires PHP: 7.2
Stable tag: 4.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The plugin permits you to append reviews on the posts using a shortcode, and manage it's publications on the admin area.

== Description ==

Manage and let your users review your Products or your Business using that plugin. It's also a plugin an example to demonstrate hexagonal architecture capabilities inside a wordpress plugin. Please check The Repository at: [Github](https://github.com/warguns/wp-reviews-hexagonal-architecture-example) for more information, please not hesitate to let a star if you like it.

✅ Plugin Features:

* 📈 Improves Your SEO page adding Rich snippets json-ld selector. You can select between Product and Business.
* ☑️ Add your reviews using a shortcode.
* ⭐️ Star selectors on the Form as well as admin area.
* ✉️ You can decide which wordpress post is reviewed.
* [/] Shortcut generator.
* 🪟 Show and hide different components: the Average, the Form or the Review list using the shortcut.
* 🏰 Choose between Product, LocalBusiness, or disable json-ld to compatibilize with other platforms.

⌨️ Code Features:

* ⬣ Code structured using Hexagonal architecture, with Solid principles.
* 🔬 Unit test and acceptance test ready.
* 🧑‍💻 Code organized ready for use on other platforms.
* 👌 Following the Wp Coding standards.
* 📦 Object Oriented.


== Installation ==

You can find that plugin on the wordpress plugin directory or in case of download the zip, please follow those steps:

1. Uncompress the zip.
2. Upload `hexagonal-reviews` folder to the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Place `[hexagonal-reviews post_id=1]` in your posts

== Frequently Asked Questions ==

= Can I use for real Reviews? =

If it's usable for you then yes, there's no problem.

= Where can I find the plugin's public css? =

Please go to: src/UI/Wordpress/Front/assets to edit public visible css.

== Screenshots ==

1. Form on the Post.
2. Backend.
3. Review List

== Changelog ==

= 1.0 =
* First Commit

= 1.2.6 =
* Fixed some dependencies not added.
* Removed unnecessary call.

= 1.2.7 =
* Add type attribute for decide between product and business on json-ld
* Add stars on average.

= 1.2.8 =
* Fix css
* Fix error with shortcuts inside the description

= 1.2.9 =
* Shortcut Generator
* Enable show/hide capabilities of the average, form and reviews.

= 1.2.10 =

* Fix 1.2.9

= 1.2.11 =

* Enable show/hide capabilities of the rich capabilities.
* Improvements on the shortcut generator to select the rich snippet behavior.
* Removal of the List by Post empty page (Integration with the List Reviews coming soon!)

= 1.2.12 =

* Added a little database migration tool.
* Improved average using the Binomial proportion confidence interval.

== Upgrade Notice ==

No upgrades yet
