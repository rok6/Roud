(function (window, $) {
  'use strict';

  var $headers = $('article[id*=post-]').find(':header:not(h1)');
  var depth = 0,
      current_level = 0,
      count = [0,0,0,0,0];

  $headers.each(function(i){
    var num = this.tagName.match(/[2-6]/g);


    if( current_level < num ) {
      current_level = num;
      count[depth]++;
      depth++;
    }
    else if( current_level > num ) {
      current_level = num;
      count[depth] = 0;
      depth--;
    }
    console.log(count);
    $(this).attr({id, 'section' + count[0] + count[1]});

  });


})(this, this.jQuery);
