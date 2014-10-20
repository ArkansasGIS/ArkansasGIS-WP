/*-----------------------------------------------------------------------------------*/
/* Responsive menus */
/*-----------------------------------------------------------------------------------*/
(function(a){var b=0;a.fn.mobileMenu=function(c){function m(a){if(f()&&!g(a)){l(a)}else if(f()&&g(a)){j(a)}else if(!f()&&g(a)){k(a)}}function l(b){if(e(b)){var c='<select id="mobileMenu_'+b.attr("id")+'" class="mobileMenu">';c+='<option value="">'+d.topOptionText+"</option>";b.find("li").each(function(){var b="";var e=a(this).parents("ul, ol").length;for(i=1;i<e;i++){b+=d.indentString}var f=a(this).find("a:first-child").attr("href");var g=b+a(this).clone().children("ul, ol").remove().end().text();c+='<option value="'+f+'">'+g+"</option>"});c+="</select>";b.parent().append(c);a("#mobileMenu_"+b.attr("id")).change(function(){h(a(this))});j(b)}else{alert("mobileMenu will only work with UL or OL elements!")}}function k(b){b.css("display","");a("#mobileMenu_"+b.attr("id")).hide()}function j(b){b.hide("display","none");a("#mobileMenu_"+b.attr("id")).show()}function h(a){if(a.val()!==null){document.location.href=a.val()}}function g(c){if(c.attr("id")){return a("#mobileMenu_"+c.attr("id")).length>0}else{b++;c.attr("id","mm"+b);return a("#mobileMenu_mm"+b).length>0}}function f(){return a(window).width()<d.switchWidth}function e(a){return a.is("ul, ol")}var d={switchWidth:768,topOptionText:"Select a page",indentString:"   "};return this.each(function(){if(c){a.extend(d,c)}var b=a(this);a(window).resize(function(){m(b)});m(b)})}})(jQuery);

/*-----------------------------------------------------------------------------------*/
/* Double Tap to Go */
/*-----------------------------------------------------------------------------------*/

;(function( $, window, document, undefined )
{
  $.fn.doubleTapToGo = function( params )
  {
    if( !( 'ontouchstart' in window ) &&
      !window.navigator.msPointerEnabled &&
      !navigator.userAgent.toLowerCase().match( /windows phone/i ) ) return false;

    this.each( function()
    {
      var curItem = false;

      $( this ).on( 'click', function( e )
      {
        var item = $( this );
        if( item[ 0 ] != curItem[ 0 ] )
        {
          e.preventDefault();
          curItem = item;
        }
      });

      $( document ).on( 'click touchstart MSPointerDown', function( e )
      {
        var resetItem = true,
          parents   = $( e.target ).parents();

        for( var i = 0; i < parents.length; i++ )
          if( parents[ i ] == curItem[ 0 ] )
            resetItem = false;

        if( resetItem )
          curItem = false;
      });
    });
    return this;
  };
})( jQuery, window, document );

/*-----------------------------------------------------------------------------------*/
/* FITVIDS.JS - Responsive video embeds */
/*-----------------------------------------------------------------------------------*/
/*global jQuery */
/*jshint browser:true */
/*!
* FitVids 1.1
*
* Copyright 2013, Chris Coyier - http://css-tricks.com + Dave Rupert - http://daverupert.com
* Credit to Thierry Koblentz - http://www.alistapart.com/articles/creating-intrinsic-ratios-for-video/
* Released under the WTFPL license - http://sam.zoy.org/wtfpl/
*
*/

(function( $ ){

  "use strict";

  $.fn.fitVids = function( options ) {
    var settings = {
      customSelector: null
    };

    if(!document.getElementById('fit-vids-style')) {
      // appendStyles: https://github.com/toddmotto/fluidvids/blob/master/dist/fluidvids.js
      var head = document.head || document.getElementsByTagName('head')[0];
      var css = '.fluid-width-video-wrapper{width:100%;position:relative;padding:0;}.fluid-width-video-wrapper iframe,.fluid-width-video-wrapper object,.fluid-width-video-wrapper embed {position:absolute;top:0;left:0;width:100%;height:100%;}';
      var div = document.createElement('div');
      div.innerHTML = '<p>x</p><style id="fit-vids-style">' + css + '</style>';
      head.appendChild(div.childNodes[1]);
    }

    if ( options ) {
      $.extend( settings, options );
    }

    return this.each(function(){
      var selectors = [
        "iframe[src*='player.vimeo.com']",
        "iframe[src*='youtube.com']",
        "iframe[src*='youtube-nocookie.com']",
        "iframe[src*='kickstarter.com'][src*='video.html']",
        "object",
        "embed"
      ];

      if (settings.customSelector) {
        selectors.push(settings.customSelector);
      }

      var $allVideos = $(this).find(selectors.join(','));
      $allVideos = $allVideos.not("object object"); // SwfObj conflict patch

      $allVideos.each(function(){
        var $this = $(this);
        if (this.tagName.toLowerCase() === 'embed' && $this.parent('object').length || $this.parent('.fluid-width-video-wrapper').length) { return; }
        var height = ( this.tagName.toLowerCase() === 'object' || ($this.attr('height') && !isNaN(parseInt($this.attr('height'), 10))) ) ? parseInt($this.attr('height'), 10) : $this.height(),
            width = !isNaN(parseInt($this.attr('width'), 10)) ? parseInt($this.attr('width'), 10) : $this.width(),
            aspectRatio = height / width;
        if(!$this.attr('id')){
          var videoID = 'fitvid' + Math.floor(Math.random()*999999);
          $this.attr('id', videoID);
        }
        $this.wrap('<div class="fluid-width-video-wrapper"></div>').parent('.fluid-width-video-wrapper').css('padding-top', (aspectRatio * 100)+"%");
        $this.removeAttr('height').removeAttr('width');
      });
    });
  };
// Works with either jQuery or Zepto
})( window.jQuery || window.Zepto );