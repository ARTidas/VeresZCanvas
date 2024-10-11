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
        return window.innerWidth * 0.93;
    };
    getHeight() {
        return window.innerHeight * 0.75;
    };

    saveImage() {
        saveCanvas('VeresZLidar_' + (new Date()).toISOString(), 'jpg');
    }

    //https://www.30secondsofcode.org/js/s/convert-degrees-radians/#:~:text=JavaScript's%20Math.,PI%20%2F%20180.0%20.
    degreesToRads = deg => (deg * Math.PI) / 180.0;
    radsToDegrees = rad => (rad * 180.0) / Math.PI;

    /* ********************************************************
     * *** Function which translates **************************
     * **** pointer YPRDXYZ to point XYZ ***********************
     * ********************************************************/
    getPointFromYPRD(
        inputOrientationPitch, inputOrientationRoll, inputOrientationYaw,
        inputPositionX, inputPositionY, inputPositionZ,
        inputDistance
    ) {
        // Convert degrees to radians
        const pitch = inputOrientationPitch * (Math.PI / 180);
        const roll = inputOrientationRoll * (Math.PI / 180);
        const yaw = inputOrientationYaw * (Math.PI / 180);
        
        // Set the initial YPR
        /*const initialPitch = 180.00 * (Math.PI / 180);
        const initialRoll = 90.00 * (Math.PI / 180);
        const initialYaw = 180.00 * (Math.PI / 180);*/
        /*const initialPitch = 0.00 * (Math.PI / 180);
        const initialRoll = 0.00 * (Math.PI / 180);
        const initialYaw = 0.00 * (Math.PI / 180);*/
        /*const initialPitch = 0.0 * (Math.PI / 180);
        const initialRoll = 0.0 * (Math.PI / 180);
        const initialYaw = 0.0 * (Math.PI / 180);*/
        const initialPitch = -90.00 * (Math.PI / 180);
        const initialRoll = -90.00 * (Math.PI / 180);
        const initialYaw = 0.00 * (Math.PI / 180);
        // Convert initial YPR to Quaternion
        let initialQuaternion = this.toQuaternion(initialYaw, initialPitch, initialRoll);
    
        // Convert current YPR to Quaternion
        const currentQuaternion = this.toQuaternion(-yaw, pitch, roll);
    
        // Combine the initial quaternion with the current quaternion
        const finalQuaternion = this.multiplyQuaternions(initialQuaternion, currentQuaternion);
    
        // Define the forward vector
        const forwardVector = { x: 0, y: 0, z: 1 }; // Assuming forward is in the +Z direction
    
        // Rotate the forward vector by the final quaternion
        const rotatedForwardVector = this.rotateVectorByQuaternion(forwardVector, finalQuaternion);
    
        // Compute the point coordinates based on position and distance
        const pointX = inputPositionX + rotatedForwardVector.x * inputDistance;
        const pointY = inputPositionY + rotatedForwardVector.y * inputDistance;
        const pointZ = inputPositionZ + rotatedForwardVector.z * inputDistance;
    
        return {
            'Position': {
                'X': pointX,
                'Y': pointY,
                'Z': pointZ
            },
            'PointerOrientation': {
                'Pitch': inputOrientationPitch,
                'Roll': inputOrientationRoll,
                'Yaw': inputOrientationYaw
            },
            'PointerPosition': {
                'X': inputPositionX,
                'Y': inputPositionY,
                'Z': inputPositionZ
            },
            'PointerDistance': inputDistance
        };
    }

    toQuaternion(yaw, pitch, roll) {
        const halfYaw = yaw * 0.5;
        const halfPitch = pitch * 0.5;
        const halfRoll = roll * 0.5;
    
        const cy = Math.cos(halfYaw);
        const sy = Math.sin(halfYaw);
        const cp = Math.cos(halfPitch);
        const sp = Math.sin(halfPitch);
        const cr = Math.cos(halfRoll);
        const sr = Math.sin(halfRoll);
    
        const q = {
            w: cr * cp * cy + sr * sp * sy,
            x: sr * cp * cy - cr * sp * sy,
            y: cr * sp * cy + sr * cp * sy,
            z: cr * cp * sy - sr * sp * cy
        };
    
        return q;
    }
    
    rotateVectorByQuaternion(v, q) {
        const x = v.x, y = v.y, z = v.z;
        const qx = q.x, qy = q.y, qz = q.z, qw = q.w;
    
        const ix = qw * x + qy * z - qz * y;
        const iy = qw * y + qz * x - qx * z;
        const iz = qw * z + qx * y - qy * x;
        const iw = -qx * x - qy * y - qz * z;
    
        return {
            x: ix * qw + iw * -qx + iy * -qz - iz * -qy,
            y: iy * qw + iw * -qy + iz * -qx - ix * -qz,
            z: iz * qw + iw * -qz + ix * -qy - iy * -qx
        };
    }

    multiplyQuaternions(q1, q2) {
        return {
            w: q1.w * q2.w - q1.x * q2.x - q1.y * q2.y - q1.z * q2.z,
            x: q1.w * q2.x + q1.x * q2.w + q1.y * q2.z - q1.z * q2.y,
            y: q1.w * q2.y - q1.x * q2.z + q1.y * q2.w + q1.z * q2.x,
            z: q1.w * q2.z + q1.x * q2.y - q1.y * q2.x + q1.z * q2.w
        };
    }

}