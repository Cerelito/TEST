<?php
require_once __DIR__ . '/../includes/lang.php';
$pageTitle = $lang === 'es' ? 'La Salina Isla Mujeres — Frente al Mar' : 'La Salina Isla Mujeres — Oceanfront Living';
include __DIR__ . '/../includes/header.php';
?>

<section class="prop-hero">
    <div class="prop-hero-bg-icon">🏝️</div>
    <div class="prop-hero-content">
        <a href="/solumare/properties/index.php" class="prop-hero-back">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
            <?= t('prop_back') ?>
        </a>
        <div class="prop-hero-badge">
            🐠 <?= $lang === 'es' ? 'Isla Mujeres · Pueblo Mágico' : 'Isla Mujeres · Pueblo Mágico' ?>
        </div>
        <h1 class="prop-hero-title">La Salina<br>Isla Mujeres</h1>
        <div class="prop-hero-location">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
            Isla Mujeres, Quintana Roo, México
        </div>
        <div class="prop-hero-tags">
            <span class="prop-tag">🏛️ <?= $lang === 'es' ? '2 Torres' : '2 Towers' ?></span>
            <span class="prop-tag">🛏 2–3 <?= t('rooms') ?></span>
            <span class="prop-tag">📐 106–170 m²</span>
            <span class="prop-tag">🌊 <?= $lang === 'es' ? 'Frente al Caribe' : 'Caribbean Oceanfront' ?></span>
            <span class="prop-tag">🏖️ Club Punta Limón</span>
        </div>
    </div>
</section>

<div class="prop-detail-grid">
    <div class="prop-main">

        <div class="prop-section prop-desc reveal">
            <h2 class="prop-section-title"><span>📖</span> <?= $lang === 'es' ? 'Descripción' : 'Description' ?></h2>
            <?php if ($lang === 'es'): ?>
                <p>La Salina redefine la vida frente al mar, ofreciendo apartamentos de 2 y 3 recámaras con vistas panorámicas <strong>sin obstrucciones</strong> al Mar Caribe mexicano en el corazón de Isla Mujeres, declarado Pueblo Mágico.</p>
                <p>El proyecto está conformado por dos elegantes torres — <strong>Pelícano</strong> y <strong>Fragata</strong> — diseñadas por el reconocido <strong>Grupo Artila</strong> en colaboración con <strong>Artigas Arquitectos</strong>, firma con más de 75 años de legado arquitectónico en México.</p>
                <p>La arquitectura contemporánea combina materiales de bajo mantenimiento, ventanas y puertas premium, y soluciones de eficiencia energética, garantizando confort, durabilidad y sustentabilidad a largo plazo.</p>
                <p>Los residentes tienen acceso exclusivo al <strong>Club Punta Limón Sun Club</strong>, un club privado con restaurante-bar, alberca panorámica con vistas al Caribe, áreas de descanso y acceso directo a playa privada.</p>
            <?php else: ?>
                <p>La Salina redefines oceanfront living, offering 2 and 3-bedroom apartments with <strong>unobstructed panoramic views</strong> of the Mexican Caribbean in the heart of Isla Mujeres, a designated Pueblo Mágico.</p>
                <p>The project consists of two elegant towers — <strong>Pelícano</strong> and <strong>Fragata</strong> — designed by the renowned <strong>Grupo Artila</strong> in collaboration with <strong>Artigas Arquitectos</strong>, a firm with over 75 years of architectural legacy in Mexico.</p>
                <p>Contemporary architecture combines low-maintenance materials, premium windows and doors, and energy-efficiency solutions, ensuring long-term comfort, durability, and sustainability.</p>
                <p>Residents have exclusive access to the <strong>Punta Limón Sun Club</strong>, a private club featuring a restaurant-bar, panoramic pool overlooking the Caribbean, relaxation areas, and direct private beach access.</p>
            <?php endif; ?>
        </div>

        <!-- Towers -->
        <div class="prop-section reveal">
            <h2 class="prop-section-title"><span>🏛️</span> <?= $lang === 'es' ? 'Torres del Proyecto' : 'Project Towers' ?></h2>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                <?php
                $towers = [
                    ['🦅', $lang === 'es' ? 'Torre Pelícano' : 'Pelícano Tower',
                     $lang === 'es' ? 'Unidades tipo A y A2. Apartamentos de 2 recámaras con vistas panorámicas al Caribe. Desde 106 m² hasta 135 m².' : 'Units type A and A2. 2-bedroom apartments with panoramic Caribbean views. From 106 m² to 135 m².'],
                    ['🦅', $lang === 'es' ? 'Torre Fragata' : 'Fragata Tower',
                     $lang === 'es' ? 'Unidades tipo B y C. Apartamentos de 3 recámaras con acabados premium. De 150 m² hasta 170 m². Terrazas amplias.' : 'Units type B and C. 3-bedroom apartments with premium finishes. From 150 m² to 170 m². Spacious terraces.'],
                ];
                foreach ($towers as $tower): ?>
                    <div style="background:linear-gradient(135deg,var(--blue-dark),var(--blue-deep));border-radius:var(--radius-lg);padding:28px;color:var(--white);">
                        <div style="font-size:2.5rem;margin-bottom:12px;"><?= $tower[0] ?></div>
                        <h3 style="font-family:var(--font-serif);font-size:1.2rem;color:var(--gold-light);margin-bottom:10px;"><?= $tower[1] ?></h3>
                        <p style="font-size:0.9rem;color:rgba(255,255,255,0.7);line-height:1.65;"><?= $tower[2] ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Amenities -->
        <div class="prop-section reveal">
            <h2 class="prop-section-title"><span>✨</span> <?= t('prop_amenities') ?></h2>
            <div class="amenities-grid">
                <?php
                $amenities = $lang === 'es' ? [
                    ['🏊','Albercas Privadas'],
                    ['♨️','Jacuzzi Panorámico'],
                    ['🧘','Yoga Deck Frente al Mar'],
                    ['🎾','Racquet Club'],
                    ['🧒','Área Infantil'],
                    ['🍖','BBQ & Lounge'],
                    ['🚗','Estacionamiento Privado'],
                    ['🏖️','Club Punta Limón'],
                    ['🍹','Restaurante-Bar'],
                    ['🌊','Acceso a Playa Privada'],
                    ['🌅','Alberca Panorámica'],
                    ['🏃','Andador Fitness'],
                    ['🌿','Jardines Diseñados'],
                    ['🔒','Seguridad Privada'],
                    ['📡','WiFi de Alta Velocidad'],
                    ['⚡','Eficiencia Energética'],
                ] : [
                    ['🏊','Private Pools'],
                    ['♨️','Panoramic Jacuzzi'],
                    ['🧘','Oceanfront Yoga Deck'],
                    ['🎾','Racquet Club'],
                    ['🧒','Children\'s Area'],
                    ['🍖','BBQ & Lounge'],
                    ['🚗','Private Parking'],
                    ['🏖️','Punta Limón Club'],
                    ['🍹','Restaurant-Bar'],
                    ['🌊','Private Beach Access'],
                    ['🌅','Panoramic Pool'],
                    ['🏃','Fitness Trail'],
                    ['🌿','Designed Gardens'],
                    ['🔒','Private Security'],
                    ['📡','High-Speed WiFi'],
                    ['⚡','Energy Efficiency'],
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
                    ['🌊','Vista al Caribe'],['🏊','Alberca'],['🏖️','Beach Club'],
                    ['🌅','Atardecer'],['🛏️','Recámara'],['🍽️','Comedor'],
                    ['🛁','Baño Principal'],['🌴','Terraza'],['🏛️','Lobby'],
                ] : [
                    ['🌊','Caribbean View'],['🏊','Pool'],['🏖️','Beach Club'],
                    ['🌅','Sunset'],['🛏️','Bedroom'],['🍽️','Dining Room'],
                    ['🛁','Main Bath'],['🌴','Terrace'],['🏛️','Lobby'],
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

        <!-- Architecture -->
        <div class="prop-section reveal">
            <h2 class="prop-section-title"><span>🏗️</span> <?= $lang === 'es' ? 'Arquitectura & Diseño' : 'Architecture & Design' ?></h2>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <?php
                $architects = [
                    ['🎨','Grupo Artila', $lang === 'es' ? 'Diseño arquitectónico conceptual y desarrollo creativo del proyecto' : 'Conceptual architectural design and creative project development'],
                    ['🏛️','Artigas Arquitectos', $lang === 'es' ? 'Firma con +75 años de legado arquitectónico en México' : 'Firm with 75+ years of architectural legacy in Mexico'],
                ];
                foreach ($architects as $arch): ?>
                    <div style="padding:20px;background:var(--gray-100);border-radius:var(--radius-md);border-left:3px solid var(--blue-mid);">
                        <div style="font-size:2rem;margin-bottom:8px;"><?= $arch[0] ?></div>
                        <h4 style="font-family:var(--font-serif);color:var(--blue-deep);margin-bottom:6px;"><?= $arch[1] ?></h4>
                        <p style="font-size:0.85rem;color:var(--text-light);line-height:1.5;"><?= $arch[2] ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Location -->
        <div class="prop-section reveal">
            <h2 class="prop-section-title"><span>📍</span> <?= t('prop_location') ?></h2>
            <div style="background:linear-gradient(135deg,var(--blue-dark),var(--blue-deep));border-radius:var(--radius-lg);height:280px;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:12px;color:rgba(255,255,255,0.6);">
                <span style="font-size:4rem">🗺️</span>
                <span style="font-size:1rem;font-weight:600;color:var(--white);">Isla Mujeres, Quintana Roo</span>
                <span style="font-size:0.82rem;letter-spacing:0.1em;text-transform:uppercase;color:rgba(255,255,255,0.5);"><?= $lang === 'es' ? 'Costa Este · Pueblo Mágico' : 'East Coast · Pueblo Mágico' ?></span>
                <a href="https://maps.google.com/?q=Isla+Mujeres,Quintana+Roo" target="_blank" rel="noopener" class="btn btn-primary btn-sm" style="margin-top:8px;">
                    <?= $lang === 'es' ? 'Ver en Google Maps' : 'View on Google Maps' ?>
                </a>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="prop-invest-card reveal reveal-right">
        <div class="prop-invest-header">
            <h3>💰 <?= t('prop_invest') ?></h3>
            <div class="invest-stat">
                <span class="invest-stat-label"><?= t('from') ?></span>
                <span class="invest-stat-value highlight"><?= $lang === 'es' ? 'Consultar' : 'Contact Us' ?></span>
            </div>
            <div class="invest-stat">
                <span class="invest-stat-label"><?= $lang === 'es' ? 'Plusvalía Proyectada' : 'Projected Appreciation' ?></span>
                <span class="invest-stat-value green">12–15%</span>
            </div>
            <div class="invest-stat">
                <span class="invest-stat-label"><?= $lang === 'es' ? 'Torres' : 'Towers' ?></span>
                <span class="invest-stat-value">Pelícano + Fragata</span>
            </div>
            <div class="invest-stat">
                <span class="invest-stat-label"><?= t('rooms') ?></span>
                <span class="invest-stat-value">2 – 3</span>
            </div>
            <div class="invest-stat">
                <span class="invest-stat-label"><?= t('area') ?></span>
                <span class="invest-stat-value">106 – 170 m²</span>
            </div>
            <div class="invest-stat">
                <span class="invest-stat-label"><?= $lang === 'es' ? 'Tipos de Unidad' : 'Unit Types' ?></span>
                <span class="invest-stat-value">A, A2, B, C</span>
            </div>
            <div class="invest-stat">
                <span class="invest-stat-label"><?= $lang === 'es' ? 'Arquitectos' : 'Architects' ?></span>
                <span class="invest-stat-value" style="font-size:0.88rem;">Grupo Artila</span>
            </div>
            <div class="invest-stat">
                <span class="invest-stat-label"><?= $lang === 'es' ? 'Esquema' : 'Scheme' ?></span>
                <span class="invest-stat-value" style="font-size:0.88rem;"><?= $lang === 'es' ? 'Renta Vacacional' : 'Vacation Rental' ?></span>
            </div>
        </div>
        <div class="prop-invest-body">
            <a href="/solumare/index.php#contact" class="btn btn-gold"><?= t('contact_now') ?></a>
            <a href="https://wa.me/529842341660?text=Hola%2C%20me%20interesa%20información%20sobre%20La%20Salina%20Isla%20Mujeres" target="_blank" rel="noopener" class="btn btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                WhatsApp
            </a>
            <p class="prop-invest-note">
                <?= $lang === 'es'
                    ? '* Datos proyectados. Sujeto a disponibilidad y condiciones de mercado.'
                    : '* Projected data. Subject to availability and market conditions.' ?>
            </p>
        </div>
    </div>
</div>

<button class="scroll-top" id="scrollTop" aria-label="Volver arriba">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="18 15 12 9 6 15"/></svg>
</button>

<?php include __DIR__ . '/../includes/footer.php'; ?>
