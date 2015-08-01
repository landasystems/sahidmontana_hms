function printElement(id) {
    elem = document.getElementById(id);
    var popupWin = window.open('', '_blank', 'width=1000,height=700');
    popupWin.document.open()
    popupWin.document.write('<html><head><link rel="stylesheet" type="text/css" href="css/print.css" /></head><body onload="window.print()">' + elem.innerHTML + '</html>');
    popupWin.document.close();
}
document.documentElement.className += 'loadstate';