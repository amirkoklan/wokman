$(function() {
    $("#accordion").accordion({
        heightStyle: "content",
        collapsible: true,
        active:false
    });
});
function print4(email_id) {
    var message = "message".concat(email_id);
    var prtContent = document.getElementById(message).innerHTML;
    var WinPrint = window.open('', '', 'letf=0,top=0,width=700,height=800,toolbar=0,scrollbars=0,status=0');
    WinPrint.document.write('<html><title>::Print::</title><link rel="stylesheet" type="text/css" href="css/print.css" /></head><body onload="window.print()">');
    WinPrint.document.write(prtContent, prtContent, prtContent, prtContent);
    WinPrint.document.write('</body></html>');
    WinPrint.document.close();
    WinPrint.focus();
    WinPrint.print();
    WinPrint.close();
}
function print(email_id) {
    var message = "message".concat(email_id);
    var prtContent = document.getElementById(message).innerHTML;
    var WinPrint = window.open('', '', 'letf=0,top=0,width=700,height=800,toolbar=0,scrollbars=0,status=0');
    WinPrint.document.write('<html><title>::Print::</title><link rel="stylesheet" type="text/css" href="css/print1.css" /></head><body onload="window.print()">');
    WinPrint.document.write(prtContent);
    WinPrint.document.write('</body></html>');
    WinPrint.document.close();
    WinPrint.focus();
    WinPrint.print();
    WinPrint.close();
}

window.setInterval(function(){
var request = $.ajax({
  url: "../wokman/PHP/ajax.php",
  type: "POST",
  dataType: "html"
});

request.done(function( msg ) {
$('#accordion').prepend(msg)
    .accordion('destroy').accordion({heightStyle: "content",collapsible: true,active: false});
});

request.fail(function( jqXHR, textStatus ) {
  alert( "Request failed: " + textStatus );
});
}, 30000);