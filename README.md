scriptless-socialsharing
========================

A plugin that provides scriptless and privacy friendly sharing buttons for Facebook, Twitter, Google+, Pinterest, Linkedin, Xing, Reddit, Stumbleupon, Tumblr, Whatsapp (iOS only) and E-Mail. 

To have it work correctly you should to enable the `html_meta_tags` plugin and the Open Graph (og:) meta data elements.

The plugin loads an default CSS styling using an icon font optionally (Thanks to icomoon.io and fontawesome.io). If you wish to use theme based custom icons and css to avoid extra loading you can disable it on the plugin options.

##Usage:

- Place the file `scriptless-socialsharing.php` and folder of the same name within `/plugins`
- Enable the plugin
- Place `<?php printScriptlessSocialSharingButtons(); ?>` on your theme files where you wish the buttons to appear.
