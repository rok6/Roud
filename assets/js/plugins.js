(function (window, $) {
  'use strict';

  /**
   * jQuery plugins
   *
   *  - Plays -
   *
   */

  var settings = {
    'event'      : '',
    'source_id'  : ''
  };

  var methods = {

    init: function ( options ) {
      console.log( 'init' );

      settings = $.extend( true, settings, options);

    },

    se  : function ( options ) {
      console.log( 'methods.se' );
      methods.init( options );

      var $se = $( settings['source_id'] )[0];
          $se.volume = 0.2;

      return this.on( settings['event'], function(){
        $se.currentTime = 0;
        $se.play();
      });
    }

  };

  $.fn.Plays = function( method ) {

    if ( methods[method] ) {
      return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ) );
    }
    else if ( typeof method === 'object' || ! method ) {
      return methods.init.apply( this, arguments );
    }
    else {
      $.error( 'Method ' +  method + ' does not exist' );
    }

  };

})(this, this.jQuery);
