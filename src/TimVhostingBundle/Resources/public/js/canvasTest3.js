"use strict";

window.addEventListener("load", windowLoadHandlerNew, false);

function windowLoadHandlerNew() {
    constellationExampleApp();
}

function constellationExampleApp() {

    var fps = 60;
    var canvasObject = null;
    window.isDebug = false;
    // window.isDebug = true;
    var velocity = 0.2;
    var distance = 120;
    var countStar = 500;
    var positionX = 0;
    var positionY = 0;
    var radius = 200;
    var selectColor = '#FF0000';
    var canvasColor = '#111';
    var config = {
        star: {
            color: '#FFFFFF',
            width: 2
        }
    };
    var stars = [];
    var stats = null;
    var canvasId = 'example';
    var renderTimer = null;
    var lines = [];
    var endBtn = null;

    function init() {
        if (!isCanvasSupport()) {
            console.log('Your browser does not support HTML5 canvas.');
            return;
        }

        canvasObject = new CanvasObject(canvasId);

        stats = initStats();

        createStars();

        initMouseEvents(handleMouseMove);
        initMouseClick(handleClick);

        renderTimer = setInterval(initMove, 1/fps*100);
    }

    function initMove() {
        stats.begin();

        paintStars();
        connect();
        move();

        endBtn.render(canvasObject);

        stats.end();
    }

    function handleMouseMove(e) {
        // console.log('Mouse move', e);

        var field = canvasObject.canvas.getBoundingClientRect();
        positionX = e.clientX - field.left;
        positionY = e.clientY - field.top;
    }

    function handleClick(e) {

        if (isEndBtnClick(e)) {
            stopTimers();
            return false;
        }

        function isEndBtnClick(e) {
            var mx = e.pageX;
            var my = e.pageY;

            // console.log('mx', mx);
            // console.log('my', my);
            // console.log('endBtn.position', endBtn.position);
            // console.log('endBtn', endBtn);

            return (mx >= endBtn.position.x && mx <= endBtn.position.x + endBtn.width
            && my >= endBtn.position.y && my <= endBtn.position.y + endBtn.height);
        }
    }

    var Star = function() {
        this.x = getRandom() * canvasObject.canvas.width;
        this.y = getRandom() * canvasObject.canvas.height;

        this.vx = (velocity - (getRandom() * 0.5));
        this.vy = (velocity - (getRandom() * 0.5));

        this.radius = getRandom() * config.star.width;
        this.color = config.star.color;
    };

    Star.prototype.render = function() {
        canvasObject.ctx.beginPath();
        canvasObject.ctx.fillStyle = this.color;
        canvasObject.ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2, false);
        canvasObject.ctx.fill();

        // writeLog('Render star');
    };

    function connect() {
        var count = countStar;
        var starOne = null;
        var starTwo = null;

        lines = [];

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

                        var isIntersect = false;
                        for(var index = 0, len = lines.length; index < len; index++ ) {
                            var res = intersectFromPhp(
                                lines[index].p1.x, lines[index].p1.y, lines[index].p2.x, lines[index].p2.y,
                                starOne.x, starOne.y, starTwo.x, starTwo.y);
                            if (res) {
                                isIntersect = res;
                                break;
                            }
                        }

                        if (!isIntersect) {
                            canvasObject.ctx.beginPath();
                            canvasObject.ctx.strokeStyle = selectColor;
                            // ctx.fillStyle = selectColor;
                            canvasObject.ctx.moveTo(starOne.x, starOne.y);
                            canvasObject.ctx.lineTo(starTwo.x, starTwo.y);
                            canvasObject.ctx.stroke();
                            canvasObject.ctx.closePath();

                            var line = new Line(new Point(starOne.x, starOne.y),
                                new Point(starTwo.x, starTwo.y));

                            lines.push(line);
                        }
                    }
                }
            }
        }

        // writeLog('Connect stars');
    }

    function move() {
        var count = countStar;

        for (var i = 0; i < count; i++) {
            var star = stars[i];

            if (star.y < 0 || star.y > canvasObject.canvas.height) {
                // star.vx = star.vx;
                star.vy = - star.vy;
            } else if (star.x < 0 || star.x > canvasObject.canvas.width) {
                star.vx = - star.vx;
                // star.vy = star.vy;
            }

            star.x += star.vx;
            star.y += star.vy;
        }

        // writeLog('Move stars');
    }

    function createStars() {
        var count = countStar;
        var tempStar = null;

        canvasObject.clearCanvas(canvasColor);

        for(var index = 0; index < count; index++) {
            tempStar = new Star();
            tempStar.render();

            stars.push(tempStar);
        }

        createEndBtn(canvasObject);

        connect();
        move();
    }

    function paintStars() {
        canvasObject.clearCanvas(canvasColor);

        for(var index in stars) {
            var tempStar = stars[index];
            tempStar.render();
        }
    }

    var Point = function(x1, y1) {
        this.x = x1;
        this.y = y1;
    };

    Point.prototype.set = function(x1, y1) {
        this.x = x1;
        this.y = y1;
    };

    var Line = function(p1, p2) {
        this.p1 = p1;
        this.p2 = p2;
    };

    Line.prototype.set = function(p1, p2) {
        this.p1 = p1;
        this.p2 = p2;
    };

    var Button = function() {
        this.name = 'Button';
        this.color = 'white';
        this.position = new Point(0, 0);
        this.width = 100;
        this.height = 50;
        this.text = 'Button';
        this.textAlign = 'center';
    };
    Button.prototype.setPosition = function(newX, newY) {
        this.position.x = newX;
        this.position.y = newY;
    };
    Button.prototype.render = function(canvasObject) {
        var ctx = canvasObject.ctx;
        if (ctx.measureText(this.text).width > this.width) {
            this.width = ctx.measureText(this.text).width;
        }
        if (ctx.measureText(this.text).height > this.height) {
            this.height = ctx.measureText(this.text).height;
        }

        ctx.font = "18pt Arial, sans-serif";
        ctx.textAlign = this.textAlign;
        ctx.textBaseline = "middle";
        ctx.strokeStyle = this.color;
        ctx.lineWidth = "2";
        ctx.beginPath();
        ctx.fillStyle = this.color;
        if (this.textAlign == 'center') {
            ctx.strokeRect(this.position.x - this.width / 2, this.position.y - this.height / 2, this.width, this.height);
        } else {
            ctx.strokeRect(this.position.x, this.position.y, this.width, this.height);
        }
        ctx.fill();
        ctx.stroke();

        ctx.fillStyle = this.color;
        if (this.textAlign == 'center') {
            ctx.fillText(this.text, this.position.x, this.position.y);
        } else if (this.textAlign == 'left') {
            ctx.fillText(this.text, this.position.x + 10, this.position.y + this.height / 2);
        } else {
            // todo: check situation
        }
        // ctx.fillText(ctx.measureText(this.text).width, this.position.x, this.position.y);
    };

    function createEndBtn(canvasObject) {
        var ctx = canvasObject.ctx;
        endBtn = new Button();
        endBtn.setPosition(canvasObject.canvas.width - endBtn.width / 2 - 1, 0 + endBtn.height / 2 + 1);
        endBtn.name = 'EndButton';
        endBtn.text = 'End';
        endBtn.render(canvasObject);
    }

    var stopTimers = function() {
        clearInterval(renderTimer);
    };

    init();
}