<?php

$config['bookmarklet']['href_code'] = "javascript:void((function(){e=document.createElement('script');e.setAttribute('id','web_scraper');e.setAttribute('type','text/javascript');e.setAttribute('charset','UTF-8');e.setAttribute('src','".base_url()."load_web_scraper/bookmarklet.js?v='+(new Date()).valueOf());document.body.appendChild(e);})());";