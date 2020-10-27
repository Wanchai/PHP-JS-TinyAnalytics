<?php

error_reporting(E_ALL & ~E_NOTICE);

include 'config.php';  // change your password here or create a config.php file: <?php $PASSWORD = '...';

session_set_cookie_params(30 * 24 * 3600, dirname($_SERVER['SCRIPT_NAME']));   // remember me
session_start();
if ($_GET['action'] === 'logout') {
    $_SESSION['logged'] = 0;
    header('Location: .');   // reload page to prevent ?action=logout to stay
}
if ($_POST['pass'] === $PASSWORD) {
    $_SESSION['logged'] = 1;
    header('Location: .');   // reload page to prevent form resubmission popup when refreshing / this works even if no .htaccess RewriteRule 
}
if (!isset($_SESSION['logged']) || !$_SESSION['logged'] == 1) {
    echo '<html><head><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>TinyAnalytics</title><link rel="icon" href="favicon.ico"></head><body><form action="." method="post"><input type="password" name="pass" value="" autofocus><input type="submit" value="Submit"></form></body></html>';
    exit();
}
include 'tracker.php';
if ($_GET['action'] === 'summarize') {
    summarize(true);
    header('Location: .');
}
summarize();
?>

<html>

<head>
    <title>TinyAnalytics</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="favicon.ico">
    <script src="https://www.gstatic.com/charts/loader.js"></script>
    <link href="assets/style.css" rel="stylesheet">
</head>

<body>
    <div id="content">
        <?php
        $sites = glob("logs/*.visitors", GLOB_BRACE);

        if (!is_writable(realpath(dirname(__FILE__))))
            echo '<p class="warning">&#8226; TinyAnalytics currently can\'t write data. Please give the write permissions to TinyAnalytics with:</p><pre class="code">chown ' . exec('whoami') . ' ' . realpath(dirname(__FILE__)) . '/logs' . '</pre>';

        if (count($sites) == 0) {
            $snippet = htmlspecialchars("<script>\n const xhr = new XMLHttpRequest();\n xhr.open('POST', 'https://" . $_SERVER['SERVER_NAME'] . "/req.php');\n xhr.setRequestHeader('Content-Type', 'application/json');\n xhr.send(JSON.stringify({ \"sn\": \"my-website-name\" }));\n</script>");
            echo "<p class=\"warning\">&#8226; No analytics data yet. Add this tracking code in your website, and then visit it at least once.</p>
            <pre class=\"code\">$snippet</pre>";
        }

        foreach ($sites as $site) {
            $sitename = basename($site, '.visitors');
            $referersfile = substr($site, 0, -9) . '.referers';
            $referers = '';
            $str = file_get_contents($referersfile);
            $urls = json_decode($str, true);
            foreach ($urls as $url) {
                $url = preg_replace('/[\"<>]+/', '', $url);
                $displayedurl = preg_replace('#^https?://#', '', $url);
                $displayedurl = $url;
                $referers .= '<p><a href="' . $url . '">' . $displayedurl . '</a></p>';
            }
            $str = file_get_contents($site);
            $visitors = json_decode($str, true);
            $points = '';
            $max = 0;
            for ($i = 0; $i < 30; $i++) {
                $key = date("Y-m-d", time() - $i * 3600 * 24);
                $y = ((isset($visitors[$key])) ? $visitors[$key] : 0);
                $points .= $y . ',';
                $max = max($max, $y);
            }

            echo '<div class="site"><div class="referers">' . $referers . '</div><h1>' . $sitename . '</h1><div class="chart" data="' . substr($points, 0, -1) . '"></div></div>';
        }
        ?>
    </div>
    <script src="assets/script.min.js"></script>
    <div id="footer">Powered by <a href="https://github.com/Wanchai/PHP-JS-TinyAnalytics">TinyAnalytics</a>. <a href="?action=summarize">Reprocess data</a>. <a href="?action=logout">Log out</a>.</div>
</body>

</html>