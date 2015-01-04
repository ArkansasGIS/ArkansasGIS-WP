<?php

/**
 * Master Slider Data Parser Class.
 *
 * @package   MasterSlider
 * @author    averta [averta.net]
 * @license   LICENSE.txt
 * @link      http://masterslider.com
 * @copyright Copyright © 2014 averta
 */


class MSP_Parser {


	public $maybe_encoded_data;

	// ready to parse data
	public $parsable_data ;

	// recent parsed slider setting
	public $recent_setting;

	// recent parsed slides
	public $recent_slides ;

	// recent parsed styles
	public $recent_styles ;


	public $current_slider_id;

	public $join_char = "\n";
	public $tab_char  = "\t";


	
	public function __construct() {


		if ( apply_filters( 'masterslider_compress_custom_css' , 1 ) ) {
			$this->join_char = "";
			$this->tab_char  = "";
		}
	}

	public function get_setting() {

	}

	public function is_key_true( $array, $key, $default = 'true' ) {
		if( isset( $array[ $key ] ) ) {
			return $array[ $key ] ? 'true' : 'false';
		} else {
			return $default;
		}
	}

	public function parse_setting( $setting = array() ) {

		// make sure $setting is not serialized
		$setting = maybe_unserialize( $setting );

		$setid = isset( $setting['setId'] ) ? (string) $setting['setId'] : '';

		$post_cats = isset( $setting['postCats'] ) ? (array) $setting['postCats'] : array();
		$post_tags = isset( $setting['postTags'] ) ? (array) $setting['postTags'] : array();
		$tax_term_ids = implode( ',', array_merge( $post_cats, $post_tags ) );

		// slider options
		return array(

	        'id'            => is_numeric( $this->current_slider_id ) ? $this->current_slider_id : ( isset( $setting['sliderId'] ) ? (string) $setting['sliderId'] : '' ),
	        'uid'           => '',         // an unique and temporary id 
	        'class'         => isset( $setting['className'] ) ? (string) $setting['className'] : '',      // a class that adds to slider wrapper
	        'margin'        => 0,

	        'inline_style'  => isset( $setting['inlineStyle'] ) ? esc_attr( $setting['inlineStyle'] ) : '',
	        'bg_color'  	=> isset( $setting['bgColor'] ) ? (string) $setting['bgColor'] : '',
	        'bg_image'  	=> isset( $setting['bgImage'] ) ? msp_get_the_relative_media_url( $setting['bgImage'] ) : '',

	        'title'         => isset( $setting['name'] )  ? (string) $setting['name']  : __( 'Untitled Slider', MSWP_TEXT_DOMAIN ),       // slider name

	        'slider_type'   => isset( $setting['type'] ) ? (string) $setting['type'] : 'custom',   // values: custom, express, flickr, post_view
	        

	        'width'         => isset( $setting['width'] )  ? (int) rtrim($setting['width'] , 'px' ) : 300,     // base width of slides. It helps the slider to resize in correct ratio.
	        'height'        => isset( $setting['height'] ) ? (int) rtrim($setting['height'], 'px' ) : 150,     // base height of slides, It helps the slider to resize in correct ratio.

	        'start'         => isset( $setting['start'] ) ? (int) $setting['start'] : 1,
	        'space'         => isset( $setting['space'] ) ? (int) $setting['space'] : 0,

	        'grab_cursor'   => $this->is_key_true( $setting, 'grabCursor', 'true' ),  // Whether the slider uses grab mouse cursor
	        'swipe'         => $this->is_key_true( $setting, 'swipe', 'true' ),  // Whether the drag/swipe navigation is enabled

	        'wheel'         => $this->is_key_true( $setting, 'wheel', 'true' ), // Enables mouse scroll wheel navigation
	        'mouse'         => $this->is_key_true( $setting, 'mouse', 'true' ),  // Whether the user can use mouse drag navigation

	        'crop' 			=> $this->is_key_true( $setting, 'autoCrop', 'false' ),  // Automatically crop slide images?

	        'autoplay'      => $this->is_key_true( $setting, 'autoplay', 'false' ), // Enables the autoplay slideshow
	        'loop'          => $this->is_key_true( $setting, 'loop', 'false' ), // 
	        'shuffle'       => $this->is_key_true( $setting, 'shuffle', 'false' ), // Enables the shuffle slide order
	        'preload'       => isset( $setting['preload'] ) ? $setting['preload'] : 0,

	        'wrapper_width' => isset( $setting['wrapperWidth'] ) ? (int) $setting['wrapperWidth'] : '',
	        'wrapper_width_unit' => isset( $setting['wrapperWidthUnit'] ) ? $setting['wrapperWidthUnit'] : 'px',
	        
	        'layout' 		=> isset( $setting['layout'] ) ? (string) $setting['layout'] : 'boxed',

	        'fullscreen_margin' => isset( $setting['fullscreenMargin'] ) ? (int) $setting['fullscreenMargin'] : 0,


	        'height_limit'  => 'true', // It force the slide to use max height value as its base specified height value.
	        'auto_height'   => $this->is_key_true( $setting, 'autoHeight', 'false' ),
	        'smooth_height' => 'true',
	        
	        'end_pause'     => $this->is_key_true( $setting, 'endPause' , 'false' ),
	        'over_pause'    => $this->is_key_true( $setting, 'overPause', 'false' ),

	        'fill_mode'     => apply_filters( 'masterslider_params_default_fill_mode', 'fill' ), 
	        'center_controls'=> $this->is_key_true( $setting, 'centerControls', 'true' ),

	        'speed'         => isset( $setting['speed'] ) ? (int) $setting['speed'] : 17,

	        'skin'          => isset( $setting['skin'] ) ? $setting['skin'] : 'ms-skin-default', // slider skin. should be seperated by space
	        'template'      => isset( $setting['msTemplate'] ) ? (string) $setting['msTemplate'] : 'custom',
	        'template_class'=> isset( $setting['msTemplateClass'] ) ? (string) $setting['msTemplateClass'] : '',
	        'direction'     => isset( $setting['dir'] ) ? (string) $setting['dir'] : 'h',
	        'view'          => isset( $setting['trView'] ) ? (string) $setting['trView'] : 'basic',

	        'gfonts'        => isset( $setting['usedFonts'] ) ? (string) $setting['usedFonts'] : '',

	        'parallax_mode' => isset( $setting['parallaxMode'] ) ? (string) $setting['parallaxMode'] : 'swipe',


	        'flickr_key'    => isset( $setting['apiKey'] ) ? (string) $setting['apiKey'] : '',
	        'flickr_id'     => $setid,
	        'flickr_count'  => isset( $setting['imgCount'] ) ? (int) $setting['imgCount'] : 10,
	        'flickr_type'   => isset( $setting['setType'] ) ? (string) $setting['setType'] : 'photos',
	        'flickr_size'   => isset( $setting['imgSize'] ) ? (string) $setting['imgSize'] : 'c',
	        'flickr_thumb_size' => isset( $setting['thumbSize'] ) ? (string) $setting['thumbSize'] : 'q',


	        'ps_post_type' 		=> isset( $setting['postType'] ) ? (string) $setting['postType'] : '',
			'ps_tax_term_ids' 	=> $tax_term_ids,
			'ps_post_count' 	=> isset( $setting['postCount'] ) ? (int) $setting['postCount'] : 10,
			'ps_image_from' 	=> isset( $setting['postImageType'] ) ? (string) $setting['postImageType'] : 'auto',
			'ps_order' 			=> isset( $setting['postOrder'] ) ? (string) $setting['postOrder'] : 'DESC',
			'ps_orderby' 		=> isset( $setting['postOrderDir'] ) ? (string) $setting['postOrderDir'] : 'menu_order date',
			'ps_posts_not_in'   => isset( $setting['postExcludeIds'] ) ? (string) $setting['postExcludeIds'] : '',
			'ps_excerpt_len' 	=> isset( $setting['postExcerptLen'] ) ? (int) $setting['postExcerptLen'] : 100,
			'ps_offset' 		=> isset( $setting['postOffset'] ) ? (int) $setting['postOffset'] : 0,
			'ps_link_slide' 	=> isset( $setting['postLinkSlide'] ) ? (boolean) $setting['postLinkSlide'] : false,
			'ps_link_target' 	=> isset( $setting['postLinkTarget'] ) ? (string) $setting['postLinkTarget'] : '_self',
			'ps_slide_bg'  		=> isset( $setting['postSlideBg'] ) ? msp_get_the_relative_media_url( $setting['postSlideBg'] ) : '',

			'wc_only_featured' 	=> $this->is_key_true( $setting, 'wcOnlyFeatured', 'false' ),
			'wc_only_instock' 	=> $this->is_key_true( $setting, 'wcOnlyInstock' , 'false' ),
			'wc_only_onsale' 	=> $this->is_key_true( $setting, 'wcOnlyOnsale'  , 'false' ),
			

	        'facebook_username' => isset( $setting['setType'] ) && ( 'photostream' == $setting['setType'] ) ? $setid : '',
	        'facebook_albumid'  => isset( $setting['setType'] ) && ( 'album' == $setting['setType'] ) ? $setid : '',
	        'facebook_count'	=> isset( $setting['imgCount'] ) ? (int) $setting['imgCount'] : 10,
	        'facebook_type' 	=> isset( $setting['setType'] ) ? (string) $setting['setType'] : 'album',
	        'facebook_size' 	=> isset( $setting['imgSize'] ) ? (string) $setting['imgSize'] : 'orginal',
	        'facebook_thumb_size' => isset( $setting['thumbSize'] ) ? (string) $setting['thumbSize'] : '320',

	        'arrows'           => 'false',   // display arrows?
	        'arrows_autohide'  => 'true',   // auto hide arrows?
	        'arrows_overvideo' => 'true',   // visible over slide video while playing?
	        'arrows_hideunder' => '',

	        'bullets'          => 'false',  // display bullets?
	        'bullets_autohide' => 'true',   // auto hide bullets?
	        'bullets_overvideo'=> 'true',   // visible over slide video while playing?
	        'bullets_align'    => 'bottom',
	        'bullets_margin'   => '',
	        'bullets_hideunder'=> '',
	        
	        'thumbs'           => 'false',  // display thumbnails?
	        'thumbs_autohide'  => 'true',   // auto hide thumbs?
	        'thumbs_overvideo' => 'true',   // visible over slide video while playing?
	        'thumbs_type' 	   => 'thumbs', // thumb or tabs
	        'thumbs_speed'     => 17,       // scrolling speed. It accepts float values between 0 and 100
	        'thumbs_inset'     => 'true',	// insert thumbs inside slider
	        'thumbs_align'     => 'bottom',
	        'thumbs_margin'    => 0,
	        'thumbs_width'     => 100,
	        'thumbs_height'    => 80,
	        'thumbs_space'     => 5,
	        'thumbs_hideunder' => '',

	        'scroll'           => 'false',  // display scrollbar?
	        'scroll_autohide'  => 'true',   // auto hide scroll?
	        'scroll_overvideo' => 'true',   // visible over slide video while playing?
	        'scroll_align' 	   => 'top',
	        'scroll_inset' 	   => 'true',
	        'scroll_margin'    => '',
	        'scroll_hideunder' => '',
	        'scroll_color'     => '#3D3D3D',
			'scroll_width' 	   => '',


	        'circletimer'          => 'false',  // display circletimer?
	        'circletimer_autohide' => 'true',   // auto hide circletimer?
	        'circletimer_overvideo'=> 'true',   // visible over slide video while playing?
	        'circletimer_color'    => '#A2A2A2',// color of circle timer
	        'circletimer_radius'   => 4,        // radius of circle timer in pixels
	        'circletimer_stroke'   => 10,       // the stroke of circle timer in pixels
	        'circletimer_margin'   => '',
	        'circletimer_hideunder'=> '',

	        'timebar'          => 'false',   // display timebar?
	        'timebar_autohide' => 'true',   // auto hide timebar?
	        'timebar_overvideo'=> 'true',   // visible over slide video while playing?
	        'timebar_align'    => 'bottom',
	        'timebar_hideunder'=> '',
	        'timebar_color'    => '#FFFFFF',
			'timebar_width'    => '',


	        'slideinfo'          => 'false',   // display timebar?
	        'slideinfo_autohide' => 'true',   // auto hide timebar?
	        'slideinfo_overvideo'=> 'true',   // visible over slide video while playing?
	        'slideinfo_align'    => 'bottom',
	        'slideinfo_inset'    => 'false',
	        'slideinfo_margin'   => '',
	        'slideinfo_hideunder'=> '',
	        'slideinfo_width'	 => '',
			'slideinfo_height'   => '',

			'on_change_start' 	 => '',
			'on_change_end'		 => '',
			'on_waiting' 		 => '',
			'on_resize' 		 => '',
			'on_video_play' 	 => '',
			'on_video_close' 	 => '',
			'on_swipe_start' 	 => '',
			'on_swipe_move' 	 => '',
			'on_swipe_end' 		 => ''

	    );
			
		
	}


	public function parse_slide( $slide = array() ) {

		// make sure $slide is not serialized
		$slide = maybe_unserialize( $slide );

		if( empty( $slide ) )
			return $slide;

		// get slider setting and controls
		$slider_setting = $this->get_slider_setting();

		// get slide onfo if is set (usage: for tab content if is set)
		$info = isset( $slide['info'] ) ? $slide['info'] : '';

		if( isset( $slide['bg'] ) ) {
			$slide_src = msp_get_the_absolute_media_url( $slide['bg'] );

			// generate thumb for master slider panel
			msp_get_the_resized_image_src( $slide_src, 150, 150, true );
		}

		$thumb = '';

		// add thumb just if thumblist is added to controls list
		// also always add thumbnail if slider template is gallery  
		if( ( 'true' == $slider_setting['thumbs'] && 'thumbs' == $slider_setting['thumbs_type'] ) || 
		      'image-gallery' == $slider_setting['template'] 
		  ){

			if( isset( $slide['thumb'] ) && ! empty( $slide['thumb'] ) ) {
				$thumb = $slide['thumb'];
				$thumb = msp_get_the_relative_media_url( $thumb );

			} elseif( isset( $slide['bg'] ) ) {

				// set custom thumb size if slider template is gallery
				if( 'image-gallery' == $slider_setting['template']  )
					$thumb = msp_get_the_resized_image_src( $slide_src, 175, 140, true );
				else
					$thumb = msp_get_the_resized_image_src( $slide_src, $slider_setting['thumbs_width'], $slider_setting['thumbs_height'], true );

				$thumb = msp_get_the_relative_media_url( $thumb );

			} else {
				$thumb = '';
			}

		}


		$slides = array(

            'slide_order'=> isset( $slide['order'] ) ? (int) $slide['order'] : 0,

            'css_class' => isset( $slide['cssClass'] ) ? (string) $slide['cssClass'] : '',
            'css_id'    => isset( $slide['cssId'] ) ? (string) $slide['cssId'] : '',

            'ishide'	=> $this->is_key_true( $slide, 'ishide', 'false' ),

            'src'       => isset( $slide['bg'] ) ? esc_attr( msp_get_the_relative_media_url( $slide['bg'] ) ) : '',
            'src_full'  => isset( $slide['bg'] ) ? esc_attr( msp_get_the_relative_media_url( $slide['bg'] ) ) : '',

            'title'     => '', // image title
            'alt'       => isset( $slide['bgAlt'] ) ? esc_attr($slide['bgAlt']) : '', // image alternative text
            'link'      => isset( $slide['link'] ) ? esc_url( $slide['link'] ) : '',
            'target'    => isset( $slide['linkTarget'] ) ? (string) $slide['linkTarget'] : '',
            'video'     => isset( $slide['video'] ) ? esc_attr( $slide['video'] ) : '', // youtube or vimeo video link

            'info'      => wp_slash( do_shortcode( $info ) ), // image alternative text

            'mp4'       => isset( $slide['bgv_mp4'] ) ? esc_attr( $slide['bgv_mp4'] ) : '', // self host video bg
            'webm'      => isset( $slide['bgv_webm'] ) ? esc_attr( $slide['bgv_webm'] ) : '', // self host video bg
            'ogg'       => isset( $slide['bgv_ogg'] ) ? esc_attr( $slide['bgv_ogg'] ) : '', // self host video bg
            'autopause' => $this->is_key_true( $slide, 'bgv_autopause', 'false' ),
            'mute'      => $this->is_key_true( $slide, 'bgv_mute', 'true' ),
            'loop'      => $this->is_key_true( $slide, 'bgv_loop', 'true' ),
            'vbgalign'  => isset( $slide['bgv_fillmode'] ) ? (string) $slide['bgv_fillmode'] : 'fill',

            'thumb'     => $thumb,
            'tab'		=> 'true' == $slider_setting['thumbs'] && 'tabs' == $slider_setting['thumbs_type'] ? str_replace( '"', '&quote;', $info ) : '',
            'delay'     => isset( $slide['duration'] ) ? (string) $slide['duration'] : '', // data-delay 
            'bgalign'   => isset( $slide['fillMode'] ) ? (string) $slide['fillMode'] : 'fill', // data-fill-mode
            'bgcolor'   => isset( $slide['bgColor']  ) ? (string) $slide['bgColor'] : ''
        );

		return $slides;
	}


	public function parse_each_style( $style_obj, $allowed_style_type = array( 'custom' ) ) {

		// make sure $style_obj is not serialized
		$style_obj = maybe_unserialize( $style_obj );

		if( empty( $style_obj ) )
			return $style_obj;

		$allowed_style_type = (array) $allowed_style_type;

		if( ! isset( $style_obj['type'] ) || ( ! in_array( $style_obj['type'], $allowed_style_type ) ) )
			return '';

		// the css block selector
		$class_name = isset( $style_obj['className'] ) ? ".". $style_obj['className'] : '';
		// store css styles
		$css = '';

        $supported_css_props = array(

            'backgroundColor' 	=> array('background-color'	, ''  ),

            'paddingTop'		=> array('padding-top'		, 'px'),
            'paddingRight'		=> array('padding-right'	, 'px'),
            'paddingBottom' 	=> array('padding-bottom'	, 'px'),
            'paddingLeft' 		=> array('padding-left'		, 'px'),

            'borderTop' 		=> array('border-top'		, 'px'),
            'borderRight' 		=> array('border-right'		, 'px'),
            'borderBottom' 		=> array('border-bottom'	, 'px'),
            'borderLeft' 		=> array('border-left'		, 'px'),
            
            'borderColor' 		=> array('border-color'		, ''  ),
            'borderRadius' 		=> array('border-radius'	, 'px'),
            'borderStyle' 		=> array('border-style'		, ''  ),

            'fontFamily' 		=> array('font-family'		, ''  ),
            'fontWeight' 		=> array('font-weight'		, ''  ),
            'fontSize' 			=> array('font-size'		, 'px'),

            'textAlign' 		=> array('text-align'		, ''  ),
            'letterSpacing'     => array('letter-spacing'	, 'px'),
            'lineHeight' 		=> array('line-height'		, 'px'),
            'whiteSpace' 		=> array('white-space'		, ''  ),
            'color' 			=> array('color'			, ''  )
        );

        foreach ( $supported_css_props as $js_prop => $parse_option ) {

        	if( isset( $style_obj[$js_prop] ) && ! empty( $style_obj[$js_prop] ) ) {
        		// if prop is font-family add quote around font name
        		if ( 'fontFamily' == $js_prop )
        			$css .= sprintf( "%s%s:\"%s\";", $this->tab_char, $parse_option['0'], rtrim( $style_obj[$js_prop] ) ) . $this->join_char;

        		elseif ( 'lineHeight' == $js_prop &&  'normal' == $style_obj[$js_prop] )
        			$css .= sprintf( "%s%s:%s;", $this->tab_char, $parse_option['0'], rtrim( $style_obj[$js_prop] ) ) . $this->join_char;

        		else
        			$css .= sprintf( "%s%s:%s%s;"  , $this->tab_char, $parse_option['0'], rtrim( $style_obj[$js_prop], $parse_option['1'] ), $parse_option['1'] ) . $this->join_char;
 				
        	}
        }

        // add custom styles at the end
        $css .= isset( $style_obj['custom'] ) ? $this->tab_char . $style_obj['custom'] .$this->join_char : '';
        // create css block
        $css_block = $this->join_char.$class_name." { ".$this->join_char.$css." } \n";
        //$css_block = sprintf( "\n%s {\n%s\n} \n", $class_name, $css );
        
        return apply_filters( 'msp_parse_each_style', $css_block, $class_name, $css, $supported_css_props );
	}






	// set/store panel raw and parsed data for further use
	public function set_data( $data, $slider_id = null ) {
		$this->reset();

		$this->maybe_encoded_data = $data;
		$this->current_slider_id  = $slider_id;

		$decoded = msp_maybe_base64_decode( $data );
		$this->parsable_data = json_decode($decoded);
	}


	// reset cache data
	public function reset() {
		$this->recent_setting 		= null;
		$this->recent_slides  		= null;
		$this->recent_styles  		= null;
		$this->maybe_encoded_data 	= null;
	}



	// get decoded and parsable panel data
	public function get_parsable_data() {
		return $this->parsable_data;
	}






	public function get_raw_callbacks(){
		if ( isset( $this->parsable_data->{'MSPanel.Callback'} ) )
			return $this->parsable_data->{'MSPanel.Callback'};
		return array();
	}


	public function get_callbacks_params(){
		$callbacks_list = $this->get_raw_callbacks();

		$callbacks_params = array();

		foreach ($callbacks_list as $id => $callback_json) {
			$raw_json_decoded_callback = json_decode( $callback_json, true );
			$callback_params = $this->get_callback_params( $raw_json_decoded_callback );
			$callbacks_params = wp_parse_args( $callback_params, $callbacks_params );
		}

		return $callbacks_params;
	}


	public function get_callback_params( $callback ) {
		
		$name = isset( $callback['name'] ) ? (string) $callback['name'] : '';

		switch ( $name ) {
			case 'CHANGE_START':
				return array( 'on_change_start' => isset( $callback['content'] ) ? base64_encode( $callback['content'] ) : '' );
			case 'CHANGE_END':
				return array( 'on_change_end'   => isset( $callback['content'] ) ? base64_encode( $callback['content'] ) : '' );
			case 'WAITING':
				return array( 'on_waiting'      => isset( $callback['content'] ) ? base64_encode( $callback['content'] ) : '' );
			case 'RESIZE':
				return array( 'on_resize' 		=> isset( $callback['content'] ) ? base64_encode( $callback['content'] ) : '' );
			case 'VIDEO_PLAY':
				return array( 'on_video_play'   => isset( $callback['content'] ) ? base64_encode( $callback['content'] ) : '' );
			case 'VIDEO_CLOSE':
				return array( 'on_video_close'  => isset( $callback['content'] ) ? base64_encode( $callback['content'] ) : '' );
			case 'SWIPE_START':
				return array( 'on_swipe_start'  => isset( $callback['content'] ) ? base64_encode( $callback['content'] ) : '' );
			case 'SWIPE_MOVE':
				return array( 'on_swipe_move'   => isset( $callback['content'] ) ? base64_encode( $callback['content'] ) : '' );
			case 'SWIPE_END':
				return array( 'on_swipe_end'    => isset( $callback['content'] ) ? base64_encode( $callback['content'] ) : '' );
			default:
				return array();
		}
        
	}








	public function get_raw_controls(){
		if ( isset( $this->parsable_data->{'MSPanel.Control'} ) )
			return $this->parsable_data->{'MSPanel.Control'};
		return array();
	}


	public function get_controls_params(){
		$controls_list = $this->get_raw_controls();

		$controls_params = array();

		foreach ($controls_list as $id => $control_json) {
			$raw_json_decoded_control = json_decode( $control_json, true );
			$control_params = $this->get_control_params( $raw_json_decoded_control );
			$controls_params = wp_parse_args( $control_params, $controls_params );
		}

		return $controls_params;
	}


	public function get_control_params( $control ) {
		
		$name = isset( $control['name'] ) ? (string) $control['name'] : '';

		switch ( $name ) {
			case 'thumblist':
				return array(
					'thumbs'           => 'true', 
			        'thumbs_autohide'  => $this->is_key_true( $control, 'autoHide' , 'true' ),
			        'thumbs_overvideo' => $this->is_key_true( $control, 'overVideo', 'true' ),
			        'thumbs_speed'     => isset( $control['speed'] ) ? (int) $control['speed'] : 17,
			        'thumbs_type' 	   => isset( $control['type'] ) ? (string) $control['type'] : 'thumbs',
			        'thumbs_inset'     => $this->is_key_true( $control, 'inset', 'false' ),
			        'thumbs_align'     => isset( $control['align'] ) ? (string) $control['align'] : 'bottom',
			        'thumbs_margin'    => isset( $control['margin'] ) ? (int) $control['margin'] : '',
			        'thumbs_width'     => isset( $control['width'] ) ? (int) $control['width'] : 100,
			        'thumbs_height'    => isset( $control['height'] ) ? (int) $control['height'] : 80,
			        'thumbs_space'     => isset( $control['space'] ) ? (int) $control['space'] : 5,
			        'thumbs_hideunder' => isset( $control['hideUnder'] ) ? (int) $control['hideUnder'] : '',
			        'thumbs_fillmode'  => isset( $control['fillMode'] ) ? (string) $control['fillMode'] : 'fill'
				);
			case 'bullets':
				return array(
					'bullets'          => 'true',
			        'bullets_autohide' => $this->is_key_true( $control, 'autoHide' , 'true' ),
			        'bullets_overvideo'=> $this->is_key_true( $control, 'overVideo', 'true' ),
			        'bullets_align'    => isset( $control['align'] ) ? (string) $control['align'] : 'bottom',
			        'bullets_margin'   => isset( $control['margin'] ) ? (int) $control['margin'] : '',
			        'bullets_hideunder' => isset( $control['hideUnder'] ) ? (int) $control['hideUnder'] : ''
				);
			case 'scrollbar':
				return array(
					'scroll'           => 'true',
			        'scroll_autohide'  => $this->is_key_true( $control, 'autoHide' , 'true' ),
			        'scroll_overvideo' => $this->is_key_true( $control, 'overVideo', 'true' ),
			        //'scroll_width'     => isset( $control['width'] ) ? (int) $control['width'] : '',
			        'scroll_align' 	   => isset( $control['align'] ) ? (string) $control['align'] : 'top',
			        'scroll_color' 	   => isset( $control['color'] ) ? (string) $control['color'] : '#3D3D3D',
			        'scroll_margin'    => isset( $control['margin'] ) ? (int) $control['margin'] : '',
			        'scroll_inset' 	   => $this->is_key_true( $control, 'inset', 'true' ),
			        'scroll_hideunder' => isset( $control['hideUnder'] ) ? (int) $control['hideUnder'] : '',
			        'scroll_width'	   => isset( $control['width'] ) ? (int) $control['width'] : ''
				);
			case 'arrows':
				return array(
					'arrows'           => 'true',
			        'arrows_autohide'  => $this->is_key_true( $control, 'autoHide' , 'true' ),
			        'arrows_overvideo' => $this->is_key_true( $control, 'overVideo', 'true' ),
			        'arrows_hideunder' => isset( $control['hideUnder'] ) ? (int) $control['hideUnder'] : ''
				);
			case 'timebar':
				return array(
					'timebar'          => 'true',
			        'timebar_autohide' => $this->is_key_true( $control, 'autoHide' , 'true' ),
			        'timebar_overvideo'=> $this->is_key_true( $control, 'overVideo', 'true' ),
			        'timebar_align'    => isset( $control['align'] ) ? (string) $control['align'] : 'bottom',
			        'timebar_color'    => isset( $control['color'] ) ? (string) $control['color'] : '#FFFFFF',
			        'timebar_hideunder'=> isset( $control['hideUnder'] ) ? (int) $control['hideUnder'] : '',
			        'timebar_width'	   => isset( $control['width'] ) ? (int) $control['width'] : ''
				);
			case 'circletimer':
				return array(
					'circletimer'          => 'true',
			        'circletimer_autohide' => $this->is_key_true( $control, 'autoHide' , 'true' ),
			        'circletimer_overvideo'=> $this->is_key_true( $control, 'overVideo', 'true' ),
			        'circletimer_color'    => isset( $control['color'] ) ? (string) $control['color'] : '#A2A2A2',
			        'circletimer_radius'   => isset( $control['radius'] ) ? (int) $control['radius'] : 4,
			        'circletimer_stroke'   => isset( $control['stroke'] ) ? (int) $control['stroke'] : 10,
			        'circletimer_margin'   => isset( $control['margin'] ) ? (int) $control['margin'] : '',
			        'circletimer_hideunder'=> isset( $control['hideUnder'] ) ? (int) $control['hideUnder'] : ''
				);
			case 'slideinfo':
				return array(
					'slideinfo'          => 'true',
			        'slideinfo_autohide' => $this->is_key_true( $control, 'autoHide' , 'true' ),
			        'slideinfo_overvideo'=> $this->is_key_true( $control, 'overVideo', 'true' ),
			        'slideinfo_align'    => isset( $control['align'] ) ? (string) $control['align'] : 'bottom',
			        'slideinfo_inset'    => $this->is_key_true( $control, 'inset', 'false' ),
			        'slideinfo_margin'   => isset( $control['margin'] ) ? (int) $control['margin'] : '',
			        'slideinfo_hideunder'=> isset( $control['hideUnder'] ) ? (int) $control['hideUnder'] : '',
			        'slideinfo_width'	 => isset( $control['width'] )  ? (int) $control['width'] : '',
					'slideinfo_height'	 => isset( $control['height'] ) ? (int) $control['height'] : ''
				);
			default:
				return array();
		}
        
	}





	public function has_raw_setting(){
		if ( isset( $this->parsable_data->{'MSPanel.Settings'} ) && isset( $this->parsable_data->{'MSPanel.Settings'}->{'1'} ) )
			return true;
		return false;
	}


	public function get_raw_setting(){
		if ( $this->has_raw_setting() )
			return $this->parsable_data->{'MSPanel.Settings'}->{'1'};
		return null;
	}


	public function get_slider_setting( $force_new_parse = false ){
		$raw_setting = $this->get_raw_setting();

		if( is_null( $raw_setting ) ){
			return $this->parse_setting();
		}

		if( is_null( $this->recent_setting ) || $force_new_parse ) {
			$raw_json_decoded_setting = json_decode( $raw_setting, true );
			$this->recent_setting = $this->parse_setting( $raw_json_decoded_setting );
			$this->recent_setting = wp_parse_args( $this->get_controls_params() , $this->recent_setting );
			$this->recent_setting = wp_parse_args( $this->get_callbacks_params(), $this->recent_setting );
		}
		return $this->recent_setting;
	}







	// is slides data passed in raw panel data?
	public function has_raw_slide() {
		if ( isset( $this->parsable_data->{'MSPanel.Slide'} ) )
			return true;
		return false;
	}


	public function get_raw_slides() {
		if ( $this->has_raw_slide() ) {
			return $this->parsable_data->{'MSPanel.Slide'};
		}
		return null;
	}


	public function get_parsable_slides() {

		if( ! $raw_slides = $this->get_raw_slides() ){
			return array();
		}

		$valid_slides = array();

		foreach ( $raw_slides as $id => $raw_slide ) {
			$raw_json_decoded_slide = json_decode( $raw_slide, true );
			$valid_slides[ $raw_json_decoded_slide['order'] ] = $raw_json_decoded_slide;
		}

		ksort( $valid_slides );
		return $valid_slides;
	}


	public function get_slides( $force_new_parse = false ) {

		if( is_null( $this->recent_slides ) || $force_new_parse ) {

			$parsable_slides = $this->get_parsable_slides();

			if ( empty( $parsable_slides ) )
				return  $parsable_slides;

			$slides = array();

			foreach ( $parsable_slides as $slide ) {
				$slides[] = $this->parse_slide( $slide );
			}

			$this->recent_slides = $slides;
		}
		return $this->recent_slides;
	}







	public function has_raw_style() {
		if ( isset( $this->parsable_data->{'MSPanel.Style'} ) )
			return true;
		return false;
	}


	public function get_raw_styles() {
		if ( $this->has_raw_style() ) {
			return $this->parsable_data->{'MSPanel.Style'};
		}
		return null;
	}


	public function get_parsable_styles() {

		if( ! $raw_styles = $this->get_raw_styles() ){
			return array();
		}

		$valid_styles = array();

		foreach ( $raw_styles as $id => $raw_style ) {
			$raw_json_decoded_style = json_decode( $raw_style, true );
			$valid_styles[] = $raw_json_decoded_style;
		}

		return $valid_styles;
	}


	public function get_styles_list( $force_new_parse = false ) {

		if( is_null( $this->recent_styles ) || $force_new_parse ) {

			$parsable_styles = $this->get_parsable_styles();

			if ( empty( $parsable_styles ) )
				return  $parsable_styles;

			$styles = array();

			foreach ( $parsable_styles as $id => $style ) {
				$styles[$id] = $this->parse_each_style( $style );
			}

			$this->recent_styles = $styles;
		}

		return $this->recent_styles;
	}


	public function get_styles( $force_new_parse = false ) {
		$styles_list = $this->get_styles_list();
		return implode( $this->join_char, $styles_list );
	}












	private function get_preset_styles_list( $parsable_preset_styles ) {

		if ( empty( $parsable_preset_styles ) )
			return  $parsable_preset_styles;

		$preset_styles = array();

		foreach ( $parsable_preset_styles as $id => $preset_style ) {
			$preset_styles[$id] = $this->parse_each_style( $preset_style, 'preset' );
		}

		return $preset_styles;
	}

	public function preset_data_to_styles( $raw_preset_styles ){
		$valid_preset_styles = array();

		foreach ( $raw_preset_styles as $id => $raw_preset_style ) {
			$raw_json_decoded_preset_style = json_decode( $raw_preset_style, true );
			$valid_preset_styles[] = $raw_json_decoded_preset_style;
		}

		$preset_styles_list = $this->get_preset_styles_list( $valid_preset_styles );
		return implode( $this->join_char, $preset_styles_list );
	}

	public function get_preset_styles( $raw_preset ) {

		$b64_decoded = msp_maybe_base64_decode( $raw_preset );
		$preset_data = json_decode( $b64_decoded );
		
		if ( ! isset( $preset_data->{'MSPanel.PresetStyle'} ) )
			return '';

		$raw_preset_styles = $preset_data->{'MSPanel.PresetStyle'};

		return $this->preset_data_to_styles( $raw_preset_styles );
	}






	private function get_buttons_styles_list( $parsable_buttons_styles ) {

		if ( empty( $parsable_buttons_styles ) )
			return  $parsable_buttons_styles;

		$buttons_styles = array();

		foreach ( $parsable_buttons_styles as $id => $button_style ) {
			if( ! isset( $button_style['className'] ) ) continue;

			if( isset( $button_style['normal'] ) )
				$button_styles[] = sprintf( ".%s{ %s }", $button_style['className'], str_replace("\n", "", $button_style['normal'] ) );
			if( isset( $button_style['hover'] ) )
				$button_styles[] = sprintf( ".%s:hover{ %s }", $button_style['className'], str_replace("\n", "", $button_style['hover'] ) );
			if( isset( $button_style['active'] ) )
				$button_styles[] = sprintf( ".%s:active{ %s }", $button_style['className'], str_replace("\n", "", $button_style['active'] ) );
		}
		
		return $button_styles;
	}

	public function buttons_data_to_styles( $raw_buttons_styles ){
		$valid_buttons_styles = array();

		foreach ( $raw_buttons_styles as $id => $raw_buttons_style ) {
			$raw_json_decoded_buttons_style = json_decode( $raw_buttons_style, true );
			$valid_buttons_styles[] = $raw_json_decoded_buttons_style;
		}
		
		$buttons_styles_list = $this->get_buttons_styles_list( $valid_buttons_styles );
		return implode( $this->join_char. " ", $buttons_styles_list );
	}

	public function get_buttons_styles( $raw_buttons ) {

		$b64_decoded = msp_maybe_base64_decode( $raw_buttons );
		$buttons_data = json_decode( $b64_decoded );
		
		if ( ! isset( $buttons_data->{'MSPanel.ButtonStyle'} ) )
			return '';

		$raw_buttons_styles = $buttons_data->{'MSPanel.ButtonStyle'};
		return $this->buttons_data_to_styles( $raw_buttons_styles );
	}
	






	public function parser_slider( $force_new_parse = false ) {
		$this->get_slider_setting( $force_new_parse );
		$this->get_slides( $force_new_parse );
		$this->get_styles( $force_new_parse );
	}


	public function get_results( $force_new_parse = false ) {
		$result = array();

		$result['setting'] = $this->get_slider_setting( $force_new_parse );
		$result['slides']  = $this->get_slides( $force_new_parse );
		$result['styles']  = $this->get_styles( $force_new_parse );

		return $result;
	}


	// pretty human readable print for parsed data
	public function pretty_print() {
		axpp( $this->parsable_data );
	}


}