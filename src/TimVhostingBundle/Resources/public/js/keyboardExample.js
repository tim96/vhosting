window.addEventListener("load", windowLoadHandlerNew, false);

// Initialize canvas and required variables
var canvas = document.getElementById("example"),
    ctx = canvas.getContext("2d"),
    W = 640, // window.innerWidth,
    H = 480, // window.innerHeight,
    fps = 60;

canvas.width = W;
canvas.height = H;

function paintCanvas() {
    ctx.fillStyle = "green";
    ctx.fillRect(0, 0, W, H);
}

function draw() {
    paintCanvas();
}

function init() {
    draw();
}

function windowLoadHandlerNew() {
    init();
}