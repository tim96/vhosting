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
    var countStar = 250;
    var config = {
        star: {
            color: '#FFFFFF',
            width: 2
        }
    };
    var stars = [];

    function init() {
        if (!isCanvasSupport()) {
            console.log('Your browser does not support HTML5 canvas.')
            return;
        }

        initCanvas();

        initStats();

        createStars();
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

    function clearCanvas() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
    }

    var Star = function() {
        this.x = getRandom() * canvas.width;
        this.y = getRandom() * canvas.height;

        this.vx = (velocity - (getRandom() * 0.5));
        this.vy = (velocity - (getRandom() * 0.5));

        this.radius = getRandom() * config.star.width;
        this.color = config.star.color;
    };

    Star.prototype.render = function() {
        ctx.beginPath();
        ctx.fillStyle = this.color;
        ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2, false);
        ctx.fill();

        writeLog('Render star');
    };

    function createStars() {
        var count = countStar;

        clearCanvas();

        for(var index = 0; index < count; index++) {
            var tempStar = new Star();
            tempStar.render();

            stars.push(tempStar);
        }
    }

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