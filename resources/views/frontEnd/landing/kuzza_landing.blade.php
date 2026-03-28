<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>KUZZA — Simplifying Education for Every Child</title>
@php
    $kuzzaLogoPath = is_file('/public/images/kuzza_logo.png')
        ? 'images/kuzza_logo.png'
        : 'images/kuzza_logo.svg';
    $kuzzaLogoUrl = asset($kuzzaLogoPath);
    $kuzzaLogoMime = str_ends_with($kuzzaLogoPath, '.svg') ? 'image/svg+xml' : 'image/png';
    $kuzzaLogoIsSvg = str_ends_with($kuzzaLogoPath, '.svg');
    $kuzzaLogoSvgInline = '';
    if ($kuzzaLogoIsSvg) {
        $kuzzaLogoAbs = public_path($kuzzaLogoPath);
        if (is_readable($kuzzaLogoAbs)) {
            $kuzzaLogoSvgInline = file_get_contents($kuzzaLogoAbs) ?: '';
        }
    }
@endphp
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
@if (! $kuzzaLogoIsSvg)
<link rel="preload" href="{{ $kuzzaLogoUrl }}" as="image" type="{{ $kuzzaLogoMime }}">
@endif
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
<noscript><link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"></noscript>
<style>
:root {
  --purple: #5B2D8E;
  --purple-deep: #3A1A6B;
  --yellow: #F5C518;
  --yellow-light: #FFE066;
  --pink-accent: #E8A0D8;
  --lilac: #C8A8E9;
  --white: #FAFAF8;
  --off-white: #F2EEF9;
  --text-dark: #1A0A2E;
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; scroll-padding-top: 5.5rem; }
body { font-family: 'DM Sans', sans-serif; background: var(--white); color: var(--text-dark); overflow-x: hidden; }
body.nav-open { overflow: hidden; }

/* Fixed header + .landing-promo (yellow promo + ticker) in document flow */
.site-header {
  position: fixed; top: 0; left: 0; right: 0; z-index: 200;
}
.landing-promo {
  margin-top: 0;
  padding-top: calc(1.7rem + 52px);
  background: linear-gradient(90deg, #FFE84D 0%, var(--yellow) 50%, #F0B800 100%);
}
.header-top {
  position: relative;
  padding: 0.85rem 5vw;
  background: rgba(58,26,107,0.97);
  backdrop-filter: blur(12px);
  isolation: isolate;
}
.nav-logo {
  display: flex; align-items: center; gap: 0.65rem;
  min-width: max-content;
  position: relative;
  z-index: 2;
  overflow: visible;
  justify-self: start;
  flex: 0 0 auto;
}
.nav-logo-img {
  height: 52px; width: 220px; max-width: 220px;
  object-fit: contain;
  object-position: left center;
  display: block;
  flex-shrink: 0;
}
.nav-logo-svg-wrap {
  display: block;
  flex-shrink: 0;
  line-height: 0;
  width: 220px;
  min-width: 220px;
}
.nav-logo-svg-wrap svg {
  display: block;
  height: 52px;
  width: 220px;
  max-width: 220px;
}
.nav-logo-link {
  display: inline-flex;
  align-items: center;
  flex-wrap: nowrap;
  min-width: max-content;
  gap: 0.65rem;
}
/* System serif so “by MyBidhaa” paints immediately (no webfont wait in the header) */
.nav-cta {
  background: var(--yellow); color: var(--purple-deep);
  font-weight: 700; font-size: 0.82rem; letter-spacing: 0.06em;
  padding: 0.5rem 1.3rem; border-radius: 100px; border: none;
  cursor: pointer; text-transform: uppercase;
  transition: transform 0.2s, box-shadow 0.2s;
}
.nav-cta:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(245,197,24,0.45); }

.header-top-inner {
  display: grid;
  /* First column must not collapse to 0 (minmax(0,auto) allowed that vs 1fr center). */
  grid-template-columns: max-content minmax(0, 1fr) max-content;
  align-items: center;
  gap: 0.75rem 1rem;
  width: 100%;
  max-width: 100%;
  position: relative;
  z-index: 1;
}
.header-nav {
  display: flex; align-items: center; justify-content: center;
  flex-wrap: wrap; gap: 0.15rem 0.35rem;
  list-style: none; margin: 0; padding: 0;
  min-width: 0;
}
@media (min-width: 1101px) {
  .header-nav {
    position: relative;
    z-index: 1;
  }
}
.header-end {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  flex-shrink: 0;
  position: relative;
  z-index: 2;
}
.header-nav a {
  color: rgba(255,255,255,0.78);
  text-decoration: none;
  font-size: 0.68rem;
  font-weight: 600;
  letter-spacing: 0.05em;
  text-transform: uppercase;
  padding: 0.4rem 0.45rem;
  border-radius: 6px;
  white-space: nowrap;
  transition: color 0.2s, background 0.2s;
}
.header-nav a:hover { color: #fff; background: rgba(255,255,255,0.08); }
.header-nav a.active {
  color: var(--yellow);
  background: rgba(245,197,24,0.18);
}
.nav-menu-toggle {
  display: none;
  flex-direction: column; justify-content: center; gap: 5px;
  width: 42px; height: 42px;
  padding: 8px; border: none; border-radius: 8px;
  background: rgba(255,255,255,0.12);
  cursor: pointer;
  flex-shrink: 0;
}
.nav-menu-toggle span {
  display: block; height: 2px; width: 100%;
  background: var(--yellow); border-radius: 1px;
  transition: transform 0.2s, opacity 0.2s;
}
body.nav-open .nav-menu-toggle span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
body.nav-open .nav-menu-toggle span:nth-child(2) { opacity: 0; }
body.nav-open .nav-menu-toggle span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }
.header-actions { display: flex; align-items: center; gap: 0.5rem; flex-shrink: 0; margin: 0; padding: 0; }
.nav-cta-secondary {
  background: transparent; color: var(--yellow);
  font-weight: 700; font-size: 0.82rem; letter-spacing: 0.06em;
  padding: 0.45rem 1.1rem; border-radius: 100px;
  border: 2px solid var(--yellow);
  cursor: pointer; text-transform: uppercase;
  transition: transform 0.2s, background 0.2s, color 0.2s;
  font-family: inherit;
}
.nav-cta-secondary:hover {
  background: rgba(245,197,24,0.15);
  transform: translateY(-2px);
}

.gs-modal { position: fixed; inset: 0; z-index: 300; display: flex; align-items: center; justify-content: center; padding: 1rem; opacity: 0; visibility: hidden; transition: opacity 0.25s, visibility 0.25s; }
.gs-modal.is-open { opacity: 1; visibility: visible; }
.gs-modal-backdrop { position: absolute; inset: 0; background: rgba(26,10,46,0.72); backdrop-filter: blur(4px); }
.gs-modal-panel {
  position: relative; z-index: 1; width: 100%; max-width: 440px;
  background: var(--white); color: var(--text-dark);
  border-radius: 20px; padding: 1.75rem 1.5rem 1.5rem;
  box-shadow: 0 24px 64px rgba(58,26,107,0.35);
  max-height: min(90vh, 640px); overflow-y: auto;
}
.gs-modal-close {
  position: absolute; top: 0.75rem; right: 0.75rem;
  width: 36px; height: 36px; border: none; border-radius: 50%;
  background: var(--off-white); color: var(--purple-deep);
  font-size: 1.4rem; line-height: 1; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  transition: background 0.2s;
}
.gs-modal-close:hover { background: #e8dff5; }
.gs-modal-panel h2 { font-family: 'Playfair Display', serif; font-size: 1.35rem; color: var(--purple-deep); margin-bottom: 0.5rem; line-height: 1.25; }
.gs-modal-intro { font-size: 0.92rem; color: #5c4a78; margin-bottom: 1.25rem; line-height: 1.5; }
.gs-field { margin-bottom: 1rem; }
.gs-field label { display: block; font-size: 0.78rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--purple-deep); margin-bottom: 0.35rem; }
.gs-field input {
  width: 100%; padding: 0.65rem 0.85rem; border-radius: 10px;
  border: 1px solid #d4c8ec; font-size: 1rem; font-family: inherit;
  transition: border-color 0.2s;
}
.gs-field input:focus { outline: none; border-color: var(--purple); }
.gs-field .error { font-size: 0.8rem; color: #b00020; margin-top: 0.25rem; }
.gs-submit {
  width: 100%; margin-top: 0.25rem;
  background: var(--purple-deep); color: var(--yellow);
  font-weight: 700; font-size: 0.9rem; letter-spacing: 0.04em;
  padding: 0.75rem 1.25rem; border: none; border-radius: 100px;
  cursor: pointer; text-transform: uppercase;
  transition: transform 0.2s, box-shadow 0.2s;
}
.gs-submit:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(58,26,107,0.35); }
.gs-submit:disabled { opacity: 0.65; cursor: not-allowed; }
.gs-success {
  text-align: center; padding: 1.5rem 0.5rem;
  font-size: 1.05rem; font-weight: 600; color: var(--purple-deep);
  line-height: 1.5;
}
.gs-form-error { font-size: 0.88rem; color: #b00020; margin-bottom: 0.75rem; }

@media (max-width: 1100px) {
  .header-top-inner {
    display: flex;
    flex-wrap: nowrap;
    justify-content: space-between;
    align-items: center;
  }
  /* min-width:0 here collapsed the wordmark beside the menu buttons */
  .nav-logo { flex: 0 0 auto; min-width: max-content; max-width: none; }
  .nav-logo-img { width: 170px; max-width: 170px; height: 42px; }
  .nav-logo-svg-wrap { width: 170px; min-width: 170px; }
  .nav-logo-svg-wrap svg { width: 170px; max-width: 170px; height: 42px; }
  .header-end { flex: 0 0 auto; }
  .nav-menu-toggle { display: flex; }
  .header-nav {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    z-index: 199;
    transform: none;
    flex-direction: column; flex-wrap: nowrap; justify-content: center;
    gap: 0.25rem;
    padding: 5rem 1.5rem 2rem;
    background: rgba(26,10,46,0.97);
    backdrop-filter: blur(12px);
    opacity: 0; visibility: hidden;
    pointer-events: none;
    transition: opacity 0.25s ease, visibility 0.25s;
  }
  body.nav-open .header-nav {
    opacity: 1; visibility: visible; pointer-events: auto;
    z-index: 201;
  }
  body.nav-open .header-top-inner {
    position: relative;
    z-index: 202;
  }
  .header-nav a {
    font-size: 0.95rem;
    padding: 0.65rem 1rem;
    width: 100%; max-width: 280px; text-align: center;
    pointer-events: auto;
  }
}

/* PROMO STRIP (in page flow under fixed header) */
.shout-inner {
  display: flex; align-items: center; justify-content: center;
  flex-wrap: wrap; gap: 0.8rem 1.8rem; padding: 0.9rem 4vw;
  background: linear-gradient(90deg, #FFE84D 0%, var(--yellow) 50%, #F0B800 100%);
  position: relative; overflow: hidden;
}
.shout-inner::before {
  content: ''; position: absolute; inset: 0;
  background: repeating-linear-gradient(-45deg, transparent, transparent 18px, rgba(91,45,142,0.05) 18px, rgba(91,45,142,0.05) 20px);
  pointer-events: none;
}
.shout-badge {
  background: var(--purple-deep); color: var(--yellow);
  font-size: 0.65rem; font-weight: 800; letter-spacing: 0.1em;
  text-transform: uppercase; padding: 0.28rem 0.8rem; border-radius: 100px;
  animation: badgePop 1.8s ease infinite; flex-shrink: 0; position: relative;
}
@keyframes badgePop { 0%,100%{transform:scale(1)} 50%{transform:scale(1.07)} }
.shout-text {
  font-family: 'Playfair Display', serif;
  font-size: clamp(1.3rem, 3vw, 2.2rem);
  font-weight: 900; color: var(--purple-deep); line-height: 1;
  letter-spacing: -0.02em; position: relative;
  text-shadow: 2px 2px 0 rgba(255,255,255,0.35);
}
.shout-free {
  color: var(--purple); -webkit-text-stroke: 1.5px var(--purple-deep);
  font-size: 1.1em; animation: freeGlow 2s ease infinite;
}
@keyframes freeGlow { 0%,100%{text-shadow:none} 50%{text-shadow:0 0 18px rgba(91,45,142,0.4)} }
.shout-sub { font-size: 0.82rem; color: rgba(58,26,107,0.72); font-weight: 500; max-width: 260px; line-height: 1.4; position: relative; }
.shout-btn {
  background: var(--purple-deep); color: var(--yellow);
  font-weight: 800; font-size: 0.85rem; letter-spacing: 0.04em;
  padding: 0.6rem 1.4rem; border-radius: 100px; border: none;
  cursor: pointer; text-decoration: none; white-space: nowrap;
  transition: transform 0.2s, box-shadow 0.2s;
  animation: btnPulse 2.5s ease infinite; display: inline-block; position: relative;
}
.shout-btn:hover { transform: translateY(-3px) scale(1.04); box-shadow: 0 10px 28px rgba(58,26,107,0.5); }
@keyframes btnPulse { 0%,100%{box-shadow:0 4px 16px rgba(58,26,107,0.35)} 50%{box-shadow:0 4px 28px rgba(58,26,107,0.65)} }
.shout-ticker { background: var(--purple-deep); overflow: hidden; white-space: nowrap; padding: 0.42rem 0; }
.ticker-track {
  display: inline-flex; gap: 3rem;
  animation: tickerScroll 30s linear infinite;
  color: var(--yellow); font-size: 0.75rem; font-weight: 700;
  letter-spacing: 0.08em; text-transform: uppercase;
}
@keyframes tickerScroll { from{transform:translateX(0)} to{transform:translateX(-50%)} }

/* HERO */
.hero {
  min-height: 100vh; background: var(--purple-deep);
  display: grid; grid-template-columns: 1fr 1fr;
  align-items: center; padding: 5rem 5vw 6rem; gap: 4rem;
  position: relative; overflow: hidden;
  scroll-margin-top: 5rem;
}
#offer,
#problem,
#solution,
#how-it-works,
#market,
#roadmap,
#impact,
#partners,
#cta {
  scroll-margin-top: 5rem;
}
.hero::before {
  content: ''; position: absolute; top: -120px; right: -120px;
  width: 600px; height: 600px;
  background: radial-gradient(circle, rgba(245,197,24,0.18) 0%, transparent 70%);
  border-radius: 50%; pointer-events: none;
}
.hero::after {
  content: ''; position: absolute; bottom: -80px; left: -80px;
  width: 400px; height: 400px;
  background: radial-gradient(circle, rgba(200,168,233,0.22) 0%, transparent 70%);
  border-radius: 50%; pointer-events: none;
}
.hero-eyebrow {
  display: inline-flex; align-items: center; gap: 0.5rem;
  background: rgba(245,197,24,0.15); border: 1px solid rgba(245,197,24,0.4);
  color: var(--yellow); font-size: 0.75rem; font-weight: 600;
  letter-spacing: 0.12em; text-transform: uppercase;
  padding: 0.4rem 1rem; border-radius: 100px; margin-bottom: 1.6rem;
  animation: fadeUp 0.6s ease both;
}
.hero-eyebrow::before { content: '●'; font-size: 0.5rem; animation: pulse 2s infinite; }
@keyframes pulse { 0%,100%{opacity:1} 50%{opacity:0.3} }
@keyframes fadeUp { from{opacity:0;transform:translateY(24px)} to{opacity:1;transform:translateY(0)} }
.hero h1 {
  font-family: 'Playfair Display', serif;
  font-size: clamp(2.8rem, 5vw, 5rem); font-weight: 900;
  line-height: 1.06; color: #fff; margin-bottom: 1.4rem;
  animation: fadeUp 0.7s 0.1s ease both;
}
.hero h1 em { font-style: normal; color: var(--yellow); }
.hero-sub {
  font-size: 1.05rem; line-height: 1.75; color: rgba(255,255,255,0.72);
  max-width: 480px; margin-bottom: 2.2rem;
  animation: fadeUp 0.7s 0.2s ease both;
}
.hero-actions { display: flex; gap: 1rem; flex-wrap: wrap; animation: fadeUp 0.7s 0.3s ease both; }
.btn-primary {
  background: var(--yellow); color: var(--purple-deep);
  font-weight: 700; font-size: 1rem;
  padding: 0.9rem 2rem; border-radius: 100px; border: none;
  cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;
}
.btn-primary:hover { transform: translateY(-3px); box-shadow: 0 12px 32px rgba(245,197,24,0.4); }
.btn-ghost {
  background: transparent; color: #fff; font-weight: 500; font-size: 1rem;
  padding: 0.9rem 2rem; border-radius: 100px;
  border: 1.5px solid rgba(255,255,255,0.35);
  cursor: pointer; transition: border-color 0.2s, background 0.2s;
  box-sizing: border-box;
}
.btn-ghost:hover { border-color: var(--yellow); background: rgba(245,197,24,0.08); }
.hero-stats { display: flex; gap: 2.5rem; margin-top: 3rem; animation: fadeUp 0.7s 0.4s ease both; }
.stat-num { font-family: 'Playfair Display', serif; font-size: 2rem; font-weight: 900; color: var(--yellow); line-height: 1; }
.stat-label { font-size: 0.76rem; color: rgba(255,255,255,0.52); letter-spacing: 0.04em; margin-top: 0.2rem; }
.hero-visual {
  display: flex; flex-direction: column; gap: 1rem;
  animation: fadeUp 0.8s 0.2s ease both; position: relative; z-index: 1;
}
.card-float {
  background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.13);
  backdrop-filter: blur(8px); border-radius: 20px; padding: 1.4rem 1.6rem;
  transition: transform 0.3s;
}
.card-float:hover { transform: translateY(-4px); }
.card-float.yellow-card { background: var(--yellow); border-color: var(--yellow); }
.card-icon { font-size: 1.8rem; margin-bottom: 0.6rem; }
.card-title { font-weight: 700; font-size: 1rem; margin-bottom: 0.3rem; color: #fff; }
.card-float.yellow-card .card-title { color: var(--purple-deep); }
.card-desc { font-size: 0.82rem; color: rgba(255,255,255,0.65); line-height: 1.5; }
.card-float.yellow-card .card-desc { color: rgba(58,26,107,0.72); }
.card-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }

/* SECTIONS */
.section { padding: 6rem 5vw; }
.section-label {
  display: inline-block; font-size: 0.73rem; font-weight: 700; letter-spacing: 0.14em;
  text-transform: uppercase; color: var(--purple);
  border-bottom: 2px solid var(--yellow); padding-bottom: 0.2rem; margin-bottom: 1rem;
}
.section-title {
  font-family: 'Playfair Display', serif; font-size: clamp(1.9rem, 3.5vw, 3rem);
  font-weight: 900; line-height: 1.15; color: var(--purple-deep); margin-bottom: 1.2rem;
}
.section-body { font-size: 1rem; line-height: 1.8; color: #4a3670; max-width: 560px; }

/* PROBLEM */
.problem-grid { display: grid; grid-template-columns: 1fr 1fr; align-items: center; gap: 5rem; max-width: 1200px; margin: 0 auto; }
.problem-cards { display: flex; flex-direction: column; gap: 1rem; margin-top: 2rem; }
.prob-card {
  background: var(--off-white); border-left: 4px solid var(--purple);
  border-radius: 0 14px 14px 0; padding: 1.1rem 1.4rem;
  display: flex; align-items: flex-start; gap: 1rem;
  transition: border-color 0.2s, transform 0.2s;
}
.prob-card:hover { border-color: var(--yellow); transform: translateX(4px); }
.prob-card:nth-child(2) { border-color: var(--pink-accent); }
.prob-card:nth-child(3) { border-color: var(--lilac); }
.prob-icon { font-size: 1.5rem; flex-shrink: 0; }
.prob-text strong { display: block; font-weight: 700; color: var(--purple-deep); margin-bottom: 0.2rem; font-size: 0.95rem; }
.prob-text span { font-size: 0.83rem; color: #6b5490; line-height: 1.5; }
.prob-stat-block { background: var(--purple-deep); border-radius: 24px; padding: 2.4rem; color: #fff; display: flex; flex-direction: column; gap: 1.6rem; }
.big-num { font-family: 'Playfair Display', serif; font-size: 3.8rem; font-weight: 900; color: var(--yellow); line-height: 1; }
.big-desc { font-size: 0.84rem; color: rgba(255,255,255,0.62); margin-top: 0.3rem; line-height: 1.5; }
.stat-divider { border: none; border-top: 1px solid rgba(255,255,255,0.1); }

/* SOLUTION */
.solution-bg { background: var(--purple-deep); }
.solution-inner { max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: 1fr 1fr; gap: 5rem; align-items: center; }
.solution-inner .section-title { color: #fff; }
.solution-inner .section-body { color: rgba(255,255,255,0.65); }
.solution-pills { display: flex; flex-wrap: wrap; gap: 0.6rem; margin-top: 1.4rem; }
.pill {
  background: rgba(245,197,24,0.15); color: var(--yellow);
  border: 1px solid rgba(245,197,24,0.35);
  font-size: 0.78rem; font-weight: 600; letter-spacing: 0.04em;
  padding: 0.32rem 0.9rem; border-radius: 100px;
}
.feature-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
.feat {
  background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.1);
  border-radius: 18px; padding: 1.4rem; transition: background 0.2s, transform 0.2s;
}
.feat:hover { background: rgba(255,255,255,0.12); transform: translateY(-4px); }
.feat-icon { font-size: 1.7rem; margin-bottom: 0.7rem; }
.feat-name { font-weight: 700; color: #fff; margin-bottom: 0.3rem; font-size: 0.93rem; }
.feat-info { font-size: 0.8rem; color: rgba(255,255,255,0.55); line-height: 1.5; }

/* HOW IT WORKS */
.how-inner { max-width: 1200px; margin: 0 auto; }
.how-header { text-align: center; margin-bottom: 4rem; }
.how-header .section-body { margin: 0 auto; text-align: center; }
.steps { display: grid; grid-template-columns: repeat(4,1fr); gap: 1.5rem; position: relative; }
.steps::before {
  content: ''; position: absolute; top: 2.4rem; left: 10%; right: 10%;
  height: 2px; background: linear-gradient(90deg, var(--purple), var(--yellow)); z-index: 0;
}
.step {
  background: var(--off-white); border-radius: 20px; padding: 2rem 1.4rem 1.6rem;
  text-align: center; position: relative; z-index: 1;
  border: 1px solid #e0d6f5; transition: box-shadow 0.2s, transform 0.2s;
}
.step:hover { transform: translateY(-6px); box-shadow: 0 20px 40px rgba(91,45,142,0.12); }
.step-num {
  width: 46px; height: 46px; background: var(--purple); color: #fff;
  font-family: 'Playfair Display', serif; font-size: 1.15rem; font-weight: 900;
  border-radius: 50%; display: flex; align-items: center; justify-content: center;
  margin: 0 auto 1rem; border: 3px solid var(--white); box-shadow: 0 0 0 3px var(--purple);
}
.step:nth-child(2) .step-num { background: var(--yellow); color: var(--purple-deep); box-shadow: 0 0 0 3px var(--yellow); }
.step:nth-child(3) .step-num { background: var(--pink-accent); color: var(--purple-deep); box-shadow: 0 0 0 3px var(--pink-accent); }
.step:nth-child(4) .step-num { background: var(--lilac); color: var(--purple-deep); box-shadow: 0 0 0 3px var(--lilac); }
.step-icon { font-size: 1.6rem; margin-bottom: 0.6rem; }
.step-name { font-weight: 700; color: var(--purple-deep); margin-bottom: 0.4rem; font-size: 0.93rem; }
.step-desc { font-size: 0.8rem; color: #6b5490; line-height: 1.5; }

/* MARKET */
.market-bg { background: var(--off-white); }
.market-inner { max-width: 1200px; margin: 0 auto; }
.market-header { margin-bottom: 3rem; }
.market-cards { display: grid; grid-template-columns: repeat(3,1fr); gap: 1.5rem; }
.mcard { border-radius: 22px; padding: 2rem; display: flex; flex-direction: column; gap: 0.8rem; }
.mcard.tam { background: var(--purple-deep); }
.mcard.sam { background: var(--purple); }
.mcard.som { background: var(--yellow); }
.mcard-label { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase; opacity: 0.65; }
.mcard.tam .mcard-label, .mcard.sam .mcard-label { color: #fff; }
.mcard.som .mcard-label { color: var(--purple-deep); }
.mcard-name { font-weight: 700; font-size: 1rem; }
.mcard.tam .mcard-name, .mcard.sam .mcard-name { color: #fff; }
.mcard.som .mcard-name { color: var(--purple-deep); }
.mcard-value { font-family: 'Playfair Display', serif; font-size: 2.8rem; font-weight: 900; line-height: 1; }
.mcard.tam .mcard-value { color: var(--yellow); }
.mcard.sam .mcard-value { color: var(--yellow-light); }
.mcard.som .mcard-value { color: var(--purple-deep); }
.mcard-desc { font-size: 0.8rem; line-height: 1.5; }
.mcard.tam .mcard-desc, .mcard.sam .mcard-desc { color: rgba(255,255,255,0.65); }
.mcard.som .mcard-desc { color: rgba(58,26,107,0.72); }

/* ROADMAP */
.roadmap-inner { max-width: 1100px; margin: 0 auto; }
.roadmap-header { text-align: center; margin-bottom: 3.5rem; }
.timeline { display: grid; grid-template-columns: repeat(5,1fr); position: relative; }
.timeline::before {
  content: ''; position: absolute; top: 50px; left: 0; right: 0; height: 3px;
  background: linear-gradient(90deg, var(--purple) 0%, var(--yellow) 100%);
}
.tnode { display: flex; flex-direction: column; align-items: center; }
.tnode-dot {
  width: 24px; height: 24px; border-radius: 50%;
  background: var(--purple); border: 4px solid var(--white);
  outline: 3px solid var(--purple);
  margin-top: 39px; margin-bottom: 1.2rem; position: relative; z-index: 1;
}
.tnode:nth-child(3) .tnode-dot { background: var(--yellow); outline-color: var(--yellow); }
.tnode:nth-child(5) .tnode-dot { background: var(--pink-accent); outline-color: var(--pink-accent); }
.tnode-card {
  background: var(--off-white); border: 1px solid #e0d6f5;
  border-radius: 16px; padding: 1.2rem; text-align: center; width: 92%;
}
.tnode-year { font-family: 'Playfair Display', serif; font-size: 1.4rem; font-weight: 900; color: var(--purple); margin-bottom: 0.4rem; }
.tnode-schools { font-weight: 700; font-size: 0.86rem; color: var(--purple-deep); }
.tnode-gmv { font-size: 0.77rem; color: #7b5ab8; margin-top: 0.2rem; }

/* STATS STRIP */
.stats-strip { background: var(--purple-deep); padding: 4rem 5vw; }
.stats-strip-inner { max-width: 1000px; margin: 0 auto; display: grid; grid-template-columns: repeat(4,1fr); gap: 2rem; text-align: center; }
.strip-num { font-family: 'Playfair Display', serif; font-size: 3rem; font-weight: 900; color: var(--yellow); line-height: 1; }
.strip-label { font-size: 0.82rem; color: rgba(255,255,255,0.6); margin-top: 0.4rem; line-height: 1.4; }

/* PARTNERSHIPS */
.partner-bg { background: var(--off-white); }
.partner-inner { max-width: 1100px; margin: 0 auto; }
.partner-header { text-align: center; margin-bottom: 2.5rem; }
.partner-logos {
  display: flex; flex-wrap: wrap; justify-content: center;
  gap: 1.2rem 1.8rem; align-items: center;
}
.partner-chip {
  background: #fff; border: 1px solid #ddd6f0;
  border-radius: 10px; padding: 0.7rem 1.4rem;
  font-weight: 700; font-size: 0.85rem; color: var(--purple-deep);
  white-space: nowrap; transition: border-color 0.2s, box-shadow 0.2s;
}
.partner-chip:hover { border-color: var(--purple); box-shadow: 0 4px 14px rgba(91,45,142,0.1); }

/* CTA */
.cta-section {
  background: linear-gradient(135deg, var(--purple-deep) 0%, #6B2FA0 100%);
  padding: 8rem 5vw; text-align: center; position: relative; overflow: hidden;
}
.cta-section::before {
  content: ''; position: absolute; top: -200px; left: 50%; transform: translateX(-50%);
  width: 800px; height: 800px;
  background: radial-gradient(circle, rgba(245,197,24,0.12) 0%, transparent 60%);
  pointer-events: none;
}
.cta-inner { position: relative; z-index: 1; }
.cta-title {
  font-family: 'Playfair Display', serif;
  font-size: clamp(2.2rem, 4.5vw, 4rem);
  font-weight: 900; color: #fff; line-height: 1.1; margin-bottom: 1.2rem;
}
.cta-title em { font-style: normal; color: var(--yellow); }
.cta-sub {
  font-size: 1rem; color: rgba(255,255,255,0.65);
  max-width: 520px; margin: 0 auto 2.4rem; line-height: 1.7;
}
.cta-buttons { display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap; }

/* CONTACT STRIP */
.contact-strip {
  background: var(--yellow); padding: 1.4rem 5vw;
  display: flex; justify-content: center; align-items: center; gap: 3rem; flex-wrap: wrap;
}
.contact-item { display: flex; align-items: center; gap: 0.6rem; color: var(--purple-deep); font-weight: 600; font-size: 0.9rem; }

/* FOOTER */
footer { background: var(--text-dark); color: rgba(255,255,255,0.45); text-align: center; padding: 2rem; font-size: 0.8rem; }
footer strong { color: var(--yellow); }

/* REVEAL */
.reveal { opacity: 0; transform: translateY(30px); transition: opacity 0.7s ease, transform 0.7s ease; }
.reveal.visible { opacity: 1; transform: none; }

/* RESPONSIVE */
@media (max-width: 960px) {
  .hero { grid-template-columns: 1fr; padding-top: 4rem; gap: 2.5rem; }
  .problem-grid, .solution-inner { grid-template-columns: 1fr; gap: 2.5rem; }
  .card-row, .feature-grid { grid-template-columns: 1fr; }
  .steps { grid-template-columns: 1fr 1fr; }
  .steps::before { display: none; }
  .market-cards { grid-template-columns: 1fr; }
  .timeline { grid-template-columns: 1fr 1fr; }
  .timeline::before { display: none; }
  .stats-strip-inner { grid-template-columns: 1fr 1fr; }
  .shout-sub { display: none; }
}
@media (max-width: 600px) {
  .hero { padding-top: 3rem; }
  .steps, .timeline { grid-template-columns: 1fr; }
  .stats-strip-inner { grid-template-columns: 1fr 1fr; }
  .contact-strip { gap: 1.2rem; }
}
</style>
</head>
<body>

<header class="site-header">
  <div class="header-top">
    <div class="header-top-inner">
      <div class="nav-logo">
        <a href="#hero" class="nav-logo-link" style="display:flex;align-items:center;gap:0.65rem;text-decoration:none;color:inherit">
          @if ($kuzzaLogoSvgInline !== '')
            <span class="nav-logo-svg-wrap" role="img" aria-label="KUZZA">{!! $kuzzaLogoSvgInline !!}</span>
          @else
            <img src="{{ $kuzzaLogoUrl }}" alt="KUZZA" class="nav-logo-img" width="320" height="60" fetchpriority="high" decoding="async" loading="eager">
          @endif
        </a>
      </div>
      <nav class="header-nav" id="header-nav" aria-label="Page sections">
        <a href="#offer" data-nav-link>Offer</a>
        <a href="#hero" data-nav-link>Home</a>
        <a href="#problem" data-nav-link>Problem</a>
        <a href="#solution" data-nav-link>Solution</a>
        <a href="#how-it-works" data-nav-link>How it works</a>
        <a href="#market" data-nav-link>Market</a>
        <a href="#roadmap" data-nav-link>Roadmap</a>
        <a href="#impact" data-nav-link>Impact</a>
        <a href="#partners" data-nav-link>Partners</a>
        <a href="#cta" data-nav-link>Contact</a>
      </nav>
      <div class="header-end">
        <button type="button" class="nav-menu-toggle" id="nav-menu-toggle" aria-controls="header-nav" aria-expanded="false" aria-label="Open menu">
          <span></span><span></span><span></span>
        </button>
        <div class="header-actions">
          <button type="button" class="nav-cta-secondary" id="get-started-open">Get Started</button>
          <a href="{{ route('login') }}" class="nav-cta" style="text-decoration:none;display:inline-flex;align-items:center;justify-content:center">Login</a>
        </div>
      </div>
    </div>
  </div>
</header>

<div class="gs-modal" id="get-started-modal" aria-hidden="true" role="dialog" aria-labelledby="gs-modal-title" aria-modal="true">
  <div class="gs-modal-backdrop" id="get-started-backdrop" tabindex="-1"></div>
  <div class="gs-modal-panel">
    <button type="button" class="gs-modal-close" id="get-started-close" aria-label="Close">&times;</button>
    <h2 id="gs-modal-title">Get started</h2>
    <p class="gs-modal-intro">To get started fill the form below and we will get back to you.</p>
    <div class="gs-form-error" id="get-started-form-error" hidden></div>
    <form id="get-started-form" novalidate>
      @csrf
      <div class="gs-field">
        <label for="gs-name">Name</label>
        <input type="text" id="gs-name" name="name" required autocomplete="name" maxlength="255">
        <div class="error" id="gs-name-err" hidden></div>
      </div>
      <div class="gs-field">
        <label for="gs-organisation">School or organisation</label>
        <input type="text" id="gs-organisation" name="organisation" required autocomplete="organization" maxlength="255">
        <div class="error" id="gs-organisation-err" hidden></div>
      </div>
      <div class="gs-field">
        <label for="gs-phone">Phone number</label>
        <input type="tel" id="gs-phone" name="phone" required autocomplete="tel" maxlength="60">
        <div class="error" id="gs-phone-err" hidden></div>
      </div>
      <div class="gs-field">
        <label for="gs-email">Email</label>
        <input type="email" id="gs-email" name="email" required autocomplete="email" maxlength="255">
        <div class="error" id="gs-email-err" hidden></div>
      </div>
      <button type="submit" class="gs-submit" id="get-started-submit">Submit</button>
    </form>
    <div class="gs-success" id="get-started-success" hidden></div>
  </div>
</div>

<div class="landing-promo" id="offer">
  <div class="shout-inner">
    <div class="shout-badge">🔥 Limited Offer</div>
    <div class="shout-text">GET KUZZA ERP <span class="shout-free">FOR FREE</span></div>
    <div class="shout-sub">Zero cost. Fully integrated. Built for rural &amp; special needs schools.</div>
    <a href="#" class="shout-btn js-open-get-started" role="button">Claim Your Free ERP &rarr;</a>
  </div>
  <div class="shout-ticker">
    <div class="ticker-track">
      <span>🎓 FREE KUZZA ERP FOR QUALIFYING SCHOOLS</span>
      <span>✦</span>
      <span>🚀 ONBOARD TODAY — ZERO COST</span>
      <span>✦</span>
      <span>📦 NATIONWIDE DELIVERY TO ALL 47 COUNTIES</span>
      <span>✦</span>
      <span>📱 USSD &amp; SMS ACCESS FOR EVERY PARENT</span>
      <span>✦</span>
      <span>♿ INCLUSIVE ASSISTIVE LEARNING MATERIALS</span>
      <span>✦</span>
      <span>🎓 FREE KUZZA ERP FOR QUALIFYING SCHOOLS</span>
      <span>✦</span>
      <span>🚀 ONBOARD TODAY — ZERO COST</span>
      <span>✦</span>
      <span>📦 NATIONWIDE DELIVERY TO ALL 47 COUNTIES</span>
      <span>✦</span>
      <span>📱 USSD &amp; SMS ACCESS FOR EVERY PARENT</span>
      <span>✦</span>
      <span>♿ INCLUSIVE ASSISTIVE LEARNING MATERIALS</span>
      <span>✦</span>
    </div>
  </div>
</div>

<!-- HERO -->
<section class="hero" id="hero">
  <div class="hero-content">
    <div class="hero-eyebrow">KUZZA Project &middot; Kenya</div>
    <h1>Education belongs to <em>every</em> child.</h1>
    <p class="hero-sub">MyBidhaa's KUZZA platform eliminates barriers in educational procurement — connecting rural schools, special needs institutions, parents, and vendors in one seamless ecosystem.</p>
    <div class="hero-actions">
      <a href="#" class="js-open-get-started" role="button" style="text-decoration:none">
        <button class="btn-primary">Onboard Your School</button>
      </a>
      <a href="#how-it-works" class="btn-ghost" style="text-decoration:none;display:inline-flex;align-items:center;justify-content:center">Learn How It Works</a>
    </div>
    <div class="hero-stats">
      <div class="stat">
        <div class="stat-num">30K+</div>
        <div class="stat-label">Rural Schools in Kenya</div>
      </div>
      <div class="stat">
        <div class="stat-num">KES 7B</div>
        <div class="stat-label">Total Market Opportunity</div>
      </div>
      <div class="stat">
        <div class="stat-num">Free</div>
        <div class="stat-label">ERP for Qualifying Schools</div>
      </div>
    </div>
  </div>
  <div class="hero-visual">
    <div class="card-float yellow-card">
      <div class="card-icon">🎓</div>
      <div class="card-title">Student-First, Always</div>
      <div class="card-desc">Every feature ensures learners — including those with special needs — receive the right materials on time.</div>
    </div>
    <div class="card-row">
      <div class="card-float">
        <div class="card-icon">📱</div>
        <div class="card-title">USSD Access</div>
        <div class="card-desc">No smartphone needed — parents order via SMS or USSD.</div>
      </div>
      <div class="card-float">
        <div class="card-icon">🚚</div>
        <div class="card-title">Nationwide Delivery</div>
        <div class="card-desc">All 47 counties reached through our logistics network.</div>
      </div>
    </div>
    <div class="card-float">
      <div class="card-icon">🏫</div>
      <div class="card-title">Free School ERP Included</div>
      <div class="card-desc">Rural &amp; special needs schools receive KUZZA's ERP at zero cost — fully integrated with the marketplace.</div>
    </div>
  </div>
</section>

<!-- PROBLEM -->
<section class="section" id="problem">
  <div class="problem-grid">
    <div class="reveal">
      <div class="section-label">The Problem</div>
      <h2 class="section-title">Kenya's education system leaves too many behind.</h2>
      <p class="section-body">Fragmented supply chains, digital exclusion, and the absence of inclusive procurement tools mean millions of learners in rural and underserved communities still lack essential materials.</p>
      <div class="problem-cards">
        <div class="prob-card">
          <div class="prob-icon">⚠️</div>
          <div class="prob-text">
            <strong>Fragmented Procurement</strong>
            <span>Multiple vendors cause 30% delays and a 15% drop in exam performance for vulnerable students.</span>
          </div>
        </div>
        <div class="prob-card">
          <div class="prob-icon">📵</div>
          <div class="prob-text">
            <strong>Digital Exclusion</strong>
            <span>Schools lose up to 20% of budgets due to manual, inefficient procurement processes.</span>
          </div>
        </div>
        <div class="prob-card">
          <div class="prob-icon">♿</div>
          <div class="prob-text">
            <strong>No Assistive Materials</strong>
            <span>Most schools lack Braille books, adaptive devices, and specialised learning tools.</span>
          </div>
        </div>
      </div>
    </div>
    <div class="prob-stat-block reveal">
      <div>
        <div class="big-num">30%</div>
        <div class="big-desc">Procurement delays affecting students with disabilities and those in rural communities — UNICEF (2021)</div>
      </div>
      <hr class="stat-divider">
      <div>
        <div class="big-num">20%</div>
        <div class="big-desc">Average school budget lost to uncoordinated procurement — OECD Education Report 2019</div>
      </div>
      <hr class="stat-divider">
      <div>
        <div class="big-num">3,000</div>
        <div class="big-desc">Special needs schools in Kenya without reliable access to assistive learning tools — UNESCO (2020)</div>
      </div>
    </div>
  </div>
</section>

<!-- SOLUTION -->
<section class="section solution-bg" id="solution">
  <div class="solution-inner">
    <div class="reveal">
      <div class="section-label" style="color:var(--yellow);border-color:var(--yellow)">The Solution</div>
      <h2 class="section-title">One platform. Every stakeholder. Zero barriers.</h2>
      <p class="section-body">KUZZA by MyBidhaa is Kenya's first inclusive educational procurement ecosystem — purpose-built for schools and communities that traditional platforms ignore.</p>
      <div class="solution-pills">
        <span class="pill">Inclusive Marketplace</span>
        <span class="pill">USSD Checkout</span>
        <span class="pill">Free School ERP</span>
        <span class="pill">Nationwide Logistics</span>
        <span class="pill">Special Needs Category</span>
        <span class="pill">Resource Tracking</span>
      </div>
    </div>
    <div class="feature-grid reveal">
      <div class="feat">
        <div class="feat-icon">🛒</div>
        <div class="feat-name">Inclusive Marketplace</div>
        <div class="feat-info">Centralised access to books, assistive tools, uniforms, and school supplies — all in one place.</div>
      </div>
      <div class="feat">
        <div class="feat-icon">📟</div>
        <div class="feat-name">Basic Phone Access</div>
        <div class="feat-info">Parents in remote areas order and pay via SMS or USSD — no smartphone required.</div>
      </div>
      <div class="feat">
        <div class="feat-icon">🖥️</div>
        <div class="feat-name">Free KUZZA ERP</div>
        <div class="feat-info">Qualifying schools get a fully integrated ERP for procurement, records, and operations at no cost.</div>
      </div>
      <div class="feat">
        <div class="feat-icon">📊</div>
        <div class="feat-name">Transparent Tracking</div>
        <div class="feat-info">Digital reporting ensures donated or subsidised resources reach exactly the right learners.</div>
      </div>
    </div>
  </div>
</section>

<!-- HOW IT WORKS -->
<section class="section" id="how-it-works">
  <div class="how-inner">
    <div class="how-header reveal">
      <div class="section-label" style="display:block;text-align:center;border:none;color:var(--purple)">How It Works</div>
      <h2 class="section-title" style="text-align:center">From assignment to delivery<br>in four simple steps.</h2>
      <p class="section-body">KUZZA connects every link in the educational supply chain — automatically.</p>
    </div>
    <div class="steps reveal">
      <div class="step">
        <div class="step-num">1</div>
        <div class="step-icon">🏫</div>
        <div class="step-name">School Assigns</div>
        <div class="step-desc">Schools list required items per student via the KUZZA ERP or MyBidhaa portal.</div>
      </div>
      <div class="step">
        <div class="step-num">2</div>
        <div class="step-icon">📱</div>
        <div class="step-name">Parent Notified</div>
        <div class="step-desc">Parents receive requirements via app, WhatsApp, or SMS instantly.</div>
      </div>
      <div class="step">
        <div class="step-num">3</div>
        <div class="step-icon">💳</div>
        <div class="step-name">Parent Pays</div>
        <div class="step-desc">Confirmed via USSD or SMS. Payment via M-Pesa, KUZZA Wallet, or school fees.</div>
      </div>
      <div class="step">
        <div class="step-num">4</div>
        <div class="step-icon">📦</div>
        <div class="step-name">Delivered</div>
        <div class="step-desc">Materials shipped nationwide via MyBidhaa's logistics network, direct to school.</div>
      </div>
    </div>
  </div>
</section>

<!-- PARTNERSHIPS -->
<section class="section partner-bg" id="partners">
  <div class="partner-inner">
    <div class="partner-header reveal">
      <div class="section-label" style="display:block;text-align:center;border:none">Strategic Partners</div>
      <h2 class="section-title" style="text-align:center">Backed by trusted names in Kenyan education.</h2>
    </div>
    <div class="partner-logos reveal">
      <div class="partner-chip">📚 Longhorn Publishers</div>
      <div class="partner-chip">📡 Safaricom</div>
      <div class="partner-chip">📦 Posta Kenya</div>
      <div class="partner-chip">💻 LOHO Learning</div>
      <div class="partner-chip">🏫 Project Elimu</div>
      <div class="partner-chip">💬 SomaChat</div>
      <div class="partner-chip">🚀 Shadow Logistics</div>
      <div class="partner-chip">🖊️ Sai Office Kenya</div>
      <div class="partner-chip">🎵 MusicLand</div>
      <div class="partner-chip">📖 Chaka Bookshop</div>
      <div class="partner-chip">💳 Aspira (Lipa Baadaye)</div>
      <div class="partner-chip">🗄️ TierData</div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="cta-section" id="cta">
  <div class="cta-inner reveal">
    <h2 class="cta-title">Ready to transform<br>education access <em>together?</em></h2>
    <p class="cta-sub">Whether you're a school, NGO, vendor, or investor — KUZZA has a place for you in Kenya's most inclusive education ecosystem.</p>
    <div class="cta-buttons">
      <a href="#" class="js-open-get-started" role="button" style="text-decoration:none">
        <button class="btn-primary" style="font-size:1.05rem;padding:1rem 2.4rem">Onboard Your School — Free</button>
      </a>
      <a href="mailto:kuzza@mybidhaa.com" style="text-decoration:none">
        <button class="btn-ghost" style="font-size:1.05rem;padding:1rem 2.4rem">Talk to Our Team</button>
      </a>
    </div>
  </div>
</section>

<!-- CONTACT STRIP -->
<div class="contact-strip">
  <div class="contact-item"><span>🌐</span> www.mybidhaa.com</div>
  <div class="contact-item"><span>✉️</span> kuzza@mybidhaa.com</div>
  <div class="contact-item"><span>📞</span> +254 729 000 403</div>
</div>

<!-- FOOTER -->
<footer>
  <p>&copy; 2026 <strong>MyBidhaa</strong> &mdash; Simplifying Educational Procurement. KUZZA Project, Kenya.</p>
</footer>

<script>
  const reveals = document.querySelectorAll('.reveal');
  const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach((entry, i) => {
      if (entry.isIntersecting) {
        setTimeout(() => entry.target.classList.add('visible'), i * 80);
        revealObserver.unobserve(entry.target);
      }
    });
  }, { threshold: 0.1 });
  reveals.forEach(el => revealObserver.observe(el));

  (function () {
    const sectionIds = ['offer', 'hero', 'problem', 'solution', 'how-it-works', 'market', 'roadmap', 'impact', 'partners', 'cta'];
    const sections = sectionIds.map(function (id) { return document.getElementById(id); }).filter(Boolean);
    const navLinks = document.querySelectorAll('[data-nav-link]');
    const toggle = document.getElementById('nav-menu-toggle');
    const siteHeader = document.querySelector('.site-header');

    function setActiveNav(id) {
      navLinks.forEach(function (a) {
        a.classList.toggle('active', a.getAttribute('href') === '#' + id);
      });
    }

    function updateScrollSpy() {
      if (!sections.length) return;
      const headerH = siteHeader ? siteHeader.offsetHeight : 88;
      const scrollY = window.scrollY || document.documentElement.scrollTop;
      const y = scrollY + headerH + 12;
      var current = sections[0].id;
      for (var i = 0; i < sections.length; i++) {
        var sec = sections[i];
        var secTop = sec.getBoundingClientRect().top + scrollY;
        if (secTop <= y) current = sec.id;
      }
      setActiveNav(current);
    }

    if (toggle) {
      toggle.addEventListener('click', function () {
        var open = document.body.classList.toggle('nav-open');
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        toggle.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
      });
    }

    navLinks.forEach(function (a) {
      a.addEventListener('click', function () {
        document.body.classList.remove('nav-open');
        if (toggle) {
          toggle.setAttribute('aria-expanded', 'false');
          toggle.setAttribute('aria-label', 'Open menu');
        }
      });
    });

    window.addEventListener('scroll', function () {
      window.requestAnimationFrame(updateScrollSpy);
    }, { passive: true });
    window.addEventListener('resize', updateScrollSpy);
    updateScrollSpy();
  })();

  (function () {
    var modal = document.getElementById('get-started-modal');
    if (!modal) return;
    var openBtn = document.getElementById('get-started-open');
    var closeBtn = document.getElementById('get-started-close');
    var backdrop = document.getElementById('get-started-backdrop');
    var form = document.getElementById('get-started-form');
    var successEl = document.getElementById('get-started-success');
    var formErr = document.getElementById('get-started-form-error');
    var submitBtn = document.getElementById('get-started-submit');
    var csrfMeta = document.querySelector('meta[name="csrf-token"]');
    var postUrl = @json(route('landing.get-started'));
    var errMap = { name: 'gs-name-err', organisation: 'gs-organisation-err', phone: 'gs-phone-err', email: 'gs-email-err' };

    function clearFieldErrors() {
      Object.keys(errMap).forEach(function (key) {
        var el = document.getElementById(errMap[key]);
        if (el) { el.hidden = true; el.textContent = ''; }
      });
    }

    function openModal() {
      modal.classList.add('is-open');
      modal.setAttribute('aria-hidden', 'false');
      document.body.style.overflow = 'hidden';
    }

    function closeModal() {
      modal.classList.remove('is-open');
      modal.setAttribute('aria-hidden', 'true');
      document.body.style.overflow = '';
    }

    function resetModal() {
      form.reset();
      form.hidden = false;
      successEl.hidden = true;
      successEl.textContent = '';
      formErr.hidden = true;
      formErr.textContent = '';
      submitBtn.disabled = false;
      clearFieldErrors();
    }

    function onKey(e) {
      if (e.key === 'Escape' && modal.classList.contains('is-open')) {
        closeModal();
        resetModal();
      }
    }

    if (openBtn) openBtn.addEventListener('click', function () { resetModal(); openModal(); });
    document.querySelectorAll('.js-open-get-started').forEach(function (trigger) {
      trigger.addEventListener('click', function (e) {
        e.preventDefault();
        resetModal();
        openModal();
      });
    });
    if (closeBtn) closeBtn.addEventListener('click', function () { closeModal(); resetModal(); });
    if (backdrop) backdrop.addEventListener('click', function () { closeModal(); resetModal(); });
    document.addEventListener('keydown', onKey);

    if (form) {
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        formErr.hidden = true;
        formErr.textContent = '';
        clearFieldErrors();
        submitBtn.disabled = true;

        var fd = new FormData(form);
        var headers = { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' };
        if (csrfMeta) headers['X-CSRF-TOKEN'] = csrfMeta.getAttribute('content');

        fetch(postUrl, { method: 'POST', headers: headers, body: fd })
          .then(function (res) { return res.json().then(function (data) { return { res: res, data: data }; }); })
          .then(function (_ref) {
            var res = _ref.res;
            var data = _ref.data || {};
            if (res.ok) {
              form.hidden = true;
              successEl.textContent = data.message || 'Thank you, we will get in touch soon.';
              successEl.hidden = false;
              setTimeout(function () {
                closeModal();
                resetModal();
              }, 2500);
              return;
            }
            if (res.status === 422 && data.errors) {
              Object.keys(data.errors).forEach(function (key) {
                var id = errMap[key];
                if (!id) return;
                var el = document.getElementById(id);
                if (el && data.errors[key] && data.errors[key][0]) {
                  el.textContent = data.errors[key][0];
                  el.hidden = false;
                }
              });
              submitBtn.disabled = false;
              return;
            }
            formErr.textContent = data.message || 'Something went wrong. Please try again.';
            formErr.hidden = false;
            submitBtn.disabled = false;
          })
          .catch(function () {
            formErr.textContent = 'Network error. Please try again.';
            formErr.hidden = false;
            submitBtn.disabled = false;
          });
      });
    }
  })();
</script>
</body>
</html>