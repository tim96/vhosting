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
    var distance = 120;
    var countStar = 400;
    var positionX = 0;
    var positionY = 0;
    var radius = 150;
    var selectColor = '#FF0000';
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

    Star.prototype.connect = function() {
        var count = countStar;
        var starOne = null;
        var starTwo = null;

        for(var i = 0; i < count; i++) {
            for(var j = 0; j < count; j++) {
                starOne = stars[i];
                starTwo = stars[j];

                var diffX = starOne.x - starTwo.x;
                var diffY = starOne.y - starTwo.y;

                if (diffX < distance && diffY < distance &&
                    diffX > - distance && diffY > - distance)
                {
                    diffX = starOne.x - positionX;
                    diffY = starOne.y - positionY;

                    if (diffX < radius && diffY < radius &&
                        diffX > - radius && diffY > - radius) {
                        ctx.beginPath();
                        ctx.strokeStyle = selectColor;
                        // ctx.fillStyle = selectColor;
                        ctx.moveTo(starOne.x, starOne.y);
                        ctx.lineTo(starTwo.x, starTwo.y);
                        ctx.stroke();
                        ctx.closePath();
                    }
                }
            }
        }
        writeLog('Connect stars');
    };

    Star.prototype.move = function() {
        var count = countStar;

        for (var i = 0; i < count; i++) {
            var star = stars[i];

            if (star.y < 0 || star.y > canvas.height) {
                // star.vx = star.vx;
                star.vy = - star.vy;
            } else if (star.x < 0 || star.x > canvas.width) {
                star.vx = - star.vx;
                // star.vy = star.vy;
            }

            star.x += star.vx;
            star.y += star.vy;
        }

        writeLog('Move stars');
    };

    function createStars() {
        var count = countStar;
        var tempStar = null;

        clearCanvas();

        for(var index = 0; index < count; index++) {
            tempStar = new Star();
            tempStar.render();

            stars.push(tempStar);
        }

        tempStar.connect();
        tempStar.move();
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