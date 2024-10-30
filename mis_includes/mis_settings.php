<?php

/**
 * Menu build
 */

function mis_main_tab() {
	add_plugins_page(
		'Make it Srcset',
		'Make it Srcset',
		'manage_options',
		'mis_plugin_options',
		'mis_plugin_menu'
	);
}
add_action('admin_menu', 'mis_main_tab');

function mis_plugin_menu() { ?>
	<div class="wrap">
		<div id="icon-plugins" class="icon32"></div>
		<h2><?php _e('Make it Srcset: Settings', 'makeitSrcset'); ?></h2>
		<?php settings_errors(); ?>

		<form method="post" action="options.php">
			<?php
			settings_fields('mis_plugin_options');
			do_settings_sections('mis_plugin_options');
			submit_button(); ?>
		</form>
	</div>
<?php }



/** Default options
 * @return mixed|void
 */

function mis_plugin_default_options() {
	$defaults = array(
        'mis_imgWidth_noMq'  => '320',
		'mis_imgWidth_noMq_R'  => '480',
		'mis_imgWidth_firstMq'  => '768',
		'mis_imgWidth_secondMq'  => '1024',
		'mis_imgWidth_thirdMq'  => '1280',
		'mis_imgWidth_fourthMq'  => '1440',
        'mis_imgWidth_fatscreen' => '1680',
        'mis_srcsetSize_noMq' => '100',
        'mis_srcsetSize_firstMq' => '100',
        'mis_srcsetSize_secondMq' => '100',
        'mis_srcsetSize_thirdMq' => '100',
        'mis_srcsetSize_fourthMq' => '100',
        'mis_userpathPicturefill' => '',
        'mis_userpathLazyload' => ''
	);
	return apply_filters( 'mis_plugin_default_options', $defaults );
}



/**
 * INIT
 */

function mis_plugin_initialize_options() {
	if(false == get_option('mis_plugin_options')) {
		add_option('mis_plugin_options', apply_filters('mis_plugin_default_options', mis_plugin_default_options()));
	}




    /** Screen extremes options -------------------------------------------------------------------------------------------------------------------------
     * Settings section for mis_imagewidths
     */

    add_settings_section(
        'mis_imagewidths',
        __('1. Screen ranges', 'makeitSrcset'),
        'mis_imagewidths_callback',
        'mis_plugin_options'
    );

    /**
     * Settings section callback for mis_imagewidths
     */

    function mis_imagewidths_callback() {
        echo '<p>' . __( '<strong>Aka:</strong> What are the needs in pixels for your smallest / fatests screens?<br/><br/><strong>Width</strong> refers the physical width of the screen.<br/><strong>Hires-image-width</strong> refers to what size the image needs to stay sharp on hi-res screens, such as retina.', 'makeitSrcset' ) . '</p>';
    }

    /**
     * Setting fields for mis_imagewidths
     */

    add_settings_field(
        'mis_imgWidth_noMq',
        '1.1.1 Width, smallest targeted screen:',
        'mis_imgWidth_noMq_callback',
        'mis_plugin_options',
        'mis_imagewidths',
        array(        
            __('px', 'makeitSrcset'),
        )
    );

    add_settings_field(
        'mis_imgWidth_noMq_R',
        '1.1.2 Hires-image-width, smallest targeted screen:',
        'mis_imgWidth_noMq_R_callback',
        'mis_plugin_options',
        'mis_imagewidths',
        array(        
            __('px <em>(Should be at least 1.5 times the value set in 1.1.1)</em>', 'makeitSrcset'),
        )
    );

    add_settings_field(
        'mis_imgWidth_fatscreen',
        '1.2 Width, fatest targeted screen:',
        'mis_imgWidth_fatscreen_callback',
        'mis_plugin_options',
        'mis_imagewidths',
        array(        
            __('px', 'makeitSrcset'),
        )
    );

    /** Setting fields callbacks for mis_imagewidths
     * @param $args
     */

    function mis_imgWidth_noMq_callback($args) {
        $options = get_option( 'mis_plugin_options' );
        $mis_imgWidth_noMq = $options['mis_imgWidth_noMq'];
        $html = '<input type="text" id="mis_imgWidth_noMq" name="mis_plugin_options[mis_imgWidth_noMq]" value="' . preg_replace('/[^0-9]+/', '', $mis_imgWidth_noMq ) . '" />';
        $html .= '<label for="mis_imgWidth_noMq">&nbsp;'  . $args[0] . '</label>';
        echo $html;
    }

    function mis_imgWidth_noMq_R_callback($args) {
        $options = get_option( 'mis_plugin_options' );
        $mis_imgWidth_noMq_R = $options['mis_imgWidth_noMq_R'];
        $html = '<input type="text" id="mis_imgWidth_noMq_R" name="mis_plugin_options[mis_imgWidth_noMq_R]" value="' . preg_replace('/[^0-9]+/', '', $mis_imgWidth_noMq_R ) . '" />';
        $html .= '<label for="mis_imgWidth_noMq_R">&nbsp;'  . $args[0] . '</label>';
        echo $html;
    }

    function mis_imgWidth_fatscreen_callback($args)
    {
        $options = get_option('mis_plugin_options');
        $mis_imgWidth_fatscreen = $options['mis_imgWidth_fatscreen'];
        $html = '<input type="text" id="mis_imgWidth_fatscreen" name="mis_plugin_options[mis_imgWidth_fatscreen]" value="' . preg_replace('/[^0-9]+/', '', $mis_imgWidth_fatscreen) . '" />';
        $html .= '<label for="mis_imgWidth_fatscreen">&nbsp;' . $args[0] . '</label>';
        echo $html;
    }




    /** Mediaqueries options -------------------------------------------------------------------------------------------------------------------------
     * Settings section for mis_mediaqueries
     */

	add_settings_section(
		'mis_mediaqueries',
		__('2.1 Srcset sizes &mdash; breakpoints', 'makeitSrcset'),
		'mis_mediaqueries_callback',
		'mis_plugin_options'
	);

    /**
     * Settings section callback for mis_mediaqueries
     */

    function mis_mediaqueries_callback() {
        echo '<p>' . __( '<strong>Make it Srcset</strong> uses four min-width mediaqueries, <em>within the sizes-attribute for each image</em>. Add your default values in ascending order.<br/><strong>Tip:</strong> Use the same breakpoints as your themes layout do if you are using min-width mediaqueries. If you are using fewer media queries in your layout than four &mdash; <em>enter common screensizes (higher than your final breakpoint) to fill the blanks.</em>', 'makeitSrcset' ) . '</p>';
    }

    /**
     * Settings fields for mis_mediaqueries
     */

	add_settings_field(
		'mis_imgWidth_firstMq',
		'2.1.1 First breakpoint:',
		'mis_imgSizeFirstMq_callback',
		'mis_plugin_options',
		'mis_mediaqueries',
		array(        
			__('px', 'makeitSrcset'),
		)
	);

	add_settings_field(
		'mis_imgWidth_secondMq',
		'2.1.2 Second breakpoint:',
		'mis_imgSizeSecondMq_callback',
		'mis_plugin_options',
		'mis_mediaqueries',
		array(        
			__('px', 'makeitSrcset'),
		)
	);

	add_settings_field(
		'mis_imgWidth_thirdMq',
		'2.1.3 Third breakpoint:',
		'mis_imgSizeThirdMq_callback',
		'mis_plugin_options',
		'mis_mediaqueries',
		array(        
			__('px', 'makeitSrcset'),
		)
	);

	add_settings_field(
		'mis_imgWidth_fourthMq',
		'2.1.4 Fourth breakpoint:',
		'mis_imgSizeFourthMq_callback',
		'mis_plugin_options',
		'mis_mediaqueries',
		array(        
			__('px', 'makeitSrcset'),
		)
	);

    /** Setting fields callbacks for mis_mediaqueries
     * @param $args
     */

    function mis_imgSizeFirstMq_callback($args) {
        $options = get_option( 'mis_plugin_options' );
        $betaQuery = $options['mis_imgWidth_firstMq'];
        $html = '<input type="text" id="mis_imgWidth_firstMq" name="mis_plugin_options[mis_imgWidth_firstMq]" value="' . preg_replace('/[^0-9]+/', '', $betaQuery ) . '" />';
        $html .= '<label for="mis_imgWidth_firstMq">&nbsp;'  . $args[0] . '</label>';
        echo $html;
    }

    function mis_imgSizeSecondMq_callback($args) {
        $options = get_option( 'mis_plugin_options' );
        $charlieQuery = $options['mis_imgWidth_secondMq'];
        $html = '<input type="text" id="mis_imgWidth_secondMq" name="mis_plugin_options[mis_imgWidth_secondMq]" value="' . preg_replace('/[^0-9]+/', '', $charlieQuery ) . '" />';
        $html .= '<label for="mis_imgWidth_secondMq">&nbsp;'  . $args[0] . '</label>';
        echo $html;
    }

    function mis_imgSizeThirdMq_callback($args) {
        $options = get_option( 'mis_plugin_options' );
        $deltaQuery = $options['mis_imgWidth_thirdMq'];
        $html = '<input type="text" id="mis_imgWidth_thirdMq" name="mis_plugin_options[mis_imgWidth_thirdMq]" value="' . preg_replace('/[^0-9]+/', '', $deltaQuery ) . '" />';
        $html .= '<label for="mis_imgWidth_thirdMq">&nbsp;'  . $args[0] . '</label>';
        echo $html;
    }

    function mis_imgSizeFourthMq_callback($args) {
        $options = get_option( 'mis_plugin_options' );
        $echoQuery = $options['mis_imgWidth_fourthMq'];
        $html = '<input type="text" id="mis_imgWidth_fourthMq" name="mis_plugin_options[mis_imgWidth_fourthMq]" value="' . preg_replace('/[^0-9]+/', '', $echoQuery ) . '" />';
        $html .= '<label for="mis_imgWidth_fourthMq">&nbsp;'  . $args[0] . '</label>';
        echo $html;
    }



    /** Settings section for srcset-sizes -------------------------------------------------------------------------------------------------------------------------
     * Settings section for mis_srcsetSizes
     */

    add_settings_section(
        'mis_srcsetSizes',
        __('2.2 Srcset sizes &mdash; image widths', 'makeitSrcset'),
        'mis_srcsetSizes_callback',
        'mis_plugin_options'
    );

    /**
     * Settings section callback for mis_srcsetSizes
     */

    function mis_srcsetSizes_callback() {
        echo '<p>' . __( '<strong>Aka:</strong> What proportion of the screen does your images need, within each mediaquery?<br/><em>Use common screensizes if you are using fewer mediaqueries than four.</em>', 'makeitSrcset' ) . '</p>';
    }

    /**
     * Setting fields for mis_srcsetSizes
     */

    add_settings_field(
        'mis_srcsetSize_noMq',
        '2.2.0 Images relative width<br/>< first breakpoint:',
        'mis_srcsetsize_one_callback',
        'mis_plugin_options',
        'mis_srcsetSizes',
        array(        
            __('vw', 'makeitSrcset'),
        )
    );

    add_settings_field(
        'mis_srcsetSize_firstMq',
        '2.2.1 Images relative width<br/>> first breakpoint:',
        'mis_srcsetsize_two_callback',
        'mis_plugin_options',
        'mis_srcsetSizes',
        array(        
            __('vw', 'makeitSrcset'),
        )
    );

    add_settings_field(
        'mis_srcsetSize_secondMq',
        '2.2.2 Images relative width<br/>> second breakpoint:',
        'mis_srcsetsize_three_callback',
        'mis_plugin_options',
        'mis_srcsetSizes',
        array(        
            __('vw', 'makeitSrcset'),
        )
    );

    add_settings_field(
        'mis_srcsetSize_thirdMq',
        '2.2.3 Images relative width<br/>> third breakpoint:',
        'mis_srcsetsize_four_callback',
        'mis_plugin_options',
        'mis_srcsetSizes',
        array(        
            __('vw', 'makeitSrcset'),
        )
    );

    add_settings_field(
        'mis_srcsetSize_fourthMq',
        '2.2.4 Images relative width<br/>> fourth breakpoint:',
        'mis_srcsetsize_five_callback',
        'mis_plugin_options',
        'mis_srcsetSizes',
        array(        
            __('vw', 'makeitSrcset'),
        )
    );

    /** Setting fields callbacks for mis_srcsetSizes
     * @param $args
     */

    function mis_srcsetsize_one_callback($args) {
        $options = get_option( 'mis_plugin_options' );
        $mis_srcsetSize_noMq = $options['mis_srcsetSize_noMq'];
        $html = '<input type="text" id="mis_srcsetSize_noMq" name="mis_plugin_options[mis_srcsetSize_noMq]" value="' . preg_replace('/[^0-9]+/', '', $mis_srcsetSize_noMq ) . '" />';
        $html .= '<label for="mis_srcsetSize_noMq">&nbsp;'  . $args[0] . '</label>';
        echo $html;
    }

    function mis_srcsetsize_two_callback($args) {
        $options = get_option( 'mis_plugin_options' );
        $mis_srcsetSize_firstMq = $options['mis_srcsetSize_firstMq'];
        $html = '<input type="text" id="mis_srcsetSize_firstMq" name="mis_plugin_options[mis_srcsetSize_firstMq]" value="' . preg_replace('/[^0-9]+/', '', $mis_srcsetSize_firstMq ) . '" />';
        $html .= '<label for="mis_srcsetSize_firstMq">&nbsp;'  . $args[0] . '</label>';
        echo $html;
    }

    function mis_srcsetsize_three_callback($args) {
        $options = get_option( 'mis_plugin_options' );
        $mis_srcsetSize_secondMq = $options['mis_srcsetSize_secondMq'];
        $html = '<input type="text" id="mis_srcsetSize_secondMq" name="mis_plugin_options[mis_srcsetSize_secondMq]" value="' . preg_replace('/[^0-9]+/', '', $mis_srcsetSize_secondMq ) . '" />';
        $html .= '<label for="mis_srcsetSize_secondMq">&nbsp;'  . $args[0] . '</label>';
        echo $html;
    }

    function mis_srcsetsize_four_callback($args) {
        $options = get_option( 'mis_plugin_options' );
        $mis_srcsetSize_thirdMq = $options['mis_srcsetSize_thirdMq'];
        $html = '<input type="text" id="mis_srcsetSize_thirdMq" name="mis_plugin_options[mis_srcsetSize_thirdMq]" value="' . preg_replace('/[^0-9]+/', '', $mis_srcsetSize_thirdMq ) . '" />';
        $html .= '<label for="mis_srcsetSize_thirdMq">&nbsp;'  . $args[0] . '</label>';
        echo $html;
    }

    function mis_srcsetsize_five_callback($args) {
        $options = get_option( 'mis_plugin_options' );
        $mis_srcsetSize_fourthMq = $options['mis_srcsetSize_fourthMq'];
        $html = '<input type="text" id="mis_srcsetSize_fourthMq" name="mis_plugin_options[mis_srcsetSize_fourthMq]" value="' . preg_replace('/[^0-9]+/', '', $mis_srcsetSize_fourthMq ) . '" />';
        $html .= '<label for="mis_srcsetSize_fourthMq">&nbsp;'  . $args[0] . '</label>';
        echo $html;
    }



    /** Settings section for images pulled by WYSIWYG-editor -------------------------------------------------------------------------------------------------------------------------
     * Settings section for mis_wysiwyg
     */

    add_settings_section(
        'mis_wysiwyg',
        __('3. Shortcodes', 'makeitSrcset'),
        'mis_wysiwyg_callback',
        'mis_plugin_options'
    );

    /**
     * Settings section callback for mis_wysiwyg
     */

    function mis_wysiwyg_callback() {
        echo '<p>' . __( '<strong>Shortcode:</strong> Enables [makeitSrcset] to handle images from wysiwyg-editor.<br/><strong>Shortcode generator:</strong> Pull generated shortcodes (instead of images) to wysiwyg-editor from the add media-button.', 'makeitSrcset' ) . '</p>';
    }

    add_settings_field(
        'mis_shortcode',
        '3.1 Enable shortcode:',
        'mis_shortcode_callback',
        'mis_plugin_options',
        'mis_wysiwyg',
        array(        
            __( '', 'makeitSrcset' ),
        )
    );

    add_settings_field(
        'mis_shortcodeGen',
        '3.2 Enable shortcode generator:',
        'mis_shortcodeGen_callback',
        'mis_plugin_options',
        'mis_wysiwyg',
        array(        
            __( '', 'makeitSrcset' ),
        )
    );

    /** Setting fields callbacks for mis_wysiwyg
     * @param $args
     */

    function mis_shortcode_callback($args) {
        $options = get_option('mis_plugin_options');
        $html = '<input type="checkbox" id="mis_shortcode" name="mis_plugin_options[mis_shortcode]" value="1" ' . checked(1, isset($options['mis_shortcode']) ? $options['mis_shortcode'] : 0, false) . '/>';
        $html .= '<label for="mis_shortcode">&nbsp;'  . $args[0] . '</label>';
        echo $html;
    }

    function mis_shortcodeGen_callback($args) {
        $options = get_option('mis_plugin_options');
        $html = '<input type="checkbox" id="mis_shortcodeGen" name="mis_plugin_options[mis_shortcodeGen]" value="1" ' . checked(1, isset($options['mis_shortcodeGen']) ? $options['mis_shortcodeGen'] : 0, false) . '/>';
        $html .= '<label for="mis_shortcodeGen">&nbsp;'  . $args[0] . '</label>';
        echo $html;
    }



    /** Settings section for JS-libraries -------------------------------------------------------------------------------------------------------------------------
     * Settings section for mis_jslib
     */

    add_settings_section(
        'mis_jslib',
        __('4. Javascript: Picturefill, Lazyload & Popup / Lightbox', 'makeitSrcset'),
        'mis_jslib_callback',
        'mis_plugin_options'
    );

    /**
     * Settings section callback for mis_jslib
     */

    function mis_jslib_callback() {
        echo '<p>' . __( '<strong>Picturefill:</strong> A srcset-polyfill for crossbrowser support.<br/><strong>Lazysizes:</strong> Javascript plugin to lazyload any content &mdash; your srcset images in this case.<br/><br/><em>There might be later versions of Picturefill and Lazysizes than the ones provided by Make it Srcset.<br/>Use 4.1.2 and 4.2.2 to enqueue your own up-to-date files.</em><br/><strong>Popup / Lightbox:</strong> Enqueue script and styles for popup. <a href="https://samuelbrynolf.se/2016/04/wordpress-plugin-make-it-srcset/">Usage -> See documentation</a>', 'makeitSrcset' ) . '</p>';
    }

    /**
     * Setting fields for mis_jslib
     */

    add_settings_field(
        'mis_picturefill',
        '4.1.1 Use Picturefill:',
        'mis_picturefill_callback',
        'mis_plugin_options',
        'mis_jslib',
        array(        
            __( '', 'makeitSrcset' ),
        )
    );

    add_settings_field(
        'mis_userpathPicturefill',
        '4.1.2 Enqueue Picturefill<br/>> <em>v.3.0.2</em>:',
        'mis_userpathPicturefill_callback',
        'mis_plugin_options',
        'mis_jslib',
        array(        
            __( 'Replace the provided v3.0.2 with another version. Expects a full path.', 'makeitSrcset' ),
        )
    );

    add_settings_field(
        'mis_lazyload',
        '4.2.1 Use Lazysizes:',
        'mis_lazyload_callback',
        'mis_plugin_options',
        'mis_jslib',
        array(        
            __( '', 'makeitSrcset' ),
        )
    );

    add_settings_field(
        'mis_userpathLazyload',
        '4.2.2 Enqueue Lazysizes<br/>> <em>v1.5.0</em>:',
        'mis_userpathLazyload_callback',
        'mis_plugin_options',
        'mis_jslib',
        array(        
            __( 'Replace the provided v1.5.0 with another version. Expects a full path.', 'makeitSrcset' ),
        )
    );

    add_settings_field(
        'mis_popup',
        '4.2.3 Enqueue popup-scripts and styles:',
        'mis_popup_callback',
        'mis_plugin_options',
        'mis_jslib',
        array(
            __( '', 'makeitSrcset' ),
        )
    );

    /** Setting fields callbacks for mis_jslib
     * @param $args
     */

    function mis_picturefill_callback($args) {
        $options = get_option('mis_plugin_options');
        $html = '<input type="checkbox" id="mis_picturefill" name="mis_plugin_options[mis_picturefill]" value="1" ' . checked(1, isset($options['mis_picturefill']) ? $options['mis_picturefill'] : 0, false) . '/>';
        $html .= '<label for="mis_picturefill">&nbsp;'  . $args[0] . '</label>';
        echo $html;
    }

    function mis_userpathPicturefill_callback($args) {
        $options = get_option('mis_plugin_options');
        $html = '<input type="text" id="mis_userpathPicturefill" placeholder="http://" name="mis_plugin_options[mis_userpathPicturefill]" value="' . esc_url_raw($options['mis_userpathPicturefill']) . '" />';
        $html .= '<label for="mis_userpathPicturefill">&nbsp;'  . $args[0] . '</label>';
        echo $html;
    }

    function mis_lazyload_callback($args) {
        $options = get_option('mis_plugin_options');
        $html = '<input type="checkbox" id="mis_lazyload" name="mis_plugin_options[mis_lazyload]" value="1" ' . checked(1, isset($options['mis_lazyload']) ? $options['mis_lazyload'] : 0, false) . '/>';
        $html .= '<label for="mis_lazyload">&nbsp;'  . $args[0] . '</label>';
        echo $html;
    }

    function mis_userpathLazyload_callback($args) {
        $options = get_option('mis_plugin_options');
        $html = '<input type="text" id="mis_userpathLazyload" placeholder="http://" name="mis_plugin_options[mis_userpathLazyload]" value="' . esc_url_raw($options['mis_userpathLazyload']) . '" />';
        $html .= '<label for="mis_userpathLazyload">&nbsp;'  . $args[0] . '</label>';
        echo $html;
    }

    function mis_popup_callback($args) {
        $options = get_option('mis_plugin_options');
        $html = '<input type="checkbox" id="mis_popup" name="mis_plugin_options[mis_popup]" value="1" ' . checked(1, isset($options['mis_popup']) ? $options['mis_popup'] : 0, false) . '/>';
        $html .= '<label for="mis_popup">&nbsp;'  . $args[0] . '</label>';
        echo $html;
    }



    /** Settings section for Styles -------------------------------------------------------------------------------------------------------------------------
     * Settings section for mis_styles
     */

    add_settings_section(
        'mis_styles',
        __('5. Bugfixer', 'makeitSrcset'),
        'mis_styles_callback',
        'mis_plugin_options'
    );


    /**
     * Settings section callback for mis_styles
     */

    function mis_styles_callback() {
        echo '<p>' . __( 'When a user have turned off javascript on a browser that have native support for srcset, the provided noscript-fallback will duplicate all srcset images. <strong>Why is this even an option? Just fix it!?</strong><br/><strong>1.</strong> This is a cornercase. Enqueued styles or extra kb for detection is just not worth it.<br/><strong>2.</strong> This bugfix puts inline css in your header and expects a no-js class on your html/body-tag to work. You might just copy <strong>.no-js .mis_container > .mis_img{display:none}</strong> into your theme-stylesheet. Or check the box below and save a minute.', 'makeitSrcset' ) . '</p>';
    }

    /**
     * Setting fields for mis_styles
     */

    add_settings_field(
        'mis_preventDuplicates',
        'Save a minute:',
        'mis_preventDuplicates_callback',
        'mis_plugin_options',
        'mis_styles',
        array(
            __( '<em>Do not tell Aaron.</em>', 'makeitSrcset' ),
        )
    );

    /** Setting fields callbacks for mis_styles
     * @param $args
     */

    function mis_preventDuplicates_callback($args) {
        $options = get_option('mis_plugin_options');
        $html = '<input type="checkbox" id="mis_preventDuplicates" name="mis_plugin_options[mis_preventDuplicates]" value="1" ' . checked(1, isset($options['mis_preventDuplicates']) ? $options['mis_preventDuplicates'] : 0, false) . '/>';
        $html .= '<label for="mis_preventDuplicates">&nbsp;'  . $args[0] . '</label>';
        echo $html;
    }



    /**
     * Register and sanitize -------------------------------------------------------------------------------------------------------------------------
     */

	register_setting(
		'mis_plugin_options',
		'mis_plugin_options',
		'mis_validate_sanitize_input'
	);
}

add_action( 'admin_init', 'mis_plugin_initialize_options' );

function mis_validate_sanitize_input($input) {
	$output = array();
	foreach($input as $key => $value){
		if(isset($input[$key])){
			$output[$key] = strip_tags(stripslashes($input[$key]));
		}
	}
	return apply_filters('mis_validate_sanitize_input', $output, $input);
}



/**
 * Helpfunctions -------------------------------------------------------------------------------------------------------------------------
 */

function mis_get_option_integer($fieldID){
    $options = get_option('mis_plugin_options');
    return $value = preg_replace('/[^0-9]+/', '', $options[$fieldID]);
}

function mis_get_option_boolean($fieldID){
    $options = get_option('mis_plugin_options');
    return $value = isset($options[$fieldID]) && intval($options[$fieldID]);
}

function mis_get_option_url($fieldID){
    $options = get_option('mis_plugin_options');
    return $value = esc_url_raw($options[$fieldID]);
}