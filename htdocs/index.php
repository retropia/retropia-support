<?php /*********************************************************************
    index.php

    Helpdesk landing page. Please customize it to fit your needs.

    Peter Rotich <peter@osticket.com>
    Copyright (c)  2006-2010 osTicket
    http://www.osticket.com

    Released under the GNU General Public License WITHOUT ANY WARRANTY.
    See LICENSE.TXT for details.

    vim: expandtab sw=4 ts=4 sts=4:
    $Id: $
**********************************************************************/
require('client.inc.php');
//We are only showing landing page to users who are not logged in.
if($thisclient && is_object($thisclient) && $thisclient->isValid()) {
    require('tickets.php');
    exit;
}

$bodyclass = 'home';
require(CLIENTINC_DIR.'header.inc.php');
?>
	
	<div class="container">
		<div class="row">
			<div class="twelvecol last">
				<p class="headline">In order to streamline support requests and better serve you, we utilize a support ticket system.</p>
				<p>Every support request is assigned a unique ticket number which you can use to track the progress and responses online. For your reference we provide complete archives and history of all your support requests. A valid email address is required.</p>
			</div>
		</div>
	</div>
	
	<div class="container greyBlock">
		<div class="row">
			<div class="sixcol new">
				<h2><span></span> Open a new ticket</h2>
				<p>Please provide as much detail as possible so we can best assist you. To update a previously submitted ticket, please use the form to the right.</p>
				<form method="link" action="open.php">
					<input type="submit" class="button" value="Open New Ticket">
			    </form>
			</div>
			<div class="sixcol check last">
				<h2><span></span> Check ticket status</h2>
				<p>We provide archives and history of all your support requests complete with responses.</p>
				<form class="status_form" action="login.php" method="post">
					<div>
						<label>Email Address</label>
						<input type="text" name="lemail" size="50">
					</div>
					<div>
						<label>Ticket #</label>
						<input type="text" name="lticket" size="50">
					</div>
					<input type="submit" class="button" value="Check Status">
				</form>
			</div>
		</div>
	</div>

<?php require(CLIENTINC_DIR.'footer.inc.php'); ?>
