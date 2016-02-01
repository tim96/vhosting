window.addEventListener("load", windowLoadHandlerNew, false);

function windowLoadHandlerNew() {
    constellationExampleApp();
}

function constellationExampleApp() {

    function isCanvasSupport() {
        return Modernizr.canvas;
    }

    var fps = 30;
    var canvas = null;
    var ctx = null;
    var isDebug = true;
    // var isDebug = false;
    var velocity = 0.2;
    var length = 200;
    var config = {
        star: {
            color: '#FFFFFF',
            width: 2
        }
    };

    function init() {
        if (!isCanvasSupport()) {
            console.log('Your browser does not support HTML5 canvas.')
            return;
        }

        initCanvas();

        initStats();
    }

    function initStats() {
        var stats = new Stats();
        stats.setMode(0);
        stats.domElement.style.position = 'absolute';
        stats.domElement.style.left = '0px';
        stats.domElement.style.top = '0px';
        document.body.appendChild(stats.domElement);
    }

    function initCanvas() {
        canvas = document.getElementById("example");
        ctx = canvas.getContext("2d");
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
    }

    var Star = function() {
        this.x = getRandom() * canvas.width;
        this.y = getRandom() * canvas.height;

        this.vx = (velocity - (getRandom() * 0.5));
        this.vy = (velocity - (getRandom() * 0.5));

        this.radius = getRandom() * config.star.width;
    };

    Star.prototype.render = function() {
        context.beginPath();
        context.arc(this.x, this.y, this.radius, 0, Math.PI * 2, false);
        context.fill();

        writeLog('Render star');
    };

    function getRandom() {
        return Math.random();
    }

    function writeLog(text, object) {
        if (isDebug) {
            console.log(text, object);
        }
    }

    init();
}