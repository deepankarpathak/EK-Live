=== Pullquote Shortcode ===
Contributors: sami.keijonen
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=E65RCFVD3QGYU
Tags: shortcode, pullquote
Requires at least: 3.8
Tested up to: 3.9
Stable tag: 0.1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Register shortcode [pullquote] and add pullquote button to tinyMCE editor.

== Description ==

*Pullquote Shortcode* register shortcode [pullquote] and adds pullquote button to tinyMCE editor. If you are not using a button you can
write shortcode with content like this.

[pullquote]This is my content[/pullquote]

Pullquotes works also in text widgets.

== Installation ==

1. Upload `pullquote-shortcode` to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Start using pullquotes.

== Frequently Asked Questions ==

= Why was this plugin created? =

I needed this feature to be in a plugin because I changed theme a lot. And the button is for authors who are not so tech savvy.

= What is pullquote? =

Check out screenshots.

= How to translate this plugin? =

There is folder `/js/langs/` where are language files like `fi.js` and `en.js`. There is only couple of strings to translate. You can send me (sami.keijonen (at) foxnet.fi) the
translate files for your language.

== Screenshots ==

1. Add Pullquote
2. Add Pullquote text
3. You can also write shortcode
4. Pullquote in the front-end

== Changelog ==

= 0.1.2 =
* Tested up to 3.9.

= 0.1.1 =
* Removed shortcode syntax when using TinyMCE button. Replaced by `<div class="pullquote"></div>`.

= 0.1 =
* Everything's brand new.