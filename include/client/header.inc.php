<?php $title=($cfg && is_object($cfg))?$cfg->getTitle():'osTicket :: Support Ticket System';
header("Content-Type: text/html; charset=UTF-8\r\n");
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo Format::htmlchars($title)?></title>
    <!--[if lt IE 9]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
    <link rel="stylesheet" href="./styles/main.css" media="screen">
    <link rel="stylesheet" href="./styles/1140.css" media="screen">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="./scripts/functions.js"></script>
    <!--[if IE]>
        <style type="text/css">
            .pie {behavior: url(PIE.php); position:relative; z-index:1;}
        </style> 
    <![endif]-->
</head>
<body class="<?php echo $bodyclass; ?>">
    <header class="container">
		<div class="row">
			<div class="twelvecol last">
				<h1><a href="index.php" title="Support Ticket Center" class="title"><?php echo Format::htmlchars($title)?></a></h1>
				<nav id="mainNav">
					<ul id="topNav">
					 <li><a class="home" href="index.php">Home</a></li>
					<?php                     
			         if($thisclient && is_object($thisclient) && $thisclient->isValid()) {?>
			         <li><a class="my_tickets" href="tickets.php">My Tickets</a></li>
			         <?php }else {?>
			         <li><a class="ticket_status" href="tickets.php">Ticket Status</a></li>
			         <?php }?>
			         <li><a class="new_ticket" href="open.php">New Ticket</a></li>
			         <?php                     
			         if($thisclient && is_object($thisclient) && $thisclient->isValid()) {?>
			         <li><a class="log_out" href="logout.php">Log Out</a></li>
			         <?php }?>
					</ul>
				</nav>
				<a href="#" id="mobileNavLink">Nav</a>
			</div>
		</div>
	</header>
