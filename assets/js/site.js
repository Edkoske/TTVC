document.addEventListener('DOMContentLoaded', () => {
  const toggle = document.querySelector('.menu-toggle');
  const nav = document.querySelector('.main-nav');
  toggle?.addEventListener('click', () => {
    const expanded = toggle.getAttribute('aria-expanded') === 'true';
    toggle.setAttribute('aria-expanded', String(!expanded));
    nav?.classList.toggle('open', !expanded);
  });

  const heroSlides = [...document.querySelectorAll('.hero-slide')];
  const heroDots = [...document.querySelectorAll('.hero-dot')];
  const heroCurrent = document.querySelector('.hero-index-current');

  if (heroSlides.length) {
    let currentSlide = 0;
    const showSlide = (index) => {
      currentSlide = index;
      heroSlides.forEach((slide, i) => slide.classList.toggle('active', i === index));
      heroDots.forEach((dot, i) => dot.classList.toggle('active', i === index));
      if (heroCurrent) heroCurrent.textContent = String(index + 1).padStart(2, '0');
    };

    heroDots.forEach((dot, index) => {
      dot.addEventListener('click', () => showSlide(index));
    });

    setInterval(() => {
      const nextIndex = (currentSlide + 1) % heroSlides.length;
      showSlide(nextIndex);
    }, 5000);
  }

  document.querySelectorAll('[data-application-form]').forEach((form) => {
    const panels = [...form.querySelectorAll('[data-step-panel]')];
    const markers = [...form.querySelectorAll('[data-step-marker]')];
    let current = 0;
    const show = (index) => {
      current = index;
      panels.forEach((panel, i) => panel.classList.toggle('hidden', i !== index));
      markers.forEach((marker, i) => marker.classList.toggle('active', i === index));
      form.querySelectorAll('[data-review]').forEach((target) => {
        const source = form.elements.namedItem(target.dataset.review);
        target.textContent = source?.selectedOptions?.[0]?.textContent || source?.value || 'Not provided';
      });
      form.querySelector('[data-prev]')?.classList.toggle('hidden', index === 0);
      form.querySelector('[data-next]')?.classList.toggle('hidden', index === panels.length - 1);
      form.querySelector('[data-submit]')?.classList.toggle('hidden', index !== panels.length - 1);
    };
    form.querySelector('[data-next]')?.addEventListener('click', () => {
      const required = [...panels[current].querySelectorAll('[required]')];
      const invalid = required.find((input) => !input.reportValidity());
      if (!invalid && current < panels.length - 1) show(current + 1);
    });
    form.querySelector('[data-prev]')?.addEventListener('click', () => show(Math.max(0, current - 1)));
    show(Number(form.dataset.startStep) || 0);
  });

  document.querySelectorAll('[data-confirm]').forEach((form) => {
    form.addEventListener('submit', (event) => {
      if (!window.confirm(form.dataset.confirm || 'Continue with this action?')) event.preventDefault();
    });
  });

  document.querySelectorAll('[data-lightbox]').forEach((link) => {
    link.addEventListener('click', (event) => {
      event.preventDefault();
      let dialog = document.querySelector('.lightbox-dialog');
      if (!dialog) {
        dialog = document.createElement('dialog');
        dialog.className = 'lightbox-dialog';
        dialog.innerHTML = '<button class="lightbox-close" type="button" aria-label="Close image">×</button><img alt="">';
        document.body.append(dialog);
        dialog.querySelector('button').addEventListener('click', () => dialog.close());
        dialog.addEventListener('click', (clickEvent) => {
          if (clickEvent.target === dialog) dialog.close();
        });
      }
      const image = dialog.querySelector('img');
      image.src = link.href;
      image.alt = link.querySelector('img')?.alt || '';
      dialog.showModal();
    });
  });
});
