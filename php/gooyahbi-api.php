<?php

    //if we have 'json' parameter then return in format JSON the response
    if(isset($_GET['json'])) {

       header('content-type: text/javascript');

       echo 'gooyahbi.seed({"results":"'; 
    }

    //if we have 'search' parameter then do it
    if(isset($_GET['search'])) {
 
       $search= filter_input(INPUT_GET, 'search', FILTER_SANITIZE_SPECIAL_CHARS);

       $query = $search; 

       $queries = array();

       $endpoint = 'http://query.yahooapis.com/v1/public/yql?q='; 

       $queries[] = 'select titleNoFormatting,url,content,visibleUrl from google.search(20) where q = "'.$query.'"';

       $queries[] = 'select title,clickurl,abstract,dispurl from search.web(20) where query="'.$query.'"';

       $queries[] = 'select Title,Description,Url,DisplayUrl from microsoft.bing.web(20) where query="'.$query.'"';

       $q = "select * from query.multi where queries='".join($queries,";")."'";

       $url = $endpoint. urlencode($q). '&format=json&env=store'.'%3A%2F%2Fdatatables.org%2Falltableswithkeys&diagnostics=false';

       $content = get($url);

       $data = json_decode($content);

       if($data->query) {

            if($data->query->results->results[0]){

                  $res = $data->query->results->results[0]->results;

                  $google = '<h2>Google</h2><ul>';

                  $all = sizeof($res);

                  for($i=0;$i<$all;$i++){

                        $google .= '<li><h3><a href="'.$res[$i]->url.'">'.

                        $res[$i]->titleNoFormatting.

                               '</a></h3><p>'.$res[$i]->content.'<span>('.

                               $res[$i]->visibleUrl.')</span></p></li>';
                  }//endfor

                        $google .= '</ul>';

            } else {

                   $bing = '<h2>Google</h2><h3>No results found.</h3>';
            }


            if($data->query->results->results[1]){

                  $res = $data->query->results->results[1]->result;

                  $yahoo = '<h2>Yahoo</h2><ul>';

                  $all = sizeof($res);

                  for($i=0;$i<$all;$i++){

                        $yahoo .= '<li><h3><a href="'.$res[$i]->clickurl.'">'.

                        $res[$i]->title.'</a></h3><p>'.$res[$i]->abstract.'<span>('.$res[$i]->dispurl.')</span></p></li>';
                  }

                        $yahoo .= '</ul>';

            } else {

                   $bing = '<h2>Yahoo</h2><h3>No results found.</h3>';
            }
 
             if($data->query->results->results[2]){

                   $res = $data->query->results->results[2]->WebResult;

                   $bing = '<h2>Bing</h2><ul>';

                   $all = sizeof($res);

                   for($i=0;$i<$all;$i++){

                       $bing .= '<li><h3><a href="'.$res[$i]->Url.'">'.$res[$i]->Title.

                       '</a></h3><p>'.$res[$i]->Description.'<span>('.

                       $res[$i]->DisplayUrl.')</span></p></li>';
                   }

                   $bing .= '</ul>';

                   } else {

                     $bing = '<h2>Bing</h2><h3>No results found.</h3>';
                   }

              }//endif

         $out = '<div class="yui-gb">'.
         '<div class="yui-u first" id="google">'.$google.'</div>'.
         '<div class="yui-u" id="yahoo">'.$yahoo.'</div>'.
         '<div class="yui-u" id="bing">'.$bing.'</div>'.
         '</div>';

    //otherwise display the usage of this API
    } else {

           $out = '<pre>';

           $out .='Google, Yahoo and Bind Search (GooYahBin)<br/>'; 

           $out .='Parameters:<br/><br/>';

           $out .='search - the search term<br/>';

           $out .='json - JSON as output(return) format<br/><br/>';

           $out .=' This API uses YQL to pull data from Google, Yahoo and Bind and returns it<br/>';
           $out .=' as a div construct with headings and lists (compatibles with YUI Grids.)<br/>';
           $out .=' If you specify a JSON parameter the HTML string gets embedded<br/>'; 
           $out .=' in a JSON object and calls a function called gooyahbi.seed(obj)';

           $out .='</pre>';
    }

    //if we have parameter 'research' then replace the attribute from element "a" with target 'fr'
    if(isset($_GET['research'])) {

             $out = preg_replace("/<a /",'<a target="fr" ',$out);
    }

    //if we have parameter 'json' then return in format JSON
    if(isset($_GET['json'])) {
 
       echo addslashes($out);

       echo '"})';   

    } else {

       //return the data
       echo$out;
    }

      
    /* function util */
    function get($url) {

        $ch = curl_init();

        curl_setopt($ch,CURLOPT_URL,$url);

        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,2);

        $data = curl_exec($ch);

        curl_close($ch);

        if(empty($data)) {return 'Error retrieving.';}

              else 
                         {return $data;}

    }//end function
?>