<?php
function w($a = '')
{
    if (empty($a)) return array();
   
    return explode(' ', $a);
}

function _browser($a_browser = false, $a_version = false, $name = false)
{
    $browser_list = 'msie firefox konqueror safari netscape navigator opera mosaic lynx amaya omniweb chrome avant camino flock seamonkey aol mozilla gecko';
    $user_browser = strtolower($_SERVER['HTTP_USER_AGENT']);
    $this_version = $this_browser = '';
   
    $browser_limit = strlen($user_browser);
    foreach (w($browser_list) as $row)
    {
        $row = ($a_browser !== false) ? $a_browser : $row;
        $n = stristr($user_browser, $row);
        if (!$n || !empty($this_browser)) continue;
       
        $this_browser = $row;
        $j = strpos($user_browser, $row) + strlen($row) + 1;
        for (; $j <= $browser_limit; $j++)
        {
            $s = trim(substr($user_browser, $j, 1));
            $this_version .= $s;
           
            if ($s === '') break;
        }
    }
   
    if ($a_browser !== false)
    {
        $ret = false;
        if (strtolower($a_browser) == $this_browser)
        {
            $ret = true;
           
            if ($a_version !== false && !empty($this_version))
            {
                $a_sign = explode(' ', $a_version);
                if (version_compare($this_version, $a_sign[1], $a_sign[0]) === false)
                {
                    $ret = false;
                }
            }
        }
       
        return $ret;
    }
   
    //
    $this_platform = '';
    if (strpos($user_browser, 'linux'))
    {
        $this_platform = 'linux';
    }
    elseif (strpos($user_browser, 'macintosh') || strpos($user_browser, 'mac platform x'))
    {
        $this_platform = 'mac';
    }
    else if (strpos($user_browser, 'windows') || strpos($user_browser, 'win32'))
    {
        $this_platform = 'windows';
    }
   
    if ($name !== false)
    {
        return $this_browser . ' ' . $this_version;
    }
   
    return array(
        "browser"      => $this_browser,
        "version"      => $this_version,
        "platform"     => $this_platform,
        "useragent"    => $user_browser
    );
}
?>