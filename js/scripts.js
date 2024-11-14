document.addEventListener('DOMContentLoaded', function() {
    let lastScrollTop = 0;
    let navbar = document.getElementById('ftco-navbar');
    let scrollTimeout;
  
    window.addEventListener('scroll', function() {
      let scrollTop = window.scrollY;
  
      if (scrollTop > lastScrollTop) {
        navbar.classList.add('hidden-nav');
      } else {
        navbar.classList.remove('hidden-nav');
        navbar.classList.add('scrolled');
      }
  
      lastScrollTop = scrollTop;
  
      clearTimeout(scrollTimeout);
      scrollTimeout = setTimeout(() => {
        navbar.classList.remove('hidden-nav');
      }, 300);
    });
  });