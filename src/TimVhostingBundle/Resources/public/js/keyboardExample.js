window.addEventListener("load", windowLoadHandlerNew, false);

function keyboardExampleApp() {

    if (!isCanvasSupport()) {
        console.log('Your browser does not support HTML5 canvas.');
        return;
    }

    var stats = initStats();

    // Initialize canvas and required variables
    var canvasId = 'example';
    var canvasColor = 'green';
    var canvasObject = new CanvasObject(canvasId);
    var canvas = canvasObject.canvas;

    var W = 640;  // window.innerWidth,
    var H = 480;  // window.innerHeight,
    var fps = 30; // 60;

    canvasObject.setCanvasWidth(W);
    canvasObject.setCanvasHeight(H);

    window.isDebug = true;
    // window.isDebug = false;

    var player = null;
    var playersList = [];
    var timeSpend = 0;
    var timeBarrierAppear = 1000; // 500 ms
    var timeRender = 1/fps*100;
    var renderTimer = null;
    var barrierTimer = null;
    var isStart = false;

    // use <canvas id='example' tabindex='1'>
    // canvas.addEventListener('keydown', handleKeyDown, false);
    // or
    // document.addEventListener('keydown', handleKeyDown, false);
    // disable mouse events for debug
    // document.addEventListener('click', handleClick, false);
    // document.addEventListener('mousemove', handleMouseMove, false);
    initKeyDown(handleKeyDown);

    var BaseObject = function() {
        this.name = "BaseObject";
        this.color = '#FFF000';
        this.position = new Point(0, 0);
        this.width = 15;
        this.height = 15;
        this.speed = 10;
    };
    BaseObject.prototype.render = function(ctx) {
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
    BaseObject.prototype.setPosition = function(newX, newY) {
        this.position.x = newX;
        this.position.y = newY;
    };
    BaseObject.prototype.changeSpeed = function(newSpeed) {
        this.speed = newSpeed;
    };
    BaseObject.prototype.isDeleted = function() {
        return this.position.y < 0;
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
    Bullet.prototype.isDeleted = function() {
        return this.position.y + this.height < 0;
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
        this.color = getRandomColor();
        this.position = new Point(0, 0);
        this.width = 15;
        this.height = 15;
        this.speed = 20;
    };

    Barrier.prototype = Object.create(BaseObject.prototype);
    Barrier.prototype.constructor = BaseObject;

    Barrier.prototype.isDeleted = function() {
        return (this.position.y > canvas.height + 20);
    };

    function createBarier() {
        var barrier = new Barrier();

        var diffX = Math.floor((Math.random() * canvas.width));
        var maxX = canvas.width - barrier.width;
        if (diffX > maxX) {
            diffX = maxX;
        }
        var diffY = -barrier.height;

        barrier.setPosition(diffX, diffY);

        downMove(barrier);

        playersList.push(barrier);

        // writeLog('Create barier', barrier);
    }

    function downMove(object) {
        object.update = function(time){
            // this.position.x = 0;
            this.position.y += this.speed * time;
        }
    }

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

    function paintPlayers(ctx)
    {
        for(var index in playersList) {
            playersList[index].render(ctx);
        }
    }

    function updatePlayers()
    {
        for(var i = 0, len = playersList.length; i < len; i++) {
            if (playersList[i].isDeleted()) {
                playersList.splice(i, 1);
                return;
            }

            playersList[i].update(1/fps);
        }

        // writeLog('count Players', playersList.length);
    }

    function paintCanvas() {
        canvasObject.clearCanvas(canvasColor);
    }

    function draw() {
        stats.begin();

        paintCanvas();

        paintPlayers(canvasObject.ctx);
        // another way
        // playersList.forEach(function(e) { e.render(); });
        stats.end();

        updatePlayers();
        // another way
        // playersList.forEach(function(e) { e.update(1/fps); });

        // writeLog('draw');
    }

    function initTimers() {
        renderTimer = setInterval(draw, timeRender);
        barrierTimer = setInterval(createBarier, timeBarrierAppear);

        // writeLog('initTimers', timeRender);
    }

    function init(canvasObject) {
        player = new Player();
        player.position.set(canvasObject.canvas.width / 2, canvasObject.canvas.height - player.height);
        player.render(canvasObject.ctx);

        playersList.push(player);

        initTimers();
    }

    this.destroy = function() {

        paintCanvas();

        player = null;
        playersList = [];

        this.stopTimers();
    };

    this.stopTimers = function() {
        clearInterval(renderTimer);
        clearInterval(barrierTimer);

        // writeLog('stop timers', 1);
    };

    init(canvasObject);

    return this;
}

function windowLoadHandlerNew() {
    // keyboardExampleApp();
    var application = null;

    $('#start').click(function() {
        application = keyboardExampleApp();
    });

    $('#stop').click(function() {
        if (application != null) {
            application.destroy();
            application = null;
        }
    });
}