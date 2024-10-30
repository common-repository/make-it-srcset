<?php

/**
 * Add needed image formats
 */

function mis_new_imgsizes() {
    if (function_exists('add_theme_support')) {
        add_theme_support('post-thumbnails');
    }

    if (function_exists('add_image_size')) {
        add_image_size('mis_imgSize_fatscreen', mis_get_option_integer('mis_imgWidth_fatscreen'));
        add_image_size('mis_imgSize_fourthMq', mis_get_option_integer('mis_imgWidth_fourthMq'));
        add_image_size('mis_imgSize_thirdMq', mis_get_option_integer('mis_imgWidth_thirdMq'));
        add_image_size('mis_imgSize_secondMq', mis_get_option_integer('mis_imgWidth_secondMq'));
        add_image_size('mis_imgSize_firstMq', mis_get_option_integer('mis_imgWidth_firstMq'));
        add_image_size('mis_imgSize_noMq_R', mis_get_option_integer('mis_imgWidth_noMq_R'));
        add_image_size('mis_imgSize_noMq', mis_get_option_integer('mis_imgWidth_noMq'));
        add_image_size('mis_imgSize_xs', 160);
    }
}

add_action('after_setup_theme', 'mis_new_imgsizes');



/** Srcset HTML-builder (for shortcodes and add_filter for the_content) / Template tag
 * @param null $mis_attachment_id
 * @param null $mis_srcsetSize_noMq
 * @param null $mis_srcsetSize_firstMq
 * @param null $mis_srcsetSize_secondMq
 * @param null $mis_srcsetSize_thirdMq
 * @param null $mis_srcsetSize_fourthMq
 * @param null $mis_parent_css_class
 * @param null $mis_figcaption
 * @param null $mis_enablepopup
 * @param bool $mis_filter_the_content
 * @return array
 */

function makeitSrcset(
$mis_attachment_id = null,
$mis_srcsetSize_noMq = null,
$mis_srcsetSize_firstMq = null,
$mis_srcsetSize_secondMq = null,
$mis_srcsetSize_thirdMq = null,
$mis_srcsetSize_fourthMq = null,
$mis_parent_css_class = null,
$mis_figcaption = null,
$mis_enablepopup = null){

    /**
     * Vars: Set srcset sizes
     */

    $mis_srcsetSize_noMq = (is_null($mis_srcsetSize_noMq) || empty($mis_srcsetSize_noMq)) ? mis_get_option_integer('mis_srcsetSize_noMq') : preg_replace('/[^0-9]+/', '', $mis_srcsetSize_noMq);
    $mis_srcsetSize_firstMq = (is_null($mis_srcsetSize_firstMq) || empty($mis_srcsetSize_firstMq)) ? mis_get_option_integer('mis_srcsetSize_firstMq') : preg_replace('/[^0-9]+/', '', $mis_srcsetSize_firstMq);
    $mis_srcsetSize_secondMq = (is_null($mis_srcsetSize_secondMq) || empty($mis_srcsetSize_secondMq)) ? mis_get_option_integer('mis_srcsetSize_secondMq') : preg_replace('/[^0-9]+/', '', $mis_srcsetSize_secondMq);
    $mis_srcsetSize_thirdMq = (is_null($mis_srcsetSize_thirdMq) || empty($mis_srcsetSize_thirdMq)) ? mis_get_option_integer('mis_srcsetSize_thirdMq') : preg_replace('/[^0-9]+/', '', $mis_srcsetSize_thirdMq);
    $mis_srcsetSize_fourthMq = (is_null($mis_srcsetSize_fourthMq) || empty($mis_srcsetSize_fourthMq)) ? mis_get_option_integer('mis_srcsetSize_fourthMq') : preg_replace('/[^0-9]+/', '', $mis_srcsetSize_fourthMq);

    /**
     * Vars: Set image-vars IF there is an attachment ID passed as an integer and if that attachment is an image. If not give a link to documentation
     */

    if (is_numeric($mis_attachment_id) && isset($mis_attachment_id) && wp_attachment_is_image($mis_attachment_id)) {
        $mis_imgSize_fatscreen = wp_get_attachment_image_src($mis_attachment_id, 'mis_imgSize_fatscreen');
        $mis_imgSize_fourthMq = wp_get_attachment_image_src($mis_attachment_id, 'mis_imgSize_fourthMq');
        $mis_imgSize_thirdMq = wp_get_attachment_image_src($mis_attachment_id, 'mis_imgSize_thirdMq');
        $mis_imgSize_secondMq = wp_get_attachment_image_src($mis_attachment_id, 'mis_imgSize_secondMq');
        $mis_imgSize_firstMq = wp_get_attachment_image_src($mis_attachment_id, 'mis_imgSize_firstMq');
        $mis_imgSize_noMq_R = wp_get_attachment_image_src($mis_attachment_id, 'mis_imgSize_noMq_R');
        $mis_imgSize_noMq = wp_get_attachment_image_src($mis_attachment_id, 'mis_imgSize_noMq');
        $mis_imgSize_xs = wp_get_attachment_image_src($mis_attachment_id, 'mis_imgSize_xs');
        $mis_alt = get_post_meta($mis_attachment_id, '_wp_attachment_image_alt', true);
        $mis_img = get_post($mis_attachment_id);
        $mis_filename = $mis_img->post_name;

    } else {

        /**
         * Error msg for omitting attachment-ID
         */

        echo '<script>console.log("Hi! makeitSrcset() / [makeitSrcset]- shortcode needs the attachment-ID for the image you want to show. Read up on: http://note-to-helf.com/wordpress-plugin-make-it-srcset");</script>';
        return;
    }

    /**
     * Var: Css-classes for srcset parent element
     */

    $mis_imgParent_cssClass = (is_null($mis_parent_css_class) || empty($mis_parent_css_class) ? '' : ' '.$mis_parent_css_class);

    /**
     * Var: Parent container tag (if figcaption exists make it a figure-element)
     */

    if (is_null($mis_figcaption) || empty($mis_figcaption)) {
        $mis_containerTag = '<div class="mis_container mis_div'.$mis_imgParent_cssClass.'">';
    } else {
        $mis_containerTag = '<figure class="mis_container mis_figure'.$mis_imgParent_cssClass.'">';
    }

    /**
     * Var: Img tag
     */

    $mis_imgTag = '<img class="mis_img'.(mis_get_option_boolean('mis_lazyload') ? ' lazyload' : '').(is_null($mis_enablepopup) || empty($mis_enablepopup) ? '' : ' mis_popup').'" data-misid="mis_img-'.$mis_attachment_id.'"'.($mis_alt ? ' alt="'.$mis_alt.'"' : ' alt="'.$mis_filename.'"').(mis_get_option_boolean('mis_lazyload') ? ' data-srcset':' srcset').'=';

    /**
     * Var: Srcset-src
     */

    $mis_imgSize_original = wp_get_attachment_image_src($mis_attachment_id, 'full');
    $mis_original_attr = $mis_imgSize_original[0] . ' ' . $mis_imgSize_original[1] . 'w, ';
    $mis_fatscreenSize_attr = $mis_imgSize_fatscreen[0] . ' ' . $mis_imgSize_fatscreen[1] . 'w, ';
    $mis_fourthMqSize_attr = $mis_imgSize_fourthMq[0] . ' ' . $mis_imgSize_fourthMq[1] . 'w, ';
    $mis_thirdhMqSize_attr = $mis_imgSize_thirdMq[0] . ' ' . $mis_imgSize_thirdMq[1] . 'w, ';
    $mis_secondMqSize_attr = $mis_imgSize_secondMq[0] . ' ' . $mis_imgSize_secondMq[1] . 'w, ';
    $mis_firstMqSize_attr = $mis_imgSize_firstMq[0] . ' ' . $mis_imgSize_firstMq[1] . 'w, ';
    $mis_noMq_RSize_attr = $mis_imgSize_noMq_R[0] . ' ' . $mis_imgSize_noMq_R[1] . 'w, ';
    $mis_noMqSize_attr = $mis_imgSize_noMq[0] . ' ' . $mis_imgSize_noMq[1] . 'w, ';
    $mis_xsSize_attr = $mis_imgSize_xs[0] . ' ' . $mis_imgSize_xs[1] . 'w';

    if($mis_imgSize_fatscreen[3]){
        $mis_srcsetImages = $mis_fatscreenSize_attr . $mis_fourthMqSize_attr . $mis_thirdhMqSize_attr . $mis_secondMqSize_attr . $mis_firstMqSize_attr . $mis_noMq_RSize_attr . $mis_noMqSize_attr . $mis_xsSize_attr;
    } elseif($mis_imgSize_fourthMq[3]){
        $mis_srcsetImages = $mis_original_attr . $mis_fourthMqSize_attr . $mis_thirdhMqSize_attr . $mis_secondMqSize_attr . $mis_firstMqSize_attr . $mis_noMq_RSize_attr . $mis_noMqSize_attr . $mis_xsSize_attr;
    } elseif($mis_imgSize_thirdMq[3]){
        $mis_srcsetImages = $mis_original_attr . $mis_thirdhMqSize_attr . $mis_secondMqSize_attr . $mis_firstMqSize_attr . $mis_noMq_RSize_attr . $mis_noMqSize_attr . $mis_xsSize_attr;
    } elseif($mis_imgSize_secondMq[3]){
        $mis_srcsetImages = $mis_original_attr . $mis_secondMqSize_attr . $mis_firstMqSize_attr . $mis_noMq_RSize_attr . $mis_noMqSize_attr . $mis_xsSize_attr;
    } elseif($mis_imgSize_firstMq[3]){
        $mis_srcsetImages = $mis_original_attr . $mis_firstMqSize_attr . $mis_noMq_RSize_attr . $mis_noMqSize_attr . $mis_xsSize_attr;
    } elseif($mis_imgSize_noMq_R[3]){
        $mis_srcsetImages = $mis_original_attr . $mis_noMq_RSize_attr . $mis_noMqSize_attr . $mis_xsSize_attr;
    } elseif($mis_imgSize_noMq[3]){
        $mis_srcsetImages = $mis_original_attr . $mis_noMqSize_attr . $mis_xsSize_attr;
    } else {

        /**
         * Attachment has not needed imageformats (aka uploaded before plugin was active) - use built in wp-formats
         */

        $mis_imgSize_fatscreen = wp_get_attachment_image_src($mis_attachment_id, 'full');
        $mis_img_defaultLarge = wp_get_attachment_image_src($mis_attachment_id, 'large');
        $mis_img_defaultMedium = wp_get_attachment_image_src($mis_attachment_id, 'medium');
        $mis_img_defaultThumb = wp_get_attachment_image_src($mis_attachment_id, 'thumbnail');

        $mis_srcsetImages =
            $mis_imgSize_fatscreen[0] . ' ' . $mis_imgSize_fatscreen[1] . 'w, ' .
            $mis_img_defaultLarge[0] . ' ' . $mis_img_defaultLarge[1] . 'w, ' .
            $mis_img_defaultMedium[0] . ' ' . $mis_img_defaultMedium[1] . 'w, ' .
            $mis_img_defaultThumb[0] . ' ' . $mis_img_defaultThumb[1] . 'w';
    }

    /**
     * Var: Srcset-sizes and Srcset-mediaqueries
     */

    $mis_srcsetSizes = '(min-width: '.$mis_imgSize_fourthMq[1].'px) '.$mis_srcsetSize_fourthMq.'vw, (min-width: '.$mis_imgSize_thirdMq[1].'px) '.$mis_srcsetSize_thirdMq.'vw, (min-width: '.$mis_imgSize_secondMq[1].'px) '.$mis_srcsetSize_secondMq.'vw, (min-width: '.$mis_imgSize_firstMq[1].'px) '.$mis_srcsetSize_firstMq.'vw, '. $mis_srcsetSize_noMq.'vw';

    /**
     * Var: Endtag img
     */

    $mis_closeImgTag = '/>';

    /**
     * Var: Figcaption
     */

    if (is_null($mis_figcaption) || empty($mis_figcaption)) {
        $mis_figcaptionTag = '';
    } else {
        $mis_figcaptionTag = '<figcaption class="mis_figcaption">'.$mis_figcaption.'</figcaption>';
    }

    /**
     * Var: Fallback img in noscript-tag
     */

    $mis_noscriptTag = '<noscript class="mis_noscript"><img class="mis_img mis_nojs" src="'.($mis_imgSize_xs[3] ? $mis_imgSize_secondMq[0] : $mis_img_defaultLarge[0]).'"'.($mis_alt ? ' alt="'.$mis_alt.'"' : ' alt="'.$mis_filename.'"').'/></noscript>';

    /**
     * Var: Endtag parent container
     */

    if (is_null($mis_figcaption) || empty($mis_figcaption)) {
        $mis_closeImgContainer = '</div>';
    } else {
        $mis_closeImgContainer = '</figure>';
    }

    /**
     * BUILD HTML
     */

    echo $mis_containerTag.$mis_imgTag.'"'.$mis_srcsetImages.'" sizes="'.$mis_srcsetSizes.'"'.$mis_closeImgTag.$mis_figcaptionTag.$mis_noscriptTag.$mis_closeImgContainer;
}



/**
 * Shortcode
 */

if (mis_get_option_boolean('mis_shortcode')) {
    add_shortcode('makeitSrcset', 'mis_shortcode');
}

function mis_shortcode($atts){
    extract(shortcode_atts(
        array(
            'image_id' => null,
            'srcsetSize_noMq' => null,
            'srcsetSize_firstMq' => null,
            'srcsetSize_secondMq' => null,
            'srcsetSize_thirdMq' => null,
            'srcsetSize_fourthMq' => null,
            'parent_css_class' => null,
            'figcaption' => null,
            'popup' => null
        ), $atts));

    /**
     * Reference og_get_contents: https://wordpress.org/support/topic/plugin-called-via-shortcode-appears-at-the-wrong-place-on-post?replies=5
     */

    ob_start();
        makeitSrcset($image_id, $srcsetSize_noMq, $srcsetSize_firstMq, $srcsetSize_secondMq, $srcsetSize_thirdMq, $srcsetSize_fourthMq, $parent_css_class, $figcaption, $popup);
        $mis_shortcode = ob_get_contents();
    ob_end_clean();

    return $mis_shortcode;
}

if (mis_get_option_boolean('mis_shortcodeGen')) {
    add_filter('image_send_to_editor', 'mis_mlib_shortcode_gen', 10, 9);
}


/** Generate shortcode from media uploader, to editor
 * @param $html
 * @param $id
 * @param $caption
 * @param $title
 * @param $align
 * @param $url
 * @return string
 */

function mis_mlib_shortcode_gen($html, $id, $caption, $title, $align, $url) {
    return "[makeitSrcset image_id='$id' srcsetSize_noMq='' srcsetSize_firstMq='' srcsetSize_secondMq='' srcsetSize_thirdMq='' srcsetSize_fourthMq='' parent_css_class='$align' figcaption='' popup='']";
}


/**
 * Prevent duplicate images for browsers that support Srcset but have javascript turned off
 */

if (mis_get_option_boolean('mis_preventDuplicates')) {
    add_action('wp_head','mis_nojs_style');
}

function mis_nojs_style(){
    $output="<style>.no-js .mis_container > .mis_img{display:none}</style>";
    echo $output;
}



/** Add async attributes to mis_enqueue_scripts-files
 * @param $url
 * @return mixed|string
 */

function mis_async_forscript($url){
    if (strpos($url, '#mis_asyncload')===false)
        return $url;
    else if (is_admin())
        return str_replace('#mis_asyncload', '', $url);
    else
        return str_replace('#mis_asyncload', '', $url)."' async='async";
}



/**
 * Enqueue scripts
 */

function mis_enqueue_scripts(){
    $mis_userpathPicturefill = mis_get_option_url('mis_userpathPicturefill');
    $mis_userpathLazyload = mis_get_option_url('mis_userpathLazyload');

    /**
     * If user want all built in scripts, enqueue a bundled version...
     */

    if (mis_get_option_boolean('mis_picturefill') && mis_get_option_boolean('mis_lazyload') && empty($mis_userpathPicturefill) && empty($mis_userpathLazyload)) {
        wp_enqueue_script('mis_bundled', plugins_url('/mis_vendor/mis_bundled.min.js#mis_asyncload', __FILE__), array(), null, false);
    } else {

        /**
         * ...if not all built in scripts, check if they want picturefill at all...
         */

        if (mis_get_option_boolean('mis_picturefill')) {

            /**
             * ...yes? do they want their own / updated version?
             */

            if(empty($mis_userpathPicturefill)){

                /**
                 * ... no? Run built in picurefill
                 */

                wp_enqueue_script('mis_picturefill', plugins_url('/mis_vendor/mis_picturefill.min.js#mis_asyncload', __FILE__), array(), null, false);
            } else {

                /**
                 * ... Yes? Run picturefill user path
                 */

                wp_enqueue_script('picturefill', $mis_userpathPicturefill.'#mis_asyncload', array(), null, false);
            }
        }

        /**
         * ...if not all built in scripts, check if they want lazyload at all...
         */

        if (mis_get_option_boolean('mis_lazyload')) {

            /**
             * ...yes? do they want their own / updated version?
             */

            if(empty($mis_userpathLazyload)){

                /**
                 * ... no? Run built in Lazysizes
                 */

                wp_enqueue_script('mis_lazysizes', plugins_url('/mis_vendor/mis_lazysizes.min.js#mis_asyncload', __FILE__), array(), null, false);
            } else {

                /**
                 * ... Yes? Run Lazysizes user path
                 */

                wp_enqueue_script('lazysizes', $mis_userpathLazyload.'#mis_asyncload', array(), null, false);
            }
        }
    }

    if(mis_get_option_boolean('mis_popup')){
        wp_enqueue_style( 'mis_popup_style', plugins_url('/mis_styles/mis_popup.css', __FILE__), array(), null, 'all' );
        wp_enqueue_script('mis_popup_script', plugins_url('/mis_scripts/mis_popup.js', __FILE__), array('jquery'), null, true);
    }

    add_filter('clean_url', 'mis_async_forscript', 11, 1);
}