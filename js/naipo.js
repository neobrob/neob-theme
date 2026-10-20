// NAIPO — interactions du site (adapté de script.js, maquette v16.4).
// Le header et le footer sont rendus par WordPress (header.php / footer.php) ;
// le lien actif de la navigation est calculé côté serveur.

document.addEventListener('DOMContentLoaded', () => {

  const isHome = document.body.classList.contains('is-home');
  const header = document.querySelector('.site-header');
  const languageToggle = document.getElementById('language-toggle');
  const languageMenu = document.getElementById('language-menu');
  const languageButtons = languageMenu ? [...languageMenu.querySelectorAll('[data-lang]')] : [];
  const homeHeroBackground = document.querySelector('.home-hero-bg');
  const homePartnersBackground = document.querySelector('.home-partners-bg');
  const heroBubbles = document.querySelector('.hero-bubbles');
  const siteMenuToggle = document.getElementById('site-menu-toggle');
  const siteNavigation = document.getElementById('site-navigation');


  /* =========================================================
     MENU MOBILE
     ========================================================= */

  function closeSiteMenu() {
    if (!header || !siteMenuToggle) return;
    header.classList.remove('menu-open');
    document.body.classList.remove('mobile-menu-open');
    siteMenuToggle.setAttribute('aria-expanded', 'false');
    siteMenuToggle.setAttribute('aria-label', 'Open navigation menu');
  }

  if (header && siteMenuToggle && siteNavigation) {
    siteMenuToggle.addEventListener('click', event => {
      event.stopPropagation();
      const opening = !header.classList.contains('menu-open');
      header.classList.toggle('menu-open', opening);
      document.body.classList.toggle('mobile-menu-open', opening);
      siteMenuToggle.setAttribute('aria-expanded', String(opening));
      siteMenuToggle.setAttribute('aria-label', opening ? 'Close navigation menu' : 'Open navigation menu');
      if (opening && languageMenu) {
        languageMenu.hidden = true;
        languageToggle?.setAttribute('aria-expanded', 'false');
      }
    });

    siteNavigation.addEventListener('click', event => {
      if (event.target.closest('a')) closeSiteMenu();
    });

    document.addEventListener('click', event => {
      if (header.classList.contains('menu-open') && !header.contains(event.target)) closeSiteMenu();
    });

    window.addEventListener('resize', () => {
      if (window.innerWidth > 1100) closeSiteMenu();
    });
  }


  /* =========================================================
     BOUTON "RETOUR EN HAUT"
     ========================================================= */

  const backToTop = document.createElement('button');
  backToTop.className = 'back-to-top';
  backToTop.type = 'button';
  backToTop.setAttribute('aria-label', 'Back to top');
  backToTop.innerHTML = '↑';
  document.body.appendChild(backToTop);
  backToTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));


  /* =========================================================
     DÉFILEMENT : header, bouton, parallaxes de l'accueil
     ========================================================= */

  function updateOnScroll() {
    header?.classList.toggle('is-scrolled', window.scrollY > 40);
    backToTop.classList.toggle('is-visible', window.scrollY > 350);

    if (!isHome) return;

    if (heroBubbles) {
      heroBubbles.style.transform = `translateY(${window.scrollY * 0.10}px)`;
    }

    if (homeHeroBackground) {
      const offset = Math.min(window.scrollY * 0.40, 500);
      homeHeroBackground.style.transform = `translate3d(0, ${offset}px, 0) scale(1.18)`;
    }

    if (homePartnersBackground) {
      const partnersSection = homePartnersBackground.closest('.home-v5-partners');
      if (partnersSection) {
        const rect = partnersSection.getBoundingClientRect();
        const progress = (window.innerHeight - rect.top) / (window.innerHeight + rect.height);
        const offset = (progress - 0.5) * 120;
        homePartnersBackground.style.transform = `translate3d(0, ${offset}px, 0) scale(1.28)`;
      }
    }
  }

  window.addEventListener('scroll', updateOnScroll, { passive: true });
  updateOnScroll();


  /* =========================================================
     APPARITIONS AU DÉFILEMENT
     ========================================================= */

  // Délais posés depuis l'éditeur via une classe "reveal-delay-N".
  document.querySelectorAll('[class*="reveal-delay-"]').forEach(el => {
    const match = el.className.match(/reveal-delay-(\d+)/);
    if (match && !el.dataset.revealDelay) el.dataset.revealDelay = match[1];
  });

  // Décalage automatique des cartes dans les grilles (comme dans la maquette).
  const staggers = {
    '.partner-grid': [0, 80, 160],
    '.core-team-grid': [0, 70, 140],
    '.research-project-list': [0, 80, 160],
    '.about-governance-grid': [0, 90, 180]
  };
  Object.entries(staggers).forEach(([selector, delays]) => {
    document.querySelectorAll(selector).forEach(grid => {
      [...grid.children].filter(child => child.classList.contains('reveal')).forEach((child, i) => {
        if (!child.dataset.revealDelay) child.dataset.revealDelay = String(delays[i % delays.length]);
      });
    });
  });

  function initReveals() {
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const targets = [...document.querySelectorAll('.reveal:not(.is-in)')];

    targets.forEach(el => {
      const delay = Number(el.dataset.revealDelay || 0);
      if (!reduceMotion && delay > 0) el.style.transitionDelay = `${delay}ms`;
    });

    if (reduceMotion || !('IntersectionObserver' in window)) {
      targets.forEach(el => el.classList.add('is-in'));
      return;
    }

    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-in');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12 });

    targets.forEach(el => observer.observe(el));
  }

  initReveals();


  /* =========================================================
     SÉLECTEUR DE LANGUE (visuel — voir README pour le multilingue)
     ========================================================= */

  if (languageToggle && languageMenu) {
    languageToggle.addEventListener('click', event => {
      event.stopPropagation();
      const willOpen = languageMenu.hidden;
      languageMenu.hidden = !willOpen;
      languageToggle.setAttribute('aria-expanded', String(willOpen));
    });

    languageButtons.forEach(button => {
      button.addEventListener('click', event => {
        event.stopPropagation();
        languageToggle.firstChild.textContent = button.dataset.lang + ' ';
        languageButtons.forEach(b => b.classList.toggle('is-active', b === button));
        languageMenu.hidden = true;
        languageToggle.setAttribute('aria-expanded', 'false');
      });
    });

    document.addEventListener('click', () => {
      languageMenu.hidden = true;
      languageToggle.setAttribute('aria-expanded', 'false');
    });
  }

});
