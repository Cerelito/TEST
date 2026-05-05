<?php
require_once __DIR__ . '/../includes/lang.php';
$pageTitle = $lang === 'es' ? 'Propiedades — Catálogo de Inversión' : 'Properties — Investment Catalog';
$isProperties = true;
include __DIR__ . '/../includes/header.php';

$properties = [
    [
        'slug'     => 'ekab-tulum',
        'emoji'    => '🌴',
        'badge'    => $lang === 'es' ? 'Disponible' : 'Available',
        'cert'     => 'LEED Gold',
        'zone'     => 'tulum',
        'location' => 'Tulum, Quintana Roo',
        'name'     => 'Ekab Tulum',
        'desc'     => $lang === 'es'
            ? 'Condominios de lujo con certificación LEED Gold en el corazón de Tulum. Servicios hoteleros 5 estrellas y más de 30 amenidades premium.'
            : 'LEED Gold luxury condominiums in the heart of Tulum. 5-star hotel services and over 30 premium amenities.',
        'units'    => '190',
        'rooms'    => '1–4',
        'area'     => '67–823',
        'price'    => '$4M MXN',
        'roi'      => '10%',
        'roi_label'=> 'ROI USD',
    ],
    [
        'slug'     => 'la-salina',
        'emoji'    => '🏝️',
        'badge'    => $lang === 'es' ? 'Pre-venta' : 'Pre-sale',
        'cert'     => null,
        'zone'     => 'isla',
        'location' => 'Isla Mujeres, Quintana Roo',
        'name'     => 'La Salina Isla Mujeres',
        'desc'     => $lang === 'es'
            ? 'Apartamentos frente al Caribe en Isla Mujeres. Vistas panorámicas sin obstrucciones, dos torres de diseño arquitectónico premiado y Club Punta Limón.'
            : 'Apartments overlooking the Caribbean in Isla Mujeres. Unobstructed panoramic views, two award-winning architectural towers, and Punta Limón Club.',
        'units'    => '2 Torres',
        'rooms'    => '2–3',
        'area'     => '106–170',
        'price'    => $lang === 'es' ? 'Consultar' : 'Contact Us',
        'roi'      => '12–15%',
        'roi_label'=> $lang === 'es' ? 'Plusvalía' : 'Appreciation',
    ],
];
?>

<!-- Hero -->
<div class="props-list-hero">
    <div style="position:relative;z-index:2;">
        <span class="section-tag" style="color:var(--gold-light);"><?= t('nav_properties') ?></span>
        <h1 class="section-title light" style="margin-top:12px;"><?= t('all_properties') ?></h1>
        <p class="section-sub light"><?= t('feat_sub') ?></p>
    </div>
</div>

<!-- Filters + Grid -->
<section class="section" style="background:var(--off-white);">
    <div class="container">
        <div class="props-filters reveal">
            <button class="filter-btn active" data-filter="all"><?= t('filter_all') ?></button>
            <button class="filter-btn" data-filter="tulum"><?= t('filter_tulum') ?></button>
            <button class="filter-btn" data-filter="isla"><?= t('filter_isla') ?></button>
            <button class="filter-btn" data-filter="cabos"><?= t('filter_cabos') ?></button>
        </div>

        <div class="properties-grid" id="propGrid">
            <?php foreach ($properties as $i => $p): ?>
            <div class="prop-card reveal <?= $i > 0 ? 'reveal-delay-' . $i : '' ?>" data-zone="<?= $p['zone'] ?>">
                <div class="prop-card-img">
                    <div class="prop-card-img-inner">
                        <span class="prop-card-img-icon"><?= $p['emoji'] ?></span>
                        <span class="prop-card-img-sub"><?= $p['location'] ?></span>
                    </div>
                    <div class="prop-card-overlay"></div>
                    <span class="prop-card-badge"><?= $p['badge'] ?></span>
                    <?php if ($p['cert']): ?><span class="prop-card-cert"><?= $p['cert'] ?></span><?php endif; ?>
                </div>
                <div class="prop-card-body">
                    <div class="prop-card-location">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        <?= $p['location'] ?>
                    </div>
                    <h3 class="prop-card-title"><?= $p['name'] ?></h3>
                    <p class="prop-card-desc"><?= $p['desc'] ?></p>
                    <div class="prop-card-specs">
                        <div class="spec-item">
                            <span class="spec-value"><?= $p['units'] ?></span>
                            <span class="spec-label"><?= t('units') ?></span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-value"><?= $p['rooms'] ?></span>
                            <span class="spec-label"><?= t('rooms') ?></span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-value"><?= $p['area'] ?></span>
                            <span class="spec-label">m² <?= t('area') ?></span>
                        </div>
                    </div>
                    <div class="prop-card-invest">
                        <div>
                            <span class="invest-price-label"><?= t('from') ?></span>
                            <div class="invest-price" style="font-size:<?= strlen($p['price']) > 8 ? '1.1rem' : '1.6rem' ?>"><?= $p['price'] ?></div>
                        </div>
                        <div class="invest-roi">
                            <span class="invest-roi-label"><?= $p['roi_label'] ?></span>
                            <div class="invest-roi-value"><?= $p['roi'] ?></div>
                        </div>
                    </div>
                    <div class="prop-card-actions">
                        <a href="/solumare/properties/<?= $p['slug'] ?>.php" class="btn btn-primary btn-sm"><?= t('btn_details') ?></a>
                        <a href="/solumare/index.php#contact" class="btn btn-outline-blue btn-sm"><?= t('btn_contact') ?></a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

            <!-- Coming Soon card -->
            <div class="prop-card reveal reveal-delay-3" data-zone="cabos" style="opacity:0.7;">
                <div class="prop-card-img">
                    <div class="prop-card-img-inner">
                        <span class="prop-card-img-icon">⛰️</span>
                        <span class="prop-card-img-sub">Los Cabos, BCS</span>
                    </div>
                    <div class="prop-card-overlay"></div>
                    <span class="prop-card-badge"><?= $lang === 'es' ? 'Próximamente' : 'Coming Soon' ?></span>
                </div>
                <div class="prop-card-body">
                    <div class="prop-card-location">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        Los Cabos, Baja California Sur
                    </div>
                    <h3 class="prop-card-title"><?= $lang === 'es' ? 'Nuevo Desarrollo' : 'New Development' ?></h3>
                    <p class="prop-card-desc"><?= $lang === 'es'
                        ? 'Próxima apertura en Los Cabos. Regístrate para recibir información exclusiva de preventa.'
                        : 'Coming soon to Los Cabos. Register to receive exclusive pre-sale information.' ?></p>
                    <div style="text-align:center;padding:24px 0;">
                        <div style="font-size:3rem;margin-bottom:12px;">🚀</div>
                        <a href="/solumare/index.php#contact" class="btn btn-outline-blue btn-sm">
                            <?= $lang === 'es' ? 'Registrar Interés' : 'Register Interest' ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<button class="scroll-top" id="scrollTop" aria-label="Volver arriba">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="18 15 12 9 6 15"/></svg>
</button>

<?php include __DIR__ . '/../includes/footer.php'; ?>
