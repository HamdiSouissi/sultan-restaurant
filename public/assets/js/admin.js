'use strict';
/* Sultan — espace d'administration : interactions légères (aucune dépendance). */
(function () {
  const $  = (sel, ctx = document) => ctx.querySelector(sel);
  const $$ = (sel, ctx = document) => Array.from(ctx.querySelectorAll(sel));

  /* --- navigation mobile --- */
  const sidebar = $('#admSidebar');
  const burger  = $('#admBurger');
  const scrim   = $('#admScrim');

  function setNav(open) {
    if (!sidebar || !burger) return;
    sidebar.classList.toggle('open', open);
    burger.setAttribute('aria-expanded', open ? 'true' : 'false');
    if (scrim) scrim.hidden = !open;
    document.body.style.overflow = open && window.innerWidth <= 900 ? 'hidden' : '';
  }

  if (burger) burger.addEventListener('click', () => setNav(!sidebar.classList.contains('open')));
  if (scrim) scrim.addEventListener('click', () => setNav(false));
  $$('#admSidebar .navlink').forEach((a) => a.addEventListener('click', () => setNav(false)));
  window.addEventListener('keydown', (e) => { if (e.key === 'Escape') setNav(false); });
  window.addEventListener('resize', () => { if (window.innerWidth > 900) setNav(false); });

  /* --- messages flash --- */
  $$('.flash').forEach((flash) => {
    let done = false;
    const close = () => {
      if (done) return;
      done = true;
      flash.style.transition = 'opacity .3s, transform .3s';
      flash.style.opacity = '0';
      flash.style.transform = 'translateY(-6px)';
      setTimeout(() => flash.remove(), 320);
    };
    const x = $('.flash-x', flash);
    if (x) x.addEventListener('click', close);
    setTimeout(close, 7000);
  });

  /* --- confirmations --- */
  $$('form[data-confirm]').forEach((form) => {
    form.addEventListener('submit', (e) => {
      const message = form.getAttribute('data-confirm') || 'Confirmer cette action ?';
      if (!window.confirm(message)) e.preventDefault();
    });
  });

  /* --- aperçu avant envoi d'une image --- */
  $$('input[type=file][name=image]').forEach((input) => {
    input.addEventListener('change', () => {
      const file = input.files && input.files[0];
      if (!file || !file.type || file.type.indexOf('image/') !== 0) return;
      const field = input.closest('.field');
      if (!field) return;
      let holder = field.querySelector('.js-preview');
      if (!holder) {
        holder = document.createElement('div');
        holder.className = 'current-img js-preview';
        holder.innerHTML = '<img alt="" width="88" height="88">';
        field.appendChild(holder);
      }
      const img = holder.querySelector('img');
      if (img) {
        const url = URL.createObjectURL(file);
        img.src = url;
        img.addEventListener('load', () => URL.revokeObjectURL(url), { once: true });
      }
    });
  });

  /* --- anti double-envoi --- */
  $$('form').forEach((form) => {
    form.addEventListener('submit', () => {
      const btn = form.querySelector('button.abtn:not([type=button])');
      if (btn) setTimeout(() => btn.classList.add('is-busy'), 0);
    });
  });
})();
