<?php
/**
 */
class Html
{
    # $tags_str = '[hola_mundo], [mundo_cruel]'
    public function tags($url, $tags_str) {
        if ( ! $tags_str) {
            return;
        }

        $tags_array = explode(',', $tags_str);

        $str = '';
        foreach($tags_array as $tag) {
            $tag = trim($tag, " []");
            $tag = self::small_case2PascallCase($tag);
            $str .= "<a class=\"tag\" href=\"$url$tag\">$tag</a>";
        }    
        return $str;
    }

    #
    public function small_case2camelCase($str) {
        $str = str_replace('_', ' ', $str);
        $str = ucwords($str);
        return str_replace(' ',  '', $str);    
    }

    #
    public function small_case2PascallCase($str) {
        $str = self::small_case2camelCase($str);
        return ucfirst($str);  
    }
}
