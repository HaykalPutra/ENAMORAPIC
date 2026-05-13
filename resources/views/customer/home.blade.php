<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Enamorapic — Premium Photography</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400;1,600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
/* ===== ROOT & RESET ===== */
:root {
  --gold: #6ea7d8;
  --gold-light: #c4dbf2;
  --gold-dim: rgba(110,167,216,0.18);
  --ink: #f2f6fb;
  --ink2: #e8eef6;
  --ink3: #16324f;
  --cream: #fbfdff;
  --text-light: #152c45;
  --text-dim: rgba(28,49,73,0.62);
  --nav-h: 80px;
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; font-size: 16px; }
body {
  font-family: 'DM Sans', sans-serif;
  background:
    radial-gradient(1100px 600px at -5% -10%, rgba(110,167,216,0.12), transparent 60%),
    radial-gradient(900px 520px at 105% 6%, rgba(53,90,132,0.08), transparent 58%),
    var(--ink);
  color: var(--text-light);
  cursor: none;
  overflow-x: hidden;
}
h1,h2,h3,h4,h5 { font-family: 'Cormorant Garamond', serif; }
::-webkit-scrollbar { width: 5px; }
::-webkit-scrollbar-track { background: var(--ink); }
::-webkit-scrollbar-thumb { background: var(--gold-dim); border-radius: 10px; }

/* ===== CUSTOM CURSOR ===== */
#cursor { position: fixed; width: 10px; height: 10px; background: var(--gold); border-radius: 50%; pointer-events: none; z-index: 99999; transform: translate(-50%,-50%); transition: width .2s, height .2s, background .2s; mix-blend-mode: difference; }
#cursor-ring { position: fixed; width: 38px; height: 38px; border: 1px solid rgba(110,167,216,0.5); border-radius: 50%; pointer-events: none; z-index: 99998; transform: translate(-50%,-50%); transition: transform .12s ease, width .25s, height .25s, opacity .25s; }
body:hover #cursor { opacity: 1; }
.cursor-grow #cursor { width: 18px; height: 18px; }
.cursor-grow #cursor-ring { width: 56px; height: 56px; border-color: rgba(110,167,216,0.8); }

/* ===== NOISE OVERLAY ===== */
#noise {
  position: fixed; inset: 0; z-index: 9997; pointer-events: none; opacity: .025;
  background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 512 512' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
  background-size: 200px 200px;
}

/* ===== PROGRESS BAR ===== */
#progress-bar { position: fixed; top: 0; left: 0; height: 2px; background: linear-gradient(90deg, var(--gold), var(--gold-light)); z-index: 9999; transition: width .1s; width: 0%; }

/* ===== NAVBAR ===== */
nav#navbar {
  position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
  height: var(--nav-h);
  display: flex; align-items: center; justify-content: space-between;
  padding: 0 48px;
  background: linear-gradient(to bottom, rgba(7, 26, 46, 0.56), rgba(7, 26, 46, 0));
  transition: all .4s ease;
}
nav#navbar.scrolled {
  background: rgba(10, 34, 58, 0.86);
  backdrop-filter: blur(18px);
  -webkit-backdrop-filter: blur(18px);
  border-bottom: 1px solid rgba(110,167,216,0.24);
  box-shadow: 0 8px 40px rgba(3, 14, 28, 0.28);
}
nav#navbar.scrolled .nav-links {
  background: transparent;
  border-color: transparent;
}
nav#navbar.scrolled .nav-links a {
  color: rgba(214,230,246,0.86);
}
nav#navbar.scrolled .nav-links a:hover {
  color: #ffffff;
}
nav#navbar.scrolled .nav-cta {
  color: #d8e8f7 !important;
  border-color: rgba(110,167,216,.45);
}

.nav-brand {
  display: inline-flex; align-items: center; text-decoration: none;
  position: relative; z-index: 2;
  padding: 0;
}
.nav-brand-logo {
  width: 155px;
  height: auto;
  object-fit: contain;
  filter: brightness(1.12) contrast(1.06) drop-shadow(0 3px 12px rgba(0,0,0,0.3));
}
.nav-brand-icon {
  width: 36px; height: 36px; border-radius: 10px;
  background: linear-gradient(135deg, var(--gold) 0%, #2f5076 100%);
  display: flex; align-items: center; justify-content: center;
  box-shadow: 0 4px 14px rgba(110,167,216,0.35);
  flex-shrink: 0;
}
.nav-brand-icon svg { width: 18px; height: 18px; fill: #fff; }
.nav-brand-name {
  font-family: 'Cormorant Garamond', serif;
  font-size: 1.35rem; font-weight: 600;
  color: #fff; letter-spacing: .5px; line-height: 1;
}
.nav-brand-name small {
  display: block; font-family: 'DM Sans', sans-serif;
  font-size: .58rem; font-weight: 300; letter-spacing: 2.5px;
  color: var(--gold); text-transform: uppercase; margin-top: 2px;
}
.nav-center {
  position: absolute;
  left: 50%;
  transform: translateX(-50%);
  z-index: 2;
}
.nav-links {
  display: flex;
  align-items: center;
  gap: 34px;
  padding: 0;
  border-radius: 0;
  background: transparent !important;
  border: 0 !important;
  backdrop-filter: none !important;
  -webkit-backdrop-filter: none !important;
}
.nav-links a {
  font-size: .72rem; font-weight: 500; letter-spacing: 2px;
  text-transform: uppercase; color: rgba(214,230,246,.84);
  text-decoration: none; position: relative; padding-bottom: 4px;
  transition: color .25s;
}
.nav-links a::after {
  content: ''; position: absolute; bottom: 0; left: 50%; transform: translateX(-50%);
  width: 0; height: 1px; background: var(--gold); transition: width .3s;
}
.nav-links a:hover { color: #fff; }
.nav-links a:hover::after { width: 100%; }
.nav-cta {
  padding: 9px 24px; border-radius: 999px;
  border: 1px solid rgba(110,167,216,.46);
  color: #d8e8f7 !important;
  font-size: .7rem !important; font-weight: 500 !important;
  letter-spacing: 2px !important;
  backdrop-filter: blur(6px);
  transition: all .25s !important;
  text-decoration: none;
  position: relative;
  z-index: 2;
  background: rgba(10, 37, 62, 0.42);
}
.nav-cta:hover { background: rgba(110,167,216,.18) !important; border-color: var(--gold) !important; color: #fff !important; }
.nav-cta::after { display: none !important; }

/* Mobile nav toggle */
.nav-toggle { display: none; background: none; border: none; cursor: none; padding: 8px; }
.nav-toggle span { display: block; width: 24px; height: 1.5px; background: #fff; margin: 5px 0; transition: all .3s; }
@media (max-width: 900px) {
  nav#navbar { padding: 0 24px; }
  .nav-brand-logo { width: 128px; }
  .nav-center { position: static; transform: none; margin-left: auto; }
  .nav-cta { display: none; }
  .nav-toggle { display: block; margin-left: 10px; }
  .nav-links {
    position: fixed; inset: 0; top: var(--nav-h); background: rgba(8,20,33,.97);
    backdrop-filter: blur(20px); flex-direction: column; justify-content: center;
    gap: 32px; margin-left: 0; opacity: 0; pointer-events: none; transition: opacity .3s;
    border-radius: 0;
    border: 0;
    padding: 0;
  }
  .nav-links.open { opacity: 1; pointer-events: all; }
  .nav-links a { font-size: 1rem; letter-spacing: 3px; }
  .nav-links .nav-login-mobile { display: inline-flex; }
}

.nav-links .nav-login-mobile { display: none; }

/* ===== HERO ===== */
#hero {
  position: relative; min-height: 100vh; display: flex; align-items: center; justify-content: center;
  overflow: hidden; text-align: center; background: var(--ink);
}
#hero-canvas { position: absolute; inset: 0; z-index: 0; }
.hero-bg-img {
  position: absolute; inset: 0; z-index: 1;
  background: url('{{ asset('assets/images/In-to.jpg') }}') center/cover no-repeat;
  opacity: 0.92;
  transform: scale(1.04);
  transition: none;
  will-change: transform;
}
.hero-bg-img.loaded { transform: scale(1.04); }
.hero-bg-video {
  position: absolute;
  inset: 0 auto 0 0;
  width: 58%;
  height: 100%;
  object-fit: cover;
  object-position: left center;
  z-index: 1;
  pointer-events: none;
  filter: saturate(1.05) contrast(1.03) brightness(0.86);
  mask-image: linear-gradient(to right, rgba(0,0,0,1) 70%, rgba(0,0,0,0) 100%);
  -webkit-mask-image: linear-gradient(to right, rgba(0,0,0,1) 70%, rgba(0,0,0,0) 100%);
}
.hero-overlay {
  position: absolute; inset: 0; z-index: 2;
  background: linear-gradient(to bottom,
    rgba(12,12,20,0.3) 0%,
    rgba(12,12,20,0.15) 40%,
    rgba(12,12,20,0.6) 80%,
    rgba(12,12,20,1) 100%);
}
.hero-content {
  position: relative; z-index: 3;
  padding-top: var(--nav-h);
  max-width: 980px; margin: 0 auto; padding-left: 24px; padding-right: 24px;
}
.hero-tag {
  display: inline-flex; align-items: center; gap: 8px;
  padding: 7px 18px; border-radius: 999px;
  border: 1px solid rgba(110,167,216,0.35);
  background: rgba(110,167,216,0.08);
  backdrop-filter: blur(8px);
  font-size: .68rem; font-weight: 500; letter-spacing: 2.5px;
  text-transform: uppercase; color: var(--gold-light);
  margin-bottom: 28px;
  opacity: 0; animation: fadeUp .8s ease .3s forwards;
}
.hero-tag span.dot { width: 6px; height: 6px; border-radius: 50%; background: var(--gold); display: inline-block; animation: pulse 2s infinite; }
.hero-title {
  font-size: clamp(3rem, 8vw, 6.5rem); font-weight: 300; line-height: 1.05;
  color: #fff; margin-bottom: 8px;
  opacity: 0; animation: fadeUp .9s ease .5s forwards;
}
.hero-title em { font-style: italic; color: var(--gold-light); display: block; }
.hero-subtitle {
  font-family: 'DM Sans', sans-serif; font-size: clamp(.85rem, 2vw, 1.05rem);
  color: rgba(255,255,255,.6); font-weight: 300; letter-spacing: .5px;
  margin-top: 18px; margin-bottom: 32px;
  opacity: 0; animation: fadeUp .9s ease .7s forwards;
}
.hero-subtitle span { color: var(--gold); }
.hero-cta-wrap {
  display: flex; align-items: center; justify-content: center; gap: 16px; flex-wrap: wrap;
  opacity: 0; animation: fadeUp .9s ease .9s forwards;
}
.btn-gold {
  display: inline-flex; align-items: center; gap: 10px;
  padding: 15px 36px; border-radius: 999px;
  background: linear-gradient(135deg, var(--gold) 0%, #355a84 100%);
  color: #0c0c14; font-family: 'DM Sans', sans-serif;
  font-size: .78rem; font-weight: 500; letter-spacing: 2px; text-transform: uppercase;
  text-decoration: none; border: none; cursor: none;
  box-shadow: 0 8px 30px rgba(110,167,216,.4);
  transition: all .28s ease; position: relative; overflow: hidden;
}
.btn-gold::before {
  content: ''; position: absolute; inset: 0; border-radius: 999px;
  background: linear-gradient(135deg, rgba(255,255,255,.25) 0%, transparent 60%);
  opacity: 0; transition: opacity .25s;
}
.btn-gold:hover { transform: translateY(-2px); box-shadow: 0 14px 40px rgba(110,167,216,.5); color: #0c0c14; }
.btn-gold:hover::before { opacity: 1; }
.btn-outline-gold {
  display: inline-flex; align-items: center; gap: 10px;
  padding: 15px 36px; border-radius: 999px;
  border: 1px solid rgba(110,167,216,.45);
  color: var(--gold-light); font-family: 'DM Sans', sans-serif;
  font-size: .78rem; font-weight: 500; letter-spacing: 2px; text-transform: uppercase;
  text-decoration: none; cursor: none; background: transparent;
  transition: all .28s ease;
}
.btn-outline-gold:hover { background: rgba(110,167,216,.1); border-color: var(--gold); color: #fff; transform: translateY(-2px); }
.hero-scroll {
  position: absolute; bottom: 40px; left: 50%; transform: translateX(-50%);
  z-index: 3; display: flex; flex-direction: column; align-items: center; gap: 8px;
  opacity: 0; animation: fadeIn 1s ease 1.4s forwards;
}
.hero-scroll span { font-size: .65rem; letter-spacing: 2.5px; text-transform: uppercase; color: var(--text-dim); }
.scroll-line { width: 1px; height: 48px; background: linear-gradient(to bottom, var(--gold), transparent); animation: scrollPulse 2s ease-in-out infinite; }
@keyframes scrollPulse { 0%,100% { opacity: .3; transform: scaleY(.6) translateY(0); } 50% { opacity: 1; transform: scaleY(1) translateY(4px); } }

.hero-note {
  position: absolute;
  right: 8%;
  bottom: 17%;
  z-index: 3;
  max-width: 340px;
  text-align: left;
  font-size: clamp(1rem, 1.45vw, 1.15rem);
  line-height: 1.45;
  color: rgba(227,237,247,0.88);
  font-weight: 300;
  letter-spacing: 0.2px;
  opacity: 0;
  animation: fadeUp .9s ease 1.05s forwards;
}

/* ===== 3D FLOATING SHAPES ===== */
.shape-wrap { position: absolute; z-index: 2; pointer-events: none; }
.shape-ring {
  border-radius: 50%; border: 1px solid rgba(110,167,216,0.2);
  animation: shapeDrift var(--dur,20s) ease-in-out infinite alternate;
}
.shape-1 { top: 12%; left: 8%; width: 180px; height: 180px; --dur: 18s; }
.shape-2 { top: 20%; right: 6%; width: 120px; height: 120px; --dur: 14s; animation-delay: -4s; }
.shape-3 { bottom: 18%; left: 12%; width: 80px; height: 80px; --dur: 22s; animation-delay: -8s; }
.shape-dot { position: absolute; border-radius: 50%; background: var(--gold); animation: shapeDrift var(--dur,16s) ease-in-out infinite alternate; }
.shape-dot-1 { top: 35%; left: 4%; width: 6px; height: 6px; opacity: .5; --dur: 12s; }
.shape-dot-2 { top: 65%; right: 8%; width: 4px; height: 4px; opacity: .4; --dur: 17s; animation-delay: -5s; }
.shape-dot-3 { top: 50%; left: 50%; width: 5px; height: 5px; opacity: .3; --dur: 21s; animation-delay: -9s; }
@keyframes shapeDrift {
  0%   { transform: translate(0,0) rotate(0deg); }
  33%  { transform: translate(12px,-18px) rotate(60deg); }
  66%  { transform: translate(-8px, 14px) rotate(-40deg); }
  100% { transform: translate(6px, -6px) rotate(20deg); }
}

/* ===== MARQUEE ===== */
.marquee-section {
  overflow: hidden; border-top: 1px solid rgba(110,167,216,0.14); border-bottom: 1px solid rgba(110,167,216,0.14);
  padding: 18px 0; background: #f1f6fc;
}
.marquee-track {
  display: flex; gap: 48px; width: max-content;
  animation: marquee 22s linear infinite;
}
.marquee-item {
  display: flex; align-items: center; gap: 14px; white-space: nowrap;
  font-size: .72rem; letter-spacing: 2.5px; text-transform: uppercase;
  color: var(--text-dim); font-weight: 400;
}
.marquee-item::before { content: '✦'; color: var(--gold); font-size: .5rem; }
@keyframes marquee { to { transform: translateX(-50%); } }

/* ===== SECTION COMMONS ===== */
section { padding: 120px 0; }
.section-label {
  font-family: 'DM Sans', sans-serif; font-size: .65rem; letter-spacing: 4px;
  text-transform: uppercase; color: var(--gold); margin-bottom: 16px; display: block;
}
.section-title {
  font-size: clamp(2.2rem, 5vw, 3.8rem); font-weight: 300; line-height: 1.1;
  color: #162d46;
}
.section-title em { color: var(--gold-light); font-style: italic; }
.section-divider { width: 48px; height: 1px; background: var(--gold); margin: 24px 0; }
.section-subtitle { color: var(--text-dim); font-size: .95rem; font-weight: 300; line-height: 1.7; }

/* ===== ABOUT ===== */
#about { background: var(--ink); }
.about-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 80px; align-items: center; }
.about-visual {
  position: relative; aspect-ratio: 4/5;
  transform-style: preserve-3d; perspective: 800px;
}
.about-img-main {
  width: 100%; height: 100%; object-fit: cover; border-radius: 4px;
  position: relative; z-index: 2;
  box-shadow: 0 40px 80px rgba(0,0,0,0.6);
  transition: transform .6s cubic-bezier(0.22,1,0.36,1);
}
.about-visual:hover .about-img-main { transform: scale(1.02) rotate(-0.5deg); }
.about-img-accent {
  position: absolute; bottom: -28px; right: -28px; z-index: 3;
  width: 48%; aspect-ratio: 3/4; object-fit: cover; border-radius: 4px;
  border: 4px solid var(--ink);
  box-shadow: 0 20px 50px rgba(0,0,0,0.5);
  transition: transform .6s cubic-bezier(0.22,1,0.36,1) .08s;
}
.about-visual:hover .about-img-accent { transform: translate(4px, 4px) rotate(1deg); }
.about-frame {
  position: absolute; top: -16px; left: -16px; right: 32px; bottom: 32px; z-index: 1;
  border: 1px solid rgba(110,167,216,0.2); border-radius: 4px;
  pointer-events: none;
}
.about-badge {
  position: absolute; top: 24px; left: -20px; z-index: 4;
  background: linear-gradient(135deg, var(--gold) 0%, #355a84 100%);
  padding: 12px 18px; border-radius: 4px;
  box-shadow: 0 8px 24px rgba(110,167,216,0.4);
}
.about-badge .num { font-family: 'Cormorant Garamond', serif; font-size: 2rem; color: #fff; font-weight: 600; line-height: 1; }
.about-badge .lbl { font-size: .6rem; letter-spacing: 1.5px; text-transform: uppercase; color: rgba(255,255,255,.8); }
.stats-row { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-top: 40px; }
.stat-box { padding: 20px; border: 1px solid rgba(110,167,216,0.15); border-radius: 4px; background: rgba(110,167,216,0.05); }
.stat-box .num { font-family: 'Cormorant Garamond', serif; font-size: 2.8rem; color: var(--gold-light); font-weight: 400; line-height: 1; }
.stat-box .lbl { font-size: .68rem; letter-spacing: 2px; text-transform: uppercase; color: var(--text-dim); margin-top: 6px; }

@media (max-width: 768px) {
  .about-grid { grid-template-columns: 1fr; gap: 60px; }
  .about-visual { max-width: 480px; margin: 0 auto; }
}

/* ===== PORTFOLIO 3D GRID ===== */
#portfolio { background: var(--ink2); }
.portfolio-intro { text-align: center; margin-bottom: 64px; }
.portfolio-3d-grid {
  display: grid; grid-template-columns: repeat(12, 1fr);
  grid-template-rows: auto;
  gap: 16px;
}
.p-item {
  position: relative; overflow: hidden; border-radius: 4px; cursor: none;
  transform-style: preserve-3d; will-change: transform;
  transition: transform .5s cubic-bezier(0.22,1,0.36,1), box-shadow .5s;
}
.p-item:hover { transform: scale(1.02) translateY(-4px); box-shadow: 0 30px 60px rgba(0,0,0,.6); }
.p-item-1 { grid-column: 1/7; grid-row: 1; aspect-ratio: 4/3; }
.p-item-2 { grid-column: 7/10; grid-row: 1; aspect-ratio: 3/4; }
.p-item-3 { grid-column: 10/13; grid-row: 1; aspect-ratio: 3/4; }
.p-item-4 { grid-column: 1/5; grid-row: 2; aspect-ratio: 3/4; }
.p-item-5 { grid-column: 5/9; grid-row: 2; aspect-ratio: 3/4; }
.p-item-6 { grid-column: 9/13; grid-row: 2; aspect-ratio: 4/3; }
.p-img { width: 100%; height: 100%; object-fit: cover; transition: transform .7s ease, filter .7s ease; filter: brightness(.92) saturate(.9); }
.p-item:hover .p-img { transform: scale(1.06); filter: brightness(1) saturate(1.05); }
.p-overlay {
  position: absolute; inset: 0;
  background: linear-gradient(to top, rgba(12,12,20,.85) 0%, rgba(12,12,20,.1) 50%, transparent 100%);
  opacity: 0; transition: opacity .4s; display: flex; align-items: flex-end; padding: 20px;
}
.p-item:hover .p-overlay { opacity: 1; }
.p-overlay-text { font-size: .7rem; letter-spacing: 2px; text-transform: uppercase; color: var(--gold-light); }
.p-expand-btn {
  position: absolute; top: 14px; right: 14px;
  width: 36px; height: 36px; border-radius: 50%;
  background: rgba(110,167,216,.2); border: 1px solid rgba(110,167,216,.4);
  display: flex; align-items: center; justify-content: center;
  color: var(--gold-light); font-size: .9rem;
  opacity: 0; transform: scale(.8); transition: all .3s;
  cursor: none;
}
.p-item:hover .p-expand-btn { opacity: 1; transform: scale(1); }

@media (max-width: 900px) {
  .portfolio-3d-grid { grid-template-columns: 1fr 1fr; gap: 10px; }
  .p-item-1, .p-item-2, .p-item-3, .p-item-4, .p-item-5, .p-item-6 {
    grid-column: auto; grid-row: auto; aspect-ratio: 1;
  }
}
@media (max-width: 600px) {
  .portfolio-3d-grid { grid-template-columns: 1fr; }
}

/* ===== PACKAGES ===== */
#packages { background: var(--ink); }
.packages-intro { display: flex; align-items: flex-end; justify-content: space-between; margin-bottom: 64px; flex-wrap: wrap; gap: 24px; }
.filter-tabs {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
  padding: 8px;
  border-radius: 999px;
  background: rgba(13, 40, 66, 0.06);
  border: 1px solid rgba(110,167,216,0.16);
  backdrop-filter: blur(8px);
  -webkit-backdrop-filter: blur(8px);
}
.filter-tab {
  padding: 9px 20px;
  border-radius: 999px;
  font-size: .68rem;
  letter-spacing: 1.35px;
  text-transform: uppercase;
  border: 1px solid rgba(110,167,216,0.2);
  color: rgba(22,50,79,0.78);
  background: rgba(255,255,255,0.64);
  cursor: none;
  text-decoration: none;
  transition: all .28s ease;
}
.filter-tab:visited,
.filter-tab:hover,
.filter-tab:focus,
.filter-tab:active {
  text-decoration: none;
}
.filter-tab:hover {
  border-color: rgba(110,167,216,0.45);
  color: #16324f;
  background: #ffffff;
  transform: translateY(-1px);
}
.filter-tab.active {
  border-color: rgba(110,167,216,0.58);
  color: #16324f;
  background: linear-gradient(135deg, rgba(196,219,242,0.65) 0%, rgba(110,167,216,0.28) 100%);
  box-shadow: 0 8px 20px rgba(110,167,216,0.18);
}
.packages-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
.pkg-card {
  border: 1px solid rgba(110,167,216,.18); border-radius: 4px; overflow: hidden;
  background: #ffffff;
  transition: transform .4s cubic-bezier(0.22,1,0.36,1), border-color .4s, box-shadow .4s;
  transform-style: preserve-3d;
  position: relative;
  display: flex;
  flex-direction: column;
  height: 100%;
}
.pkg-card::after {
  content: ''; position: absolute; inset: 0; border-radius: 4px;
  box-shadow: inset 0 0 0 1px rgba(110,167,216,0);
  transition: box-shadow .4s;
  pointer-events: none;
}
.pkg-card:hover {
  transform: translateY(-12px) scale(1.015);
  border-color: rgba(110,167,216,.3);
  box-shadow: 0 40px 80px rgba(0,0,0,.5);
}
.pkg-card:hover::after { box-shadow: inset 0 0 0 1px rgba(110,167,216,.25); }
.pkg-img-wrap { position: relative; aspect-ratio: 4/3; overflow: hidden; }
.pkg-img { width: 100%; height: 100%; object-fit: cover; transition: transform .7s ease; }
.pkg-card:hover .pkg-img { transform: scale(1.06); }
.pkg-badge {
  position: absolute; top: 14px; right: 14px;
  padding: 5px 12px; border-radius: 999px; font-size: .62rem; letter-spacing: 1.5px;
  text-transform: uppercase; background: rgba(21,44,69,.78);
  border: 1px solid rgba(110,167,216,.4); color: var(--gold-light);
  backdrop-filter: blur(8px);
}
.pkg-body {
  padding: 28px 24px;
  display: flex;
  flex-direction: column;
  flex: 1;
}
.pkg-name {
  font-size: 1.4rem; font-weight: 400; color: #162d46; margin-bottom: 8px;
  line-height: 1.3;
  min-height: 3.6rem;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
.pkg-desc {
  font-size: .85rem; color: var(--text-dim); font-weight: 300; line-height: 1.6; margin-bottom: 20px;
  min-height: 4.2rem;
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
.pkg-price { font-family: 'Cormorant Garamond', serif; }
.pkg-price .curr { font-size: .85rem; color: var(--text-dim); }
.pkg-price .amount { font-size: 2.2rem; color: var(--gold-light); font-weight: 400; }
.pkg-features { margin: 18px 0; padding: 0; list-style: none; min-height: 102px; }
.pkg-features li { display: flex; align-items: center; gap: 10px; font-size: .82rem; color: var(--text-dim); padding: 6px 0; border-bottom: 1px solid rgba(255,255,255,.04); }
.pkg-features li:last-child { border-bottom: none; }
.pkg-features li i { color: var(--gold); font-size: .8rem; }
.pkg-cta { display: flex; gap: 10px; margin-top: auto; padding-top: 10px; }
.pkg-btn-outline {
  flex: 1; padding: 11px; border-radius: 4px; border: 1px solid rgba(255,255,255,.12);
  color: var(--text-dim); background: transparent; font-family: 'DM Sans', sans-serif;
  font-size: .72rem; letter-spacing: 1.5px; text-transform: uppercase; text-decoration: none;
  text-align: center; cursor: none; transition: all .25s;
}
.pkg-btn-outline:hover { border-color: rgba(110,167,216,.45); color: #18324f; }
.pkg-btn-solid {
  flex: 1.4; padding: 11px; border-radius: 4px;
  background: linear-gradient(135deg, var(--gold) 0%, #355a84 100%);
  color: #0c0c14; border: none; font-family: 'DM Sans', sans-serif;
  font-size: .72rem; letter-spacing: 1.5px; text-transform: uppercase;
  cursor: none; transition: all .25s;
  box-shadow: 0 6px 20px rgba(110,167,216,.3);
}
.pkg-btn-solid:hover { box-shadow: 0 10px 28px rgba(110,167,216,.5); transform: translateY(-1px); }

@media (max-width: 900px) { .packages-grid { grid-template-columns: 1fr; max-width: 480px; margin: 0 auto; } }

/* ===== TESTIMONIALS ===== */
#testimonials { background: var(--ink2); position: relative; overflow: hidden; }
.testi-bg-text {
  position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%);
  font-family: 'Cormorant Garamond', serif; font-size: clamp(80px, 15vw, 160px);
  color: rgba(110,167,216,0.06); white-space: nowrap; pointer-events: none;
  user-select: none; font-weight: 600; letter-spacing: 8px;
}
.testi-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-top: 64px; }
.testi-card {
  padding: 32px 28px; border: 1px solid rgba(110,167,216,.2); border-radius: 4px;
  background: rgba(110,167,216,0.05);
  transition: all .4s cubic-bezier(0.22,1,0.36,1);
  position: relative;
}
.testi-card::before {
  content: '"'; position: absolute; top: 20px; right: 24px;
  font-family: 'Cormorant Garamond', serif; font-size: 5rem; color: rgba(110,167,216,.12);
  line-height: 1; pointer-events: none;
}
.testi-card:hover { transform: translateY(-8px); border-color: rgba(110,167,216,.22); background: rgba(110,167,216,0.07); }
.testi-stars { color: var(--gold); font-size: .7rem; letter-spacing: 2px; margin-bottom: 16px; }
.testi-text { font-size: .9rem; color: var(--text-dim); font-style: italic; line-height: 1.75; margin-bottom: 20px; font-weight: 300; }
.testi-author { display: flex; align-items: center; gap: 14px; }
.testi-avatar {
  width: 42px; height: 42px; border-radius: 50%; object-fit: cover;
  border: 1px solid rgba(110,167,216,.3);
}
.testi-name { font-size: .9rem; font-weight: 500; color: #18314c; }
.testi-cat { font-size: .68rem; letter-spacing: 1.5px; text-transform: uppercase; color: var(--gold); }
@media (max-width: 768px) { .testi-grid { grid-template-columns: 1fr; } }

/* ===== FAQ ===== */
#faq { background: var(--ink); }
.faq-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 80px; align-items: start; }
.faq-list { margin-top: 48px; }
.faq-item { border-bottom: 1px solid rgba(110,167,216,.18); }
.faq-q {
  width: 100%; text-align: left; background: none; border: none; cursor: none;
  padding: 20px 0; display: flex; align-items: center; justify-content: space-between; gap: 16px;
  font-family: 'Cormorant Garamond', serif; font-size: 1.15rem; color: #16304a; font-weight: 400;
  transition: color .25s;
}
.faq-q:hover { color: var(--gold-light); }
.faq-q .icon { width: 24px; height: 24px; border: 1px solid rgba(110,167,216,.35); border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: .75rem; color: var(--text-dim); transition: all .3s; }
.faq-item.open .faq-q .icon { background: var(--gold); border-color: var(--gold); color: #0c0c14; transform: rotate(45deg); }
.faq-a { max-height: 0; overflow: hidden; transition: max-height .4s ease, padding .4s ease; }
.faq-a p { font-size: .9rem; color: var(--text-dim); line-height: 1.75; padding-bottom: 20px; font-weight: 300; }
.faq-item.open .faq-a { max-height: 200px; }
.faq-visual { position: sticky; top: 120px; }
.faq-img { width: 100%; aspect-ratio: 3/4; object-fit: cover; border-radius: 4px; }
.faq-img-note { margin-top: 20px; padding: 20px; border: 1px solid rgba(110,167,216,.15); border-radius: 4px; background: rgba(110,167,216,.05); }
.faq-img-note h6 { font-family: 'Cormorant Garamond', serif; font-size: 1.15rem; color: #16304a; margin-bottom: 6px; }
.faq-img-note p { font-size: .82rem; color: var(--text-dim); font-weight: 300; }
@media (max-width: 768px) { .faq-grid { grid-template-columns: 1fr; } .faq-visual { display: none; } }

/* ===== FOOTER ===== */
footer {
  background: #f3f7fc;
  border-top: 1px solid rgba(110,167,216,0.14);
  padding: 72px 0 40px;
}
.footer-grid { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 48px; margin-bottom: 56px; }
.footer-logo-card {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 10px 14px;
  border-radius: 14px;
  background: #091a2c;
  border: 1px solid rgba(110,167,216,0.25);
  box-shadow: 0 10px 24px rgba(7, 18, 31, 0.22);
  margin-bottom: 8px;
}
.footer-logo-card img {
  width: 170px;
  height: auto;
  object-fit: contain;
  filter: brightness(1.08) contrast(1.05);
}
.footer-brand p { font-size: .85rem; color: var(--text-dim); line-height: 1.75; margin-top: 16px; font-weight: 300; max-width: 260px; }
.footer-col h6 { font-family: 'DM Sans', sans-serif; font-size: .68rem; letter-spacing: 3px; text-transform: uppercase; color: var(--gold); margin-bottom: 20px; }
.footer-col ul { list-style: none; display: flex; flex-direction: column; gap: 10px; }
.footer-col ul li a { font-size: .85rem; color: var(--text-dim); text-decoration: none; transition: color .2s; }
.footer-col ul li a:hover { color: #16304a; }
.footer-col ul li { font-size: .85rem; color: var(--text-dim); display: flex; align-items: center; gap: 8px; }
.footer-socials { display: flex; gap: 10px; margin-top: 20px; }
.social-btn {
  width: 38px; height: 38px; border-radius: 50%; border: 1px solid rgba(255,255,255,.1);
  display: flex; align-items: center; justify-content: center; color: var(--text-dim);
  text-decoration: none; font-size: .95rem; transition: all .25s; cursor: none;
}
.social-btn:hover { border-color: var(--gold); color: var(--gold); background: rgba(110,167,216,.1); transform: translateY(-2px); }
.footer-bottom { padding-top: 32px; border-top: 1px solid rgba(110,167,216,.2); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }
.footer-bottom p { font-size: .78rem; color: rgba(22,48,74,.56); }
@media (max-width: 768px) { .footer-grid { grid-template-columns: 1fr 1fr; } }
@media (max-width: 480px) { .footer-grid { grid-template-columns: 1fr; } }

/* ===== LIGHTBOX ===== */
#lightbox {
  position: fixed; inset: 0; z-index: 99000; background: rgba(0,0,0,.95);
  backdrop-filter: blur(12px); display: flex; align-items: center; justify-content: center;
  opacity: 0; pointer-events: none; transition: opacity .3s;
}
#lightbox.open { opacity: 1; pointer-events: all; }
#lightbox img { max-width: 90vw; max-height: 85vh; object-fit: contain; border-radius: 4px; box-shadow: 0 40px 80px rgba(0,0,0,.8); }
.lb-close { position: absolute; top: 24px; right: 24px; width: 42px; height: 42px; border-radius: 50%; border: 1px solid rgba(255,255,255,.2); background: rgba(255,255,255,.1); color: #fff; font-size: 1.1rem; display: flex; align-items: center; justify-content: center; cursor: none; transition: all .25s; }
.lb-close:hover { background: rgba(255,255,255,.2); transform: rotate(90deg); }
.lb-nav { position: absolute; top: 50%; transform: translateY(-50%); width: 48px; height: 48px; border-radius: 50%; border: 1px solid rgba(255,255,255,.2); background: rgba(255,255,255,.1); color: #fff; display: flex; align-items: center; justify-content: center; cursor: none; transition: all .25s; font-size: 1rem; }
.lb-nav:hover { background: rgba(255,255,255,.25); }
.lb-prev { left: 24px; } .lb-next { right: 24px; }
.lb-counter { position: absolute; bottom: 24px; left: 50%; transform: translateX(-50%); font-size: .72rem; letter-spacing: 2px; color: var(--text-dim); }

/* ===== MODAL BOOKING ===== */
.modal-backdrop { background: rgba(0,0,0,.7); backdrop-filter: blur(8px); }
.modal-content {
  background: #f7f9fd !important; border: 1px solid rgba(17,40,73,.12) !important;
  border-radius: 4px !important; color: #1a2332;
}
.modal-header { border-bottom: 1px solid rgba(255,255,255,.07) !important; padding: 28px 28px 20px !important; }
.modal-body { padding: 24px 28px !important; }
.modal-footer { border-top: 1px solid rgba(17,40,73,.08) !important; padding: 16px 28px 24px !important; background: #f7f9fd !important; }
.modal-title { font-family: 'Cormorant Garamond', serif !important; font-size: 1.5rem !important; font-weight: 400 !important; color: #1a2332 !important; }
.modal-content .form-label { font-size: .68rem; letter-spacing: 2px; text-transform: uppercase; color: var(--text-dim); }
.modal-content .form-control, .modal-content .form-select {
  background: #ffffff !important; border: 1px solid rgba(17,40,73,.15) !important;
  color: #1a2332 !important; border-radius: 6px !important;
}
.modal-content .form-control:focus, .modal-content .form-select:focus {
  border-color: #6ea7d8 !important; box-shadow: 0 0 0 3px rgba(110,167,216,.22) !important;
  background: #ffffff !important;
}
.modal-content .form-control::placeholder { color: #8a99b2 !important; }
.modal-content .form-select option { background: #ffffff; color: #1a2332; }
.btn-close-white { filter: none !important; }
.modal-content #val_nama,
.modal-content #val_harga,
.modal-content #terminText {
  color: #2a3f5e !important;
}
.modal-content #terminInfo strong {
  color: #2a3f5e !important;
}

/* ===== TOAST ===== */
.toast-wrap { position: fixed; top: 100px; right: 24px; z-index: 9999; min-width: 320px; animation: slideInRight .5s ease; }
@keyframes slideInRight { from { transform: translateX(400px); opacity: 0; } to { transform: translateX(0); opacity: 1; } }

/* ===== FLOATING CHAT ===== */
.chat-float {
  position: fixed; right: 22px; bottom: 22px; z-index: 9996;
  display: flex; flex-direction: column; gap: 12px; align-items: flex-end;
}
.chat-float-btn {
  width: 52px; height: 52px; border-radius: 50%; border: 1px solid rgba(110,167,216,.5);
  background: rgba(10, 37, 62, 0.9); color: #d8e8f7; display: flex; align-items: center; justify-content: center;
  box-shadow: 0 16px 36px rgba(0,0,0,.35); cursor: none; transition: transform .2s, box-shadow .2s;
}
.chat-float-btn:hover { transform: translateY(-2px); box-shadow: 0 20px 46px rgba(0,0,0,.45); }
.chat-float-btn.ai { background: linear-gradient(135deg, rgba(110,167,216,.95), #2f5076); color: #0c0c14; border-color: rgba(110,167,216,.8); }
.chat-float-label {
  background: rgba(8,20,33,.92); color: #d8e8f7; font-size: .68rem; letter-spacing: 1.2px; text-transform: uppercase;
  padding: 6px 10px; border-radius: 999px; border: 1px solid rgba(110,167,216,.3); margin-right: 8px;
}
.chat-float-row { display: flex; align-items: center; gap: 8px; }

.chat-nudge {
  position: absolute; right: 0; bottom: 132px;
  background: rgba(8,20,33,.95); color: #d8e8f7; font-size: .78rem; line-height: 1.4;
  padding: 10px 14px; border-radius: 12px; border: 1px solid rgba(110,167,216,.35);
  box-shadow: 0 16px 36px rgba(0,0,0,.35); max-width: 220px;
  animation: nudgePop .6s ease, nudgeFloat 3.2s ease-in-out infinite;
}
.chat-nudge.hidden { opacity: 0; pointer-events: none; transition: opacity .4s; }
.chat-nudge::after {
  content: ''; position: absolute; right: 18px; bottom: -8px;
  width: 14px; height: 14px; background: rgba(8,20,33,.95);
  border-right: 1px solid rgba(110,167,216,.35);
  border-bottom: 1px solid rgba(110,167,216,.35);
  transform: rotate(45deg);
}
@keyframes nudgePop { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
@keyframes nudgeFloat { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-4px); } }

.chat-panel {
  position: fixed; right: 22px; bottom: 94px; width: 480px; max-width: 94vw; max-height: 62vh; z-index: 9996;
  background: rgba(10, 24, 40, .96); border: 1px solid rgba(110,167,216,.35);
  border-radius: 12px; box-shadow: 0 24px 60px rgba(0,0,0,.5); overflow: hidden;
  opacity: 0; pointer-events: none; transform: translateY(10px); transition: all .25s ease;
}
.chat-panel.open { opacity: 1; pointer-events: all; transform: translateY(0); }
.chat-panel-header {
  padding: 14px 16px; border-bottom: 1px solid rgba(110,167,216,.2); display: flex; align-items: center; justify-content: space-between;
  color: #d8e8f7; font-size: .8rem; letter-spacing: 1.2px; text-transform: uppercase;
}
.chat-panel-body { padding: 14px; overflow: auto; max-height: 44vh; }
.chat-msg { margin-bottom: 12px; display: flex; gap: 8px; }
.chat-msg .bubble {
  padding: 10px 12px; border-radius: 10px; font-size: .85rem; line-height: 1.5; max-width: 84%;
}
.chat-msg.user { justify-content: flex-end; }
.chat-msg.user .bubble { background: rgba(110,167,216,.25); color: #e9f1fb; border: 1px solid rgba(110,167,216,.35); }
.chat-msg.ai .bubble { background: rgba(255,255,255,.08); color: #dbe6f4; border: 1px solid rgba(255,255,255,.1); }
.chat-msg.ai .bubble.package { padding: 0; border: 1px solid rgba(110,167,216,.25); overflow: hidden; }
.chat-pkg-card { display: block; color: inherit; text-decoration: none; }
.chat-pkg-img { width: 100%; height: 150px; object-fit: cover; display: block; }
.chat-pkg-body { padding: 12px 14px; }
.chat-pkg-title { font-size: .95rem; font-weight: 500; color: #eaf2fb; margin-bottom: 6px; }
.chat-pkg-desc { font-size: .78rem; color: rgba(214,230,246,.68); line-height: 1.5; margin-bottom: 8px; }
.chat-pkg-meta { font-size: .7rem; letter-spacing: 1.5px; text-transform: uppercase; color: rgba(214,230,246,.65); margin-bottom: 8px; }
.chat-pkg-price { font-family: 'Cormorant Garamond', serif; font-size: 1.25rem; color: var(--gold-light); }
.chat-panel-footer {
  border-top: 1px solid rgba(110,167,216,.2); padding: 12px; display: flex; gap: 8px; align-items: center;
}
.chat-input {
  flex: 1; background: rgba(255,255,255,.08); border: 1px solid rgba(110,167,216,.35); color: #e7f0fb;
  border-radius: 999px; padding: 10px 14px; font-size: .85rem; outline: none;
}
.chat-send {
  width: 40px; height: 40px; border-radius: 50%; border: 1px solid rgba(110,167,216,.5);
  background: linear-gradient(135deg, var(--gold) 0%, #355a84 100%); color: #0c0c14; cursor: none;
  display: flex; align-items: center; justify-content: center;
}
.chat-hint { font-size: .72rem; color: rgba(214,230,246,.6); margin-top: 8px; }
@media (max-width: 600px) {
  .chat-panel { right: 14px; left: 14px; width: auto; }
  .chat-float { right: 14px; bottom: 16px; }
}

/* ===== SCROLL REVEAL ===== */
.reveal { opacity: 0; transform: translateY(36px); transition: opacity .8s ease, transform .8s cubic-bezier(0.22,1,0.36,1); }
.reveal.visible { opacity: 1; transform: translateY(0); }
.reveal-left { opacity: 0; transform: translateX(-40px); transition: opacity .8s ease, transform .8s cubic-bezier(0.22,1,0.36,1); }
.reveal-left.visible { opacity: 1; transform: translateX(0); }
.reveal-right { opacity: 0; transform: translateX(40px); transition: opacity .8s ease, transform .8s cubic-bezier(0.22,1,0.36,1); }
.reveal-right.visible { opacity: 1; transform: translateX(0); }
.delay-1 { transition-delay: .1s !important; }
.delay-2 { transition-delay: .2s !important; }
.delay-3 { transition-delay: .3s !important; }
.delay-4 { transition-delay: .4s !important; }
.delay-5 { transition-delay: .5s !important; }

/* ===== KEYFRAMES ===== */
@keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
@keyframes fadeIn  { from { opacity: 0; } to { opacity: 1; } }
@keyframes pulse   { 0%,100% { transform: scale(1); opacity: .7; } 50% { transform: scale(1.5); opacity: 1; } }
@keyframes spin    { to { transform: rotate(360deg); } }

/* Loading btn */
.btn-loading { opacity: .8; pointer-events: none; }
.btn-loading::before { content: ''; display: inline-block; width: 13px; height: 13px; margin-right: 10px; border: 2px solid rgba(0,0,0,.25); border-top-color: #0c0c14; border-radius: 50%; animation: spin .7s linear infinite; vertical-align: middle; }

/* Ripple */
.ripple-effect { position: absolute; border-radius: 50%; background: rgba(255,255,255,.3); transform: scale(0); animation: rippleAnim .6s linear; pointer-events: none; }
@keyframes rippleAnim { to { transform: scale(4); opacity: 0; } }

/* Container */
.container { max-width: 1200px; margin: 0 auto; padding: 0 48px; }
@media (max-width: 600px) { .container { padding: 0 20px; } section { padding: 80px 0; } }

@media (max-width: 992px) {
  .hero-bg-video {
    width: 100%;
    mask-image: none;
    -webkit-mask-image: none;
    opacity: 0.85;
  }
  .hero-note {
    position: static;
    max-width: 520px;
    text-align: center;
    margin: 26px auto 0;
    padding: 0 20px;
  }
}

/* Prefers reduced motion */
@media (prefers-reduced-motion: reduce) { *, *::before, *::after { animation-duration: .01ms !important; transition-duration: .01ms !important; } }
</style>
</head>
<body>

<!-- Overlays -->
<div id="noise"></div>
<div id="progress-bar"></div>
<div id="cursor"></div>
<div id="cursor-ring"></div>

<!-- TOAST -->
@if(session('success'))
<div class="toast-wrap">
  <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 shadow-lg">
    <i class="bi bi-check-circle-fill me-2"></i><strong>Booking Berhasil!</strong>
    <small class="d-block ms-4 mt-1">Tim kami akan segera menghubungi Anda.</small>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
</div>
@endif
@if(session('error'))
<div class="toast-wrap">
  <div class="alert alert-danger alert-dismissible fade show border-0 rounded-3 shadow-lg">
    <i class="bi bi-x-circle-fill me-2"></i><strong>Gagal!</strong>
    <small class="d-block ms-4 mt-1">{{ session('error') }}</small>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
</div>
@endif
@if($errors->any())
<div class="toast-wrap">
  <div class="alert alert-danger alert-dismissible fade show border-0 rounded-3 shadow-lg">
    <i class="bi bi-exclamation-triangle-fill me-2"></i><strong>Data tidak valid!</strong>
    <ul class="mb-0 ms-2 mt-1 small">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
</div>
@endif

<!-- ===== NAVBAR ===== -->
<nav id="navbar">
  <a href="{{ route('home') }}" class="nav-brand">
    <img src="{{ asset('assets/images/enamora.png') }}" alt="Logo Enamora" class="nav-brand-logo" loading="eager" onerror="this.src='{{ asset('assets/images/enamora.svg') }}'">
  </a>
  <div class="nav-center">
    <div class="nav-links" id="navLinks">
      <a href="#about">Tentang</a>
      <a href="#portfolio">Portfolio</a>
      <a href="#packages">Paket</a>
      <a href="#faq">FAQ</a>
      <a href="{{ route('login') }}" class="nav-login-mobile">Login Staff</a>
    </div>
  </div>
  <a href="{{ route('login') }}" class="nav-cta">Login Staff</a>
  <button class="nav-toggle" id="navToggle" aria-label="menu">
    <span></span><span></span><span></span>
  </button>
</nav>

<!-- ===== HERO ===== -->
<section id="hero">
  <canvas id="hero-canvas"></canvas>
  <div class="hero-bg-img" id="heroBg"></div>
  <video class="hero-bg-video" autoplay muted loop playsinline preload="auto" aria-hidden="true">
    <source src="{{ asset('assets/videos/6%20Detik.mp4') }}" type="video/mp4">
  </video>
  <div class="hero-overlay"></div>

  <!-- 3D floating shapes -->
  <div class="shape-wrap shape-1"><div class="shape-ring" style="width:100%;height:100%"></div></div>
  <div class="shape-wrap shape-2"><div class="shape-ring" style="width:100%;height:100%"></div></div>
  <div class="shape-wrap shape-3"><div class="shape-ring" style="width:100%;height:100%"></div></div>
  <div class="shape-dot shape-dot-1"></div>
  <div class="shape-dot shape-dot-2"></div>
  <div class="shape-dot shape-dot-3"></div>

  <div class="hero-content">
    <div class="hero-tag">
      <span class="dot"></span>
      Premium Wedding Photography
    </div>
    <h1 class="hero-title">
      Abadikan Momen
      <em>Terindah</em>
    </h1>
    <p class="hero-subtitle">
      <span>Wedding</span> &nbsp;·&nbsp; <span>Prewedding</span> &nbsp;·&nbsp; <span>Engagement</span><br>
      dengan sentuhan elegan &amp; profesional
    </p>
    <div class="hero-cta-wrap">
      <a href="#packages" class="btn-gold">
        <i class="bi bi-camera"></i> Lihat Paket
      </a>
      <a href="#portfolio" class="btn-outline-gold">
        <i class="bi bi-images"></i> Portfolio
      </a>
    </div>
  </div>
  <div class="hero-scroll">
    <span>Scroll</span>
    <div class="scroll-line"></div>
  </div>
  <p class="hero-note">
    Kami bantu mengabadikan wedding, prewedding, dan engagement Anda dengan visual sinematik yang elegan, hangat, dan timeless.
  </p>
</section>

<!-- ===== MARQUEE ===== -->
<div class="marquee-section">
  <div class="marquee-track" id="marqueeTrack">
    <div class="marquee-item">Wedding Photography</div>
    <div class="marquee-item">Prewedding</div>
    <div class="marquee-item">Engagement Session</div>
    <div class="marquee-item">Bandung & Sekitarnya</div>
    <div class="marquee-item">500+ Project Selesai</div>
    <div class="marquee-item">5 Tahun Pengalaman</div>
    <div class="marquee-item">Wedding Photography</div>
    <div class="marquee-item">Prewedding</div>
    <div class="marquee-item">Engagement Session</div>
    <div class="marquee-item">Bandung & Sekitarnya</div>
    <div class="marquee-item">500+ Project Selesai</div>
    <div class="marquee-item">5 Tahun Pengalaman</div>
  </div>
</div>

<!-- ===== ABOUT ===== -->
<section id="about">
  <div class="container">
    <div class="about-grid">
      <div class="reveal-left">
        <div class="about-visual">
          <div class="about-frame"></div>
          <img src="https://images.unsplash.com/photo-1583939003579-730e3918a45a?w=800&q=80" class="about-img-main" alt="Enamorapic Wedding">
          <img src="https://images.unsplash.com/photo-1522673607200-164d1b6ce486?w=600&q=80" class="about-img-accent" alt="Enamorapic Wedding">
          <div class="about-badge">
            <div class="num">5</div>
            <div class="lbl">Tahun</div>
          </div>
        </div>
      </div>
      <div class="reveal-right" style="transition-delay:.15s">
        <span class="section-label">Tentang Kami</span>
        <h2 class="section-title">Cerita Di Balik<br><em>Setiap Momen</em></h2>
        <div class="section-divider"></div>
        <p class="section-subtitle">
          <strong style="color:#16304a">Enamorapic</strong> hadir untuk mengubah momen berharga Anda menjadi kenangan abadi. Kami percaya fotografi bukan sekadar menekan tombol rana — melainkan tentang menangkap emosi, tawa, dan air mata bahagia.
        </p>
        <p class="section-subtitle" style="margin-top:14px">
          Setiap frame kami kerjakan dengan detail, warna yang natural namun elegan, serta komposisi yang bercerita untuk generasi mendatang.
        </p>
        <div class="stats-row reveal" style="transition-delay:.3s">
          <div class="stat-box">
            <div class="num" data-count="500">0</div>
            <div class="lbl">Project Selesai</div>
          </div>
          <div class="stat-box">
            <div class="num" data-count="200">0</div>
            <div class="lbl">Pasangan Bahagia</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== PORTFOLIO ===== -->
<section id="portfolio">
  <div class="container">
    <div class="portfolio-intro reveal">
      <span class="section-label">Portfolio</span>
      <h2 class="section-title">Karya <em>Terbaik</em> Kami</h2>
    </div>
    @php
    $imgs = [
      ['url'=>'https://images.unsplash.com/photo-1519741497674-611481863552?w=1200&q=80','cat'=>'Wedding'],
      ['url'=>'https://images.unsplash.com/photo-1465495976277-4387d4b0b4c6?w=800&q=80','cat'=>'Prewedding'],
      ['url'=>'https://images.unsplash.com/photo-1606216794074-735e91aa2c92?w=800&q=80','cat'=>'Engagement'],
      ['url'=>'https://images.unsplash.com/photo-1511285560929-80b456fea0bc?w=800&q=80','cat'=>'Wedding'],
      ['url'=>'https://images.unsplash.com/photo-1583939003579-730e3918a45a?w=800&q=80','cat'=>'Prewedding'],
      ['url'=>'https://images.unsplash.com/photo-1522673607200-164d1b6ce486?w=800&q=80','cat'=>'Engagement'],
    ];
    @endphp
    <div class="portfolio-3d-grid">
      @foreach($imgs as $i => $img)
      <div class="p-item p-item-{{ $i+1 }} reveal" style="transition-delay:{{ $i * 0.08 }}s"
           data-lb="{{ $img['url'] }}" data-lb-idx="{{ $i }}">
        <img src="{{ $img['url'] }}" class="p-img" alt="Portfolio {{ $img['cat'] }}" loading="lazy">
        <div class="p-overlay">
          <span class="p-overlay-text">{{ $img['cat'] }}</span>
          <button class="p-expand-btn lb-trigger" data-idx="{{ $i }}">
            <i class="bi bi-arrows-fullscreen"></i>
          </button>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

<!-- ===== PACKAGES ===== -->
<section id="packages">
  <div class="container">
    <div class="packages-intro">
      <div class="reveal-left">
        <span class="section-label">Pricelist</span>
        <h2 class="section-title">{{ $judul ?? 'Paket Eksklusif' }}</h2>
        <p class="section-subtitle" style="margin-top:12px; max-width:400px">Pilih paket yang sesuai dengan kebutuhan momen spesial Anda</p>
      </div>
      <div class="filter-tabs reveal-right">
        <a href="{{ route('home') }}" class="filter-tab {{ !request('kategori') ? 'active' : '' }}">Semua</a>
        <a href="{{ route('home', ['kategori'=>'Wedding']) }}" class="filter-tab {{ request('kategori')=='Wedding' ? 'active' : '' }}">Wedding</a>
        <a href="{{ route('home', ['kategori'=>'Pre-Wedding']) }}" class="filter-tab {{ request('kategori')=='Pre-Wedding' ? 'active' : '' }}">Prewedding</a>
        <a href="{{ route('home', ['kategori'=>'Engagement']) }}" class="filter-tab {{ request('kategori')=='Engagement' ? 'active' : '' }}">Engagement</a>
      </div>
    </div>
    <div class="packages-grid">
      @forelse($pakets as $idx => $paket)
      @php
        $fallback = 'https://images.unsplash.com/photo-1537633552985-df8429e8048b?w=800&q=80';
        $imgPath = public_path('assets/images/' . ($paket->gambar ?? ''));
        $imgUrl  = ($paket->gambar && file_exists($imgPath)) ? asset('assets/images/' . $paket->gambar) : $fallback;
        $featurePoints = collect($paket->detail_points ?? [])->take(3)->values();
      @endphp
      <div class="pkg-card reveal" style="transition-delay:{{ $idx * 0.12 }}s">
        <div class="pkg-img-wrap">
          <img src="{{ $imgUrl }}" class="pkg-img" alt="{{ $paket->nama_paket }}"
               onerror="this.src='{{ $fallback }}'">
          <span class="pkg-badge">{{ $paket->kategori }}</span>
        </div>
        <div class="pkg-body">
          <h4 class="pkg-name">{{ $paket->nama_paket }}</h4>
          <p class="pkg-desc">{{ Str::limit($paket->deskripsi, 90) }}</p>
          <div class="pkg-price">
            <span class="curr">Rp&thinsp;</span>
            <span class="amount">{{ number_format((float)$paket->harga, 0, ',', '.') }}</span>
          </div>
          <ul class="pkg-features">
            @if($featurePoints->isNotEmpty())
              @foreach($featurePoints as $featurePoint)
                <li><i class="bi bi-check-circle"></i> {{ $featurePoint }}</li>
              @endforeach
            @else
              <li><i class="bi bi-check-circle"></i> Detail paket tersedia di halaman detail</li>
            @endif
          </ul>
          <div class="pkg-cta">
            <a href="{{ route('paket.show', $paket->paket_id) }}" class="pkg-btn-outline">Detail</a>
            <button class="pkg-btn-solid"
              data-id="{{ $paket->paket_id }}"
              data-nama="{{ $paket->nama_paket }}"
              data-harga="{{ $paket->harga }}"
              data-pakettype="{{ $paket->paket_type ?? 'wedding_only' }}"
              data-bs-toggle="modal"
              data-bs-target="#modalBook">
              Book Now
            </button>
          </div>
        </div>
      </div>
      @empty
      <div class="col-12 text-center" style="grid-column:1/-1; padding:80px 0; color:var(--text-dim)">
        <i class="bi bi-camera" style="font-size:3rem; opacity:.2"></i>
        <p style="margin-top:16px">Paket tidak ditemukan.</p>
      </div>
      @endforelse
    </div>
  </div>
</section>

<!-- ===== TESTIMONIALS ===== -->
<section id="testimonials">
  <div class="testi-bg-text">LOVE</div>
  <div class="container">
    <div class="text-center reveal">
      <span class="section-label">Testimonials</span>
      <h2 class="section-title">Happy <em>Couples</em></h2>
    </div>
    <div class="testi-grid">
      @php $testis = [
        ['nama'=>'Rani & Dimas','cat'=>'Wedding','text'=>'Hasil fotonya sangat aesthetic dan timnya sangat sabar mengarahkan gaya. Recommended banget untuk semua pasangan!'],
        ['nama'=>'Sarah & Rizky','cat'=>'Engagement','text'=>'Suka banget sama tone warnanya. Enamorapic berhasil bikin momen engagement kita jadi makin spesial dan berkesan.'],
        ['nama'=>'Andien & Tomi','cat'=>'Prewedding','text'=>'Profesional dan tepat waktu. Hasil foto dikirim cepat dan editingnya rapi. Tidak mengecewakan sama sekali!'],
      ]; @endphp
      @foreach($testis as $i => $t)
      <div class="testi-card reveal" style="transition-delay:{{ $i * 0.15 }}s">
        <div class="testi-stars">★★★★★</div>
        <p class="testi-text">"{{ $t['text'] }}"</p>
        <div class="testi-author">
          <img src="https://ui-avatars.com/api/?name={{ urlencode($t['nama']) }}&background=c9a84c&color=fff&size=80" class="testi-avatar" alt="{{ $t['nama'] }}">
          <div>
            <div class="testi-name">{{ $t['nama'] }}</div>
            <div class="testi-cat">{{ $t['cat'] }}</div>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

<!-- ===== FAQ ===== -->
<section id="faq">
  <div class="container">
    <div class="faq-grid">
      <div>
        <div class="reveal">
          <span class="section-label">FAQ</span>
          <h2 class="section-title">Pertanyaan <em>Umum</em></h2>
        </div>
        <div class="faq-list">
          @php $faqs = [
            ['q'=>'Bagaimana cara booking jadwal?','a'=>'Pilih paket yang Anda inginkan, klik tombol "Book Now", lalu isi formulir. Admin kami akan menghubungi via WhatsApp untuk konfirmasi.'],
            ['q'=>'Berapa minimal DP yang harus dibayar?','a'=>'Minimal DP adalah 30% dari total harga paket untuk mengunci tanggal acara Anda.'],
            ['q'=>'Berapa lama waktu pengiriman foto?','a'=>'Foto edited biasanya selesai dalam 14-21 hari kerja setelah hari acara, dikirim via Google Drive.'],
            ['q'=>'Apakah bisa request lokasi di luar Bandung?','a'=>'Bisa! Kami melayani seluruh Jawa Barat dan sekitarnya. Untuk luar kota, ada biaya transportasi tambahan.'],
          ]; @endphp
          @foreach($faqs as $i => $f)
          <div class="faq-item reveal" style="transition-delay:{{ $i * 0.1 }}s">
            <button class="faq-q" onclick="toggleFaq(this)">
              {{ $f['q'] }}
              <span class="icon"><i class="bi bi-plus"></i></span>
            </button>
            <div class="faq-a"><p>{{ $f['a'] }}</p></div>
          </div>
          @endforeach
        </div>
      </div>
      <div class="faq-visual reveal-right">
        <img src="https://images.unsplash.com/photo-1606216794074-735e91aa2c92?w=600&q=80" class="faq-img" alt="FAQ visual">
        <div class="faq-img-note">
          <h6>Masih ada pertanyaan?</h6>
          <p>Hubungi kami langsung via WhatsApp, kami siap membantu!</p>
          <a href="https://wa.me/628980564584" target="_blank" class="btn-gold" style="display:inline-flex; margin-top:14px; font-size:.68rem; padding:10px 22px">
            <i class="bi bi-whatsapp"></i> Chat WhatsApp
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== FOOTER ===== -->
<footer>
  <div class="container">
    <div class="footer-grid">
      <div class="footer-brand">
        <div class="footer-logo-card">
          <img src="{{ asset('assets/images/enamora.png') }}" alt="Logo Enamora" loading="lazy" onerror="this.src='{{ asset('assets/images/enamora.svg') }}'">
        </div>
        <p>Abadikan momen terindah dalam hidupmu bersama kami. Melayani Wedding, Prewedding, dan Engagement di seluruh Indonesia.</p>
        <div class="footer-socials">
          <a href="https://www.instagram.com/enamorapic/" class="social-btn"><i class="bi bi-instagram"></i></a>
          <a href="https://wa.me/628980564584" class="social-btn"><i class="bi bi-whatsapp"></i></a>
        </div>
      </div>
      <div class="footer-col">
        <h6>Menu</h6>
        <ul>
          <li><a href="#about">Tentang</a></li>
          <li><a href="#portfolio">Portfolio</a></li>
          <li><a href="#packages">Paket</a></li>
          <li><a href="#faq">FAQ</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h6>Kontak</h6>
        <ul>
          <li><i class="bi bi-whatsapp" style="color:var(--gold)"></i> +62 898-0564-584</li>
          <li><i class="bi bi-geo-alt" style="color:var(--gold)"></i> Bandung, Jawa Barat</li>
          <li><i class="bi bi-instagram" style="color:var(--gold)"></i> @enamorapic</li>
        </ul>
      </div>
      <div class="footer-col">
        <h6>Layanan</h6>
        <ul>
          <li><a href="#packages">Wedding</a></li>
          <li><a href="#packages">Prewedding</a></li>
          <li><a href="#packages">Engagement</a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <p>© {{ date('Y') }} Enamorapic Photography. All rights reserved.</p>
      <p style="color:rgba(110,167,216,.5); font-size:.7rem; letter-spacing:1.5px; text-transform:uppercase">Made with ✦</p>
    </div>
  </div>
</footer>

<!-- ===== FLOATING CHAT BUTTONS ===== -->
<div class="chat-float">
  <div class="chat-nudge" id="chatNudge">Masih bingung? Diskusikan saja dulu.</div>
  <div class="chat-float-row">
    <span class="chat-float-label">CS</span>
    <a href="https://wa.me/628980564584?text=Halo%20Enamorapic%2C%20saya%20ingin%20konsultasi%20paket%20dan%20jadwal.%20Boleh%20dibantu%3F" target="_blank" class="chat-float-btn" aria-label="Chat Customer Service">
      <i class="bi bi-whatsapp"></i>
    </a>
  </div>
  <div class="chat-float-row">
    <span class="chat-float-label">Mora AI</span>
    <button class="chat-float-btn ai" id="aiChatToggle" aria-label="Tanya Mora">
      <i class="bi bi-stars"></i>
    </button>
  </div>
</div>

<!-- ===== AI CHAT PANEL ===== -->
<div class="chat-panel" id="aiChatPanel" aria-live="polite" data-endpoint="{{ route('ai.chat') }}">
  <div class="chat-panel-header">
    <span>Mora</span>
    <button class="lb-close" id="aiChatClose" aria-label="Tutup">×</button>
  </div>
  <div class="chat-panel-body" id="aiChatBody">
    <div class="chat-msg ai">
      <div class="bubble">Halo! Aku Mora. Tanya apa saja seputar wedding, prewedding, engagement, paket, budget, atau outfit.</div>
    </div>
  </div>
  <div class="chat-panel-footer">
    <input type="text" id="aiChatInput" class="chat-input" placeholder="Contoh: Budget 5 juta cocok paket apa?" maxlength="300">
    <button class="chat-send" id="aiChatSend" aria-label="Kirim">
      <i class="bi bi-send"></i>
    </button>
  </div>
  <div class="chat-panel-footer" style="border-top:0; padding-top:0">
    <div class="chat-hint">AI hanya menjawab topik terkait Enamorapic dan layanan foto wedding/prewedding/engagement.</div>
  </div>
</div>

<!-- ===== LIGHTBOX ===== -->
<div id="lightbox">
  <button class="lb-close" onclick="closeLightbox()"><i class="bi bi-x-lg"></i></button>
  <button class="lb-nav lb-prev" onclick="lbNav(-1)"><i class="bi bi-chevron-left"></i></button>
  <img id="lbImg" src="" alt="">
  <button class="lb-nav lb-next" onclick="lbNav(1)"><i class="bi bi-chevron-right"></i></button>
  <div class="lb-counter"><span id="lbCur">1</span> / <span id="lbTot">6</span></div>
</div>

<!-- ===== MODAL BOOKING ===== -->
<div class="modal fade" id="modalBook" tabindex="-1">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content">
  <div class="modal-header">
    <div>
      <h5 class="modal-title">Form Pemesanan</h5>
      <small style="color:var(--text-dim); font-size:.78rem">Isi data diri Anda dengan lengkap</small>
    </div>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
  </div>
  <form action="{{ route('booking.store') }}" method="POST" id="formBooking">
  @csrf
  <div class="modal-body">
    <input type="hidden" name="paket_id" id="val_id">
    <input type="hidden" name="paket_type" id="val_paket_type">
    <div style="display:flex; justify-content:space-between; align-items:center; background:rgba(110,167,216,.08); border:1px solid rgba(110,167,216,.2); border-radius:4px; padding:16px; margin-bottom:20px">
      <div>
        <small style="color:var(--text-dim); font-size:.68rem; letter-spacing:1.5px; text-transform:uppercase">Paket Dipilih</small>
        <div style="color:#fff; font-weight:500; margin-top:4px" id="val_nama">—</div>
      </div>
      <div style="text-align:right">
        <small style="color:var(--text-dim); font-size:.68rem; letter-spacing:1.5px; text-transform:uppercase">Harga</small>
        <div style="color:var(--gold-light); font-family:'Cormorant Garamond',serif; font-size:1.2rem; margin-top:4px">Rp <span id="val_harga">—</span></div>
      </div>
    </div>
    <div style="background:rgba(110,167,216,.06); border:1px solid rgba(110,167,216,.15); border-radius:4px; padding:12px 16px; margin-bottom:20px; font-size:.84rem" id="terminInfo">
      <strong style="color:var(--gold)">💳 Info Pembayaran:</strong>
      <span id="terminText"></span>
    </div>
    <div class="mb-3">
      <label class="form-label">Nama Lengkap</label>
      <input type="text" name="nama" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">No WhatsApp</label>
      <input type="tel" name="wa" class="form-control" placeholder="08xxxxxxxx" required>
    </div>
    <div id="singleEventFields">
      <div class="row g-3">
        <div class="col-6">
          <label class="form-label">Tgl Acara</label>
          <input type="date" name="tgl_acara" class="form-control" min="{{ date('Y-m-d') }}" required>
        </div>
        <div class="col-6">
          <label class="form-label">Pembayaran</label>
          <select name="status_bayar" class="form-select" required>
            <option value="" disabled selected>— Pilih —</option>
            <option value="DP">DP (30% + 70%)</option>
            <option value="Lunas">Lunas</option>
          </select>
        </div>
      </div>
      <div class="mt-3">
        <label class="form-label">Lokasi Acara</label>
        <textarea name="lokasi_wedding" class="form-control" rows="2" placeholder="Alamat / Nama Gedung" required></textarea>
      </div>
    </div>
    <div id="allInEventFields" style="display:none">
      <div style="background:rgba(255,193,7,.06); border:1px solid rgba(255,193,7,.2); border-radius:4px; padding:12px; margin-bottom:16px; font-size:.84rem; color:#ffc107">
        <strong>📷 All-In Package (3 Termin)</strong>
        <small style="display:block; color:var(--text-dim); margin-top:4px">Isi detail Pre-Wedding dan Wedding secara terpisah</small>
      </div>
      <h6 style="font-size:.78rem; letter-spacing:2px; text-transform:uppercase; color:var(--text-dim); margin-bottom:12px">📸 Pre-Wedding</h6>
      <div class="mb-3">
        <label class="form-label">Tanggal Pre-Wedding</label>
        <input type="date" name="tgl_prewedd" class="form-control" min="{{ date('Y-m-d') }}" disabled>
      </div>
      <div class="mb-3">
        <label class="form-label">Lokasi Pre-Wedding</label>
        <textarea name="lokasi_prewedd" class="form-control" rows="2" disabled></textarea>
      </div>
      <h6 style="font-size:.78rem; letter-spacing:2px; text-transform:uppercase; color:var(--text-dim); margin:16px 0 12px">💒 Wedding</h6>
      <div class="mb-3">
        <label class="form-label">Tanggal Wedding</label>
        <input type="date" name="tgl_acara" class="form-control" min="{{ date('Y-m-d') }}" disabled>
      </div>
      <div class="mb-3">
        <label class="form-label">Lokasi Wedding</label>
        <textarea name="lokasi_wedding" class="form-control" rows="2" disabled></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Pembayaran</label>
        <select name="status_bayar" class="form-select" disabled>
          <option value="" disabled selected>— Pilih —</option>
          <option value="DP">DP (3 Termin: 30%+30%+40%)</option>
          <option value="Lunas">Lunas</option>
        </select>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="pkg-btn-outline" data-bs-dismiss="modal" style="flex:none; padding:10px 24px; color:var(--text-dim); border-color:rgba(255,255,255,.12)">Batal</button>
    <button type="submit" class="pkg-btn-solid" id="btnSubmitBooking" disabled style="flex:none; padding:10px 28px">Kirim Pemesanan</button>
  </div>
  </form>
</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
/* ===== CURSOR ===== */
const cur = document.getElementById('cursor');
const curRing = document.getElementById('cursor-ring');
let mx = 0, my = 0, rx = 0, ry = 0;
document.addEventListener('mousemove', e => {
  mx = e.clientX; my = e.clientY;
  cur.style.left = mx + 'px'; cur.style.top = my + 'px';
});
function animRing() {
  rx += (mx - rx) * .12; ry += (my - ry) * .12;
  curRing.style.left = rx + 'px'; curRing.style.top = ry + 'px';
  requestAnimationFrame(animRing);
}
animRing();
document.querySelectorAll('a, button, [data-lb]').forEach(el => {
  el.addEventListener('mouseenter', () => document.body.classList.add('cursor-grow'));
  el.addEventListener('mouseleave', () => document.body.classList.remove('cursor-grow'));
});

/* ===== PROGRESS BAR ===== */
const pb = document.getElementById('progress-bar');
window.addEventListener('scroll', () => {
  const s = document.documentElement;
  const pct = (s.scrollTop / (s.scrollHeight - s.clientHeight)) * 100;
  pb.style.width = pct + '%';
});

/* ===== NAVBAR ===== */
const navbar = document.getElementById('navbar');
window.addEventListener('scroll', () => navbar.classList.toggle('scrolled', window.scrollY > 40));
document.getElementById('navToggle').addEventListener('click', function() {
  document.getElementById('navLinks').classList.toggle('open');
});

/* ===== HERO CANVAS (particles) ===== */
(function() {
  const c = document.getElementById('hero-canvas');
  const ctx = c.getContext('2d');
  let W, H, pts = [];
  function resize() { W = c.width = window.innerWidth; H = c.height = window.innerHeight; }
  resize(); window.addEventListener('resize', resize);
  function Pt() { this.reset(); }
  Pt.prototype.reset = function() {
    this.x = Math.random() * W; this.y = Math.random() * H;
    this.r = Math.random() * 1.1 + .2;
    this.vy = -(Math.random() * .3 + .06); this.vx = (Math.random() - .5) * .1;
    this.a = Math.random() * .45 + .05;
    this.gold = Math.random() < .3;
    this.tp = Math.random() * Math.PI * 2; this.ts = Math.random() * .018 + .004;
  };
  for (let i = 0; i < 70; i++) pts.push(new Pt());
  (function loop() {
    ctx.clearRect(0, 0, W, H);
    pts.forEach(p => {
      p.y += p.vy; p.x += p.vx; p.tp += p.ts;
      const a = p.a * (.5 + .5 * Math.sin(p.tp));
      ctx.beginPath(); ctx.arc(p.x, p.y, p.r, 0, Math.PI*2);
      ctx.fillStyle = p.gold ? `rgba(110,167,216,${a})` : `rgba(255,255,255,${a})`;
      ctx.fill();
      if (p.y < -4) { p.reset(); p.y = H + 4; }
    });
    requestAnimationFrame(loop);
  })();
})();

/* ===== HERO BG LOADED ===== */
document.getElementById('heroBg')?.classList.add('loaded');

/* ===== SCROLL REVEAL ===== */
const ro = new IntersectionObserver(entries => {
  entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); ro.unobserve(e.target); } });
}, { threshold: .12 });
document.querySelectorAll('.reveal, .reveal-left, .reveal-right').forEach(el => ro.observe(el));

/* ===== COUNT UP ===== */
function countUp(el, end) {
  let n = 0, step = Math.ceil(end / 60);
  const t = setInterval(() => { n = Math.min(n + step, end); el.textContent = n + '+'; if (n >= end) clearInterval(t); }, 24);
}
const cro = new IntersectionObserver(entries => {
  entries.forEach(e => {
    if (e.isIntersecting) {
      document.querySelectorAll('.stat-box .num[data-count]').forEach(el => countUp(el, +el.dataset.count));
      cro.disconnect();
    }
  });
}, { threshold: .5 });
const statsEl = document.querySelector('.stats-row');
if (statsEl) cro.observe(statsEl);

/* ===== PARALLAX HERO (disabled: keep hero bg stable) ===== */

/* ===== HERO 3D MOUSE TILT ===== */
const heroSection = document.getElementById('hero');
heroSection.addEventListener('mousemove', e => {
  const dx = (e.clientX / window.innerWidth - .5) * 12;
  const dy = (e.clientY / window.innerHeight - .5) * 8;
  document.querySelectorAll('.shape-ring').forEach((s, i) => {
    const depth = (i + 1) * 0.4;
    s.style.transform = `translate(${dx * depth}px, ${dy * depth}px)`;
  });
});

/* ===== LIGHTBOX ===== */
const lbImages = @json(collect($imgs ?? [])->pluck('url'));
const lb = document.getElementById('lightbox');
let lbIdx = 0;
function openLightbox(idx) {
  lbIdx = idx;
  document.getElementById('lbImg').src = lbImages[lbIdx];
  document.getElementById('lbCur').textContent = lbIdx + 1;
  document.getElementById('lbTot').textContent = lbImages.length;
  lb.classList.add('open');
  document.body.style.overflow = 'hidden';
}
function closeLightbox() { lb.classList.remove('open'); document.body.style.overflow = ''; }
function lbNav(dir) {
  lbIdx = (lbIdx + dir + lbImages.length) % lbImages.length;
  document.getElementById('lbImg').src = lbImages[lbIdx];
  document.getElementById('lbCur').textContent = lbIdx + 1;
}
document.querySelectorAll('[data-lb], .lb-trigger').forEach(el => {
  el.addEventListener('click', () => openLightbox(+el.dataset.idx || +el.dataset.lbIdx || 0));
});
lb.addEventListener('click', e => { if (e.target === lb) closeLightbox(); });
document.addEventListener('keydown', e => {
  if (!lb.classList.contains('open')) return;
  if (e.key === 'Escape') closeLightbox();
  if (e.key === 'ArrowRight') lbNav(1);
  if (e.key === 'ArrowLeft') lbNav(-1);
});

/* ===== FAQ ===== */
function toggleFaq(btn) {
  const item = btn.closest('.faq-item');
  const isOpen = item.classList.contains('open');
  document.querySelectorAll('.faq-item.open').forEach(i => i.classList.remove('open'));
  if (!isOpen) item.classList.add('open');
}

/* ===== MODAL BOOKING ===== */
const modalBook = document.getElementById('modalBook');
modalBook.addEventListener('show.bs.modal', e => {
  const t = e.relatedTarget;
  if (!t) return;
  const type = t.dataset.pakettype || 'wedding_only';
  document.getElementById('val_id').value = t.dataset.id;
  document.getElementById('val_paket_type').value = type;
  document.getElementById('val_nama').textContent = t.dataset.nama;
  document.getElementById('val_harga').textContent = new Intl.NumberFormat('id-ID').format(t.dataset.harga);
  updateModalFields(type);
  setTimeout(checkValidity, 80);
});

function setDisabled(container, dis) {
  container.querySelectorAll('input,select,textarea').forEach(el => {
    el.disabled = dis;
    if (dis) el.removeAttribute('required');
  });
}
function updateModalFields(type) {
  const sf = document.getElementById('singleEventFields');
  const af = document.getElementById('allInEventFields');
  const tt = document.getElementById('terminText');
  if (type === 'all_in') {
    sf.style.display = 'none'; setDisabled(sf, true);
    af.style.display = 'block'; setDisabled(af, false);
    tt.innerHTML = '<br><strong style="color:var(--gold-light)">3 Termin:</strong> 30% booking + 30% H-1 Prewedd + 40% H-1 Wedding';
    ['tgl_prewedd','tgl_acara','status_bayar','lokasi_prewedd','lokasi_wedding'].forEach(n => {
      const el = af.querySelector('[name="'+n+'"]');
      if (el) el.setAttribute('required','required');
    });
  } else {
    sf.style.display = 'block'; setDisabled(sf, false);
    af.style.display = 'none'; setDisabled(af, true);
    tt.innerHTML = '<br><strong style="color:var(--gold-light)">2 Termin:</strong> 30% saat booking + 70% H-1 acara';
    ['tgl_acara','status_bayar','lokasi_wedding'].forEach(n => {
      const el = sf.querySelector('[name="'+n+'"]');
      if (el) el.setAttribute('required','required');
    });
  }
}
function checkValidity() {
  const nama = document.querySelector('[name="nama"]');
  const wa   = document.querySelector('[name="wa"]');
  const sf   = document.getElementById('singleEventFields');
  const af   = document.getElementById('allInEventFields');
  const btn  = document.getElementById('btnSubmitBooking');
  if (!nama?.value || !wa?.value) { btn.disabled = true; return; }
  let ok = false;
  if (sf.style.display !== 'none') {
    const tgl = sf.querySelector('[name="tgl_acara"]');
    const sb  = sf.querySelector('[name="status_bayar"]');
    const lw  = sf.querySelector('[name="lokasi_wedding"]');
    ok = !!(tgl?.value && sb?.value && lw?.value);
  } else {
    const tp = af.querySelector('[name="tgl_prewedd"]');
    const lp = af.querySelector('[name="lokasi_prewedd"]');
    const ta = af.querySelector('[name="tgl_acara"]');
    const sb = af.querySelector('[name="status_bayar"]');
    const lw = af.querySelector('[name="lokasi_wedding"]');
    ok = !!(tp?.value && lp?.value && ta?.value && sb?.value && lw?.value);
  }
  btn.disabled = !ok;
}
document.getElementById('formBooking').querySelectorAll('input,select,textarea').forEach(el => {
  el.addEventListener('input', checkValidity);
  el.addEventListener('change', checkValidity);
});
document.getElementById('formBooking').addEventListener('submit', function() {
  const btn = document.getElementById('btnSubmitBooking');
  btn.classList.add('btn-loading'); btn.disabled = true; btn.textContent = 'Mengirim...';
});

/* ===== AUTO OPEN BOOKING (from ?book=id) ===== */
const autoBookId = new URLSearchParams(location.search).get('book');
if (autoBookId) {
  const trigger = document.querySelector(`button[data-id="${autoBookId}"]`);
  if (trigger) {
    setTimeout(() => bootstrap.Modal.getOrCreateInstance(document.getElementById('modalBook')).show(), 200);
    const url = new URL(location.href); url.searchParams.delete('book');
    history.replaceState({}, '', url);
  }
}

/* ===== SMOOTH SCROLL ===== */
document.querySelectorAll('a[href^="#"]').forEach(a => {
  a.addEventListener('click', e => {
    const t = document.querySelector(a.getAttribute('href'));
    if (t) { e.preventDefault(); t.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
  });
});

/* ===== TOAST AUTO DISMISS ===== */
setTimeout(() => {
  const tw = document.querySelector('.toast-wrap');
  if (tw) { tw.style.opacity = '0'; tw.style.transition = 'opacity .5s'; setTimeout(() => tw.remove(), 500); }
}, 4000);

/* ===== AI CHAT (placeholder until API ready) ===== */
const aiToggle = document.getElementById('aiChatToggle');
const aiPanel = document.getElementById('aiChatPanel');
const aiClose = document.getElementById('aiChatClose');
const aiBody = document.getElementById('aiChatBody');
const aiInput = document.getElementById('aiChatInput');
const aiSend = document.getElementById('aiChatSend');
const chatNudge = document.getElementById('chatNudge');
@php
  $aiPaketData = collect($pakets ?? [])->map(function ($paket) {
    $fallback = 'https://images.unsplash.com/photo-1537633552985-df8429e8048b?w=800&q=80';
    $imgPath = public_path('assets/images/' . ($paket->gambar ?? ''));
    $imgUrl = ($paket->gambar && file_exists($imgPath)) ? asset('assets/images/' . $paket->gambar) : $fallback;
    return [
      'id' => $paket->paket_id,
      'nama' => $paket->nama_paket,
      'kategori' => $paket->kategori,
      'harga' => number_format((float) $paket->harga, 0, ',', '.'),
      'img' => $imgUrl,
    ];
  })->values();
@endphp
const aiPaketData = @json($aiPaketData);

function appendMsg(text, who) {
  const row = document.createElement('div');
  row.className = 'chat-msg ' + who;
  const bubble = document.createElement('div');
  bubble.className = 'bubble';
  bubble.textContent = text;
  row.appendChild(bubble);
  aiBody.appendChild(row);
  aiBody.scrollTop = aiBody.scrollHeight;
  return bubble;
}

function isEnamorapicTopic(text) {
  const t = text.toLowerCase();
  const isGreeting = /(halo|hai|hi|hello|assalamualaikum|pagi|siang|sore|malam)/.test(t);
  if (isGreeting) return true;
  const isRepeat = /(ulangi|ulang|repeat|lagi)/.test(t);
  if (isRepeat) return true;
  return /(enamorapic|wedding|prewedd|pre-wedd|prewedding|engagement|paket|budget|harga|outfit|lokasi|jadwal|booking|dp|termin|foto|video|pose|gaya|request|req|tema|konsep|styling|stylist)/.test(t);
}

function toggleAiPanel(open) {
  aiPanel.classList.toggle('open', open);
  if (open) { setTimeout(() => aiInput?.focus(), 50); }
}

aiToggle?.addEventListener('click', () => toggleAiPanel(true));
aiClose?.addEventListener('click', () => toggleAiPanel(false));

if (chatNudge) {
  setInterval(() => {
    chatNudge.classList.toggle('hidden');
  }, 5000);
}

async function sendAiMessage() {
  const msg = aiInput.value.trim();
  if (!msg) return;
  appendMsg(msg, 'user');
  aiInput.value = '';

  if (!isEnamorapicTopic(msg)) {
    appendMsg('Maaf, aku hanya bisa bantu pertanyaan yang relevan dengan layanan Enamorapic (wedding, prewedding, engagement, paket, budget, outfit, dan booking).', 'ai');
    return;
  }

  const typingBubble = appendMsg('Sedang mengetik...', 'ai');

  const endpoint = aiPanel?.dataset.endpoint;
  if (!endpoint) {
    typingBubble.textContent = 'Endpoint AI belum tersedia. Silakan hubungi CS.';
    return;
  }
  const token = document.querySelector('meta[name="csrf-token"]')?.content || '';
  try {
    const res = await fetch(endpoint, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': token,
      },
      body: JSON.stringify({ message: msg }),
    });
    const data = await res.json();
    const replyText = data.reply || 'Maaf, AI belum memberi jawaban.';
    typingBubble.textContent = replyText;
    if (Array.isArray(data.packages) && data.packages.length > 0) {
      appendPackageCards(data.packages);
    } else {
      appendPackageCardIfMentioned(replyText);
    }
  } catch (err) {
    typingBubble.textContent = 'Maaf, AI sedang bermasalah. Silakan coba lagi atau hubungi CS.';
  }
}

function appendPackageCards(list) {
  if (!Array.isArray(list) || list.length === 0) return;
  list.slice(0, 5).forEach(found => {
    const row = document.createElement('div');
    row.className = 'chat-msg ai';
    const bubble = document.createElement('div');
    bubble.className = 'bubble package';
    const desc = found.desc ? `<div class="chat-pkg-desc">${found.desc}</div>` : '';
    bubble.innerHTML = `
      <a class="chat-pkg-card" href="${window.location.origin}/paket/${found.id}">
        <img class="chat-pkg-img" src="${found.img}" alt="${found.nama}">
        <div class="chat-pkg-body">
          <div class="chat-pkg-meta">${found.kategori}</div>
          <div class="chat-pkg-title">${found.nama}</div>
          ${desc}
          <div class="chat-pkg-price">Rp ${found.harga}</div>
        </div>
      </a>
    `;
    row.appendChild(bubble);
    aiBody.appendChild(row);
  });
  aiBody.scrollTop = aiBody.scrollHeight;
}

function appendPackageCardIfMentioned(text) {
  if (!Array.isArray(aiPaketData) || aiPaketData.length === 0) return;
  const t = (text || '').toLowerCase();
  const found = aiPaketData.find(p => t.includes(String(p.nama || '').toLowerCase()));
  if (!found) return;
  appendPackageCards([found]);
}

aiSend?.addEventListener('click', sendAiMessage);
aiInput?.addEventListener('keydown', e => { if (e.key === 'Enter') sendAiMessage(); });

/* ===== PAGE TRANSITION LINKS (paket detail) ===== */
document.querySelectorAll('a[href*="/paket/"]').forEach(a => {
  a.addEventListener('click', e => {
    const href = a.getAttribute('href');
    if (!href) return;
    e.preventDefault();
    document.body.style.opacity = '0';
    document.body.style.transition = 'opacity .3s';
    setTimeout(() => location.href = href, 300);
  });
});
</script>
</body>
</html>