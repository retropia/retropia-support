<?php /*********************************************************************
    cron.php

    Auto-cron handle.
    File requested as 1X1 image on the footer of every staff's page

    Peter Rotich <peter@osticket.com>
    Copyright (c)  2006-2010 osTicket
    http://www.osticket.com

    Released under the GNU General Public License WITHOUT ANY WARRANTY.
    See LICENSE.TXT for details.

    vim: expandtab sw=4 ts=4 sts=4:
    $Id: $
**********************************************************************/
require('staff.inc.php');
ignore_user_abort(1);//Leave me a lone bro!
@set_time_limit(0); //useless when safe_mode is on
$data=sprintf ("%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%",
        71,73,70,56,57,97,1,0,1,0,128,255,0,192,192,192,0,0,0,33,249,4,1,0,0,0,0,44,0,0,0,0,1,0,1,0,0,2,2,68,1,0,59);
$datasize=strlen($data);
header('Content-type:  image/gif');
header('Cache-Control: no-cache, must-revalidate');
header("Content-Length: $datasize");
header('Connection: Close');
print $data;

ob_start(); //Keep the image output clean. Hide our dirt.
//TODO: Make cron DB based to allow for better time limits. Direct calls for now sucks big time.
//We DON'T want to spawn cron on every page load...we record the lastcroncall on the session per user
$sec=time()-$_SESSION['lastcroncall'];
if($sec>180): //user can call cron once every 3 minutes.
require_once(INCLUDE_DIR.'class.cron.php');    
Cron::TicketMonitor(); //Age tickets: We're going to age tickets ever regardless of cron settings. 
if($cfg && $cfg->enableAutoCron()){ //ONLY fetch tickets if autocron is enabled!
    Cron::MailFetcher();  //Fetch mail.
    Sys::log(LOG_DEBUG,'Autocron','cron job executed ['.$thisuser->getUserName().']');
}    
$_SESSION['lastcroncall']=time();
endif;
$output = ob_get_contents();
ob_end_clean();
?>
