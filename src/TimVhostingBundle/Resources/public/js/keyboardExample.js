window.addEventListener("load", windowLoadHandlerNew, false);

function canvasSupport() {
    return Modernizr.canvas;
}

function keyboardExampleApp() {

    if (!canvasSupport()) {
        console.log('Your browser does not support HTML5 canvas.')
        return;
    }

    // Initialize canvas and required variables
    var canvas = document.getElementById("example"),
        ctx = canvas.getContext("2d"),
        W = 640, // window.innerWidth,
        H = 480, // window.innerHeight,
        fps = 60;
    var isDebug = false;
    var renderTimer = setInterval(draw, 1/fps*100);

    canvas.width = W;
    canvas.height = H;

    // use <canvas id='example' tabindex='1'>
    // canvas.addEventListener('keydown', handleKeyDown, false);
    // or
    document.addEventListener('keydown', handleKeyDown, false);
    document.addEventListener('click', handleClick, false);
    document.addEventListener('mousemove', handleMouseMove, false);

    var Player = function() {
        this.name = "playerName";
        this.color = "#FFFFFF";
    };

    var Point = function(x1, y1) {
        this.x = x1;
        this.y = y1;
    };

    Point.prototype.set = function(x1, y1) {
        this.x = x1;
        this.y = y1;
    };

    var ball = {
        x: 0,
        y: 0,
        c: "white",
        w: 25,
        h: 25,

        draw: function() {
            ctx.beginPath();
            ctx.fillStyle = this.c;
            ctx.fillRect(this.x, this.y, this.w, this.h);
            ctx.fill();
        }
    };

    function handleKeyDown(e) {
        console.log('Keycode: ' + e.keyCode, e);
        return false;
    }

    function handleClick(e) {
        console.log('Click', e);
        return false;
    }

    function handleMouseMove(e) {
        console.log('MouseMove', e);
        return false;
    }

    function paintCanvas() {
        ctx.fillStyle = "green";
        ctx.fillRect(0, 0, W, H);
    }

    function draw() {
        paintCanvas();

        ball.draw();
        console.log('draw');
    }

    function init() {
        draw();
    }

    function writeLog(text, object) {
        if (isDebug) {
            console.log(text, object);
        }
    }

    init();
}



function windowLoadHandlerNew() {
    keyboardExampleApp();
}