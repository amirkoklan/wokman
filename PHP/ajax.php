<?php

fetch_unread('{imap.gmail.com:993/imap/ssl}INBOX', 'wokmanlieferservice@gmail.com', 'arwinramin');

function fetch_unread($mailbox, $username, $password) {
    $connection = imap_open($mailbox, $username, $password);
    $emails = imap_search($connection, 'SINCE ' . date('d-M-Y', strtotime("-2 day")));
    if (count($emails)) {
        rsort($emails);
        foreach ($emails as $email_id) {
            // Fetch the email's overview and show subject, from and date. 
            $overview_unread = imap_fetch_overview($connection, $email_id, 0);
            if ($overview_unread[0]->seen == 'unread') {
                $header_info_unread = imap_headerinfo($connection, $email_id);
                if ($header_info_unread->from[0]->host === "pizzeria.de") {
                    $message_unread = imap_fetchbody($connection, $email_id, 2);
                } else {
                    $message_unread = imap_fetchbody($connection, $email_id, 1);
                }
                $output .= '<h3><div class="email_item clearfix read"><span class="subject" title="' . $overview_unread[0]->subject . '">' . $overview_unread[0]->subject . '</span>
            <span class="from" title="' . $overview_unread[0]->from . '">' . $overview_unread[0]->from . '</span>
            <span class="date">' . date("D Y-m-d H:i:s", strtotime($overview_unread[0]->date)) . '</span></div></h3>
            <div id="message' . $email_id . '">
                <div class="content-message">' . $message_unread . '</div>
            <a href="#" class="print" onclick="print4(' . $email_id . ');"><img src="images/Printer-4.png" alt="printer" width="80" hight="80"></a>
            <a href="#" class="print" onclick="print(' . $email_id . ');"><img src="images/Printer.png" alt="printer" width="80" hight="80"></a>
            </div>';
            }
        }
    }
    if (!empty($output)){
    echo $output;}
}
