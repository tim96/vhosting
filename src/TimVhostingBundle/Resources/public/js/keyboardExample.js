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
        fps = 30; // 60;
    var isDebug = false;
    var renderTimer = setInterval(draw, 1/fps*100);
    var player = null;
    var playersList = [];

    canvas.width = W;
    canvas.height = H;

    // use <canvas id='example' tabindex='1'>
    // canvas.addEventListener('keydown', handleKeyDown, false);
    // or
    document.addEventListener('keydown', handleKeyDown, false);
    document.addEventListener('click', handleClick, false);
    // document.addEventListener('mousemove', handleMouseMove, false);

    var Player = function() {
        this.name = "playerName";
        this.color = "#FFFFFF";
        this.position = new Point(0, 0);
        this.width = 25;
        this.height = 25;
        this.speed = 2;
    };

    Player.prototype.render = function() {
        ctx.beginPath();
        ctx.fillStyle = this.color;
        ctx.fillRect(this.position.x, this.position.y, this.width, this.height);
        ctx.fill();
        writeLog('Render player');
    };
    Player.prototype.update = function() {
        if (this.position.x < 0) this.setPositionX(0);
        if (this.position.x + this.width > canvas.width) this.setPositionX(canvas.width - this.width);
    };
    Player.prototype.about = function() {
        console.log('Player', this);
    };
    Player.prototype.changeStyle = function(color) {
        this.color = color;
    };
    Player.prototype.changeSize = function(width, height) {
        this.width = width;
        this.height = height;
    };
    Player.prototype.setPositionX = function(newX) {
        this.position.x = newX;
    };
    Player.prototype.setPositionY = function(newY) {
        this.position.y = newY;
    };
    Player.prototype.changeSpeed = function(newSpeed) {
        this.speed = newSpeed;
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
        //console.log('Keycode: ' + e.keyCode, e);
        var left = 37;
        var right = 39;
        var up = 38;
        var down = 40;
        var space = 32;
        var keyCode = e.keyCode;

        // todo: change to switch ?
        if (keyCode == left) {
            if (player.position.x - player.speed > 0) {
                player.setPositionX(player.position.x - player.speed);
            } else {
                player.setPositionX(0);
            }
        } else if (keyCode == right) {
            if (player.position.x + player.width + player.speed < canvas.width) {
                player.setPositionX(player.position.x + player.speed);
            } else {
                player.setPositionX(canvas.width - player.width);
            }
        } else if (keyCode == up) {

        } else if (keyCode == down) {

        } else if (keyCode == space) {

        }

        return false;
    }

    function handleClick(e) {
        // console.log('Click', e);
        return false;
    }

    function handleMouseMove(e) {
        // console.log('MouseMove', e);
        var field = canvas.getBoundingClientRect();
        var mouseX = e.clientX - field.left;
        if (mouseX + player.width <= canvas.width) {
            player.setPositionX(mouseX);
        } else {
            player.setPositionX(canvas.width - player.width);
        }

        return false;
    }

    function paintPlayers()
    {
        for(var index in playersList) {
            playersList[index].render();
        }
    }

    function updatePlayers()
    {
        for(var index in playersList) {
            playersList[index].update();
        }
    }

    function paintCanvas() {
        ctx.fillStyle = "green";
        ctx.fillRect(0, 0, W, H);
    }

    function draw() {
        paintCanvas();

        paintPlayers();

        updatePlayers();
        // playersList.forEach(function(e) { e.render(); });
        ball.draw();

        writeLog('draw');
    }

    function init() {
        // draw();

        player = new Player();
        player.position.set(canvas.width / 2, canvas.height - player.height);
        player.render();

        playersList.push(player);
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