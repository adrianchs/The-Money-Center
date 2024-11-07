document.querySelectorAll('nav ul li a').forEach(function(menuItem) {
    menuItem.addEventListener('mouseenter', function() {
        this.style.color = '#ff6600';
    });
    menuItem.addEventListener('mouseleave', function() {
        this.style.color = 'white';
    });
});
