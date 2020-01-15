/*
 * Apps Timeout for mouse + keyboard inactivity.
 * Default timeout: 
 */
var appsTimeOut = function () {
    var xtimeout;
    window.onload = resetXTimer;
    document.onmousemove = resetXTimer;
    document.onkeypress = resetXTimer;

    function xlogout() {
        document.location = '../ajax_json_engine.php?cmdx=exit';
    }

    function resetXTimer() {
        clearTimeout(xtimeout);
        xtimeout = setTimeout(xlogout, 1800000);
    }
};
appsTimeOut();