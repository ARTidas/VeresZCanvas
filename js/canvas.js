class Canvas extends Genesys {
    constructor() {
        super();
        
        this.object = null;

        this.x1 = 0;
        this.y1 = 0;
        this.x2 = this.getWidth();
        this.y2 = this.getHeight();

        this.diagonal_slope = (this.y2 - this.y1) / (this.x2 - this.x1);
    };

    getWidth() {
        return window.innerWidth * 0.98;
    };
    getHeight() {
        return window.innerHeight * 0.95;
    };
}