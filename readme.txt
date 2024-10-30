=== Make it Srcset ===
Contributors: samuelbrynolf
Tags: srcset
Requires at least: 4.4.0
Tested up to: 4.7.4
Stable tag: 4.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Wordpress already uses srcset now. What I missed out was a simple way to handle and customize the sizes attribute—in my opion what makes srcset so effective. While I was at it, I also built in some extra functionality. Make it Srcset do not conflict with Wordpress built-in-solution.

== Description ==

Enables shortcode and a template tag to pull srcset-images by attachment-ID. This is way to complement Wordpress built in srcset which (of course) focus on the_content images and media-sizes set in settings. Sometimes your layout demands more, or you are using other ways to put images on your site not using the_thumbnail or the_content. For example, I use Advanced Custom Fields a lot pulling images by ID.

Generated markup are built from a set of custom image-sizes and the, by you configured, (srcset-)sizes attribute. Use the settings-page for default values and override them any time in the shortcode or template tag. 

* Option: Generate shortcodes by the mediauploader-button. 
* Option: Enable simple lightbox functionality. 
* Option: Enable lazhyloading. Option: Include Picturefill

Full documentation at [WordPress plugin: Make it Srcset](https://samuelbrynolf.se/2016/04/wordpress-plugin-make-it-srcset/ "Wordpress plugin: Make it Srcset")

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the Plugins->Make it Srcset settings-page to configure the plugin
4. Make sure to set the settings for step 1-2, before uploading images that will be used, since Wordpress creates image sizes on upload. (Fallback image-sizes are the ones you set up in Settings->Media)

== Changelog ==

Bugfix blurry images when uploaded image is smaller than required by settings. Add styling to lightbox overlay.