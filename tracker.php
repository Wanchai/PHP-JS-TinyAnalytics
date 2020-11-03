<?php
function summarize($force = false)
{
    $lastsummarize = realpath(dirname(__FILE__)) . '/.lastsummarize';
    if (!file_exists($lastsummarize) || ((time() - file_get_contents($lastsummarize)) > 3600) || $force) {
        include_once(realpath(dirname(__FILE__)) . '/summarize.php');
        return true;
    } else
        return false;
}

function record_visit($sitename, $ref)
{
    $logfile = realpath(dirname(__FILE__)) . '/logs/' . $sitename . '.log';
    $txt = time() . "\t" . $_SERVER['REMOTE_ADDR'] . "\t" . $ref . "\t" . (isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '') . PHP_EOL;
    file_put_contents($logfile, $txt, FILE_APPEND);
    summarize();
}
