class NetworkCanvas {
  constructor(canvas) {
    this.canvas = canvas;
    this.ctx = canvas.getContext("2d");
    // El canvas no entiende var(--lavanda), asi que los colores se leen del
    // :root al arrancar. Antes estaban escritos aca: azul, cian y verde de la
    // paleta vieja, pintandose detras de toda la portada mucho despues del
    // repintado, porque aquel barrido solo miro el CSS.
    this.colors = NetworkCanvas.paleta();
    this.width = 0;
    this.height = 0;
    this.particles = [];
    this.mouse = { x: null, y: null, radius: 220 };
    this.settings = {
      particleCount: 70,
      maxDistance: 1010,
      minimumConnections: 1,
      maximumConnections: 5,
      offscreenMargin: 180,
      particleRadius: 2.4,
      speed: 0.28,
    };

    this.resizeCanvas = this.resizeCanvas.bind(this);
    this.onMouseMove = this.onMouseMove.bind(this);
    this.onMouseLeave = this.onMouseLeave.bind(this);
    this.animate = this.animate.bind(this);

    window.addEventListener("resize", this.resizeCanvas);
    this.canvas.addEventListener("mousemove", this.onMouseMove);
    this.canvas.addEventListener("mouseleave", this.onMouseLeave);

    this.resizeCanvas();
    this.animate();
  }

  /** Los pasteles de apoyo, leidos del sistema. Vacio si el CSS no cargo. */
  static paleta() {
    const raiz = getComputedStyle(document.documentElement);
    return ["lavanda", "celeste", "salvia"]
      .map(n => raiz.getPropertyValue("--" + n).trim())
      .filter(Boolean);
  }

  getSimulationBounds() {
    const m = this.settings.offscreenMargin;
    return { left: -m, right: this.width + m, top: -m, bottom: this.height + m };
  }

  resizeCanvas() {
    const dpr = Math.min(window.devicePixelRatio || 1, 2);
    this.width = this.canvas.offsetWidth;
    this.height = this.canvas.offsetHeight;
    this.canvas.width = this.width * dpr;
    this.canvas.height = this.height * dpr;
    this.ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
    this.createParticles();
  }

  createParticles() {
    this.particles = [];
    const area = this.width * this.height;
    const total = Math.max(10, Math.min(this.settings.particleCount, Math.floor(area / 7600)));
    const bounds = this.getSimulationBounds();
    const sw = bounds.right - bounds.left;
    const sh = bounds.bottom - bounds.top;
    for (let i = 0; i < total; i++) {
      this.particles.push({
        x: bounds.left + Math.random() * sw,
        y: bounds.top + Math.random() * sh,
        vx: (Math.random() - 0.5) * this.settings.speed,
        vy: (Math.random() - 0.5) * this.settings.speed,
        radius: Math.random() * this.settings.particleRadius + 1.2,
        color: this.colors[Math.floor(Math.random() * this.colors.length)],
      });
    }
  }

  drawParticle(p) {
    this.ctx.beginPath();
    this.ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
    this.ctx.fillStyle = p.color;
    this.ctx.shadowColor = p.color;
    this.ctx.shadowBlur = 8;
    this.ctx.fill();
    this.ctx.shadowBlur = 0;
    this.ctx.beginPath();
    this.ctx.arc(p.x, p.y, p.radius + 3.5, 0, Math.PI * 2);
    this.ctx.fillStyle = p.color;
    this.ctx.globalAlpha = 0.1;
    this.ctx.fill();
    this.ctx.globalAlpha = 1;
  }

  drawConnection(p1, p2, distance, opacityMultiplier = 1) {
    const opacity = Math.max(0.08, 1 - distance / this.settings.maxDistance);
    const gradient = this.ctx.createLinearGradient(p1.x, p1.y, p2.x, p2.y);
    gradient.addColorStop(0, p1.color);
    gradient.addColorStop(1, p2.color);
    this.ctx.beginPath();
    this.ctx.moveTo(p1.x, p1.y);
    this.ctx.lineTo(p2.x, p2.y);
    this.ctx.strokeStyle = gradient;
    this.ctx.globalAlpha = Math.min(opacity * 0.42 * opacityMultiplier, 0.5);
    this.ctx.lineWidth = 1.15;
    this.ctx.stroke();
    this.ctx.globalAlpha = 1;
  }

  drawLines() {
    const drawn = new Set();
    const counts = new Array(this.particles.length).fill(0);
    const max = Math.max(this.settings.minimumConnections, this.settings.maximumConnections);
    for (let i = 0; i < this.particles.length; i++) {
      const p1 = this.particles[i];
      const nearest = this.particles
        .map((p, idx) => { if (idx === i) return null; const dx = p1.x - p.x, dy = p1.y - p.y; return { particle: p, distance: Math.sqrt(dx*dx+dy*dy), idx }; })
        .filter(Boolean)
        .sort((a, b) => a.distance - b.distance);
      let connections = counts[i];
      for (const n of nearest) {
        if (connections >= max) break;
        const key = i < n.idx ? `${i}-${n.idx}` : `${n.idx}-${i}`;
        if (drawn.has(key) || counts[n.idx] >= max) continue;
        const close = n.distance < this.settings.maxDistance;
        const needsMin = connections < this.settings.minimumConnections;
        if (close || needsMin) {
          this.drawConnection(p1, n.particle, n.distance, close ? 1 : 0.42);
          drawn.add(key);
          counts[i]++;
          counts[n.idx]++;
          connections++;
        }
        if (connections >= this.settings.minimumConnections && n.distance > this.settings.maxDistance) break;
      }
    }
  }

  updateParticles() {
    const bounds = this.getSimulationBounds();
    this.particles.forEach(p => {
      p.x += p.vx;
      p.y += p.vy;
      if (p.x <= bounds.left || p.x >= bounds.right) p.vx *= -1;
      if (p.y <= bounds.top || p.y >= bounds.bottom) p.vy *= -1;
      if (this.mouse.x !== null) {
        const dx = p.x - this.mouse.x, dy = p.y - this.mouse.y;
        const dist = Math.sqrt(dx*dx + dy*dy);
        if (dist < this.mouse.radius) {
          const force = (this.mouse.radius - dist) / this.mouse.radius;
          p.x += dx * force * 0.014;
          p.y += dy * force * 0.014;
        }
      }
    });
  }

  drawMouseConnections() {
    if (this.mouse.x === null) return;
    this.particles.forEach(p => {
      const dx = p.x - this.mouse.x, dy = p.y - this.mouse.y;
      const dist = Math.sqrt(dx*dx + dy*dy);
      if (dist < this.mouse.radius) {
        this.ctx.beginPath();
        this.ctx.moveTo(this.mouse.x, this.mouse.y);
        this.ctx.lineTo(p.x, p.y);
        this.ctx.strokeStyle = p.color;
        this.ctx.globalAlpha = (1 - dist / this.mouse.radius) * 0.36;
        this.ctx.lineWidth = 1;
        this.ctx.stroke();
        this.ctx.globalAlpha = 1;
      }
    });
  }

  animate() {
    this.ctx.clearRect(0, 0, this.width, this.height);
    this.updateParticles();
    this.drawLines();
    this.drawMouseConnections();
    this.particles.forEach(p => this.drawParticle(p));
    requestAnimationFrame(this.animate);
  }

  onMouseMove(e) {
    const rect = this.canvas.getBoundingClientRect();
    this.mouse.x = e.clientX - rect.left;
    this.mouse.y = e.clientY - rect.top;
  }

  onMouseLeave() {
    this.mouse.x = null;
    this.mouse.y = null;
  }
}

// Inyectar el canvas de fondo directamente en el body
// Si el :root no resolvio, el sistema de diseno no cargo: antes que inventar
// tres colores aca, la animacion no se dibuja.
if (NetworkCanvas.paleta().length) {
  const pageCanvas = document.createElement("canvas");
  pageCanvas.id = "networkPageCanvas";
  document.body.prepend(pageCanvas);
  new NetworkCanvas(pageCanvas);
}
