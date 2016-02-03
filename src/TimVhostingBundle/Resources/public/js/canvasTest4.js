window.addEventListener("load", windowLoadHandlerNew, false);

function windowLoadHandlerNew() {
    navigationExampleApp();
}

function navigationExampleApp() {

    var canvas = null;
    var canvasObject = null;
    // window.isDebug = false;
    window.isDebug = true;
    var fps = 60;
    var stats = null;
    var canvasId = 'example';

    function init() {
        if (!isCanvasSupport()) {
            console.log('Your browser does not support HTML5 canvas.')
            return;
        }

        canvasObject = new CanvasObject(canvasId);

        stats = initStats();

        writeLog('navigation app init', 1);
    }

    init();
}