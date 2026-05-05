<?php
require_once __DIR__ . '/lang.php';
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$base = '/solumare/';
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Solumare Real Estate - Inversión inmobiliaria de lujo en Riviera Maya, Tulum, Isla Mujeres y Los Cabos. +30 años de experiencia, +13,000 viviendas vendidas.">
    <meta name="keywords" content="bienes raíces lujo, Tulum, Riviera Maya, inversión inmobiliaria México, Isla Mujeres, Los Cabos">
    <meta property="og:title" content="Solumare - Luxury Real Estate México">
    <meta property="og:description" content="Propiedades de lujo en las zonas más exclusivas de México">
    <meta property="og:type" content="website">
    <title><?= isset($pageTitle) ? $pageTitle . ' | Solumare' : 'Solumare Real Estate — Luxury Properties México' ?></title>
    <link rel="icon" type="image/svg+xml" href="<?= $base ?>images/favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= $base ?>css/style.css">
    <?= isset($extraCss) ? $extraCss : '' ?>
</head>
<body class="<?= $currentPage ?>-page">

<!-- Loader -->
<div class="page-loader" id="pageLoader">
    <div class="loader-logo">
        <svg viewBox="0 0 120 40" fill="none" xmlns="http://www.w3.org/2000/svg">
            <text x="0" y="30" font-family="Playfair Display" font-size="28" fill="url(#logoGrad)">Solumare</text>
            <defs>
                <linearGradient id="logoGrad" x1="0" y1="0" x2="120" y2="0">
                    <stop offset="0%" stop-color="#0ea5e9"/>
                    <stop offset="100%" stop-color="#C9A84C"/>
                </linearGradient>
            </defs>
        </svg>
    </div>
    <div class="loader-bar"><div class="loader-fill"></div></div>
</div>

<!-- Navigation -->
<nav class="navbar" id="navbar">
    <div class="nav-container">
        <a href="<?= $base ?>index.php" class="nav-logo">
            <svg viewBox="0 0 140 40" fill="none" xmlns="http://www.w3.org/2000/svg" class="logo-svg">
                <text x="0" y="30" font-family="Playfair Display" font-size="26" fill="url(#navGrad)">Solumare</text>
                <defs>
                    <linearGradient id="navGrad" x1="0" y1="0" x2="140" y2="0">
                        <stop offset="0%" stop-color="#0ea5e9"/>
                        <stop offset="100%" stop-color="#C9A84C"/>
                    </linearGradient>
                </defs>
            </svg>
            <span class="logo-tagline">Real Estate</span>
        </a>

        <button class="nav-toggle" id="navToggle" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>

        <ul class="nav-menu" id="navMenu">
            <li><a href="<?= $base ?>index.php" class="nav-link <?= $currentPage === 'index' ? 'active' : '' ?>"><?= t('nav_home') ?></a></li>
            <li><a href="<?= $base ?>properties/index.php" class="nav-link <?= $currentPage === 'index' && isset($isProperties) ? 'active' : '' ?>"><?= t('nav_properties') ?></a></li>
            <li><a href="<?= $base ?>index.php#about" class="nav-link"><?= t('nav_about') ?></a></li>
            <li><a href="<?= $base ?>index.php#contact" class="nav-link"><?= t('nav_contact') ?></a></li>
            <li class="nav-lang">
                <a href="<?= langUrl('es') ?>" class="lang-btn <?= $lang === 'es' ? 'active' : '' ?>">ES</a>
                <span>|</span>
                <a href="<?= langUrl('en') ?>" class="lang-btn <?= $lang === 'en' ? 'active' : '' ?>">EN</a>
            </li>
        </ul>
    </div>
</nav>
