<?php
require_once __DIR__ . '/../includes/lang.php';
$pageTitle = $lang === 'es' ? 'Ekab Tulum — Condominios de Lujo' : 'Ekab Tulum — Luxury Condominiums';
include __DIR__ . '/../includes/header.php';
?>

<!-- Property Hero -->
<section class="prop-hero">
    <div class="prop-hero-bg-icon">🌴</div>
    <div class="prop-hero-content">
        <a href="/solumare/properties/index.php" class="prop-hero-back">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
            <?= t('prop_back') ?>
        </a>
        <div class="prop-hero-badge">
            🌿 LEED Gold · <?= $lang === 'es' ? 'Sostenibilidad Certificada' : 'Certified Sustainability' ?>
        </div>
        <h1 class="prop-hero-title">Ekab Tulum</h1>
        <div class="prop-hero-location">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
            Tulum, Quintana Roo, México
        </div>
        <div class="prop-hero-tags">
            <span class="prop-tag">🏗️ <?= $lang === 'es' ? 'Entrega Mar. 2026' : 'Delivery Mar. 2026' ?></span>
            <span class="prop-tag">🏠 190 <?= t('units') ?></span>
            <span class="prop-tag">🛏 1–4 <?= t('rooms') ?></span>
            <span class="prop-tag">📐 67–823 m²</span>
            <span class="prop-tag">⭐ <?= $lang === 'es' ? 'Servicios 5 Estrellas' : '5-Star Services' ?></span>
        </div>
    </div>
</section>

<!-- Property Detail -->
<div class="prop-detail-grid">
    <!-- Main Content -->
    <div class="prop-main">

        <!-- Description -->
        <div class="prop-section prop-desc reveal">
            <h2 class="prop-section-title"><span>📖</span> <?= $lang === 'es' ? 'Descripción' : 'Description' ?></h2>
            <?php if ($lang === 'es'): ?>
                <p>En Ekab, el lujo y la naturaleza se fusionan perfectamente, ofreciendo condominios personalizados con acabados de alto nivel, amenidades excepcionales, servicios hoteleros cinco estrellas y una visión sostenible con certificación <strong>LEED Gold</strong>.</p>
                <p>Con <strong>190 unidades</strong> distribuidas en diferentes tipologías — desde estudios de 67 m² hasta penthouses de 823 m² — Ekab Tulum representa la oportunidad de inversión más exclusiva en la Riviera Maya.</p>
                <p>El desarrollo cuenta con un innovador <strong>cenote artificial de 200 m²</strong> y una espectacular <strong>alberca natural de 1,000 m²</strong>, creando un ecosistema de lujo único en su tipo. Cada detalle ha sido diseñado pensando en maximizar tanto la experiencia de vida como el retorno de inversión.</p>
                <p>Con un <strong>rendimiento proyectado del 10% anual en dólares</strong> y una plusvalía esperada del 12–15% anual, Ekab Tulum es la inversión inmobiliaria ideal en uno de los destinos más codiciados del mundo.</p>
            <?php else: ?>
                <p>At Ekab, luxury and nature merge perfectly, offering personalized condominiums with high-end finishes, exceptional amenities, five-star hotel services, and a sustainable vision with <strong>LEED Gold</strong> certification.</p>
                <p>With <strong>190 units</strong> in various typologies — from 67 m² studios to 823 m² penthouses — Ekab Tulum represents the most exclusive investment opportunity in the Riviera Maya.</p>
                <p>The development features an innovative <strong>200 m² artificial cenote</strong> and a spectacular <strong>1,000 m² natural pool</strong>, creating a one-of-a-kind luxury ecosystem. Every detail has been designed to maximize both the living experience and return on investment.</p>
                <p>With a <strong>projected return of 10% annually in US dollars</strong> and expected capital appreciation of 12–15% per year, Ekab Tulum is the ideal real estate investment in one of the world's most coveted destinations.</p>
            <?php endif; ?>
        </div>

        <!-- Amenities -->
        <div class="prop-section reveal">
            <h2 class="prop-section-title"><span>✨</span> <?= t('prop_amenities') ?> <small style="font-size:0.8rem;font-weight:400;color:var(--text-light);font-family:var(--font-sans);">(+30)</small></h2>
            <div class="amenities-grid">
                <?php
                $amenities = $lang === 'es' ? [
                    ['🌊','Cenote Artificial 200m²'],
                    ['🏊','Alberca Natural 1,000m²'],
                    ['🏙️','Rooftop Bar + Alberca'],
                    ['💪','Fitness Center Premium'],
                    ['🧖','Spa & Wellness'],
                    ['🔥','Temazcal'],
                    ['🧘','Yoga Studio'],
                    ['🍽️','Restaurante & Snack Bar'],
                    ['💼','Coworking Lounge'],
                    ['🏖️','Beach Club Privado'],
                    ['🚗','Valet Parking'],
                    ['🛎️','Concierge 24/7'],
                    ['🛏️','Room Service'],
                    ['🔒','Seguridad 24/7'],
                    ['👨‍🍳','Chef Personal'],
                    ['🌟','Experiencias Exclusivas'],
                    ['♻️','Certificación LEED Gold'],
                    ['🏡','Jardines Tropicales'],
                    ['🧺','Lavandería Premium'],
                    ['🎪','Sala de Eventos'],
                    ['🚿','Área de Alberca Infantil'],
                    ['🎮','Sala de Juegos'],
                    ['📦','Bodegas Privadas'],
                    ['🛒','Mini Market Deli Store'],
                    ['🚲','Bicicletas Eléctricas'],
                    ['🌿','Huerto Orgánico'],
                    ['🎯','Canchas Deportivas'],
                    ['📡','WiFi Premium'],
                    ['🌬️','A/C Central'],
                    ['🌱','Energía Solar'],
                ] : [
                    ['🌊','200m² Artificial Cenote'],
                    ['🏊','1,000m² Natural Pool'],
                    ['🏙️','Rooftop Bar + Pool'],
                    ['💪','Premium Fitness Center'],
                    ['🧖','Spa & Wellness'],
                    ['🔥','Temazcal'],
                    ['🧘','Yoga Studio'],
                    ['🍽️','Restaurant & Snack Bar'],
                    ['💼','Coworking Lounge'],
                    ['🏖️','Private Beach Club'],
                    ['🚗','Valet Parking'],
                    ['🛎️','24/7 Concierge'],
                    ['🛏️','Room Service'],
                    ['🔒','24/7 Security'],
                    ['👨‍🍳','Personal Chef'],
                    ['🌟','Exclusive Experiences'],
                    ['♻️','LEED Gold Certification'],
                    ['🏡','Tropical Gardens'],
                    ['🧺','Premium Laundry'],
                    ['🎪','Event Hall'],
                    ['🚿','Children\'s Pool Area'],
                    ['🎮','Game Room'],
                    ['📦','Private Storage'],
                    ['🛒','Mini Market Deli Store'],
                    ['🚲','Electric Bicycles'],
                    ['🌿','Organic Garden'],
                    ['🎯','Sports Courts'],
                    ['📡','Premium WiFi'],
                    ['🌬️','Central A/C'],
                    ['🌱','Solar Energy'],
                ];
                foreach ($amenities as $a): ?>
                    <div class="amenity-item">
                        <span class="amenity-icon"><?= $a[0] ?></span>
                        <span><?= $a[1] ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Gallery -->
        <div class="prop-section reveal">
            <h2 class="prop-section-title"><span>📸</span> <?= t('prop_gallery') ?></h2>
            <div class="prop-gallery-grid">
                <?php
                $galleryItems = $lang === 'es' ? [
                    ['🌊','Cenote Artificial'],['🏊','Alberca Natural'],['🏙️','Rooftop'],
                    ['🌴','Jardines'],['🛎️','Lobby'],['💪','Fitness'],
                    ['🏖️','Beach Club'],['🍽️','Restaurante'],['🛏️','Suite Master'],
                ] : [
                    ['🌊','Artificial Cenote'],['🏊','Natural Pool'],['🏙️','Rooftop'],
                    ['🌴','Gardens'],['🛎️','Lobby'],['💪','Fitness'],
                    ['🏖️','Beach Club'],['🍽️','Restaurant'],['🛏️','Master Suite'],
                ];
                foreach ($galleryItems as $g): ?>
                    <div class="gallery-cell">
                        <div class="gallery-cell-inner">
                            <span><?= $g[0] ?></span>
                            <span><?= $g[1] ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Location -->
        <div class="prop-section reveal">
            <h2 class="prop-section-title"><span>📍</span> <?= t('prop_location') ?></h2>
            <div style="background:linear-gradient(135deg,var(--blue-dark),var(--blue-deep));border-radius:var(--radius-lg);height:280px;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:12px;color:rgba(255,255,255,0.6);">
                <span style="font-size:4rem">🗺️</span>
                <span style="font-size:1rem;font-weight:600;color:var(--white);">Tulum, Quintana Roo</span>
                <span style="font-size:0.82rem;letter-spacing:0.1em;text-transform:uppercase;">Riviera Maya, México</span>
                <a href="https://maps.google.com/?q=Tulum,Quintana+Roo" target="_blank" rel="noopener" class="btn btn-primary btn-sm" style="margin-top:8px;">
                    <?= $lang === 'es' ? 'Ver en Google Maps' : 'View on Google Maps' ?>
                </a>
            </div>
        </div>
    </div>

    <!-- Invest Sidebar -->
    <div class="prop-invest-card reveal reveal-right">
        <div class="prop-invest-header">
            <h3>💰 <?= t('prop_invest') ?></h3>
            <div class="invest-stat">
                <span class="invest-stat-label"><?= t('from') ?></span>
                <span class="invest-stat-value highlight">$4,000,000 MXN</span>
            </div>
            <div class="invest-stat">
                <span class="invest-stat-label"><?= t('roi') ?></span>
                <span class="invest-stat-value green">10% USD</span>
            </div>
            <div class="invest-stat">
                <span class="invest-stat-label"><?= $lang === 'es' ? 'Plusvalía Proyectada' : 'Projected Appreciation' ?></span>
                <span class="invest-stat-value green">12–15%</span>
            </div>
            <div class="invest-stat">
                <span class="invest-stat-label"><?= t('units') ?></span>
                <span class="invest-stat-value">190</span>
            </div>
            <div class="invest-stat">
                <span class="invest-stat-label"><?= t('rooms') ?></span>
                <span class="invest-stat-value">1 – 4</span>
            </div>
            <div class="invest-stat">
                <span class="invest-stat-label"><?= t('area') ?></span>
                <span class="invest-stat-value">67 – 823 m²</span>
            </div>
            <div class="invest-stat">
                <span class="invest-stat-label"><?= t('delivery') ?></span>
                <span class="invest-stat-value"><?= $lang === 'es' ? 'Torre B: Mar. 2026' : 'Tower B: Mar. 2026' ?></span>
            </div>
            <div class="invest-stat">
                <span class="invest-stat-label"><?= t('certification') ?></span>
                <span class="invest-stat-value">🌿 LEED Gold</span>
            </div>
        </div>
        <div class="prop-invest-body">
            <a href="/solumare/index.php#contact" class="btn btn-gold"><?= t('contact_now') ?></a>
            <a href="https://wa.me/529842341660?text=Hola%2C%20me%20interesa%20información%20sobre%20Ekab%20Tulum" target="_blank" rel="noopener" class="btn btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                WhatsApp
            </a>
            <p class="prop-invest-note">
                <?= $lang === 'es'
                    ? '* Datos proyectados. Rendimientos pasados no garantizan resultados futuros.'
                    : '* Projected data. Past performance does not guarantee future results.' ?>
            </p>
        </div>
    </div>
</div>

<button class="scroll-top" id="scrollTop" aria-label="Volver arriba">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="18 15 12 9 6 15"/></svg>
</button>

<?php include __DIR__ . '/../includes/footer.php'; ?>
