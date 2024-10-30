
jQuery(document).ready(function(e) {
    jQuery('.tab-links').on('click', function() {
        var tabName = jQuery(this).data('tab');
        opentab(tabName);
    });
});

function opentab(tabname) {
    var tablinks = document.querySelectorAll('.tab-links');
    var tabcontents = document.querySelectorAll('.tab-contents');

    tablinks.forEach(function(link) {
        link.classList.remove('active-link');
        
        if (link.getAttribute('data-tab') === tabname) {
            link.classList.add('active-link');
        }
    });

    tabcontents.forEach(function(content) {
        content.classList.remove('active-tab');
        
        if (content.id === tabname) {
            content.classList.add('active-tab');
        }
    });
}

