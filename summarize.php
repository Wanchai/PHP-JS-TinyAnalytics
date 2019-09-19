<?php
// Get a list of sites
$logfiles = glob('logs/*.log', GLOB_BRACE);
// Iterate through each site's logs
foreach($logfiles as $logfile) {
  // Name our working files based on the main site log name
  $visitorsfile = substr($logfile,0,-4).'.visitors';
  $referersfile = substr($logfile,0,-4).'.referers';
  $tmpfile = $logfile.'.tmp';

  // Read the Visitors file
  $visitors = json_decode(file_get_contents($visitorsfile), true);

  // Read the Referrers file
  $referers = json_decode(file_get_contents($referersfile), true);

  // Create empty list of tracked IPs
  $ipTracker = [];

  $today = date("Y-m-d");

  // Check we can open the logfile and tmpfile, then process it
  if (($log = fopen($logfile, "r")) !== FALSE && ($write = fopen($tmpfile, "w")) !== FALSE) {

    // While we have rows available in the logfile, do stuff
    while (($data = fgetcsv($log, 1000, "\t")) !== FALSE) {

      // Assign variables to the data
      $rowdate = date("Y-m-d", $data[0]);
      $ipadr = $data[1];
      $ua = $data[3];
      $referer = $data[4];

      // If the User Agent is from a bot, then skip it
      static $bots = array('bot', 'crawl', 'slurp', 'spider', 'yandex', 'WordPress', 'AHC', 'jetmon');
      foreach($bots as $bot){
        if (strpos($ua, $bot) !== false) {
          continue;
        }
      }

      // We only count each IP once a day (Unique daily visitors)
      if (in_array($ipadr, $ipTracker[$rowdate]) ) {
        continue;
      }

      // We only get this far for unique, non-bot data, so let's start recording it

      // If we have referer data, add it to the array (if it doesn't yet exist)
      if (!empty($referer) && !in_array($referer, $referers)) {
        $referers[] = $referer;
      }

      // Track the IP per date
      $ipTracker[$rowdate][] = $ipadr;

      // Write in tmpfile
      if ($rowdate == $today) {
        fwrite($write, join("\t",$data).PHP_EOL);
      }
    }
    fclose($log);
    fclose($write);

    // Replace logfile with tmpfile
    rename($tmpfile, $logfile);

    // Count Visitors by unique IPs
    foreach($ipTracker as $key => $ips) {
      $visitors[$rowdate] = count($ips);
    }

    // Write the Visitors file
    file_put_contents($visitorsfile, json_encode($visitors));

    // Write the Referrers file
    file_put_contents($referersfile, json_encode($referers));
  }
}

// Update the .lastsummarize
file_put_contents('.lastsummarize', time());
