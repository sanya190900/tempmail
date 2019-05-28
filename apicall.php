
<?php require('JSON.php');

function api_call($function, $params, $req_type='POST', $decode=true) {

    $result = null;
    if (isset($_COOKIE['SUBSCR'])) {
       $toks = explode(':', $_REQUEST['SUBSCR']);
       $hash = array_shift($toks);
       $email_addr = array_shift($toks);
       $email_timestamp = array_shift($toks);

       $params['SUBSCR'] = $_COOKIE['SUBSCR'];
    }
        $req = 'f='.$function;
        if (is_array($params)) {
            foreach ($params as $key => $val) {
                if (is_array($val)) {
                    foreach ($val as $ak => $av) {
                     $req .= '&'.$key.'%5B%5D='.urlencode($av);
                 }
             } else {
                $req .= '&'.$key.'='.urlencode($val);
            }
        }
    }

    $host = 'www.guerrillamail.com';
    if (strpos(__FILE__, '/dev')!==false) {
        $resource = '/dev/ajax.php';
    } else {
        $resource = '/ajax.php';
    }
    //echo '<A href="http://'.$host.$resource.'?'.htmlentities($req).'">'.htmlentities($host.$resource).'?'.htmlentities($req).'</a>';
    $fp = fsockopen ($host, 80, $errno, $errstr, 10);
    if ($fp) {
        if ($req_type=='GET') {
            $get = $resource.'?'.$req;
            $send  = "GET $get HTTP/1.0\r\n"; // dont need chunked so use HTTP/1.0
            $send .= "Host: $host\r\n";
            $send .= "User-Agent: Guerrilla Mail API (www.guerrillamail.com)\r\n";
            $send .= "Referer: ".$_SERVER['SERVER_NAME']."\r\n";
            if (isset($_SESSION['API_SESSION'])) {
                        //$send .= "Cookie: PHPSESSID=".$_SESSION['API_SESSION']."\r\n";
                }
                $send .= "Cookie: PHPSESSID=".session_id()."\r\n";
                        $send .= "Content-Type: text/xml\r\n";
                        $send .= "Connection: Close\r\n\r\n";
                } else {
                // Post the data

                $send = "POST ".$resource." HTTP/1.0\r\n";
                $send .= "Host: $host\r\n";
                $send .= "User-Agent: Guerrilla Mail API (www.guerrillamail.com)\r\n";
                $send .= "Referer: ".$_SERVER['SERVER_NAME']."\r\n";
                if (isset($_SESSION['API_SESSION'])) {
                    //$send .= "Cookie: PHPSESSID=".$_SESSION['API_SESSION']."\r\n";
                }
                $send .= "Cookie: PHPSESSID=".session_id()."\r\n";
                $send .= "Content-Type: application/x-www-form-urlencoded\r\n";
                $send .= "Content-Length: " . strlen($req) . "\r\n\r\n";
                $send .= $req; // post the request
            }
            //echo $send;
            if ($sent = fputs ($fp, $send, strlen($send)))  {  // do the request
                // skip headers... parse cookies
                while (!feof($fp)) { // skip the header
                    $res = fgets ($fp);
                    if (preg_match ('#Set-Cookie: PHPSESSID=(.+?);#', $res, $m)) {
                    // extracted the PHP session ID
                    // so that we can maintain a session between the client/server
                        $_SESSION['API_SESSION'] = $m[1];
                    }
                    // grab the SUBSCR cookie from the reply and set to the client
                    if (preg_match ('#Set-Cookie: (SUBSCR=.+)#', $res, $m)) {
                        $data = explode(';', $m[1]);
                        foreach ($data as $item) {
                            $pair = explode('=', $item);
                            if (trim($pair[0])=='expires') {
                            // needs to be in a unix timestamp
                            $cookie[trim($pair[0])] = strtotime(trim(urldecode($pair[1])));
                        } else {
                            $cookie[trim($pair[0])] = trim(urldecode($pair[1]));
                        }
                    }
                    if ($_SERVER['HTTP_HOST']=='localhost') {
                        $host = $_SERVER['HTTP_HOST'];
                    } else {
                        $host = '.'.str_replace('.www', '', $_SERVER['HTTP_HOST']);
                    }
                    if (setcookie('SUBSCR', $cookie['SUBSCR'], $cookie['expires'], '/', $host)) { 
                    // , $host
                    //echo 'cookie set';
                    }
                }
                if (strcmp($res, "\r\n")===0) break;
            }
        }
        $buffer = '';
        if ($sent) {                         
            while(!feof($fp)) {
                $buffer .= fread($fp, 1024);        
            }
            $JSON = new Services_JSON();
            if ($decode) {
                $result = $JSON->decode($buffer);
            } else {
                $result = $buffer;
            }
        }
        if ($fp) {
            fclose($fp);
        }
        return $result;
    }   else {
        return null;
    }
}?>