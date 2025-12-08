(function($) {
    'use strict';

    class WheelsWidget {
        constructor() {
            this.data = window.wheelsWidgetData || {};
            this.currentTransport = null;
            this.init();
        }

        init() {
            this.cacheElements();
            this.bindEvents();
            this.setInitialTransport();
        }

        cacheElements() {
            this.elements = {
                wheelsType: document.getElementById('wheels-type'),
                transportType: document.getElementById('transport-type'),
                letsFindButton: document.getElementById('lets_find'),
                widthCarInput: document.getElementById('width-car'),
                heightCarInput: document.getElementById('height-car'),
                radiusCarInput: document.getElementById('radius-car'),
                wheelsWrapper: document.querySelector('.wheels-wrapper')
            };
        }

        bindEvents() {
            // Transport type clicks
            document.querySelectorAll('.type-action').forEach(element => {
                element.addEventListener('click', () => {
                    this.changeTransportType(element.dataset.type, element.dataset.image);
                });
            });

            // Season clicks
            document.querySelectorAll('.season-type').forEach(element => {
                element.addEventListener('click', () => {
                    this.changeWheelsType(element.dataset.season);
                });
            });

            // Search button
            if (this.elements.letsFindButton) {
                this.elements.letsFindButton.addEventListener('click', () => {
                    this.goShopAction();
                });
            }
        }

        setInitialTransport() {
            if (this.elements.transportType && this.elements.transportType.value) {
                this.currentTransport = this.elements.transportType.value;
            }
        }

        changeTransportType(typeValue, imageUrl) {
            // Update active class
            document.querySelectorAll('.type-action').forEach(el => {
                el.classList.remove('wheels-active');
            });
            const activeEl = document.querySelector(`[data-type="${typeValue}"]`);
            if (activeEl) {
                activeEl.classList.add('wheels-active');
            }

            // Update hidden select
            if (this.elements.transportType) {
                this.elements.transportType.value = typeValue;
            }

            // Update current transport
            this.currentTransport = typeValue;

            // Update background image
            if (imageUrl && this.elements.wheelsWrapper) {
                this.elements.wheelsWrapper.style.backgroundImage = `url('${imageUrl}')`;
            }

            // Update parameters
            this.updateParameters(typeValue);
        }

        changeWheelsType(seasonValue) {
            // Update active class
            document.querySelectorAll('.season-type').forEach(el => {
                el.classList.remove('wheels-active');
            });
            const activeEl = document.querySelector(`[data-season="${seasonValue}"]`);
            if (activeEl) {
                activeEl.classList.add('wheels-active');
            }

            // Update hidden select
            if (this.elements.wheelsType) {
                this.elements.wheelsType.value = seasonValue;
            }
        }

        updateParameters(transportType) {
            const typeData = this.data.transportData ? this.data.transportData[transportType] : null;
            if (!typeData) return;

            this.updateSelect(this.elements.widthCarInput, typeData.width_values || []);
            this.updateSelect(this.elements.heightCarInput, typeData.height_values || []);
            this.updateSelect(this.elements.radiusCarInput, typeData.radius_values || []);
        }

        updateSelect(select, options) {
            if (!select) return;

            const currentValue = select.value;

            // Clear select
            select.innerHTML = '';

            // Add placeholder option
            const placeholder = document.createElement('option');
            placeholder.value = '';
            placeholder.textContent = 'Select value';
            select.appendChild(placeholder);

            // Add new options
            options.forEach(value => {
                const option = document.createElement('option');
                option.value = value;
                option.textContent = value;
                select.appendChild(option);
            });

            // Restore value if it exists in new options
            if (options.includes(currentValue)) {
                select.value = currentValue;
            }
        }

        goShopAction() {
            // Always redirect to shop page with query parameters
            let baseUrl = this.data.shopUrl || this.data.baseUrl + '/shop/';

            // Make sure baseUrl ends without trailing slash for clean URL building
            baseUrl = baseUrl.replace(/\/$/, '') + '/';

            const transportType = this.currentTransport || (this.elements.transportType ? this.elements.transportType.value : '');
            const typeData = this.data.transportData ? this.data.transportData[transportType] : null;

            // Create URL parameters
            const params = new URLSearchParams();

            // Add season/category as a meta parameter
            const seasonSlug = this.elements.wheelsType ? this.elements.wheelsType.value : '';
            if (seasonSlug) {
                params.append('meta_season', seasonSlug);
            }

            // Add width parameter
            if (this.elements.widthCarInput && this.elements.widthCarInput.value) {
                const paramName = this.getFilterParamName(typeData, 'width_attr', 'width');
                params.append(paramName, this.elements.widthCarInput.value);
            }

            // Add height parameter
            if (this.elements.heightCarInput && this.elements.heightCarInput.value) {
                const paramName = this.getFilterParamName(typeData, 'height_attr', 'height');
                params.append(paramName, this.elements.heightCarInput.value);
            }

            // Add diameter/radius parameter
            if (this.elements.radiusCarInput && this.elements.radiusCarInput.value) {
                const paramName = this.getFilterParamName(typeData, 'radius_attr', 'diameter');
                params.append(paramName, this.elements.radiusCarInput.value);
            }

            // Add post_type for clarity
            params.append('post_type', 'product');

            // Build final URL: /shop/?meta_season=xxx&meta_width=yyy&post_type=product
            const queryString = params.toString();
            const finalUrl = baseUrl + (queryString ? '?' + queryString : '');

            // Redirect to shop with filters
            window.location.href = finalUrl;
        }

        getFilterParamName(typeData, attrKey, defaultName) {
            if (!typeData || !typeData[attrKey]) {
                return 'meta_' + defaultName;
            }

            const attr = typeData[attrKey];

            if (attr.type === 'acf') {
                // For ACF fields, use meta_ prefix
                return 'meta_' + attr.name;
            } else if (attr.type === 'wc') {
                // For WooCommerce attributes, use filter_ prefix
                return 'filter_' + attr.name.replace('pa_', '');
            }

            return 'meta_' + defaultName;
        }
    }

    // Initialize on DOM load
    document.addEventListener('DOMContentLoaded', function() {
        // Check if widget exists on page
        if (document.querySelector('.wheels-wrapper')) {
            new WheelsWidget();
        }
    });

})(jQuery);
