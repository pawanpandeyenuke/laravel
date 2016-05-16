<!doctype html>
<html>
<head>
<link href="/demo_converse.min.css" rel="stylesheet" type="text/css" media="screen" >
<script type="text/javascript" src="/js/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="/demo_converse.nojquery.min.js"></script>
</head>
<body >
	

</body>
<script type="text/javascript">


    require(['converse'], function (converse) {
        (function () {
            /* XXX: This function initializes jquery.easing for the https://conversejs.org
            * website. This code is only useful in the context of the converse.js
            * website and converse.js itself is NOT dependent on it.
            */
            var $ = converse.env.jQuery;
            $.extend( $.easing, {
                easeInOutExpo: function (x, t, b, c, d) {
                    if (t==0) return b;
                    if (t==d) return b+c;
                    if ((t/=d/2) < 1) return c/2 * Math.pow(2, 10 * (t - 1)) + b;
                    return c/2 * (-Math.pow(2, -10 * --t) + 2) + b;
                },
            });

            $(window).scroll(function() {
                if ($(".navbar").offset().top > 50) {
                    $(".navbar-fixed-top").addClass("top-nav-collapse");
                } else {
                    $(".navbar-fixed-top").removeClass("top-nav-collapse");
                }
            });
            //jQuery for page scrolling feature - requires jQuery Easing plugin
            $('.page-scroll a').bind('click', function(event) {
                var $anchor = $(this);
                $('html, body').stop().animate({
                    scrollTop: $($anchor.attr('href')).offset().top
                }, 700, 'easeInOutExpo');
                event.preventDefault();
            });
        })();
        
jQuery.ajax({
            'url' : "{{url('/ajax/getxmppuser')}}",
            'type' : 'post',
            'dataType':'json',
            'success' : function(data){
                if(data.status==1){
converse.initialize({
          //  prebind: true,
          //  rid: data.rid,
          //  sid: data.sid,
          //  jid: data.jid,
//	    bosh_service_url: 'https://conversejs.org/http-bind/', // Please use this connection manager only for testing purposes
bosh_service_url: '//friendzsquare.com:5280/http-bind',  
          keepalive: true,
            message_carbons: true,
            play_sounds: true,
            roster_groups: true,
            show_controlbox_by_default: true,
        });
}
}
});
    });



</script>

