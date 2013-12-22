<?

// configure your imap mailboxes
$mailboxes = array(
array(
'label' 	=> 'Gmail',
'enable'	=> true,
'mailbox' 	=> '{imap.gmail.com:993/imap/ssl}INBOX',
'username' 	=> 'wokmanlieferservice@gmail.com',
'password' 	=> 'arwinramin'
)
);

// a function to decode MIME message header extensions and get the text
function decode_imap_text($str){
$result = '';
$decode_header = imap_mime_header_decode($str);
foreach ($decode_header AS $obj) {
$result .= htmlspecialchars(rtrim($obj->text, "\t"));
}
return $result;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
        <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
        <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
        <script type="text/javascript" src="JS/code.js"></script>
        <link rel="shortcut icon" href="images/favicon.ico">
        <link rel="stylesheet" type="text/css" href="css/content.css" media="screen">
        <link rel="stylesheet" type="text/css" href="css/print.css" media="print" />
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Wokman Mailbox System</title>
    </head>
    <body>
        <div id="wrapper">
            <div id="main">
                <h1>Wokman MailBox System</h1>	
                <div id="mailboxes">
                    <? if (!count($mailboxes)) { ?>
                    <p>Please configure at least one IMAP mailbox.</p>
                    <? } else { 

                    foreach ($mailboxes as $current_mailbox) {
                    ?>
                    <div class="mailbox">
                        <?
                        if (!$current_mailbox['enable']) {
                        ?>
                        <p>This mailbox is disabled.</p>
                        <?
                        } else {

                        // Open an IMAP stream to our mailbox
                        $stream = @imap_open($current_mailbox['mailbox'], $current_mailbox['username'], $current_mailbox['password']);

                        if (!$stream) { 
                        ?>
                        <p>Could not connect to: <?= $current_mailbox['label'] ?>. Error: <?= imap_last_error() ?></p>
                        <?
                        } else {
                        // Get our messages from the last week
                        // Instead of searching for this week's message you could search for all the messages in your inbox using: $emails = imap_search($stream,'ALL');
                        $emails = imap_search($stream, 'SINCE '. date('d-M-Y',strtotime("-2 day")));

                        if (!count($emails)){
                        ?>
                        <p>No e-mails found.</p>
                        <?
                        } else {

                        // If we've got some email IDs, sort them from new to old and show them
                        rsort($emails);
                        ?>
                        <div id="accordion">
                            <?
                            foreach($emails as $email_id){                        
                            // Fetch the email's overview and show subject, from and date. 
                            $overview = imap_fetch_overview($stream,$email_id,0);                            
                            $header_info = imap_headerinfo($stream,$email_id);
                            if($header_info->from[0]->host==="pizzeria.de")
                            {$message = imap_fetchbody($stream,$email_id,2);}
                            else{$message = imap_fetchbody($stream,$email_id,1);}
                            ?>
                            <h3><div class="email_item clearfix <?= $overview[0]->seen ? 'read' : 'unread' ?>"> <? // add a different class for seperating read and unread e-mails ?></div>
                                <span class="subject" title="<?= decode_imap_text($overview[0]->subject) ?>"><?= decode_imap_text($overview[0]->subject) ?></span>
                                <span class="from" title="<?= decode_imap_text($overview[0]->from) ?>"><?= decode_imap_text($overview[0]->from) ?></span>
                                <span class="date"><?= date("D Y-m-d H:i:s", strtotime($overview[0]->date)) ?></span></h3>
                            <div id="message<?= $email_id ?>">
                                <div class="content-message"><?= $message ?></div>
                                <a href="#" class="print" onclick="print4(<?= $email_id ?>);"><img src="images/Printer-4.png" id="print-photo" alt="printer" width="80" hight="80"></a>
                                <a href="#" class="print" onclick="print(<?= $email_id ?>);"><img src="images/Printer.png" id="print-photo" alt="printer" width="80" hight="80"></a>
                            </div>
                            <?
                            }?>
                        </div>
                    </div>
                    <?
                    } 

                    imap_close($stream); 
                    }

                    } 
                    ?>
                </div>
                <?
                } // end foreach 
                } ?>

            </div><!-- #main -->

            <div id="footer">
            </div>
        </div><!-- #wrapper -->
    </body>
</html>