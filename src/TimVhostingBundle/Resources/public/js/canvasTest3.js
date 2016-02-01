window.addEventListener("load", windowLoadHandlerNew, false);

function windowLoadHandlerNew() {
    constellationExampleApp();
}

function constellationExampleApp() {

    function isCanvasSupport() {
        return Modernizr.canvas;
    }

    function init() {
        if (!isCanvasSupport()) {
            console.log('Your browser does not support HTML5 canvas.')
            return;
        }
    }

    init();
}