document.addEventListener('DOMContentLoaded', () => {
  const site = document.querySelector('.tp-site');
  if (!site) return;

  // Mobile navigation
  const menuButton = site.querySelector('.tp-menu-toggle');
  const nav = site.querySelector('.tp-nav');
  if (menuButton && nav) {
    menuButton.addEventListener('click', () => {
      const open = nav.classList.toggle('is-open');
      menuButton.setAttribute('aria-expanded', String(open));
      menuButton.setAttribute('aria-label', open ? 'Close navigation' : 'Open navigation');
    });
    nav.querySelectorAll('a').forEach(link => link.addEventListener('click', () => {
      nav.classList.remove('is-open');
      menuButton.setAttribute('aria-expanded', 'false');
    }));
  }

  // Light / dark theme toggle (per-page session only)
  const themeButton = site.querySelector('.tp-theme-toggle');
  if (themeButton) {
    themeButton.addEventListener('click', () => {
      const light = site.classList.toggle('is-light');
      themeButton.textContent = light ? '☾' : '☼';
      themeButton.setAttribute('aria-label', light ? 'Switch to dark theme' : 'Switch to light theme');
    });
  }

  // Reveal sections as they enter the viewport
  const revealItems = site.querySelectorAll('.tp-section-head,.tp-member-card,.tp-skill-card,.tp-project-card,.tp-timeline-item,.tp-education-card,.tp-achievement-card');
  revealItems.forEach(item => item.classList.add('tp-reveal'));
  if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-in-view');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12 });
    revealItems.forEach(item => observer.observe(item));
  } else {
    revealItems.forEach(item => item.classList.add('is-in-view'));
  }

  // Back to top
  const backTop = site.querySelector('.tp-back-top');
  if (backTop) {
    const updateTop = () => backTop.classList.toggle('is-visible', window.scrollY > 500);
    window.addEventListener('scroll', updateTop, { passive: true });
    updateTop();
    backTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
  }

  // Project category filters
  const filterButtons = site.querySelectorAll('[data-filter]');
  const projectCards = site.querySelectorAll('.tp-person-projects [data-category]');
  filterButtons.forEach(button => button.addEventListener('click', () => {
    filterButtons.forEach(item => item.classList.toggle('is-active', item === button));
    const selected = button.dataset.filter;
    projectCards.forEach(card => {
      const category = (card.dataset.category || '').toLowerCase();
      const show = selected === 'all' || (selected === 'web-development' && category.includes('web')) || (selected === 'school-projects' && category.includes('school')) || (selected === 'other' && !category.includes('web') && !category.includes('school'));
      card.hidden = !show;
    });
  }));

  // Accessible project detail dialogs
  const openModal = modal => {
    if (!modal) return;
    modal.classList.add('is-open');
    modal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
    const close = modal.querySelector('.tp-modal-close');
    if (close) close.focus();
  };
  const closeModal = modal => {
    if (!modal) return;
    modal.classList.remove('is-open');
    modal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
  };
  site.querySelectorAll('[data-modal]').forEach(button => button.addEventListener('click', () => openModal(site.querySelector('#' + button.dataset.modal))));
  site.querySelectorAll('[data-close-modal]').forEach(button => button.addEventListener('click', () => closeModal(button.closest('.tp-modal'))));
  site.querySelectorAll('.tp-inline-detail').forEach(button => button.addEventListener('click', () => {
    const modal = site.querySelector('#detail-modal');
    if (modal) {
      const content = modal.querySelector('.tp-modal-dynamic');
      if (content) content.textContent = button.dataset.detail || 'Project details will be added soon.';
      openModal(modal);
    }
  }));
  document.addEventListener('keydown', event => {
    if (event.key === 'Escape') site.querySelectorAll('.tp-modal.is-open').forEach(closeModal);
  });

  // Contact form: mailto fallback, no server-side email is claimed.
  site.querySelectorAll('[data-contact-form]').forEach(form => form.addEventListener('submit', event => {
    event.preventDefault();
    const recipient = form.dataset.recipient || '';
    const note = form.querySelector('.tp-form-note');
    if (!recipient || recipient.includes('example.com')) {
      if (note) note.textContent = 'Please add a real recipient email address in the portfolio settings before using this form.';
      return;
    }
    const data = new FormData(form);
    const subject = encodeURIComponent(data.get('subject'));
    const body = encodeURIComponent('Name: ' + data.get('name') + '\nEmail: ' + data.get('email') + '\n\n' + data.get('message'));
    window.location.href = 'mailto:' + recipient + '?subject=' + subject + '&body=' + body;
    if (note) note.textContent = 'Your email app should open with the message prepared.';
  }));
});
