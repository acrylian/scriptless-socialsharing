<?php
/**
 * A plugin that provides scriptless and privacy friendly sharing buttons for 
 * Facebook, Twitter, Google+, Pinterest, Linkedin and Xing. 
 * 
 * To have it work correctly you need to enable the html_meta_tags plugin 
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
 * @author Malte Müller (acrylian) <info@maltem.de>
 * @copyright 2014 Malte Müller
 * @license GPL v3 or later
 * @package plugins
 * @subpackage social
 */
$plugin_is_filter = 9 | THEME_PLUGIN;
$plugin_description = gettext('A plugin that provides scriptless and privacy friendly sharing buttons for Facebook, Twitter, Google+, Pinterest, Linkedin, Xing, reddit and stumbleupon.');
$plugin_author = 'Malte Müller (acrylian)';
$plugin_version = '1.1';
$option_interface = 'scriptless_socialsharing_options';
if (getOption('scriptless_socialsharing_iconfont')) {
  zp_register_filter('theme_head', 'scriptlesssocialsharingCSS');
}

class scriptless_socialsharing_options {

  /**
   * class instantiation function
   */
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
                'linkedin' => 'scriptless_socialsharing_linkedin',
                'xing' => 'scriptless_socialsharing_xing',
                'Reddit' => 'scriptless_socialsharing_reddit',
                'StumbleUpon' => 'scriptless_socialsharing_stumbleupon'
            ),
            'desc' => gettext('Select the social networks you wish buttons to appear for.')),
        gettext('Icon font and default CSS')
        => array('key' => 'scriptless_socialsharing_iconfont',
            'type' => OPTION_TYPE_CHECKBOX,
            'order' => 1,
            'desc' => gettext("Uncheck to disable loading to use your own theme based icon font."))
    );
    return $options;
  }

}

function scriptlesssocialsharingCSS() {
  ?>
  <link rel="stylesheet" href="<?php echo FULLWEBPATH . '/' . USER_PLUGIN_FOLDER; ?>/scriptless-socialsharing/style.css" type="text/css">
  <!--[if lt IE 8]><!-->
  <link rel="stylesheet" href="<?php echo FULLWEBPATH . '/' . USER_PLUGIN_FOLDER; ?>/scriptless-socialsharing/ie7.css">
  <!--<![endif]-->
  <?php
}

/**
 * Place this where you wish the buttons to appear. The plugin includes also jQUery calls to set the buttons up to allow multiple button sets per page.
 *  
 * @param string $text Text to be displayed before the sharing list. HTML code allowed. Default "<h4>Share</h4>"
 */
function printScriptlessSocialSharingButtons($text = '<h4>Share</h4>') {
  global $_zp_gallery, $_zp_gallery_page, $_zp_current_album, $_zp_current_image, $_zp_current_zenpage_news, $_zp_current_zenpage_page, $_zp_current_category;
  $title = '';
  $desc = '';
  $url = '';
  $imgsource = '';
  switch ($_zp_gallery_page) {
    default:
    case 'index.php':
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
        } else {
          $url = $_zp_current_category->getLink();
          $title = $_zp_current_category->getTitle();
        }
      }
    case 'pages.php':
      if (function_exists("is_Pages")) {
        $url = $_zp_current_zenpage_page->getLink();
        $title = $_zp_current_zenpage_page->getTitle();
      }
      break;
  }
  //$content = strip_tags($title);
  //$desc = getContentShorten($title, 100, ' (…)', false);
  $url = PROTOCOL . "://" . $_SERVER['HTTP_HOST'].html_encode($url);
  if($text) {
     echo $text; 
  }
  ?>
  <ul class="scriptless_socialsharing">
  		<?php if (getOption('scriptless_socialsharing_facebook')) { ?>
    		<li><a class="icon-facebook" href="http://www.facebook.com/sharer/sharer.php?u=<?php echo $url; ?>" title="Facebook">Facebook</a></li>
      <?php } ?>

    <?php if (getOption('scriptless_socialsharing_twitter')) { ?>
    		<li><a class="icon-twitter" href="https://twitter.com/intent/tweet?text=<?php echo $title; ?>&amp;url=<?php echo $url; ?>" title="Twitter">Twitter</a></li>
    <?php } ?>

    <?php if (getOption('scriptless_socialsharing_pinterest')) { ?>
    		<li><a class="icon-pinterest" href="http://pinterest.com/pin/create/button/?url=<?php echo $url; ?>&amp;description=<?php echo $title; ?>&amp;media=<?php echo $url; ?>" title="Pinterest">Pinterest</a></li>
    <?php } ?>

    <?php if (getOption('scriptless_socialsharing_gplus')) { ?>
    		<li><a class="icon-google-plus" href="https://plus.google.com/share?url=<?php echo $url; ?>" title="Google+">Google+</a></li>
    <?php } ?>

    <?php if (getOption('scriptless_socialsharing_linkedin')) { ?>
    		<li><a class="icon-linkedin" href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo $url; ?>&amp;title=<?php echo $title; ?>&amp;source=<?php echo $url; ?>" title="LinkedIn">LinkedIn</a></li>
    <?php } ?>
    <?php if (getOption('scriptless_socialsharing_xing')) { ?>
    		<li><a class="icon-xing" href="https://www.xing-share.com/app/user?op=share;sc_p=xing-share;url=<?php echo $url; ?>" title="Xing">Xing</a></li>
      <?php } ?>
  	<?php if (getOption('scriptless_socialsharing_reddit')) { ?>
  			<li><a class="icon-reddit" href="http://reddit.com/submit?url=<?php echo $url; ?>/?socialshare&amp;title=<?php echo $title; ?>" title="Reddit">Reddit</a></li>
  	<?php } ?>
  	<?php if (getOption('scriptless_socialsharing_stumbleupon')) { ?>
  			<li><a class="icon-stumbleupon" href=" http://www.stumbleupon.com/badge/?url=<?php echo $url; ?>/?socialshare" title="StumbleUpon">StumbleUpon</a></li>
  	<?php } ?>
  </ul>
  <?php
}
?>