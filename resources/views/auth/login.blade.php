<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login Staff — Enamorapic</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
:root {
  --primary-dark: #1a2332;
  --primary-accent: #2d3e50;
  --gold: #6ea7d8;
  --gold-light: #b5d5ef;
  --gold-dim: rgba(110,167,216,0.28);
  --ink: #081421;
  --ink2: #0f2238;
  --ink3: #16324f;
  --mist: rgba(255,255,255,0.06);
  --mist2: rgba(255,255,255,0.03);
  --text: #ecf0f4;
  --text-dim: rgba(232,228,222,0.5);
  --error: #e07070;
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

html, body {
  height: 100%;
  overflow: hidden;
}

body {
  font-family: 'Inter', sans-serif;
  background: linear-gradient(180deg, #050c16 0%, #071324 52%, #081421 100%);
  color: var(--text);
  display: flex;
  align-items: center;
  justify-content: center;
}

/* ── CANVAS BACKGROUND ── */
#bg-canvas {
  position: fixed;
  inset: 0;
  z-index: 0;
  pointer-events: none;
}

/* ── SCENE (3D perspective wrapper) ── */
.scene {
  position: relative;
  z-index: 1;
  perspective: 1150px;
  width: min(480px, 94vw);
}

/* ── CARD ── */
.card {
  --mx: 50%;
  --my: 40%;
  background: linear-gradient(155deg, rgba(25,55,86,0.96) 0%, rgba(15,34,56,0.98) 52%, rgba(8,20,33,0.99) 100%);
  border: 1px solid rgba(110,167,216,0.22);
  border-radius: 28px;
  padding: 52px 44px 48px;
  box-shadow:
    0 0 0 1px rgba(0,0,0,0.6),
    0 40px 80px rgba(0,0,0,0.7),
    0 0 72px rgba(84,136,188,0.18),
    inset 0 1px 0 rgba(255,255,255,0.07);
  transform-style: preserve-3d;
  transform: rotateX(0deg) rotateY(0deg);
  transition: transform 0.08s ease-out;
  animation: cardRise 1.1s cubic-bezier(0.22,1,0.36,1) both, deepFloat 7.2s ease-in-out infinite;
  will-change: transform;
  position: relative;
  overflow: hidden;
}

/* shimmer layer */
.card::before {
  content: '';
  position: absolute;
  inset: 0;
  border-radius: 28px;
  background:
    radial-gradient(circle at var(--mx) var(--my), rgba(163,221,255,0.28) 0%, rgba(163,221,255,0.16) 18%, rgba(163,221,255,0.02) 42%, transparent 58%),
    linear-gradient(
      125deg,
      transparent 0%,
      rgba(122,191,236,0.04) 28%,
      rgba(150,219,255,0.12) 48%,
      rgba(113,178,224,0.06) 66%,
      transparent 100%
    );
  opacity: 0.38;
  animation: causticShift 9s ease-in-out infinite;
  transition: opacity 0.28s ease;
  pointer-events: none;
  z-index: 0;
}

.card:hover::before {
  opacity: 0.9;
}

.card:hover {
  box-shadow:
    0 0 0 1px rgba(0,0,0,0.62),
    0 45px 95px rgba(0,0,0,0.78),
    0 0 88px rgba(128,184,228,0.28),
    inset 0 1px 0 rgba(255,255,255,0.08);
}

/* noise grain overlay */
.card::after {
  content: '';
  position: absolute;
  inset: 0;
  border-radius: 28px;
  background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.035'/%3E%3C/svg%3E");
  pointer-events: none;
  opacity: 0.6;
  z-index: 0;
}

.card > * { position: relative; z-index: 1; }
.logo-wrap { transform: translateZ(26px); }
.divider-gold, .heading, .sub { transform: translateZ(20px); }
form { transform: translateZ(30px); }

/* ── HEADER ── */
.logo-wrap {
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 28px;
  animation: fadeUp 0.7s ease 0.2s both;
}

.logo-brand {
  width: 196px;
  min-height: 52px;
  display: flex;
  align-items: center;
  justify-content: center;
  animation: iconPulse 3s ease-in-out infinite;
  flex-shrink: 0;
}
.logo-brand img {
  width: 100%;
  height: auto;
  max-height: 56px;
  object-fit: contain;
  filter: brightness(1.06) contrast(1.03);
}

.divider-gold {
  height: 1px;
  background: linear-gradient(90deg, transparent, var(--gold-dim) 40%, var(--gold-dim) 60%, transparent);
  margin-bottom: 32px;
  animation: fadeIn 0.7s ease 0.35s both;
}

.heading {
  font-family: 'Playfair Display', serif;
  font-size: 2.5rem;
  font-weight: 500;
  line-height: 1.15;
  color: #fff;
  margin-bottom: 6px;
  animation: fadeUp 0.7s ease 0.3s both;
}
.heading em {
  font-style: italic;
  color: var(--gold-light);
}

.sub {
  font-size: 0.85rem;
  color: var(--text-dim);
  margin-bottom: 32px;
  font-weight: 400;
  letter-spacing: 0.3px;
  animation: fadeUp 0.7s ease 0.38s both;
}

/* ── ALERT ── */
.alert-error {
  background: rgba(224,112,112,0.12);
  border: 1px solid rgba(224,112,112,0.3);
  border-radius: 12px;
  padding: 12px 16px;
  font-size: 0.84rem;
  color: var(--error);
  margin-bottom: 20px;
  display: flex;
  align-items: center;
  gap: 8px;
  animation: shake 0.4s ease;
}
.alert-error svg { width: 16px; height: 16px; flex-shrink: 0; }

/* ── FORM ── */
.field {
  margin-bottom: 18px;
  animation: fadeUp 0.7s ease var(--d, 0.42s) both;
}
.field:nth-child(2) { --d: 0.48s; }

.field-label {
  display: block;
  font-size: 0.7rem;
  font-weight: 500;
  letter-spacing: 1.8px;
  text-transform: uppercase;
  color: var(--text-dim);
  margin-bottom: 8px;
}

.input-wrap {
  position: relative;
}

.input-icon {
  position: absolute;
  left: 16px;
  top: 50%;
  transform: translateY(-50%);
  color: var(--text-dim);
  pointer-events: none;
  transition: color 0.2s;
  display: flex;
}
.input-icon svg { width: 17px; height: 17px; }

.form-input {
  width: 100%;
  background: rgba(255,255,255,0.06);
  border: 1px solid rgba(255,255,255,0.14);
  border-radius: 14px;
  padding: 14px 16px 14px 46px;
  font-family: 'DM Sans', sans-serif;
  font-size: 0.95rem;
  font-weight: 400;
  color: #fff;
  outline: none;
  transition: all 0.25s ease;
  -webkit-appearance: none;
}
.form-input::placeholder { color: rgba(255,255,255,0.22); }
.form-input:focus {
  background: rgba(255,255,255,0.07);
  border-color: var(--gold);
  box-shadow: 0 0 0 3px rgba(110,167,216,0.25), 0 4px 16px rgba(0,0,0,0.3);
}
.input-wrap:focus-within .input-icon { color: var(--gold); }

/* ── PASSWORD TOGGLE ── */
.toggle-pw {
  position: absolute;
  right: 14px;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  cursor: pointer;
  color: var(--text-dim);
  padding: 4px;
  display: flex;
  transition: color 0.2s;
}
.toggle-pw:hover { color: var(--gold); }
.toggle-pw svg { width: 17px; height: 17px; }

/* ── SUBMIT BUTTON ── */
.btn-submit {
  width: 100%;
  margin-top: 8px;
  height: 52px;
  border: none;
  border-radius: 14px;
  background: linear-gradient(135deg, #7fa0c4 0%, #5f84ad 52%, #426a96 100%);
  color: #ffffff;
  font-family: 'DM Sans', sans-serif;
  font-size: 0.82rem;
  font-weight: 500;
  letter-spacing: 2.5px;
  text-transform: uppercase;
  cursor: pointer;
  position: relative;
  overflow: hidden;
  transition: all 0.25s ease;
  box-shadow: 0 6px 24px rgba(66,106,150,0.4);
  animation: fadeUp 0.7s ease 0.55s both;
}
.btn-submit::before {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, rgba(255,255,255,0.2) 0%, transparent 60%);
  opacity: 0;
  transition: opacity 0.25s;
}
.btn-submit:hover {
  transform: translateY(-2px);
  box-shadow: 0 12px 36px rgba(66,106,150,0.52);
}
.btn-submit:hover::before { opacity: 1; }
.btn-submit:active { transform: translateY(0); }

/* shine sweep */
.btn-submit::after {
  content: '';
  position: absolute;
  top: 0; left: -80%;
  width: 60%; height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.35), transparent);
  transform: skewX(-20deg);
  transition: left 0.55s ease;
}
.btn-submit:hover::after { left: 140%; }

/* ── BACK LINK ── */
.back-wrap {
  text-align: center;
  margin-top: 24px;
  animation: fadeIn 0.7s ease 0.65s both;
}
.back-link {
  color: var(--text-dim);
  font-size: 0.82rem;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 7px;
  transition: color 0.2s;
  letter-spacing: 0.3px;
}
.back-link svg { width: 14px; height: 14px; transition: transform 0.2s; }
.back-link:hover { color: var(--gold-light); }
.back-link:hover svg { transform: translateX(-3px); }

/* ── FLOATING ORBS ── */
.orb {
  position: fixed;
  border-radius: 50%;
  filter: blur(80px);
  pointer-events: none;
  z-index: 0;
  animation: orbDrift var(--dur, 14s) ease-in-out infinite alternate;
}
.orb-1 {
  width: 420px; height: 420px;
  background: radial-gradient(circle, rgba(70,129,184,0.2) 0%, transparent 70%);
  top: -120px; left: -80px;
  --dur: 11s;
}
.orb-2 {
  width: 360px; height: 360px;
  background: radial-gradient(circle, rgba(31,76,121,0.24) 0%, transparent 70%);
  bottom: -100px; right: -60px;
  --dur: 15s;
}
.orb-3 {
  width: 280px; height: 280px;
  background: radial-gradient(circle, rgba(141,197,237,0.16) 0%, transparent 70%);
  bottom: 20%; left: 10%;
  --dur: 18s;
}

/* ── KEYFRAMES ── */
@keyframes cardRise {
  from { opacity: 0; transform: translateY(32px) rotateX(6deg) scale(0.96); }
  to   { opacity: 1; transform: translateY(0) rotateX(0deg) scale(1); }
}
@keyframes fadeUp {
  from { opacity: 0; transform: translateY(12px); }
  to   { opacity: 1; transform: translateY(0); }
}
@keyframes fadeIn {
  from { opacity: 0; }
  to   { opacity: 1; }
}
@keyframes iconPulse {
  0%, 100% { transform: translateY(0); }
  50%      { transform: translateY(-1px); }
}
@keyframes causticShift {
  0%, 100% { transform: translateX(-7%) translateY(-4%) scale(1); opacity: 0.42; }
  50%      { transform: translateX(6%) translateY(5%) scale(1.06); opacity: 0.62; }
}
@keyframes deepFloat {
  0%, 100% { transform: translateY(0px) rotateX(0deg) rotateY(0deg); }
  50%      { transform: translateY(-4px) rotateX(0deg) rotateY(0deg); }
}
@keyframes orbDrift {
  from { transform: translate(0, 0) scale(1); }
  to   { transform: translate(30px, 20px) scale(1.08); }
}
@keyframes shake {
  0%, 100% { transform: translateX(0); }
  20%       { transform: translateX(-6px); }
  40%       { transform: translateX(6px); }
  60%       { transform: translateX(-4px); }
  80%       { transform: translateX(4px); }
}

/* ── LOADING STATE ── */
.btn-submit.loading .btn-text { opacity: 0; }
.btn-submit.loading::before {
  opacity: 1;
  background: none;
}
.spinner {
  display: none;
  position: absolute;
  inset: 0;
  align-items: center;
  justify-content: center;
}
.btn-submit.loading .spinner { display: flex; }
.spinner-ring {
  width: 22px; height: 22px;
  border: 2px solid rgba(0,0,0,0.2);
  border-top-color: #0a0a0f;
  border-radius: 50%;
  animation: spin 0.7s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }

@media (prefers-reduced-motion: reduce) {
  *, *::before, *::after { animation: none !important; transition: none !important; }
}

@media (max-width: 480px) {
  .card { padding: 40px 28px 36px; border-radius: 22px; }
  .heading { font-size: 2rem; }
}
</style>
</head>
<body>

<!-- Floating orbs -->
<div class="orb orb-1"></div>
<div class="orb orb-2"></div>
<div class="orb orb-3"></div>

<!-- Canvas particle background -->
<canvas id="bg-canvas"></canvas>

<div class="scene">
  <div class="card" id="card3d">

    <!-- Logo -->
    <div class="logo-wrap">
      <div class="logo-brand">
        <img src="{{ asset('assets/images/enamora.png') }}" alt="Logo Enamora" loading="eager" onerror="this.style.display='none'">
      </div>
    </div>

    <div class="divider-gold"></div>

    <h1 class="heading">Selamat<br><em>Datang</em></h1>
    <p class="sub">Masuk ke dashboard internal Enamorapic</p>

    {{-- Error alert --}}
    @if($errors->has('login'))
    <div class="alert-error">
      <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
      {{ $errors->first('login') }}
    </div>
    @endif

    <form method="POST" action="{{ route('login.post') }}" id="loginForm">
      @csrf

      <div class="field">
        <label class="field-label" for="username">Username</label>
        <div class="input-wrap">
          <span class="input-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
            </svg>
          </span>
          <input
            id="username"
            type="text"
            name="username"
            class="form-input"
            placeholder="Masukkan username"
            value="{{ old('username') }}"
            required
            autofocus
            autocomplete="username"
          >
        </div>
      </div>

      <div class="field">
        <label class="field-label" for="password">Password</label>
        <div class="input-wrap">
          <span class="input-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
          </span>
          <input
            id="password"
            type="password"
            name="password"
            class="form-input"
            placeholder="Masukkan password"
            required
            autocomplete="current-password"
            style="padding-right: 48px;"
          >
          <button type="button" class="toggle-pw" id="togglePw" aria-label="Tampilkan password">
            <svg id="eyeIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
              <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
            </svg>
          </button>
        </div>
      </div>

      <button type="submit" class="btn-submit" id="submitBtn">
        <span class="btn-text">Masuk</span>
        <div class="spinner"><div class="spinner-ring"></div></div>
      </button>
    </form>

  </div><!-- .card -->

  <div class="back-wrap">
    <a href="{{ route('home') }}" class="back-link">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M19 12H5M12 5l-7 7 7 7"/>
      </svg>
      Kembali ke Website
    </a>
  </div>
</div>

<script>
/* ── PARTICLE CANVAS ── */
(function() {
  const canvas = document.getElementById('bg-canvas');
  const ctx = canvas.getContext('2d');
  let W, H, particles = [];

  function resize() {
    W = canvas.width  = window.innerWidth;
    H = canvas.height = window.innerHeight;
  }
  resize();
  window.addEventListener('resize', resize);

  const GOLD = 'rgba(128,184,228,';
  const WHITE = 'rgba(219,236,252,';

  function Particle() {
    this.reset();
  }
  Particle.prototype.reset = function() {
    this.x = Math.random() * W;
    this.y = Math.random() * H;
    this.r = Math.random() * 1.9 + 0.45;
    this.speedY = -(Math.random() * 0.28 + 0.06);
    this.speedX = (Math.random() - 0.5) * 0.08;
    this.alpha  = Math.random() * 0.36 + 0.08;
    this.isGold = Math.random() < 0.42;
    this.twinkleSpeed = Math.random() * 0.02 + 0.005;
    this.twinklePhase = Math.random() * Math.PI * 2;
  };
  Particle.prototype.update = function(t) {
    this.y += this.speedY;
    this.x += this.speedX + Math.sin(this.twinklePhase * 0.65) * 0.085;
    this.twinklePhase += this.twinkleSpeed;
    this.currentAlpha = this.alpha * (0.6 + 0.4 * Math.sin(this.twinklePhase));
    if (this.y < -5) this.reset(), this.y = H + 5;
  };
  Particle.prototype.draw = function() {
    const col = this.isGold ? GOLD : WHITE;
    ctx.beginPath();
    ctx.arc(this.x, this.y, this.r, 0, Math.PI * 2);
    ctx.fillStyle = col + this.currentAlpha + ')';
    ctx.fill();
  };

  for (let i = 0; i < 88; i++) particles.push(new Particle());

  let t = 0;
  function loop() {
    ctx.clearRect(0, 0, W, H);
    t += 0.016;
    particles.forEach(p => { p.update(t); p.draw(); });
    requestAnimationFrame(loop);
  }
  loop();
})();

/* ── 3D CARD TILT ── */
(function() {
  const card = document.getElementById('card3d');
  const scene = card.closest('.scene');
  let animFrame;
  let targetRotX = 0;
  let targetRotY = 0;
  let currentRotX = 0;
  let currentRotY = 0;
  let floatPhase = 0;

  scene.addEventListener('mousemove', function(e) {
    const rect = scene.getBoundingClientRect();
    const cx = rect.left + rect.width  / 2;
    const cy = rect.top  + rect.height / 2;
    const dx = (e.clientX - cx) / (rect.width  / 2);
    const dy = (e.clientY - cy) / (rect.height / 2);
    targetRotX = -dy * 4.4;
    targetRotY = dx * 5.6;

    const mx = ((e.clientX - rect.left) / rect.width) * 100;
    const my = ((e.clientY - rect.top) / rect.height) * 100;
    card.style.setProperty('--mx', `${Math.max(0, Math.min(100, mx))}%`);
    card.style.setProperty('--my', `${Math.max(0, Math.min(100, my))}%`);
  });

  scene.addEventListener('mouseleave', function() {
    targetRotX = 0;
    targetRotY = 0;
    card.style.setProperty('--mx', '50%');
    card.style.setProperty('--my', '40%');
  });

  function animateTilt() {
    currentRotX += (targetRotX - currentRotX) * 0.08;
    currentRotY += (targetRotY - currentRotY) * 0.08;
    floatPhase += 0.02;
    const floatY = Math.sin(floatPhase) * 3.2;
    card.style.transform = `translateY(${floatY}px) rotateX(${currentRotX}deg) rotateY(${currentRotY}deg)`;
    animFrame = requestAnimationFrame(animateTilt);
  }
  animateTilt();

  if (window.matchMedia('(max-width: 768px)').matches) {
    scene.addEventListener('touchmove', function() {
      targetRotX = 0;
      targetRotY = 0;
    }, { passive: true });
  }
})();

/* ── PASSWORD TOGGLE ── */
(function() {
  const toggle = document.getElementById('togglePw');
  const input  = document.getElementById('password');
  const eyeIcon = document.getElementById('eyeIcon');
  const eyeOff = `<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>`;
  const eyeOn  = `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>`;
  let visible = false;
  toggle.addEventListener('click', function() {
    visible = !visible;
    input.type = visible ? 'text' : 'password';
    eyeIcon.innerHTML = visible ? eyeOff : eyeOn;
  });
})();

/* ── SUBMIT LOADING ── */
document.getElementById('loginForm').addEventListener('submit', function() {
  const btn = document.getElementById('submitBtn');
  btn.classList.add('loading');
  btn.disabled = true;
});
</script>
</body>
</html>
