(function($) {
    'use strict';
    
    class WheelsWidget {
        constructor() {
            this.data = window.wheelsWidgetData || {};
            this.init();
        }
        
        init() {
            this.cacheElements();
            this.bindEvents();
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
            // Клики по типам транспорта
            document.querySelectorAll('.type-action').forEach(element => {
                element.addEventListener('click', () => {
                    this.changeTransportType(element.dataset.type, element.dataset.image);
                });
            });
            
            // Клики по сезонам
            document.querySelectorAll('.season-type').forEach(element => {
                element.addEventListener('click', () => {
                    this.changeWheelsType(element.dataset.season);
                });
            });
            
            // Кнопка поиска
            this.elements.letsFindButton.addEventListener('click', () => {
                this.goShopAction();
            });
        }
        
        changeTransportType(typeValue, imageUrl) {
            // Обновляем активный класс
            document.querySelectorAll('.type-action').forEach(el => {
                el.classList.remove('wheels-active');
            });
            document.querySelector(`[data-type="${typeValue}"]`).classList.add('wheels-active');
            
            // Обновляем скрытый select
            this.elements.transportType.value = typeValue;
            
            // Обновляем фоновое изображение
            if (imageUrl && this.elements.wheelsWrapper) {
                this.elements.wheelsWrapper.style.backgroundImage = `url('${imageUrl}')`;
            }
            
            // Обновляем параметры
            this.updateParameters(typeValue);
        }
        
        changeWheelsType(seasonValue) {
            // Обновляем активный класс
            document.querySelectorAll('.season-type').forEach(el => {
                el.classList.remove('wheels-active');
            });
            document.querySelector(`[data-season="${seasonValue}"]`).classList.add('wheels-active');
            
            // Обновляем скрытый select
            this.elements.wheelsType.value = seasonValue;
        }
        
        updateParameters(transportType) {
            const typeData = this.data.transportData[transportType];
            if (!typeData) return;
            
            this.updateSelect(this.elements.widthCarInput, typeData.width_values || []);
            this.updateSelect(this.elements.heightCarInput, typeData.height_values || []);
            this.updateSelect(this.elements.radiusCarInput, typeData.radius_values || []);
        }
        
        updateSelect(select, options) {
            const currentValue = select.value;
            
            // Очищаем select
            select.innerHTML = '<option value="">Выберите значение</option>';
            
            // Добавляем новые options
            options.forEach(value => {
                const option = document.createElement('option');
                option.value = value;
                option.textContent = value;
                select.appendChild(option);
            });
            
            // Восстанавливаем значение, если оно есть в новых options
            if (options.includes(currentValue)) {
                select.value = currentValue;
            }
        }
        
        goShopAction() {
            const seasonSlug = this.elements.wheelsType.value;
            
            // Формируем базовый URL
            let baseUrl = this.data.baseUrl + '/product-category/' + seasonSlug + '/';
            
            // Создаем URL параметры
            const params = new URLSearchParams();

            // Добавляем параметры размеров
            if (this.elements.widthCarInput.value) {
                params.append('reifenbreite', this.elements.widthCarInput.value);
            }
            if (this.elements.heightCarInput.value) {
                params.append('reifenquerschnitt', this.elements.heightCarInput.value);
            }
            if (this.elements.radiusCarInput.value) {
                params.append('zollgroße', this.elements.radiusCarInput.value);
            }
            
            // Формируем финальный URL
            const finalUrl = baseUrl + (params.toString() ? '?' + params.toString() : '');
            
            // Перенаправляем
            window.location.href = finalUrl;
        }
    }
    
    // Инициализация при загрузке DOM
    document.addEventListener('DOMContentLoaded', function() {
        new WheelsWidget();
    });
    
})(jQuery);