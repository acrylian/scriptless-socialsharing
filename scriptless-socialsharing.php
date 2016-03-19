<?php
/**
 * A Zenphoto plugin that provides scriptless and privacy friendly sharing buttons for:
 * - Facebook
 * - Twitter
 * - Google+
 * - Pinterest 
 * - Linkedin
 * - Xing
 * - Reddit
 * - Stumbleupon
 * - Tumblr
 * - WhatsApp (iOS only)
 * - Digg
 * - Livejournal 
 * - Buffer
 * - Delicious
 * - Evernote
 * - WordPress(.com)
 * - Pocket
 * - e-mail (static link to open the visitor's mail client)
 * 
 * Note: Since no scripts are involved no share counts!
 * 
 * To have it work correctly you should to enable the html_meta_tags plugin 
 * and the Open Graph (og:) meta data elements.
 *
 * The plugin loads an default CSS styling using an icon font optionally. If you wish to use theme based custom icons 
 * and css to avoid extra loading you can disable it.
 *
 * Icon font created using the icomoon app: http://icomoon.io/#icons-icomoon
 * Fonts used:
 * - Brankic 1979 (buffer/stack icon) http://brankic1979.com/icons/ (free for personal and commercial use)
 * - Entypo+ (evernote icon) http://www.entypo.com – CC BY-SA 4.0
 * - fontawesome (all other icons) http://fontawesome.io – SIL OFL 1.1 
 *
 * Usage:
 * Place <?php printScriptlessSocialSharingButtons(); ?> on your theme files where you wish the buttons to appear.
 *
 * @author Malte Müller (acrylian)
 * @copyright 2015 Malte Müller
 * @license GPL v3 or later
 * @package plugins
 * @subpackage social
 */
$plugin_is_filter = 9 | THEME_PLUGIN;
$plugin_description = gettext('A Zenphoto plugin that provides scriptless and privacy friendly sharing buttons for Facebook, Twitter, Google+, Pinterest, Linkedin, Xing, Reddit, Stumbleupon, Tumblr, WhatsApp (iOS only) and e-mail. (Note: No share counts because of that!).');
$plugin_author = 'Malte Müller (acrylian)';
$plugin_version = '1.4';
$option_interface = 'scriptless_socialsharing_options';
if (getOption('scriptless_socialsharing_iconfont')) {
	zp_register_filter('theme_head', 'scriptlesssocialsharingCSS');
}

class scriptless_socialsharing_options {

	function __construct() {
		
	}

	function getOptionsSupported() {
		$options = array(
				gettext('Social networks') => array(
						'key' => 'scriptless_socialsharing_socialnetworks',
						'type' => OPTION_TYPE_CHECKBOX_UL,
						'order' => 0,
						'checkboxes' => array(
								'Facebook' => 'scriptless_socialsharing_facebook',
								'Twitter' => 'scriptless_socialsharing_twitter',
								'Google+' => 'scriptless_socialsharing_gplus',
								'Pinterest' => 'scriptless_socialsharing_pinterest',
								'Linkedin' => 'scriptless_socialsharing_linkedin',
								'Xing' => 'scriptless_socialsharing_xing',
								'Reddit' => 'scriptless_socialsharing_reddit',
								'StumbleUpon' => 'scriptless_socialsharing_stumbleupon',
								'Tumblr' => 'scriptless_socialsharing_tumblr',
								'Whatsapp' => 'scriptless_socialsharing_whatsapp',
								'Digg' => 'scriptless_socialsharing_digg',
								'Livejournal' => 'scriptless_socialsharing_livejournal',
								'Buffer' => 'scriptless_socialsharing_buffer',
								'Delicious' => 'scriptless_socialsharing_delicious',
								'Evernote' => 'scriptless_socialsharing_evernote',
								'WordPress' => 'scriptless_socialsharing_wordpress',
								'Pocket' => 'scriptless_socialsharing_pocket',
								gettext('E-mail') => 'scriptless_socialsharing_email',
						),
						'desc' => gettext('Select the social networks you wish buttons to appear for. Note: WhatsApp iOS only.')),
				gettext('Icon font and default CSS') => array(
						'key' => 'scriptless_socialsharing_iconfont',
						'type' => OPTION_TYPE_CHECKBOX,
						'order' => 1,
						'desc' => gettext("Uncheck to disable loading to use your own theme based icon font and css.")),
				gettext('Icons only') => array(
						'key' => 'scriptless_socialsharing_iconsonly',
						'type' => OPTION_TYPE_CHECKBOX,
						'order' => 1,
						'desc' => gettext("Check to hide the service name and only show icon buttons.")),
				gettext('Twitter user name') => array(
						'key' => 'scriptless_socialsharing_twittername',
						'type' => OPTION_TYPE_TEXTBOX,
						'order' => 1,
						'desc' => gettext("Enter your Twitter name without @ here if you like to have it appended to tweets made."))
		);
		return $options;
	}
}

function scriptlesssocialsharingCSS() {
	?>
	<link rel="stylesheet" href="<?php echo FULLWEBPATH . '/' . USER_PLUGIN_FOLDER; ?>/scriptless-socialsharing/buttons.css" type="text/css">
	<!--[if lt IE 8]><!-->
	<link rel="stylesheet" href="<?php echo FULLWEBPATH . '/' . USER_PLUGIN_FOLDER; ?>/scriptless-socialsharing/ie7.css">
	<!--<![endif]-->
	<?php
}

/**
 * Place this where you wish the buttons to appear. The plugin includes also jQUery calls to set the buttons up to allow multiple button sets per page.
 *  
 * @param string $text Text to be displayed before the sharing list. HTML code allowed. Default empty
 * @param string $staticpagetitle If using static custom pages the file name is used unless you set this. Meant to be used for multilingual sites, too.
 */
function printScriptlessSocialSharingButtons($text='', $staticpagetitle = NULL, $iconsonly = null) {
	global $_zp_gallery, $_zp_gallery_page, $_zp_current_album, $_zp_current_image, $_zp_current_zenpage_news, $_zp_current_zenpage_page, $_zp_current_category;
	$title = '';
	$desc = '';
	$url = '';
	$gallerytitle = html_encode(getBareGallerytitle());
	$imgsource = '';
	switch ($_zp_gallery_page) {
		case 'index.php':
		case 'gallery.php':
			$url = getGalleryIndexURL();
			$title = getBareGalleryTitle();
			break;
		case 'album.php':
			$url = $_zp_current_album->getLink();
			$title = $_zp_current_album->getTitle();
			break;
		case 'image.php':
			$url = $_zp_current_image->getLink();
			$title = $_zp_current_image->getTitle();
			break;
		case 'news.php':
			if (function_exists("is_NewsArticle")) {
				if (is_NewsArticle()) {
					$url = $_zp_current_zenpage_news->getLink();
					$title = $_zp_current_zenpage_news->getTitle();
				} else if (is_NewsCategory()) {
					$url = $_zp_current_category->getLink();
					$title = $_zp_current_category->getTitle();
				} else {
					$url = getNewsIndexURL();
					$title = getBareGalleryTitle() . ' - ' . gettext('News');
				}
			}
			break;
		case 'pages.php':
			if (function_exists("is_Pages")) {
				$url = $_zp_current_zenpage_page->getLink();
				$title = $_zp_current_zenpage_page->getTitle();
			}
			break;
		default: //static custom pages
			$custompage = stripSuffix($_zp_gallery_page);
			if (is_null($staticpagetitle)) {
				// Handle some static custom pages we often have
				switch ($_zp_gallery_page) {
					case 'contact.php':
						$statictitle = gettext('Contact');
						break;
					case 'archive.php':
						$statictitle = gettext('Archive');
						break;
					case 'register.php':
						$statictitle = gettext('Register');
						break;
					case 'search.php':
						$statictitle = gettext('Search');
						break;
					default:
						$statictitle = strtoupper($custompage);
						break;
				}
			} else {
				$statictitle = $staticpagetitle;
			}
			$url = getCustomPageURL($custompage);
			$title = getBareGalleryTitle() . ' - ' . $statictitle;
			break;
	}

	//$content = strip_tags($title);
	//$desc = getContentShorten($title, 100, ' (…)', false);
	$title = urlencode($title);
	//$url = PROTOCOL . "://" . $_SERVER['HTTP_HOST'] . html_encode($url);
	$url = FULLWEBPATH . html_encode($url);
	if ($text) {
		echo $text;
	}
	if (getOption('scriptless_socialsharing_facebook')) {
		$buttons[] = array(
				'class' => 'icon-facebook-f',
				'title' => 'facebook',
				'url' => 'http://www.facebook.com/sharer/sharer.php?u=' . $url
		);
	}
	if (getOption('scriptless_socialsharing_twitter')) {
		$via = '';
		if (getOption('scriptless_socialsharing_twittername')) {
			$via = '&amp;via=' . html_encode(getOption('scriptless_socialsharing_twittername'));
		}
		$buttons[] = array(
				'class' => 'icon-twitter',
				'title' => 'Twitter',
				'url' => 'https://twitter.com/intent/tweet?text=' . $title . $via . '&amp;url=' . $url
		);
	}
	if (getOption('scriptless_socialsharing_gplus')) {
		$buttons[] = array(
				'class' => 'icon-google-plus',
				'title' => 'Google+',
				'url' => 'https://plus.google.com/share?url=' . $url
		);
	}
	if (getOption('scriptless_socialsharing_pinterest')) {
		$buttons[] = array(
				'class' => 'icon-pinterest-p',
				'title' => 'Pinterest',
				'url' => 'http://pinterest.com/pin/create/button/?url=' . $url . '&amp;description=' . $title . '&amp;media=' . $url
		);
	}
	if (getOption('scriptless_socialsharing_linkedin')) {
		$buttons[] = array(
				'class' => 'icon-linkedin',
				'title' => 'Linkedin',
				'url' => 'http://www.linkedin.com/shareArticle?mini=true&amp;url=' . $url . '>&amp;title=' . $title . '&amp;source=' . $url
		);
	}
	if (getOption('scriptless_socialsharing_xing')) {
		$buttons[] = array(
				'class' => 'icon-xing',
				'title' => 'Xing',
				'url' => 'https://www.xing-share.com/app/user?op=share;sc_p=xing-share;url=' . $url
		);
	}
	if (getOption('scriptless_socialsharing_reddit')) {
		$buttons[] = array(
				'class' => 'icon-reddit',
				'title' => 'Reddit',
				'url' => 'http://reddit.com/submit?url=' . $url . '/?socialshare&amp;title=' . $title
		);
	}

	if (getOption('scriptless_socialsharing_stumbleupon')) {
		$buttons[] = array(
				'class' => 'icon-stumbleupon',
				'title' => 'StumbleUpon',
				'url' => 'http://www.stumbleupon.com/submit?url=' . $url . '&amp;title=' . $title
		);
	}
	if (getOption('scriptless_socialsharing_tumblr')) {
		$buttons[] = array(
				'class' => 'icon-tumblr',
				'title' => 'Tumblr',
				'url' => 'http://www.tumblr.com/share/link?url=' . $url . '&amp;name=' . $title
		);
	}
	if (getOption('scriptless_socialsharing_whatsapp')) { // must be hidden initially!
		$buttons[] = array(
				'class' => 'icon-whatsapp',
				'title' => 'Whatsapp',
				'url' => 'WhatsApp://send?text=' . $url
		);
	}
	if (getOption('scriptless_socialsharing_digg')) {
		$buttons[] = array(
				'class' => 'icon-digg',
				'title' => 'Digg',
				'url' => 'http://digg.com/submit?url=' . $url . '&amp;title=' . $title
		);
	}
	if (getOption('scriptless_socialsharing_livejournal')) {
		$buttons[] = array(
				'class' => 'icon-pencil',
				'title' => 'Livejournal',
				'url' => 'http://www.livejournal.com/update.bml?url=' . $url . '&amp;subject=' . $title
		);
	}
	if (getOption('scriptless_socialsharing_buffer')) {
		$buttons[] = array(
				'class' => 'icon-stack',
				'title' => 'Buffer',
				'url' => 'http://bufferapp.com/add?text=' . $url . '&amp;url=' . $url
		);
	}
	if (getOption('scriptless_socialsharing_delicious')) {
		$buttons[] = array(
				'class' => 'icon-delicious',
				'title' => 'Delicious',
				'url' => 'https://delicious.com/save?v=5&amp;provider=' . $gallerytitle . '&amp;noui&amp;jump=close&amp;url=' . $url . '&amp;title=' . $title
		);
	}
	if (getOption('scriptless_socialsharing_evernote')) {
		$buttons[] = array(
				'class' => 'icon-evernote',
				'title' => 'Evernote',
				'url' => 'http://www.evernote.com/clip.action?url=' . $url . '&amp;title=' . $title
		);
	}
	if (getOption('scriptless_socialsharing_wordpress')) {
		$buttons[] = array(
				'class' => 'icon-wordpress',
				'title' => 'WordPress',
				'url' => 'http://wordpress.com/press-this.php?u=' . $url . '&amp;t=' . $title
		);
	}
	if (getOption('scriptless_socialsharing_pocket')) {
		$buttons[] = array(
				'class' => 'icon-get-pocket',
				'title' => 'Pocket',
				'url' => 'https://getpocket.com/save?url=' . $url . '&amp;title=' . $title
		);
	}
	if (getOption('scriptless_socialsharing_email')) {
		$buttons[] = array(
				'class' => 'icon-envelope-o',
				'title' => gettext('e-mail'),
				'url' => 'mailto:?subject=' . $title . '&amp;body=' . $url
		);
	}
	if(is_null($iconsonly)) {
		$iconsonly = getOption('scriptless_socialsharing_iconsonly');
	}
	?>
	<ul class="scriptless_socialsharing">
		<?php foreach($buttons as $button) { 
			$li_class = '';
			if($button['class'] == 'icon-whatsapp') {
				$li_class = ' class="whatsappLink hidden"';
			}
			?>
			<li<?php echo $li_class; ?>>
				<a class="<?php echo $button['class']; ?>" href="<?php echo $button['url']; ?>" title="<?php echo $button['title']; ?>" target="_blank">
					<?php
					if (!$iconsonly) {
						echo $button['title'];
					}
					?>
				</a>
			</li>
			<?php if($button['class'] == 'icon-whatsapp') { ?>
				<script>
					(navigator.userAgent.match(/(iPhone)/g)) ? $(“.whatsappLink”).removeClass('hidden') : null;
				</script>
			<?php } ?>
		<?php } ?>
	</ul>
	<?php
}
?>