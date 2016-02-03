
function CanvasObject(canvasId) {

    this.canvas = null;
    this.ctx = null;

    this.initCanvas = function() {
        this.canvas = document.getElementById(canvasId);
        this.ctx = this.canvas.getContext("2d");
        this.canvas.width = window.innerWidth;
        this.canvas.height = window.innerHeight;
    };

    this.clearCanvas = function() {
        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
    };

    this.initCanvas();
}

function initStats() {
    var stats = new Stats();
    stats.setMode(0);
    stats.domElement.style.position = 'absolute';
    stats.domElement.style.left = '0px';
    stats.domElement.style.top = '0px';
    document.body.appendChild(stats.domElement);
    return stats;
}

function getRandom() {
    return Math.random();
}

function isCanvasSupport() {
    return Modernizr.canvas;
}

function initMouseEvents(handleEvent) {
    document.addEventListener('mousemove', handleEvent, false);
}

function writeLog(text, object) {
    if (window.isDebug) {
        console.log(text, object);
    }
}