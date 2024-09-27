function kenburn_posts_init(){
    var itemLinks = document.querySelectorAll('.item a');

    itemLinks.forEach(function(link) {
        link.addEventListener('mouseenter', function() {
            var backgroundSelector = '#' + this.getAttribute('data-background');
            var backgroundElement = document.querySelector(backgroundSelector);

            itemLinks.forEach(function(otherLink) {
                if (otherLink !== link) {
                    otherLink.classList.add('window');
                }
            });

            if (backgroundElement) {
                backgroundElement.classList.add('full-screen');
            }
        });

        link.addEventListener('mouseleave', function() {
            var backgroundSelector = '#' + this.getAttribute('data-background');
            var backgroundElement = document.querySelector(backgroundSelector);

            itemLinks.forEach(function(otherLink) {
                if (otherLink !== link) {
                    otherLink.classList.remove('window');
                }
            });

            if (backgroundElement) {
                backgroundElement.classList.remove('full-screen');
            }
        });
    });
}


jQuery( function( $ ) {
    if(window.elementor){
    if ( window.elementorFrontend ) {
      elementorFrontend.hooks.addAction( 'frontend/element_ready/kbpw.default', function( $scope ) {
        kenburn_posts_init()
      });
    }
  }else{
    kenburn_posts_init()
  }
  });
