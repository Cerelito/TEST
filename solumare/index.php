<?php
require_once __DIR__ . '/includes/lang.php';
$pageTitle = $lang === 'es' ? 'Inicio — Inversión Inmobiliaria de Lujo en México' : 'Home — Luxury Real Estate Investment in Mexico';
include __DIR__ . '/includes/header.php';
?>

<!-- ══════════════════════════════════════════════
     HERO
══════════════════════════════════════════════ -->
<section class="hero" id="home">
    <div class="hero-bg"></div>

    <!-- Particles -->
    <div class="hero-particles" id="heroParticles"></div>

    <!-- Ocean waves -->
    <div class="hero-ocean">
        <div class="wave wave-1"></div>
        <div class="wave wave-2"></div>
        <div class="wave wave-3"></div>
    </div>

    <div class="hero-content">
        <div class="hero-text">
            <div class="hero-badge">
                🌊 <?= t('hero_tagline') ?>
            </div>
            <h1 class="hero-title">
                <?= t('hero_tagline') ?>
                <span class="accent"><?= t('hero_sub') ?></span>
            </h1>
            <p class="hero-subtitle">Riviera Maya · Riviera Nayarit · Los Cabos</p>
            <p class="hero-desc"><?= t('hero_desc') ?></p>
            <div class="hero-actions">
                <a href="/solumare/properties/index.php" class="btn btn-primary btn-lg">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9,22 9,12 15,12 15,22"/></svg>
                    <?= t('hero_cta1') ?>
                </a>
                <a href="#contact" class="btn btn-outline btn-lg">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.6 11.2 19.79 19.79 0 01.54 2.59 2 2 0 012.53.4h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 8a16 16 0 006.29 6.29l.87-.87a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
                    <?= t('hero_cta2') ?>
                </a>
            </div>
        </div>

        <div class="hero-visual">
            <div class="hero-card-main">
                <div class="hero-img-placeholder">
                    <div class="hero-img-icon">🌴</div>
                    <span class="hero-img-label">Ekab Tulum · Riviera Maya</span>
                </div>
                <div class="hero-card-info">
                    <h3>Ekab Tulum</h3>
                    <p><?= $lang === 'es' ? 'Condominios de lujo frente al mar, Tulum' : 'Luxury oceanfront condominiums, Tulum' ?></p>
                    <span class="hero-card-badge">LEED Gold · ROI 10% Anual</span>
                </div>
            </div>

            <!-- Floating info cards -->
            <div class="hero-floating-cards">
                <div class="float-card float-card-1">
                    <div class="float-card-value">$4M MXN</div>
                    <div class="float-card-label"><?= t('from') ?></div>
                </div>
                <div class="float-card float-card-2">
                    <div class="float-card-icon">📈</div>
                    <div class="float-card-value">10%</div>
                    <div class="float-card-label"><?= t('roi') ?></div>
                </div>
                <div class="float-card float-card-3">
                    <div class="float-card-icon">✅</div>
                    <div class="float-card-value">LEED Gold</div>
                    <div class="float-card-label"><?= t('certification') ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll indicator -->
    <div style="position:absolute;bottom:32px;left:50%;transform:translateX(-50%);z-index:2;text-align:center;animation:fadeInUp 1s 1.2s ease forwards;opacity:0;">
        <div style="color:rgba(255,255,255,0.4);font-size:0.72rem;letter-spacing:0.15em;text-transform:uppercase;margin-bottom:8px;">Scroll</div>
        <div style="width:1px;height:40px;background:linear-gradient(180deg,rgba(14,165,233,0.5),transparent);margin:0 auto;animation:pulse 2s infinite;"></div>
    </div>
</section>

<!-- ══════════════════════════════════════════════
     STATS BAR
══════════════════════════════════════════════ -->
<section class="stats-bar">
    <div class="stats-grid">
        <div class="stat-item reveal">
            <span class="stat-number" data-target="30">0</span>
            <span class="stat-label">+ <?= t('stat_years') ?></span>
        </div>
        <div class="stat-item reveal reveal-delay-2">
            <span class="stat-number" data-target="13000">0</span>
            <span class="stat-label">+ <?= t('stat_homes') ?></span>
        </div>
        <div class="stat-item reveal reveal-delay-3">
            <span class="stat-number" data-target="40">0</span>
            <span class="stat-label">+ <?= t('stat_projects') ?></span>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════════════════
     FEATURED PROPERTIES
══════════════════════════════════════════════ -->
<section class="section properties-section" id="properties">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-tag"><?= t('nav_properties') ?></span>
            <h2 class="section-title"><?= t('feat_title') ?></h2>
            <p class="section-sub"><?= t('feat_sub') ?></p>
        </div>

        <div class="properties-grid">

            <!-- Card 1: Ekab Tulum -->
            <div class="prop-card reveal">
                <div class="prop-card-img">
                    <div class="prop-card-img-inner">
                        <span class="prop-card-img-icon">🌴</span>
                        <span class="prop-card-img-sub">Tulum · Quintana Roo</span>
                    </div>
                    <div class="prop-card-overlay"></div>
                    <span class="prop-card-badge"><?= $lang === 'es' ? 'Disponible' : 'Available' ?></span>
                    <span class="prop-card-cert">LEED Gold</span>
                </div>
                <div class="prop-card-body">
                    <div class="prop-card-location">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        Tulum, Quintana Roo
                    </div>
                    <h3 class="prop-card-title">Ekab Tulum</h3>
                    <p class="prop-card-desc"><?= $lang === 'es'
                        ? 'Condominios de lujo donde el lujo y la naturaleza se fusionan perfectamente. Acabados de alto nivel, amenidades excepcionales y servicios hoteleros cinco estrellas.'
                        : 'Luxury condominiums where luxury and nature merge perfectly. High-end finishes, exceptional amenities and five-star hotel services.' ?></p>

                    <div class="prop-card-specs">
                        <div class="spec-item">
                            <span class="spec-value">190</span>
                            <span class="spec-label"><?= t('units') ?></span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-value">1–4</span>
                            <span class="spec-label"><?= t('rooms') ?></span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-value">67–823</span>
                            <span class="spec-label">m² <?= t('area') ?></span>
                        </div>
                    </div>

                    <div class="prop-card-invest">
                        <div>
                            <span class="invest-price-label"><?= t('from') ?></span>
                            <div class="invest-price">$4M MXN</div>
                        </div>
                        <div class="invest-roi">
                            <span class="invest-roi-label"><?= t('roi') ?></span>
                            <div class="invest-roi-value">10%</div>
                        </div>
                    </div>

                    <div class="prop-card-actions">
                        <a href="/solumare/properties/ekab-tulum.php" class="btn btn-primary btn-sm"><?= t('btn_details') ?></a>
                        <a href="#contact" class="btn btn-outline-blue btn-sm"><?= t('btn_contact') ?></a>
                    </div>
                </div>
            </div>

            <!-- Card 2: La Salina -->
            <div class="prop-card reveal reveal-delay-2">
                <div class="prop-card-img">
                    <div class="prop-card-img-inner">
                        <span class="prop-card-img-icon">🏝️</span>
                        <span class="prop-card-img-sub">Isla Mujeres · Q. Roo</span>
                    </div>
                    <div class="prop-card-overlay"></div>
                    <span class="prop-card-badge"><?= $lang === 'es' ? 'Pre-venta' : 'Pre-sale' ?></span>
                </div>
                <div class="prop-card-body">
                    <div class="prop-card-location">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        Isla Mujeres, Quintana Roo
                    </div>
                    <h3 class="prop-card-title">La Salina Isla Mujeres</h3>
                    <p class="prop-card-desc"><?= $lang === 'es'
                        ? 'Apartamentos frente al mar que redefinen la vida en el Caribe. Vistas panorámicas sin obstrucciones al Mar Caribe en el mágico Pueblo Mágico.'
                        : 'Oceanfront apartments that redefine Caribbean living. Unobstructed panoramic views of the Caribbean Sea in the magical Pueblo Mágico.' ?></p>

                    <div class="prop-card-specs">
                        <div class="spec-item">
                            <span class="spec-value">2</span>
                            <span class="spec-label"><?= $lang === 'es' ? 'Torres' : 'Towers' ?></span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-value">2–3</span>
                            <span class="spec-label"><?= t('rooms') ?></span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-value">106–170</span>
                            <span class="spec-label">m² <?= t('area') ?></span>
                        </div>
                    </div>

                    <div class="prop-card-invest">
                        <div>
                            <span class="invest-price-label"><?= t('from') ?></span>
                            <div class="invest-price" style="font-size:1.2rem"><?= $lang === 'es' ? 'Consultar' : 'Contact Us' ?></div>
                        </div>
                        <div class="invest-roi">
                            <span class="invest-roi-label"><?= $lang === 'es' ? 'Plusvalía' : 'Capital Gain' ?></span>
                            <div class="invest-roi-value">12–15%</div>
                        </div>
                    </div>

                    <div class="prop-card-actions">
                        <a href="/solumare/properties/la-salina.php" class="btn btn-primary btn-sm"><?= t('btn_details') ?></a>
                        <a href="#contact" class="btn btn-outline-blue btn-sm"><?= t('btn_contact') ?></a>
                    </div>
                </div>
            </div>

        </div>

        <div style="text-align:center;margin-top:40px;" class="reveal">
            <a href="/solumare/properties/index.php" class="btn btn-outline-blue btn-lg">
                <?= t('all_properties') ?>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════════════════
     ABOUT
══════════════════════════════════════════════ -->
<section class="section about-section" id="about">
    <div class="container">
        <div class="about-grid">
            <div class="about-visual reveal reveal-left">
                <div class="about-img-frame">
                    <div class="about-img-emoji">🌊</div>
                    <div class="about-img-text"><?= $lang === 'es' ? 'Riviera Maya, México' : 'Riviera Maya, Mexico' ?></div>
                </div>
                <div class="about-corner-badge">
                    <span class="num">30+</span>
                    <span class="lbl"><?= t('stat_years') ?></span>
                </div>
            </div>

            <div class="about-text">
                <div class="section-header">
                    <span class="section-tag"><?= t('nav_about') ?></span>
                    <h2 class="section-title light"><?= t('about_title') ?></h2>
                    <p class="section-sub light"><?= t('about_sub') ?></p>
                </div>
                <p><?= t('about_p1') ?></p>
                <p><?= t('about_p2') ?></p>

                <div class="about-features">
                    <div class="feature-item reveal reveal-delay-1">
                        <div class="feature-icon">🎯</div>
                        <span class="feature-text"><?= t('about_f1') ?></span>
                    </div>
                    <div class="feature-item reveal reveal-delay-2">
                        <div class="feature-icon">🏆</div>
                        <span class="feature-text"><?= t('about_f2') ?></span>
                    </div>
                    <div class="feature-item reveal reveal-delay-3">
                        <div class="feature-icon">🔑</div>
                        <span class="feature-text"><?= t('about_f3') ?></span>
                    </div>
                    <div class="feature-item reveal reveal-delay-4">
                        <div class="feature-icon">📈</div>
                        <span class="feature-text"><?= t('about_f4') ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════════════════
     ZONES
══════════════════════════════════════════════ -->
<section class="section zones-section" id="zones">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-tag"><?= $lang === 'es' ? 'Destinos' : 'Destinations' ?></span>
            <h2 class="section-title"><?= t('zones_title') ?></h2>
            <p class="section-sub"><?= t('zones_sub') ?></p>
        </div>

        <div class="zones-grid">
            <div class="zone-card reveal">
                <div class="zone-bg"></div>
                <div class="zone-card-inner-img">🌴</div>
                <div class="zone-overlay"></div>
                <span class="zone-tag">Ekab Tulum</span>
                <div class="zone-content">
                    <span class="zone-emoji">🌿</span>
                    <div class="zone-name">Riviera Maya</div>
                    <div class="zone-sub">Tulum · Playa del Carmen · Cancún</div>
                </div>
            </div>
            <div class="zone-card reveal reveal-delay-2">
                <div class="zone-bg"></div>
                <div class="zone-card-inner-img">🏝️</div>
                <div class="zone-overlay"></div>
                <span class="zone-tag">La Salina</span>
                <div class="zone-content">
                    <span class="zone-emoji">🐠</span>
                    <div class="zone-name">Isla Mujeres</div>
                    <div class="zone-sub"><?= $lang === 'es' ? 'Pueblo Mágico · Caribe Mexicano' : 'Pueblo Mágico · Mexican Caribbean' ?></div>
                </div>
            </div>
            <div class="zone-card reveal reveal-delay-3">
                <div class="zone-bg"></div>
                <div class="zone-card-inner-img">⛰️</div>
                <div class="zone-overlay"></div>
                <span class="zone-tag"><?= $lang === 'es' ? 'Próximamente' : 'Coming Soon' ?></span>
                <div class="zone-content">
                    <span class="zone-emoji">🌅</span>
                    <div class="zone-name">Los Cabos</div>
                    <div class="zone-sub"><?= $lang === 'es' ? 'Baja California Sur · Mar de Cortés' : 'Baja California Sur · Sea of Cortez' ?></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════════════════
     CONTACT
══════════════════════════════════════════════ -->
<section class="section contact-section" id="contact">
    <div class="container">
        <div class="contact-grid">
            <div class="contact-info">
                <div class="section-header reveal">
                    <span class="section-tag"><?= t('nav_contact') ?></span>
                    <h2 class="section-title"><?= t('contact_title') ?></h2>
                    <p class="section-sub"><?= t('contact_sub') ?></p>
                </div>

                <div class="contact-channels">
                    <a href="tel:+529842341660" class="channel-item reveal reveal-delay-1">
                        <div class="channel-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.6 11.2 19.79 19.79 0 01.54 2.59 2 2 0 012.53.4h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 8a16 16 0 006.29 6.29l.87-.87a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
                        </div>
                        <div>
                            <div class="channel-text-label"><?= $lang === 'es' ? 'Teléfono' : 'Phone' ?></div>
                            <div class="channel-text-value">+52 984 234 1660</div>
                        </div>
                    </a>
                    <a href="mailto:info@solumare.mx" class="channel-item reveal reveal-delay-2">
                        <div class="channel-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        </div>
                        <div>
                            <div class="channel-text-label">Email</div>
                            <div class="channel-text-value">info@solumare.mx</div>
                        </div>
                    </a>
                    <a href="https://wa.me/529842341660" target="_blank" rel="noopener" class="channel-item reveal reveal-delay-3">
                        <div class="channel-icon gold">
                            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        </div>
                        <div>
                            <div class="channel-text-label">WhatsApp</div>
                            <div class="channel-text-value">+52 984 234 1660</div>
                        </div>
                    </a>
                    <div class="channel-item reveal reveal-delay-4">
                        <div class="channel-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        </div>
                        <div>
                            <div class="channel-text-label"><?= $lang === 'es' ? 'Oficina' : 'Office' ?></div>
                            <div class="channel-text-value">Playa del Carmen, Q. Roo, México</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="contact-form-wrap reveal reveal-right">
                <form id="contactForm" method="POST" action="/solumare/api/contact.php">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nombre"><?= t('form_name') ?></label>
                            <input type="text" id="nombre" name="nombre" class="form-control" placeholder="María González" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="maria@email.com" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="telefono"><?= t('form_phone') ?></label>
                            <input type="tel" id="telefono" name="telefono" class="form-control" placeholder="+52 998 000 0000">
                        </div>
                        <div class="form-group">
                            <label for="propiedad"><?= t('form_interest') ?></label>
                            <select id="propiedad" name="propiedad" class="form-control">
                                <option value=""><?= t('form_select') ?></option>
                                <option value="ekab-tulum">Ekab Tulum</option>
                                <option value="la-salina">La Salina Isla Mujeres</option>
                                <option value="general"><?= $lang === 'es' ? 'Información General' : 'General Information' ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="mensaje"><?= t('form_msg') ?></label>
                        <textarea id="mensaje" name="mensaje" class="form-control" placeholder="<?= $lang === 'es' ? 'Cuéntanos sobre tu presupuesto e intereses...' : 'Tell us about your budget and interests...' ?>"></textarea>
                    </div>
                    <button type="submit" class="form-submit">
                        <span>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:inline;vertical-align:middle;margin-right:6px;"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                            <?= t('form_send') ?>
                        </span>
                    </button>
                    <div class="form-message" id="formMsg"></div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Scroll to top -->
<button class="scroll-top" id="scrollTop" aria-label="Volver arriba">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="18 15 12 9 6 15"/></svg>
</button>

<?php include __DIR__ . '/includes/footer.php'; ?>
