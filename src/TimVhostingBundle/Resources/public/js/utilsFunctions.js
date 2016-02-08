"use strict";

function CanvasObject(canvasId) {

    this.canvas = null;
    this.ctx = null;

    this.initCanvas = function() {
        this.canvas = document.getElementById(canvasId);
        this.ctx = this.canvas.getContext("2d");
        this.canvas.width = window.innerWidth;
        this.canvas.height = window.innerHeight;
    };

    this.clearCanvas = function(color) {
        this.ctx.fillStyle = color;
        this.ctx.fillRect(0, 0, this.canvas.width, this.canvas.height);
    };

    this.setCanvasWidth = function(width) {
        this.canvas.width = width;
    };

    this.setCanvasHeight = function(height) {
        this.canvas.height = height;
    };

    this.initCanvas();
}

function initStats() {
    var stats = new Stats();
    stats.setMode(0);
    stats.domElement.style.position = 'absolute';
    stats.domElement.style.left = '0px';
    stats.domElement.style.top = '0px';
    document.body.appendChild(stats.domElement);
    return stats;
}

function getRandom() {
    return Math.random();
}

function getRandomInt(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

function getRandomColor() {
    var letters = '0123456789ABCDEF'.split('');
    var color = '#';
    for (var i = 0; i < 6; i++ ) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}

function isCanvasSupport() {
    return Modernizr.canvas;
}

function initMouseEvents(handleEvent) {
    document.addEventListener('mousemove', handleEvent, false);
}

function initMouseClick(handleEvent) {
    document.addEventListener('click', handleEvent, false);
}

function initKeyDown(handleKeyDown) {
    document.addEventListener('keydown', handleKeyDown, false);
}

function writeLog(text, object) {
    if (window.isDebug) {
        console.log(text, object);
    }
}

// todo: check this function
function intersect(sx1, sy1, sx2, sy2, dx1, dy1, dx2, dy2) {
    var sw = sx2 - sx1 + 1;
    var sh = sy2 - sy1 + 1;
    var dw = dx2 - dx1 + 1;
    var dh = dy2 - dy1 + 1;
    if (dx1 < sx1) {
        dw += dx1 - sx1;
        if (dw > sw) {
            dw = sw;
        }
    } else {
        var w = sw + sx1 - dx1;
        if (dw > w) {
            dw = w;
        }
    }
    if (dy1 < sy1) {
        dh += dy1 - sy1;
        if (dh > sh) {
            dh = sh;
        }
    } else {
        var h = sh + sy1 - dy1;
        if (dh > h) {
            dh = h;
        }
    }
    return ! (dw <= 0 || dh <= 0);
}

// todo: check this function
function intersectFromPhp($p0_x, $p0_y, $p1_x, $p1_y, $p2_x, $p2_y, $p3_x, $p3_y) {
    var $s1_x = $p1_x - $p0_x;
    var $s1_y = $p1_y - $p0_y;
    var $s2_x = $p3_x - $p2_x;
    var $s2_y = $p3_y - $p2_y;

    var $fps = (-$s2_x * $s1_y) + ($s1_x * $s2_y);
    var $fpt = (-$s2_x * $s1_y) + ($s1_x * $s2_y);

    if ($fps == 0 || $fpt == 0) {
        return false;
    }

    var $s = (-$s1_y * ($p0_x - $p2_x) + $s1_x * ($p0_y - $p2_y)) / $fps;
    var $t = ( $s2_x * ($p0_y - $p2_y) - $s2_y * ($p0_x - $p2_x)) / $fpt;

    if ($s > 0 && $s < 1 && $t > 0 && $t < 1) {
        // Collision detected
        return true;
    }
    return false;
}