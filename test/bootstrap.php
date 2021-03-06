<?php

namespace org\lecklider\charles\wordpress\wp_fail2ban
{
    /**
     * @todo Use this when phpunit can handle stderr
     */
//  define('WP_FAIL2BAN_OPENLOG_OPTIONS', LOG_NDELAY|LOG_PID|LOG_PERROR);


    global $wp_fail2ban;
    $wp_fail2ban = ['cache'=>[]];


    require_once '../../../wp-includes/version.php';
    require_once 'wp-fail2ban.php';


    function add_action($a,$b,$c=false,$d=false)
    {
        // stub
    }

    function add_filter($a,$b,$c=false,$d=false)
    {
        // stub
    }

    function is_admin()
    {
        return false;
    }

    function get_comment($id, $ary)
    {
        return [
            'comment_ID' => $id,
            'comment_post_ID' => 1,
            'comment_author' => 'phpunit',
            'comment_author_email' => 'phpunit@example.com',
            'comment_author_url' => 'http://example.com',
            'comment_author_IP' => '255.255.255.255',
            'comment_content' => 'meh'
        ];
    }

    function wp_cache_get($item, $unused)
    {
        global $wp_fail2ban;

        return (array_key_exists($item,$wp_fail2ban['cache']))
            ? $wp_fail2ban['cache'][$item]
            : false;
    }

    function wp_cache_set($item, $data)
    {
        global $wp_fail2ban;

        $wp_fail2ban['cache'][$item] = $data;

        return true;
    }

    function wp_die($msg, $title, $args)
    {

    }
}

namespace org\lecklider\charles\wordpress\wp_fail2ban\phpunit
{
    function request($url, $data = null)
    {
        $res = curl_init(getenv('WP_FAIL2BAN_TESTSITE').$url);
        if (!is_null($data)) {
            curl_setopt($res, CURLOPT_POST, true);
            curl_setopt($res, CURLOPT_POSTFIELDS, $data);
        }

        ob_start();
        curl_exec($res);
        $http = ob_get_clean();

        $fp = fopen('/var/log/auth.log', 'r');
        $pos = -2;
        $line = $t = '';
        while ($t != "\n") {
            $line = $t.$line;
            fseek($fp, $pos, SEEK_END);
            $t = fgetc($fp);
            $pos--;
        }
        fclose($fp);

        echo $line;
    }
}

namespace
{
    define('ARRAY_A', true);
    define('XMLRPC_REQUEST', true);
    define('WP_FAIL2BAN_TRUNCATE_HOST', 10);

    $_SERVER['HTTP_HOST'] = 'phpunit.local';
    $_SERVER['REMOTE_ADDR'] = '255.255.255.255';
}
