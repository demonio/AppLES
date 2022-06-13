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

        $tags_str = str_replace(' ', '', $tags_str);
        $tags_array = explode(',', $tags_str);

        $str = '';
        foreach($tags_array as $tag_small_case) {
            $tag = trim($tag_small_case, " []");
            $tag = self::small_case2PascallCase($tag);
            $str .= "<a class=\"tag\" href=\"$url?criterios=$tag_small_case\">$tag</a>";
        }    
        return $str;
    }

    #
    public static function small_case2camelCase($str) {
        $str = str_replace('_', ' ', $str);
        $str = ucwords($str);
        return str_replace(' ',  '', $str);    
    }

    #
    public static function small_case2PascallCase($str) {
        $str = self::small_case2camelCase($str);
        return ucfirst($str);  
    }
}
