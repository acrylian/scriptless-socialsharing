scriptless-socialsharing
========================

A plugin that provides scriptless and privacy friendly sharing buttons for:

- Facebook
- Twitter
- Google+
- Pinterest 
- Linkedin
- Xing
- Reddit
- Stumbleupon
- Tumblr
- WhatsApp (iOS only)
- Digg
- Livejournal 
- Buffer
- Delicious
- Evernote
- WordPress(.com)
- Pocket
- e-mail (static link to open the visitor's mail client)

Note: Since no scripts are involved no share counts!

To have it work correctly you should to enable the html_meta_tags plugin and the Open Graph (og:) meta data elements.
 
The plugin loads an default CSS styling using an icon font optionally. If you wish to use theme based custom icons css to avoid extra loading you can disable it.

Icon font created using the icomoon app: http://icomoon.io/#icons-icomoon
Fonts used:

- fontawesome http://fontawesome.io – SIL OFL 1.1 
- BRankic 1979 (buffer/stack icon) http://brankic1979.com/icons/ (free for personal and commercial use)
- Entypo+ (evernote icon) http://www.entypo.com – CC BY-SA 4.0

##Usage:

- Place the file `scriptless-socialsharing.php` and folder of the same name within `/plugins`
- Enable the plugin
- Place `<?php printScriptlessSocialSharingButtons(); ?>` on your theme files where you wish the buttons to appear.