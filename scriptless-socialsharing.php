<?php
/**
 * A Zenphoto plugin that provides scriptless and privacy friendly sharing buttons for Facebook, Twitter, Google+, Pinterest, 
 * Linkedin, Xing, Reddit, Stumbleupon, Tumblr, WhatsApp (iOS only) and e-mail. (Note: No share counts because of that!).
 * 
 * To have it work correctly you should to enable the html_meta_tags plugin 
 * and the Open Graph (og:) meta data elements.
 *
 * The plugin loads an default CSS styling using an icon font optionally. If you wish to use theme based custom icons 
 * and css to avoid extra loading you can disable it.
 *
 * Icons from the icon font "fontawesome.io" via the icomoon app 
 * http://icomoon.io/#icons-icomoon
 * License: GPL / CC BY 30
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
$plugin_version = '1.2';
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
						'checkboxes' => array(// The definition of the checkboxes
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
								gettext('E-mail') => 'scriptless_socialsharing_email'
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
function printScriptlessSocialSharingButtons($text = '', $staticpagetitle = NULL) {
	global $_zp_gallery, $_zp_gallery_page, $_zp_current_album, $_zp_current_image, $_zp_current_zenpage_news, $_zp_current_zenpage_page, $_zp_current_category;
	$title = '';
	$desc = '';
	$url = '';
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
	$url = PROTOCOL . "://" . $_SERVER['HTTP_HOST'] . html_encode($url);
	if ($text) {
		echo $text;
	}
	$buttontitles = array();
	if (!getOption('scriptless_socialsharing_iconsonly')) {
		$buttontitles = array(// The definition of the checkboxes
				'Facebook',
				'Twitter',
				'Google+',
				'Pinterest',
				'linkedin',
				'Xing',
				'Reddit',
				'StumbleUpon',
				'Tumblr',
				'Whatsapp',
				gettext('E-Mail')
		);
	}
	?>
	<ul class="scriptless_socialsharing">
		<?php if (getOption('scriptless_socialsharing_facebook')) { ?>
			<li>
				<a class="icon-facebook" href="http://www.facebook.com/sharer/sharer.php?u=<?php echo $url; ?>" title="Facebook">
					<?php
					if (!empty($buttontitles)) {
						echo $buttontitles[0];
					}
					?>
				</a>
			</li>
		<?php } ?>

		<?php
		if (getOption('scriptless_socialsharing_twitter')) {
			$via = '';
			if (getOption('scriptless_socialsharing_twittername')) {
				$via = '&amp;via=' . getOption('scriptless_socialsharing_twittername');
			}
			?>
			<li>
				<a class="icon-twitter" href="https://twitter.com/intent/tweet?text=<?php echo $title . $via; ?>&amp;url=<?php echo $url; ?>" title="Twitter">
					<?php
					if (!empty($buttontitles)) {
						echo $buttontitles[1];
					}
					?>
				</a>
			</li>
	<?php } ?>



				<?php if (getOption('scriptless_socialsharing_gplus')) { ?>
			<li><a class="icon-google-plus" href="https://plus.google.com/share?url=<?php echo $url; ?>" title="Google+">
					<?php
					if (!empty($buttontitles)) {
						echo 'Google+';
					}
					?>
				</a>
			</li>
		<?php } ?>

				<?php if (getOption('scriptless_socialsharing_pinterest')) { ?>
			<li>
				<a class="icon-pinterest-p" href="http://pinterest.com/pin/create/button/?url=<?php echo $url; ?>&amp;description=<?php echo $title; ?>&amp;media=<?php echo $url; ?>" title="Pinterest">
					<?php
					if (!empty($buttontitles)) {
						echo 'Pinterest';
					}
					?>
				</a>
			</li>
		<?php } ?>		

		<?php if (getOption('scriptless_socialsharing_linkedin')) { ?>
			<li>
				<a class="icon-linkedin" href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo $url; ?>&amp;title=<?php echo $title; ?>&amp;source=<?php echo $url; ?>" title="LinkedIn">
					<?php
					if (!empty($buttontitles)) {
						echo 'linkedin';
					}
					?>
				</a>
			</li>
		<?php } ?>
			
		<?php if (getOption('scriptless_socialsharing_xing')) { ?>
			<li>
				<a class="icon-xing" href="https://www.xing-share.com/app/user?op=share;sc_p=xing-share;url=<?php echo $url; ?>" title="Xing">
					<?php
					if (!empty($buttontitles)) {
						echo 'Xing';
					}
					?>
				</a>
			</li>
		<?php } ?>
			
		<?php if (getOption('scriptless_socialsharing_reddit')) { ?>
			<li>
				<a class="icon-reddit" href="http://reddit.com/submit?url=<?php echo $url; ?>/?socialshare&amp;title=<?php echo $title; ?>" title="Reddit">
					<?php
					if (!empty($buttontitles)) {
						echo 'Reddit';
					}
					?>
				</a>
			</li>
		<?php } ?>
			
		<?php if (getOption('scriptless_socialsharing_stumbleupon')) { ?>
			<li>
				<a class="icon-stumbleupon" href="http://www.stumbleupon.com/badge/?url=<?php echo $url; ?>/?socialshare" title="StumbleUpon">
					<?php
					if (!empty($buttontitles)) {
						echo 'Stumbleupon';
					}
					?>
				</a>
			</li>
		<?php } ?>
			
		
		<?php if (getOption('scriptless_socialsharing_tumblr')) { ?>	
			<li>
				<a class="icon-tumblr" href="http://www.tumblr.com/share/link?url=<?php echo $url; ?>&amp;name=<?php echo $title; ?>" title="Tumblr">
					<?php
					if (!empty($buttontitles)) {
						echo 'tumblr';
					}
					?>
				</a>
			</li>
		<?php } ?>		
		<?php if (getOption('scriptless_socialsharing_whatsapp')) { ?>		
			<li class="whatsappLink">
				<a class="icon-whatsapp" href="WhatsApp://send?text=<?php echo $url; ?>" title="Whatsapp">
					<?php
					if (!empty($buttontitles)) {
						echo 'WhatsApp';
					}
					?>
				</a>
			</li>
			<script>
				(navigator.userAgent.match(/(iPhone)/g)) ? $(“.whatsappLink”).fadeIn() : null;
			</script>
	<?php } ?>
			<?php if (getOption('scriptless_socialsharing_email')) { ?>
			<li>
				<a class="icon-envelope-o" href="mailto:?subject=<?php echo $title; ?>&amp;body=<?php echo $url; ?>" title="<?php echo html_encode(gettext('E-mail')); ?>">
					<?php
					if (!empty($buttontitles)) {
						echo gettext('E-mail');
					}
					?>
				</a>
			</li>
		<?php } ?>
	</ul>
	<?php
}
?>