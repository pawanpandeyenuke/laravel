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
 
    jQuery(document).ready(function(){

		/*require(['converse'], function (converse) {
      
                conObj=converse;
                    converse.initialize({                           
							 prebind: true,
							bosh_service_url: '//friendzsquare.com:5280/http-bind',
							keepalive: true,
							jid: 'alka313@friendzsquare.com',
							authentication: 'prebind',
							prebind_url: "{{url('/ajax/getxmppuser')}}",
							allow_logout: false,
							debug: false ,
							//message_carbons: true,
							send_initial_presence:true,
                    });
         });*/


        require(['converse'], function (converse) {
  
       		conObj=converse;
            converse.initialize({
                //    prebind: true,
               //     rid: data.rid,
                //    sid: data.sid,
                //    jid: data.jid,
                    bosh_service_url: '//friendzsquare.com:5280/http-bind',
				    keepalive: true,
				    authentication:'prebind',
				    jid: 'two308@friendzsquare.com',
				    authentication: 'prebind',
				    prebind_url: 'http://development.laravel.com/ajax/getxmppuser',
				    allow_logout: false,
				    send_initial_presence:true,
            });

        });
  
    });

/*converse.initialize({
    bosh_service_url: 'https://bind.example.com',
    keepalive: true,
    jid: 'me@example.com',
    authentication: 'prebind',
    prebind_url: 'http://example.com/api/prebind',
    allow_logout: false
});
*/
</script>

