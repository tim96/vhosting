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

    canvas.width = W;
    canvas.height = H;

    // use <canvas id='example' tabindex='1'>
    // canvas.addEventListener('keydown', handleKeyDown, false);
    // or
    document.addEventListener('keydown', handleKeyDown, false);

    var ball = {
        x: 50,
        y: 50,
        c: "white",
        w: 50,
        h: 50,

        draw: function() {
            ctx.beginPath();
            ctx.fillStyle = this.c;
            ctx.fillRect(this.x, this.y, this.w, this.h);
            ctx.fill();
        }
    };

    function handleKeyDown(e) {
        console.log('keycode: ' + e.keyCode, e);
        return false;
    }

    function paintCanvas() {
        ctx.fillStyle = "green";
        ctx.fillRect(0, 0, W, H);
    }

    function draw() {
        paintCanvas();

        ball.draw();
    }

    function init() {
        draw();
    }

    init();
}



function windowLoadHandlerNew() {
    keyboardExampleApp();
}