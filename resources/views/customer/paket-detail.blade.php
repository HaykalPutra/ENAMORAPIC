<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ $paket->nama_paket }} - Enamorapic</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<style>
    body {
        margin: 0;
        color: #111827;
        background: #dfe3eb;
        opacity: 0;
        transform: translateY(10px);
        transition: opacity .45s ease, transform .45s ease;
    }
    body.page-ready { opacity: 1; transform: translateY(0); }
    body.page-leave { opacity: 0; transform: translateY(8px); }

    .dynamic-backdrop {
        position: fixed;
        inset: 0;
        z-index: 0;
        overflow: hidden;
        pointer-events: none;
        background: linear-gradient(160deg, #f6f7fb 0%, #eef1f7 100%);
    }
    .dynamic-backdrop img,
    .dynamic-backdrop video {
        position: absolute;
        inset: -3%;
        width: 106%;
        height: 106%;
        object-fit: cover;
        filter: blur(22px) saturate(1.08);
        transform: scale(1.08);
        opacity: 0;
        transition: opacity .55s ease;
    }
    .dynamic-backdrop img.active,
    .dynamic-backdrop video.active {
        opacity: .55;
    }
    .dynamic-backdrop::after {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at center, rgba(255,255,255,.28) 0%, rgba(236,240,247,.66) 55%, rgba(228,233,241,.9) 100%);
    }

    .web-shell {
        max-width: 1360px;
        margin: 40px auto;
        padding: 0 20px;
        position: relative;
        z-index: 2;
    }

    .screen-card {
        background: #ffffff;
        border-radius: 28px;
        overflow: hidden;
        box-shadow: 0 30px 80px rgba(17, 24, 39, 0.15);
        position: relative;
        isolation: isolate;
    }

    .screen-card-bg {
        position: absolute;
        inset: 0;
        z-index: 1;
        overflow: hidden;
        pointer-events: none;
    }
    .screen-card-bg img,
    .screen-card-bg video {
        position: absolute;
        inset: -2%;
        width: 104%;
        height: 104%;
        object-fit: cover;
        filter: blur(22px) saturate(1.08);
        transform: scale(1.05);
        opacity: 0;
        transition: opacity .45s ease;
    }
    .screen-card-bg img.active,
    .screen-card-bg video.active {
        opacity: .85;
    }
    .screen-card-bg::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(160deg, rgba(244, 247, 252, 0.72) 0%, rgba(235, 240, 247, 0.8) 100%);
    }

    .screen-card > .row {
        position: relative;
        z-index: 2;
    }

    .left-panel {
        background: rgba(255, 255, 255, 0.72);
        border-right: 1px solid #eef2f7;
        backdrop-filter: blur(7px);
    }

    .top-nav {
        padding: 22px 26px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .back-btn {
        width: 42px;
        height: 42px;
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #f4f6fb;
        color: #111827;
        text-decoration: none;
        transition: all .25s ease;
    }
    .back-btn:hover { transform: translateY(-2px); background: #eaeef6; color: #0f172a; }

    .media-stage {
        position: relative;
        padding: 0 22px;
    }

    #paketCarousel {
        border-radius: 22px;
        overflow: hidden;
    }

    .main-media,
    .media-video,
    .media-iframe {
        width: 100%;
        height: 510px;
        object-fit: cover;
        background: #000;
    }

    .media-stage::after {
        content: '';
        position: absolute;
        left: 22px;
        right: 22px;
        bottom: 0;
        height: 180px;
        border-radius: 0 0 22px 22px;
        background: linear-gradient(to top, rgba(15, 23, 42, 0.58) 0%, rgba(15, 23, 42, 0.16) 48%, rgba(15, 23, 42, 0) 100%);
        pointer-events: none;
        z-index: 2;
    }

    .carousel.carousel-fade .carousel-item {
        opacity: 0;
        transition: opacity .8s ease;
    }
    .carousel.carousel-fade .carousel-item.active { opacity: 1; }

    .carousel-indicators {
        margin-bottom: 14px;
        z-index: 3;
    }
    .carousel-indicators [data-bs-target] {
        width: 18px;
        height: 6px;
        border-radius: 99px;
        border: 0;
        background-color: rgba(255,255,255,.55);
        transition: all .25s ease;
    }
    .carousel-indicators .active {
        width: 30px;
        background-color: #fff;
    }

    .thumb-row {
        padding: 16px 24px 8px;
        display: flex;
        gap: 10px;
        overflow-x: auto;
        justify-content: center;
        scrollbar-width: thin;
    }

    .thumb-btn {
        border: 0;
        background: transparent;
        padding: 0;
        border-radius: 12px;
        transition: transform .2s ease;
    }
    .thumb-btn:hover { transform: translateY(-2px); }

    .thumb-media {
        width: 92px;
        height: 66px;
        border-radius: 12px;
        object-fit: cover;
        border: 2px solid transparent;
        transition: all .2s ease;
    }
    .thumb-btn.active .thumb-media {
        border-color: #111827;
        box-shadow: 0 8px 22px rgba(17,24,39,.18);
    }

    .right-panel {
        padding: 28px 28px 24px;
        background: rgba(255, 255, 255, 0.76);
        min-height: 100%;
        display: flex;
        flex-direction: column;
        backdrop-filter: blur(7px);
    }

    .badge-pill {
        background: #f1f5f9;
        color: #6b7280;
        letter-spacing: 1.2px;
        font-size: .72rem;
        font-weight: 700;
        border-radius: 999px;
        display: inline-block;
        padding: 7px 14px;
        margin-bottom: 18px;
    }

    .pkg-title {
        font-size: 2rem;
        font-weight: 700;
        margin: 0 0 6px;
        color: #0f172a;
    }

    .price-label { color: #9aa3b2; font-size: .98rem; margin: 0; }
    .price-main { color: #0f172a; font-size: 2rem; font-weight: 800; margin-bottom: 20px; }

    .section-head {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 14px;
    }
    .section-line {
        width: 28px;
        height: 3px;
        border-radius: 4px;
        background: linear-gradient(90deg, #c9a14d 0%, #f1d494 100%);
    }
    .section-title {
        margin: 0;
        font-weight: 700;
        font-size: 1.25rem;
        color: #172033;
    }

    .summary-text {
        color: #637084;
        margin-bottom: 18px;
    }

    .detail-list {
        display: grid;
        gap: 10px;
    }
    .detail-item {
        display: flex;
        align-items: center;
        gap: 12px;
        background: #f8fafc;
        border: 1px solid #edf1f6;
        border-radius: 12px;
        padding: 11px 12px;
        color: #374151;
        font-size: .95rem;
    }
    .detail-icon {
        width: 30px;
        height: 30px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #0f172a;
        background: #e9eef5;
        flex: 0 0 auto;
    }

    .action-row {
        margin-top: auto;
        display: flex;
        gap: 10px;
        padding-top: 18px;
        border-top: 1px solid #eef2f7;
    }

    .share-btn {
        width: 54px;
        height: 54px;
        border-radius: 16px;
        border: 1px solid #d7deea;
        background: #fff;
        color: #5b6676;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        text-decoration: none;
        transition: all .2s ease;
    }
    .share-btn:hover { transform: translateY(-2px); color: #0f172a; }

    .book-btn {
        flex: 1;
        height: 54px;
        border-radius: 16px;
        border: 0;
        background: linear-gradient(135deg, #121f3f 0%, #0f1a34 100%);
        color: #fff;
        font-weight: 700;
        letter-spacing: .2px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 12px 28px rgba(15, 26, 52, .35);
        transition: all .25s ease;
    }
    .book-btn:hover { transform: translateY(-2px); color: #fff; }

    .page-transition-overlay {
        position: fixed;
        inset: 0;
        pointer-events: none;
        opacity: 0;
        background: radial-gradient(circle at center, rgba(255,255,255,0) 0%, rgba(17,24,39,.18) 100%);
        transition: opacity .3s ease;
        z-index: 9999;
    }
    .page-transition-overlay.active { opacity: 1; }

    @media (max-width: 1199.98px) {
        .pkg-title { font-size: 1.75rem; }
        .price-main { font-size: 1.75rem; }
    }

    @media (max-width: 991.98px) {
        .web-shell { margin: 0 auto; padding: 0; }
        .screen-card { border-radius: 0; }
        .left-panel { border-right: 0; }
        .main-media, .media-video, .media-iframe { height: 360px; }
        .media-stage::after { left: 0; right: 0; border-radius: 0; }
        .media-stage { padding: 0; }
        .top-nav { padding: 16px 18px; }
        .thumb-row { padding: 14px 16px 8px; }
        .right-panel { padding: 20px 16px 18px; }
    }
</style>
</head>
<body>
<div class="dynamic-backdrop" id="dynamicBackdrop">
    <img id="dynamicBackdropImage" alt="Backdrop media" />
    <video id="dynamicBackdropVideo" muted playsinline preload="metadata"></video>
</div>

<div class="web-shell">
    <div class="screen-card">
        <div class="screen-card-bg" id="screenCardBg">
            <img id="cardBgImage" alt="Card background" />
            <video id="cardBgVideo" muted playsinline preload="metadata" loop></video>
        </div>

        <div class="row g-0">
            <div class="col-lg-7 left-panel">
                <div class="top-nav">
                    <a href="{{ route('home') }}#katalog" class="back-btn page-transition-link" aria-label="Kembali">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <span></span>
                </div>

                <div class="media-stage">
                    <div id="paketCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="3600" data-bs-touch="true">
                        <div class="carousel-indicators">
                            @foreach($mediaItems as $index => $media)
                                <button type="button" data-bs-target="#paketCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}" aria-label="Slide {{ $index + 1 }}"></button>
                            @endforeach
                        </div>

                        <div class="carousel-inner">
                            @foreach($mediaItems as $index => $media)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}" data-media-type="{{ $media['type'] }}" data-media-url="{{ $media['url'] }}">
                                    @if($media['type'] === 'video')
                                        <video class="media-video" autoplay muted loop playsinline preload="metadata" controls>
                                            <source src="{{ $media['url'] }}">
                                        </video>
                                    @elseif($media['type'] === 'instagram')
                                        <iframe class="media-iframe" src="{{ $media['url'] }}" title="Video Instagram paket" allow="autoplay; encrypted-media; picture-in-picture" allowfullscreen></iframe>
                                    @elseif($media['type'] === 'youtube')
                                        <iframe class="media-iframe" src="{{ $media['url'] }}" title="Video paket" allow="autoplay; encrypted-media; picture-in-picture" allowfullscreen></iframe>
                                    @else
                                        <img src="{{ $media['url'] }}" class="main-media" alt="Media paket {{ $paket->nama_paket }}">
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                @if(count($mediaItems) > 1)
                    <div class="thumb-row">
                        @foreach($mediaItems as $index => $media)
                            <button type="button" class="thumb-btn {{ $index === 0 ? 'active' : '' }}" data-slide-to="{{ $index }}">
                                @if($media['type'] === 'image')
                                    <img src="{{ $media['url'] }}" class="thumb-media" alt="thumb {{ $index + 1 }}">
                                @else
                                    <div class="thumb-media d-flex align-items-center justify-content-center bg-dark text-white">
                                        <i class="bi bi-play-fill"></i>
                                    </div>
                                @endif
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="col-lg-5">
                <div class="right-panel">
                    <h1 class="pkg-title">{{ $paket->nama_paket }}</h1>
                    <p class="price-label">Mulai dari</p>
                    <div class="price-main">Rp{{ number_format((float) $paket->harga, 0, ',', '.') }}</div>

                    <div class="section-head">
                        <span class="section-line"></span>
                        <h2 class="section-title">Detail Layanan</h2>
                    </div>

                    @php
                        $details = collect($paket->detail_points ?? [])->values();
                        $summary = $details->first() ?: (trim(strip_tags((string) $paket->deskripsi)) ?: ('Paket ' . strtolower($paket->kategori)));
                        $benefits = $details->count() > 1 ? $details->slice(1)->values() : $details;
                    @endphp

                    <p class="summary-text">{{ $summary }}</p>

                    <div class="detail-list">
                        @if($benefits->isNotEmpty())
                            @foreach($benefits as $i => $point)
                                <div class="detail-item">
                                    <span class="detail-icon">
                                        <i class="bi {{ $i % 3 === 0 ? 'bi-clock' : ($i % 3 === 1 ? 'bi-cloud-arrow-down' : 'bi-gear') }}"></i>
                                    </span>
                                    <span>{{ $point }}</span>
                                </div>
                            @endforeach
                        @else
                            <div class="detail-item">
                                <span class="detail-icon"><i class="bi bi-info-circle"></i></span>
                                <span>Detail paket akan ditampilkan sesuai input dari admin/CEO.</span>
                            </div>
                        @endif
                    </div>

                    <div class="action-row">
                        <a href="https://wa.me/?text={{ urlencode('Lihat paket: ' . $paket->nama_paket . ' - ' . route('paket.show', $paket->paket_id)) }}" class="share-btn" target="_blank" rel="noopener">
                            <i class="bi bi-share"></i>
                        </a>
                        <a href="{{ route('home', ['book' => $paket->paket_id]) }}" class="book-btn page-transition-link">
                            Booking
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-transition-overlay" id="pageTransitionOverlay"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    window.addEventListener('load', () => {
        document.body.classList.add('page-ready');
    });

    const carouselEl = document.getElementById('paketCarousel');
    const cardBgImage = document.getElementById('cardBgImage');
    const cardBgVideo = document.getElementById('cardBgVideo');
    const backdropImage = document.getElementById('dynamicBackdropImage');
    const backdropVideo = document.getElementById('dynamicBackdropVideo');
    const carousel = new bootstrap.Carousel(carouselEl, {
        interval: false,
        ride: false,
        pause: true,
        wrap: true
    });

    const thumbs = Array.from(document.querySelectorAll('.thumb-btn'));
    let imageSlideTimer = null;

    function clearImageTimer() {
        if (imageSlideTimer) {
            clearTimeout(imageSlideTimer);
            imageSlideTimer = null;
        }
    }

    function syncThumb(activeIndex) {
        thumbs.forEach((btn, idx) => btn.classList.toggle('active', idx === activeIndex));
    }

    function resetCardBackground() {
        cardBgImage.classList.remove('active');
        cardBgVideo.classList.remove('active');
        cardBgVideo.pause();
    }

    function updateCardBackgroundBySlide(activeItem) {
        if (!activeItem) return;

        const mediaType = activeItem.getAttribute('data-media-type') || '';
        const mediaUrl = activeItem.getAttribute('data-media-url') || '';

        if (mediaType === 'image' && mediaUrl) {
            if (cardBgImage.src !== mediaUrl) {
                cardBgImage.src = mediaUrl;
            }
            cardBgImage.classList.add('active');
            cardBgVideo.classList.remove('active');
            cardBgVideo.pause();
            return;
        }

        if (mediaType === 'video' && mediaUrl) {
            if (cardBgVideo.src !== mediaUrl) {
                cardBgVideo.src = mediaUrl;
            }
            cardBgVideo.currentTime = 0;
            cardBgVideo.play().catch(() => {});
            cardBgVideo.classList.add('active');
            cardBgImage.classList.remove('active');
            return;
        }

        const activeSlides = Array.from(carouselEl.querySelectorAll('.carousel-item'));
        const activeIndex = activeSlides.findIndex(slide => slide.classList.contains('active'));
        const thumbImage = thumbs[activeIndex]?.querySelector('img');
        if (thumbImage && thumbImage.src) {
            if (cardBgImage.src !== thumbImage.src) {
                cardBgImage.src = thumbImage.src;
            }
            cardBgImage.classList.add('active');
            cardBgVideo.classList.remove('active');
            cardBgVideo.pause();
        }
    }

    function resetBackdrop() {
        backdropImage.classList.remove('active');
        backdropVideo.classList.remove('active');
        backdropVideo.pause();
    }

    function updateBackdropBySlide(activeItem) {
        if (!activeItem) return;

        const mediaType = activeItem.getAttribute('data-media-type') || '';
        const mediaUrl = activeItem.getAttribute('data-media-url') || '';

        if (mediaType === 'image' && mediaUrl) {
            if (backdropImage.src !== mediaUrl) {
                backdropImage.src = mediaUrl;
            }
            backdropImage.classList.add('active');
            backdropVideo.classList.remove('active');
            backdropVideo.pause();
            return;
        }

        if (mediaType === 'video' && mediaUrl) {
            if (backdropVideo.src !== mediaUrl) {
                backdropVideo.src = mediaUrl;
            }
            backdropVideo.currentTime = 0;
            backdropVideo.play().catch(() => {});
            backdropVideo.classList.add('active');
            backdropImage.classList.remove('active');
            return;
        }

        const imgInside = activeItem.querySelector('img');
        if (imgInside && imgInside.src) {
            if (backdropImage.src !== imgInside.src) {
                backdropImage.src = imgInside.src;
            }
            backdropImage.classList.add('active');
            backdropVideo.classList.remove('active');
            backdropVideo.pause();
        }
    }

    function stopAllVideos() {
        carouselEl.querySelectorAll('video').forEach(video => {
            video.pause();
            video.currentTime = 0;
            video.onended = null;
        });
        carouselEl.querySelectorAll('iframe').forEach(frame => {
            const src = frame.getAttribute('src');
            frame.setAttribute('src', src);
        });
    }

    function handleActiveSlideBehavior() {
        clearImageTimer();

        const activeItem = carouselEl.querySelector('.carousel-item.active');
        if (!activeItem) return;

        updateCardBackgroundBySlide(activeItem);
        updateBackdropBySlide(activeItem);

        const video = activeItem.querySelector('video');
        if (video) {
            video.play().catch(() => {});
            video.onended = () => {
                carousel.next();
            };
            return;
        }

        const iframe = activeItem.querySelector('iframe');
        if (iframe) {
            return;
        }

        imageSlideTimer = setTimeout(() => {
            carousel.next();
        }, 3500);
    }

    carouselEl.addEventListener('slid.bs.carousel', (e) => {
        syncThumb(e.to ?? 0);
        stopAllVideos();
        handleActiveSlideBehavior();
    });

    thumbs.forEach((btn) => {
        btn.addEventListener('click', () => {
            const index = Number(btn.getAttribute('data-slide-to') || 0);
            clearImageTimer();
            carousel.to(index);
            syncThumb(index);
        });
    });

    resetCardBackground();
    resetBackdrop();
    handleActiveSlideBehavior();

    const transitionOverlay = document.getElementById('pageTransitionOverlay');
    document.querySelectorAll('.page-transition-link').forEach(link => {
        link.addEventListener('click', (event) => {
            const href = link.getAttribute('href');
            if (!href || href.startsWith('#')) {
                return;
            }

            event.preventDefault();
            transitionOverlay.classList.add('active');
            document.body.classList.add('page-leave');

            setTimeout(() => {
                window.location.href = href;
            }, 280);
        });
    });
</script>
</body>
</html>
