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
    var isDebug = true;
    // var isDebug = false;
    var renderTimer = setInterval(draw, 1/fps*100);
    var player = null;
    var playersList = [];

    canvas.width = W;
    canvas.height = H;

    // use <canvas id='example' tabindex='1'>
    // canvas.addEventListener('keydown', handleKeyDown, false);
    // or
    document.addEventListener('keydown', handleKeyDown, false);
    // disable mouse events for debug
    // document.addEventListener('click', handleClick, false);
    // document.addEventListener('mousemove', handleMouseMove, false);

    var BaseObject = function() {
        this.name = "BaseObject";
        this.color = '#FFFFFF';
        this.position = new Point(0, 0);
        this.width = 15;
        this.height = 15;
        this.speed = 10;
    };
    BaseObject.prototype.render = function() {
        ctx.beginPath();
        ctx.fillStyle = this.color;
        ctx.fillRect(this.position.x, this.position.y, this.width, this.height);
        ctx.fill();
        // writeLog('Render baseObject');
    };
    BaseObject.prototype.about = function() {
        console.log('about object', this);
        // writeLog('About object');
    };
    BaseObject.prototype.changeStyle = function(color) {
        this.color = color;
    };
    BaseObject.prototype.changeSize = function(width, height) {
        this.width = width;
        this.height = height;
    };
    BaseObject.prototype.setPositionX = function(newX) {
        this.position.x = newX;
    };
    BaseObject.prototype.setPositionY = function(newY) {
        this.position.y = newY;
    };
    BaseObject.prototype.changeSpeed = function(newSpeed) {
        this.speed = newSpeed;
    };

    var Player = function() {
        BaseObject.call(this);
        this.name = "playerName";
        this.color = "#FFFFFF";
        this.position = new Point(0, 0);
        this.width = 25;
        this.height = 25;
        this.speed = 2;
    };
    Player.prototype = Object.create(BaseObject.prototype);
    Player.prototype.constructor = BaseObject;

    Player.prototype.update = function(time) {
        if (this.position.x < 0) this.setPositionX(0);
        if (this.position.x + this.width > canvas.width) this.setPositionX(canvas.width - this.width);
    };
    // override function about player
    Player.prototype.about = function() {
        console.log('Player', this);
    };

    var Point = function(x1, y1) {
        this.x = x1;
        this.y = y1;
    };

    Point.prototype.set = function(x1, y1) {
        this.x = x1;
        this.y = y1;
    };

    var Bullet = function() {
        BaseObject.call(this);
        this.name = "Bullet";
        this.color = '#FFFFFF';
        this.position = new Point(0, 0);
        this.width = 15;
        this.height = 15;
        this.delay = 2;
        this.speed = 20;
    };

    Bullet.prototype = Object.create(BaseObject.prototype);
    Bullet.prototype.constructor = BaseObject;

    Bullet.prototype.update = function(time) {
        this.position.y += (-this.speed) * time;
    };

    function fire(startPoint) {
        // writeLog('fire');
        var bullet = new Bullet();
        bullet.position.set(player.position.x + player.width/2 - bullet.width/2 + startPoint.x,
            player.position.y + player.width/2 - bullet.width/2 + startPoint.y);

        playersList.push(bullet);
        // writeLog('Bullet', bullet);
    }

    var Barrier = function() {
        BaseObject.call(this);
        this.name = "Barrier";
        this.color = '#FFFFFF';
        this.position = new Point(0, 0);
        this.width = 15;
        this.height = 15;
        this.speed = 10;
    };

    Barrier.prototype = Object.create(BaseObject.prototype);
    Barrier.prototype.constructor = BaseObject;

    /*var ball = {
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
    };*/

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
            fire(new Point(0, -player.width));
        }

        return false;
    }

    function handleClick(e) {
        // console.log('Click', e);
        // fire only left button click on mouse
        if (e.button == 0) {
            fire(new Point(0, -player.width));
        }

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
            playersList[index].update(1/fps);
        }
    }

    function paintCanvas() {
        ctx.fillStyle = "green";
        ctx.fillRect(0, 0, W, H);
    }

    function draw() {
        paintCanvas();

        paintPlayers();
        // another way
        // playersList.forEach(function(e) { e.render(); });

        updatePlayers();
        // another way
        // playersList.forEach(function(e) { e.update(1/fps); });

        // ball.draw();
        // writeLog('draw');
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