<?php
/**
 */
class _url
{    
    #
    static public function to($url) {
        if (Input::isAjax()) {
            exit("<script>location.href='$url'</script>");
        }
        return Redirect::to($url);
    }
}
