// Scroll Up
document.addEventListener('DOMContentLoaded', function () {
    const btn = document.querySelector('.scroll-up-btn');

    window.addEventListener('scroll', () => {
        btn.classList.toggle('show', window.scrollY > 200);
    });

    btn.querySelector('a').addEventListener('click', function (e) {
        e.preventDefault();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
});