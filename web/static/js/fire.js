const canvas = document.getElementById("fireworks");
const ctx = canvas.getContext("2d");

canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

window.addEventListener("resize", () => {
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
});

class Firework {
    constructor(x, y, colors) {
        this.x = x;
        this.y = y;
        this.colors = colors;
        this.particles = [];

        for (let i = 0; i < 80; i++) {
            this.particles.push({
                x: this.x,
                y: this.y,
                radius: Math.random() * 3 + 1, 
                speedX: Math.random() * 5 - 2.5, 
                speedY: Math.random() * 5 - 2.5,
                color: this.colors[Math.floor(Math.random() * this.colors.length)],
                life: Math.random() * 50 + 50,
                opacity: 1
            });
        }
    }

    update() {
        this.particles.forEach((particle) => {
            particle.x += particle.speedX;
            particle.y += particle.speedY;
            particle.opacity -= 0.015;
            particle.life--;

            if (particle.life <= 0) {
                particle.opacity = 0;
            }
        });

        this.particles = this.particles.filter(p => p.opacity > 0);
    }

    draw() {
        this.particles.forEach((particle) => {
            ctx.beginPath();
            ctx.arc(particle.x, particle.y, particle.radius, 0, Math.PI * 2);
            ctx.fillStyle = `rgba(${particle.color}, ${particle.opacity})`;
            ctx.fill();
        });
    }
}

const fireworks = [];

function createFirework() {
    const x = Math.random() * canvas.width;
    const y = Math.random() * (canvas.height / 2); 
    const colors = [
        "255, 0, 0",   
        "255, 165, 0", 
        "255, 255, 0", 
        "0, 255, 0",  
        "0, 255, 255",
        "0, 0, 255",   
        "128, 0, 128"  
    ];

    fireworks.push(new Firework(x, y, colors));
}

function animateFireworks() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    fireworks.forEach((firework, index) => {
        firework.update();
        firework.draw();
        if (firework.particles.length === 0) {
            fireworks.splice(index, 1);
        }
    });

    requestAnimationFrame(animateFireworks);
}

setInterval(createFirework, 800); 
animateFireworks();
