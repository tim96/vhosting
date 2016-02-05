
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

// todo: check this function
function intersect(sx1, sy1, sx2, sy2, dx1, dy1, dx2, dy2) {
    var sw = sx2 - sx1 + 1;
    var sh = sy2 - sy1 + 1;
    var dw = dx2 - dx1 + 1;
    var dh = dy2 - dy1 + 1;
    if (dx1 < sx1) {
        dw += dx1 - sx1;
        if (dw > sw) {
            dw = sw;
        }
    } else {
        var w = sw + sx1 - dx1;
        if (dw > w) {
            dw = w;
        }
    }
    if (dy1 < sy1) {
        dh += dy1 - sy1;
        if (dh > sh) {
            dh = sh;
        }
    } else {
        var h = sh + sy1 - dy1;
        if (dh > h) {
            dh = h;
        }
    }
    return ! (dw <= 0 || dh <= 0);
}