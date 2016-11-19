<?php

class YouTubeHelper {

    public static function youtubeEmbedFromUrl($youtube_url, $width, $height){
        $vid_id = extractUTubeVidId($youtube_url);
        return generateYoutubeEmbedCode($vid_id, $width, $height);
    }

    public static function extractUTubeVidId($url){
        /*
        * type1: http://www.youtube.com/watch?v=H1ImndT0fC8
        * type2: http://www.youtube.com/watch?v=4nrxbHyJp9k&feature=related
        * type3: http://youtu.be/H1ImndT0fC8
        */
        $vid_id = "";
        $flag = false;
        if(isset($url) && !empty($url)){
            /*case1 and 2*/
            $parts = explode("?", $url);
            if(isset($parts) && !empty($parts) && is_array($parts) && count($parts)>1){
                $params = explode("&", $parts[1]);
                if(isset($params) && !empty($params) && is_array($params)){
                    foreach($params as $param){
                        $kv = explode("=", $param);
                        if(isset($kv) && !empty($kv) && is_array($kv) && count($kv)>1){
                            if($kv[0]=='v'){
                                $vid_id = $kv[1];
                                $flag = true;
                                break;
                            }
                        }
                    }
                }
            }

            /*case 3*/
            if(!$flag){
                $needle = "youtu.be/";
                $pos = null;
                $pos = strpos($url, $needle);
                if ($pos !== false) {
                    $start = $pos + strlen($needle);
                    $vid_id = substr($url, $start, 11);
                    $flag = true;
                }
            }
        }
        return $vid_id;
    }

    public static function generateYoutubeEmbedCode($vid_id, $width, $height){
        $w = $width;
        $h = $height;
        $html = '<iframe width="'.$w.'" height="'.$h.'" src="http://www.youtube.com/embed/'.$vid_id.'?rel=0" frameborder="0" allowfullscreen></iframe>';
        return $html;
    }

}