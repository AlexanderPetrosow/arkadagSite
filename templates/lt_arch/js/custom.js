/*** Stick Menu ***/
jQuery(function($){
  $(window).scroll(function() {
    var winTop = $(window).scrollTop();
    if (winTop >= 100) {
      $(".header-area").addClass("is-sticky");
    } else {
	  $(".header-area").removeClass("is-sticky");
    }
  })
})
/*** Change Color Preset ***/
jQuery(function ($) {  
        a = $(".sp-megamenu-parent > li.active > a").css("color");
        document.documentElement.style.setProperty('--background-color',a)
        document.documentElement.style.setProperty('--text-color',a)
});

/*** Counter ***/
jQuery(function ($) { 
  $(document).ready(function () {
    $('.skill-box').find('b').each(function (i) {
      return $(this).prop('Counter', 0).animate({
        Counter: $(this).parent().data('percent') },
      {
        duration: 4000,
        easing: 'swing',
        step: function (now) {
          return $(this).text(Math.ceil(now) + '%');
        } });

    });
    return $('.skill-box .skills-circle li').each(function (i) {
      var _left, _percent, _right, deg, full_deg, run_duration;
      _right = $(this).find('.bar-circle-right');
      _left = $(this).find('.bar-circle-left');
      _percent = $(this).attr('data-percent');
      deg = 3.6 * _percent;
      if (_percent <= 50) {
        return _right.animate({
          circle_rotate: deg },
        {
          step: function (deg) {
            $(this).css('transform', 'rotate(' + deg + 'deg)');
          },
          duration: 1000 });

      } else {
        full_deg = 180;
        deg -= full_deg;
        run_duration = 1000 * (150 / _percent);
        return _right.animate({
          circle_rotate: full_deg },
        {
          step: function (full_deg) {
            $(this).css('transform', 'rotate(' + full_deg + 'deg)');
          },
          duration: run_duration,
          easing: 'linear',
          complete: function () {
            run_duration -= 1000;
            _left.css({
              'clip': 'rect(0, 150px, 150px, 75px)',
              'background': '#fff' });

            return _left.animate({
              circle_rotate: deg },
            {
              step: function (deg) {
                $(this).css('transform', 'rotate(' + deg + 'deg)');
              },
              duration: Math.abs(run_duration),
              easing: 'linear' });

          } });

      }
    });
  });

})




/*** Skill ***/
jQuery(function ($) { 
        
      $('lt-counter .count').each(function () {
      $(this).prop('Counter',0).animate({
          Counter: $(this).text()
      }, {
          duration: 4000,
          easing: 'swing',
          step: function (now) {
              $(this).text(Math.ceil(now));
          }
      });
  });
      
});



//*** Video
jQuery(function($){
    $(".vpop").on('click', function(e) {
  e.preventDefault();
  $("#video-popup-overlay,#video-popup-iframe-container,#video-popup-container,#video-popup-close").show();
  
  var srchref='',autoplay='',id=$(this).data('id');
  if($(this).data('type') == 'vimeo') var srchref="//player.vimeo.com/video/";
  else if($(this).data('type') == 'youtube') var srchref="https://www.youtube.com/embed/";
  
  if($(this).data('autoplay') == true) autoplay = '?autoplay=1';
  
  $("#video-popup-iframe").attr('src', srchref+id+autoplay);
  
  $("#video-popup-iframe").on('load', function() {
    $("#video-popup-container").show();
  });
});

$("#video-popup-close, #video-popup-overlay").on('click', function(e) {
  $("#video-popup-iframe-container,#video-popup-container,#video-popup-close,#video-popup-overlay").hide();
  $("#video-popup-iframe").attr('src', '');
});

});
