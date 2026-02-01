/**
 * EVOLUTION ENGINE 2026
 * Gestion des interactions UI
 */

document.addEventListener('DOMContentLoaded', () => {
    
    // --- GESTION DU MENU BURGER ---
    const burger = document.querySelector('.burger-trigger');
    const nav = document.querySelector('.nav-links');

    if (burger && nav) {
        burger.addEventListener('click', () => {
            // Bascule de l'état actif pour l'icône et le menu
            burger.classList.toggle('is-active');
            nav.classList.toggle('nav-open');
            
            // Empêche le scroll du body quand le menu est ouvert
            document.body.classList.toggle('no-scroll');
        });
    }

    // --- ANIMATION AU SCROLL (OPTIDÉ) ---
    const observerOptions = {
        threshold: 0.1
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, observerOptions);

    // Cible tous les blocs de grille pour une apparition fluide
    document.querySelectorAll('.grid-block').forEach(block => {
        observer.observe(block);
    });

});