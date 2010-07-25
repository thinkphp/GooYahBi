<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">  
   <title>GooYahBi - search Google, Yahoo and Bind in one HTTP Request</title>
   <link rel="stylesheet" href="http://yui.yahooapis.com/2.7.0/build/reset-fonts-grids/reset-fonts-grids.css" type="text/css">
   <link rel="stylesheet" href="http://yui.yahooapis.com/2.7.0/build/base/base.css" type="text/css">
   <style type="text/css">
    html,body{color:#fff;background:#222;font-family:calibri,verdana,arial,sans-serif;}
    h1{font-size:300%;margin:0;text-align:right;color:#3c3}
    h2{background:#369;padding:5px;color:#fff;font-weight:bold;-moz-box-shadow: 0px 4px 2px -2px #000;-moz-border-radius:5px;-webkit-border-radius:5px;text-shadow: #000 1px 1px;}
    h3 a{color:#69c;text-decoration:none;}
    h3{margin:0 0 .2em 0}
    .info{font-size:200%;color:#999;margin:1em 0;}
    input[type=text]{-moz-border-radius:5px;-webkit-border-radius:5px;border:1px solid #fff;padding:3px;}
    input[type=submit]{-moz-border-radius:5px;-webkit-border-radius:5px;border:2px solid #3c3;background:#3c3}
    form{font-size:150%;margin-top:-3.2em;}
    ul,ul li{margin:0;padding:0;list-style:none;}
    p span{display:block;text-align: left;margin-top:.5em;font-size:90%;color:#999;}
    #yahoo a{color:#c6c;}
    #yahoo h2{background:#c6c;}
    #bing a{color:#fc6;}
    #bing h2{background:#fc6;}
    #ft p{color:#666;text-align: left;}
    #ft a{color:#ccc;}
    #modeswitch{text-align:right;}
    #modeswitch a{color:#fff;}
    <?php if(isset($_GET['research'])) { ?>
    .smallinfo{font-size:120%;color:#999;margin:1em 0;}
    h2#resultsheader,h2#preview{background:#000;padding:2px 5px;color:#fff;margin:1em 0 0 0;font-weight:bold;-moz-box-shadow:none;-moz-border-radius:0;-webkit-border-radius:0;text-shadow:none;}
    iframe{display:block;width:100%;border:none;margin:0 0 1em 0;height:400px;}
    #results ul,#results h2{margin-right:.5em;}
    #results {height:200px;}
    #results ul{overflow: auto;height: 150px}
    <?php } ?>

   </style>  
</head>
<body>
<div id="<?php echo(isset($_GET['research']) ? 'doc2' : 'doc');?>" class="yui-t7">
   <div id="hd" role="banner"><h1>GooYahBi</h1><p id="modeswitch">Mode <?php echo (isset($_GET['research']))?'<a href="index.php">simple</a>':'<strong>simple</strong>'?> - <?php echo (!isset($_GET['research']))?'<a href="index.php?research">research</a>':'<strong>research</strong>'?></p></div>
   <div id="bd" role="main">
    <form action="index.php" method="get" id="mainform">
          <div>
               <label for="search">Search:</label>
               <input type="text" name="search" id="search" value="<?php if(isset($_GET['search'])) {echo$_GET['search'];}?>"/>
               <input type="submit" value="Go!">
          </div>
    </form>

    <?php if(!isset($_GET['research'])) { ?>
 
           <p class="info">GooHooBi allows you to search Google, Yahoo and Bing in one go. Simply add your search term above and hit the Go button.</p>

    <?php } ?>


    <?php if(isset($_GET['research'])){?>

           <p class="smallinfo">This is GooHooBi in research mode. If you click on links in the results the page will open in the same interface.</p>

           <h2 id="resultsheader">Search results</h2>

    <?php }?>

   
          <div id="results">

            <?php if(isset($_GET['search'])){

                  include('gooyahbi-api.php');
            }?>

          </div>
 
         <?php if(isset($_GET['research'])){?>

               <h2 id="preview">Web preview</h2>

               <iframe name="fr" id="fr"></iframe>

         <?php }?>


    </div>
   <div id="ft" role="contentinfo"><p>follow me @<a href="http://twitter.com/thinkphp">thinkphp</a> using YUI and YQL</p></div>

</div>
<script type="text/javascript" charset="utf-8">

        var gooyahbi = function() {
 
            var results = document.getElementById('results');

                function seed(o) {

                    results.innerHTML = o.results;   
                };

                function doSearch() {

                     results.innerHTML = '<div class="yui-gb">'+
                                         '<div class="yui-u first" id="google"><h2>Google loading &hellip;</h2></div>'+
                                         '<div class="yui-u" id="yahoo"><h2>Yahoo loading &hellip;</h2></div>'+
                                         '<div class="yui-u" id="bing"><h2>Bing loading &hellip;</h2></div>'+
                                         '</div>';

                    var query = document.getElementById('search').value;

                    <?php if(isset($_GET['research'])) { ?>

                           var research = '&research=true';

                    <?php } ?>
  
                    var url = 'gooyahbi-api.php?search='+query+'&json=true'+research;

                    loadScript(url,function(){if(window.console){console.log('Query: '+ url);}});

                }; 

                document.getElementById('mainform').onsubmit = function() {

                      doSearch();

                   return false;
                }

                function loadScript(url, callback){

                var script = document.createElement("script");

                    script.type = "text/javascript";

                if (script.readyState){  //IE

                    script.onreadystatechange = function(){

                             if (script.readyState == "loaded" || script.readyState == "complete") {

                                           script.onreadystatechange = null;

                                           callback();
                             }

                    };//end function
 
                         } else {  //Others

                                script.onload = function(){

                                           callback();
                                };
                         }

                  script.src = url;

                  document.getElementsByTagName("head")[0].appendChild(script);
              }


            return {seed:seed};

        }();

</script>
</body>
</html>
