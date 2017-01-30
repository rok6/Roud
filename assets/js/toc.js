(function (window, $) {
  'use strict';

  var $headers = $('article[id*=post-]').find(':header:not(h1)');
  var depth = 0,
      head_level = 0,
      count = [0,0,0,0,0];

  var max_depth = 2;

  $headers.each(function(i){

    var num = this.tagName.match(/[2-6]/g)[0];

    if( head_level == num ) {

    }
    else if( head_level < num ) {
      head_level = num;
      depth++;
    }
    else if( head_level > num ) {
      head_level = num;
      while( head_level <= depth ) {
        count[depth - 1] = 0;
        depth--;
      }
    }
    count[depth - 1]++;

    if( depth <= max_depth ) {
      var anchor = 'section'+ count[0];
      for( var j = 0; j < depth - 1; j++ ) {
        anchor += '-' + count[j+1];
      }
      $(this).attr({'id': anchor});
    }

  });


})(this, this.jQuery);
