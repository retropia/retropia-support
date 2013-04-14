<?php /*********************************************************************
    class.cron.php

    Nothing special...just a central location for all cron calls.
    
    Peter Rotich <peter@osticket.com>
    Copyright (c)  2006-2010 osTicket
    http://www.osticket.com

    Released under the GNU General Public License WITHOUT ANY WARRANTY.
    See LICENSE.TXT for details.

    TODO: The plan is to make cron jobs db based.
    
    vim: expandtab sw=4 ts=4 sts=4:
    $Id: $
**********************************************************************/
//TODO: Make it DB based!
class Cron {

    static function MailFetcher() {
        require_once(INCLUDE_DIR.'class.mailfetch.php');
        MailFetcher::fetchMail(); //Fetch mail..frequency is limited by email account setting.
    }

    static function TicketMonitor() {
        require_once(INCLUDE_DIR.'class.ticket.php');
        require_once(INCLUDE_DIR.'class.lock.php');
        Ticket::checkOverdue(); //Make stale tickets overdue
        TicketLock::cleanup(); //Remove expired locks 
    }

    static function PurgeLogs() {
        Sys::purgeLogs();
    }

    static function run(){ //called by outside cron NOT autocron
        Cron::MailFetcher();
        Cron::TicketMonitor();
        cron::PurgeLogs();
    }
}
?>
