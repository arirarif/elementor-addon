/**
 * Wheels Elementor Widget - Frontend JS
 * Simple form handling for tire filter
 */
(function() {
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {
        var form = document.getElementById('wheels-filter-form');

        if (!form) {
            return;
        }

        // Remove empty parameters before submit
        form.addEventListener('submit', function(e) {
            var selects = this.querySelectorAll('select');

            selects.forEach(function(select) {
                if (!select.value || select.value === '') {
                    select.removeAttribute('name');
                }
            });
        });
    });
})();
