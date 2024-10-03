class Genesys {
    constructor() {
        this.x = 0;
        this.y = 0;
        this.width = 0;
        this.speed = 1;
        this.color = 'black';

        this.object = null;

        this.fitness_score = null;

        this.is_dragged = false;
        this.is_dead = false;
    };

    getX() {
        return this.x;
    };
    getY() {
        return this.y;
    };

    display() {
        fill(this.color);
        strokeWeight(1);
        stroke('black');
        this.object = circle(
            this.x,
            this.y,
            this.width
        );

        return this.object;
    };

    intersects(target_object) {
        return dist(
                this.x,
                this.y,
                target_object.x,
                target_object.y
            ) < target_object.width
        ;
    };

    moveTowardsObject(target_object, acceleration = 0) {
        this.speed += acceleration;
        
        //Calculate the gradient descent step
        let distance_x = target_object.x - this.x;
        let distance_y = target_object.y - this.y;

        let distance = dist(this.x, this.y, target_object.x, target_object.y);
        distance_x /= distance;
        distance_y /= distance;
        distance_x *= this.speed;
        distance_y *= this.speed;

        // Update the position
        this.x += distance_x;
        this.y += distance_y;
    };

    getRandomNumber(minimum, maximum) {
        return Math.floor(Math.random() * (maximum - minimum + 1) + minimum);
    };

    getRandomHexColor() {
        return (
            '#' + 
            this.getRandomHexNumber() + 
            this.getRandomHexNumber() + 
            this.getRandomHexNumber()
        );
    };
    getRandomHexNumber() {
        var possible_hex_numbers = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 'a', 'b', 'c', 'd', 'e', 'f'];

        return (
            possible_hex_numbers[
                Math.floor(Math.random() * possible_hex_numbers.length)
            ]
        );
    };

    getRandomDirection() {
        return Math.random() < 0.5 ? 1 : -1;
    }

    findKNearestNeighbors(targets, k = settings.knn_k) {
        return (
            targets.sort(
                (a, b) => (
                    dist(this.x, this.y, a.x, a.y) - dist(this.x, this.y, b.x, b.y)
                )
            )
                .slice(0, k)
        );
    };

    getLinearRegressionAttributes(targets) {
        let x_coordinates = [];
        let y_coordinates = [];

        for (let target of targets) {
            x_coordinates.push(target.x);
            y_coordinates.push(target.y);
        }
        
        const n = x_coordinates.length;
        
        let sum_x = 0;
        let sum_y = 0;
        let sum_xy = 0;
        let sum_x2 = 0;
        let sum_y2 = 0;

        for (let i = 0; i < n; i++) {
            sum_x += x_coordinates[i];
            sum_y += y_coordinates[i];
            sum_xy += x_coordinates[i] * y_coordinates[i];
            sum_x2 += x_coordinates[i] ** 2;
            sum_y2 += y_coordinates[i] ** 2;
        }

        let slope = (n * sum_xy - sum_x * sum_y) / (n * sum_x2 - sum_x ** 2);
        //Hackaround in case we would only have one x-y pair.
        //console.log(Number.isNaN(slope));
        if (Number.isNaN(slope)) {
            slope = 0.000000000001;
        }
        const intercept = (sum_y - slope * sum_x) / n;
        const r2 = (
            (
                (n * sum_xy - sum_x * sum_y) ** 
                2
            ) /
            (
                (n * sum_x2 - sum_x ** 2) * 
                (n * sum_y2 - sum_y ** 2)
            )
        );

        return {
            slope: slope,
            intercept: intercept,
            r2: r2,
        };
    }

    getTargetClusterDirection(targets) {
        let x_coordinates = [];
        let y_coordinates = [];

        for (let target of targets) {
            x_coordinates.push(target.x);
            y_coordinates.push(target.y);
        }
    
        return {
            x: (
                x_coordinates.reduce((accumulator, x) => accumulator + x, 0) / 
                x_coordinates.length
            ),
            y: (
                y_coordinates.reduce((accumulator, y) => accumulator + y, 0) /
                y_coordinates.length
            )
        };
    }
};