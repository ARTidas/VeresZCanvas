let canvas = new Canvas();

let columns, rows;
let cell_dimension = 20; // width and height of each cell
let grid = [];
let current_cell;
let stack = []; // Initialize the stack


/** ********************************************************************
 ** *** MAIN ENTRY FUNCTION ********************************************
 ** ********************************************************************/
function setup() {
    canvas.object = createCanvas(
        canvas.getWidth(),
        canvas.getHeight()
    );

    columns = floor(canvas.getWidth() / cell_dimension);
    rows = floor(canvas.getHeight() / cell_dimension);

    // Create 2D array
    for (let j = 0; j < rows; j++) {
        for (let i = 0; i < columns; i++) {
            grid.push(new Cell(i, j));
        }
    }

    current_cell = grid[0];
}

/** ********************************************************************
 ** *** MAIN LOOPING FUNCTION ******************************************
 ** ********************************************************************/
 function draw() {
    console.log('Next cycle...');
    frameRate(30);
    background('#ddd');

    for (let i = 0; i < grid.length; i++) {
        grid[i].show();
    }

    current_cell.visited = true;
    current_cell.highlight();

    let next_cell = current_cell.checkNeighbors();

    if (next_cell) {
        next_cell.visited = true;
        stack.push(current_cell); // Push the current cell onto the stack
        removeWalls(current_cell, next_cell);
        current_cell = next_cell;
    } else if (stack.length > 0) {
        // Backtrack
        current_cell = stack.pop(); // Pop a cell from the stack
    }

    //noLoop();
}

function index(i, j) {
    if (i < 0 || j < 0 || i > columns - 1 || j > rows - 1) {
        return -1;
    }

    return i + j * columns;
}

function removeWalls(a, b) {
    let x = a.i - b.i;
    if (x === 1) {
        a.walls[3] = false;
        b.walls[1] = false;
    }
    else if (x === -1) {
        a.walls[1] = false;
        b.walls[3] = false;
    }

    let y = a.j - b.j;
    if (y === 1) {
        a.walls[0] = false;
        b.walls[2] = false;
    }
    else if (y === -1) {
        a.walls[2] = false;
        b.walls[0] = false;
    }
}


class Cell {
    constructor(i, j) {
        this.i = i;
        this.j = j;
        this.walls = [true, true, true, true]; // top, right, bottom, left
        this.visited = false;
    }
  
    show() {
        let x = this.i * cell_dimension;
        let y = this.j * cell_dimension;
  
        stroke('#000');
        if (this.walls[0]) {
            line(x, y, x + cell_dimension, y);
        }
        if (this.walls[1]) {
            line(x + cell_dimension, y, x + cell_dimension, y + cell_dimension);
        }
        if (this.walls[2]) {
            line(x + cell_dimension, y + cell_dimension, x, y + cell_dimension);
        }
        if (this.walls[3]) {
            line(x, y + cell_dimension, x, y);
        }
    
        if (this.visited) {
            noStroke();
            fill('#fff');
            rect(x, y, cell_dimension, cell_dimension);
        }
    }
  
    highlight() {
        let x = this.i * cell_dimension;
        let y = this.j * cell_dimension;
        noStroke();
        fill('#0f0');
        rect(x, y, cell_dimension, cell_dimension);
    }
  
    checkNeighbors() {
        let neighbors = [];
    
        let top = grid[index(this.i, this.j - 1)];
        let right = grid[index(this.i + 1, this.j)];
        let bottom = grid[index(this.i, this.j + 1)];
        let left = grid[index(this.i - 1, this.j)];
    
        if (top && !top.visited) {
            neighbors.push(top);
        }
        if (right && !right.visited) {
            neighbors.push(right);
        }
        if (bottom && !bottom.visited) {
            neighbors.push(bottom);
        }
        if (left && !left.visited) {
            neighbors.push(left);
        }
    
        if (neighbors.length > 0) {
            return random(neighbors);
        } else {
            // Backtrack
            return undefined;
        }
    }
}