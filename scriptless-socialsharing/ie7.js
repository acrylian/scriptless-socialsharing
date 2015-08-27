/* To avoid CSS expressions while still supporting IE 7 and IE 6, use this script */
/* The script tag referencing this file must be placed before the ending body tag. */

/* Use conditional comments in order to target IE 7 and older:
	<!--[if lt IE 8]><!-->
	<script src="ie7/ie7.js"></script>
	<!--<![endif]-->
*/

(function() {
	function addIcon(el, entity) {
		var html = el.innerHTML;
		el.innerHTML = '<span style="font-family: \'scriptless-socialbuttons\'">' + entity + '</span>' + html;
	}
	var icons = {
		'icon-envelope-o': '&#xf003;',
		'icon-twitter': '&#xf099;',
		'icon-facebook': '&#xf09a;',
		'icon-google-plus': '&#xf0d5;',
		'icon-linkedin': '&#xf0e1;',
		'icon-xing': '&#xf168;',
		'icon-tumblr': '&#xf173;',
		'icon-reddit': '&#xf1a1;',
		'icon-stumbleupon': '&#xf1a4;',
		'icon-pinterest-p': '&#xf231;',
		'icon-whatsapp': '&#xf232;',
		'0': 0
		},
		els = document.getElementsByTagName('*'),
		i, c, el;
	for (i = 0; ; i += 1) {
		el = els[i];
		if(!el) {
			break;
		}
		c = el.className;
		c = c.match(/icon-[^\s'"]+/);
		if (c && icons[c[0]]) {
			addIcon(el, icons[c[0]]);
		}
	}
}());
