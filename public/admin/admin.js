/**
 * JavaScript для админ-панели
 * 
 * Функционал:
 * - Автокомплит для поиска
 * - Интерактивные элементы
 * - Подтверждения действий
 * - Уведомления
 * 
 * @author ehosting.lv
 * @copyright 2025
 */

document.addEventListener('DOMContentLoaded', function() {
    
    /**
     * Автокомплит для поиска
     * Глобальный поиск по админ-панели с предложениями
     */
    const searchInput = document.querySelector('input[type="search"]');
    if (searchInput) {
        let searchTimeout;
        
        searchInput.addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            const query = e.target.value;
            
            if (query.length < 2) {
                hideSearchResults();
                return;
            }
            
            // Задержка перед поиском
            searchTimeout = setTimeout(() => {
                performSearch(query);
            }, 300);
        });
    }
    
    /**
     * Выполнение поиска
     */
    function performSearch(query) {
        // Здесь можно реализовать AJAX запрос к серверу
        // Для примера показываем статические результаты
        const results = [
            { title: 'Пользователи', url: '/admin/users', icon: 'user' },
            { title: 'Дашборд', url: '/admin', icon: 'home' },
            { title: 'Настройки', url: '/admin/settings', icon: 'settings' }
        ].filter(item => item.title.toLowerCase().includes(query.toLowerCase()));
        
        showSearchResults(results);
    }
    
    /**
     * Отображение результатов поиска
     */
    function showSearchResults(results) {
        // Реализация отображения результатов
        console.log('Search results:', results);
    }
    
    /**
     * Скрытие результатов поиска
     */
    function hideSearchResults() {
        // Реализация скрытия результатов
    }
    
    /**
     * Подтверждение удаления
     * Добавляет подтверждение для всех форм удаления
     */
    const deleteForms = document.querySelectorAll('form[method="POST"]');
    deleteForms.forEach(form => {
        if (form.querySelector('input[name="_method"][value="DELETE"]')) {
            form.addEventListener('submit', function(e) {
                if (!confirm('Вы уверены, что хотите удалить этот элемент?')) {
                    e.preventDefault();
                }
            });
        }
    });
    
    /**
     * Автоматическое скрытие уведомлений
     */
    const notifications = document.querySelectorAll('[x-data]');
    // Alpine.js обрабатывает это автоматически
    
    /**
     * Сортировка таблиц
     * Добавляет интерактивность к заголовкам таблиц
     */
    const sortableHeaders = document.querySelectorAll('th a');
    sortableHeaders.forEach(header => {
        header.addEventListener('click', function(e) {
            // Добавляем визуальную обратную связь
            this.style.opacity = '0.7';
            setTimeout(() => {
                this.style.opacity = '1';
            }, 200);
        });
    });
    
    /**
     * Валидация форм
     * Клиентская валидация перед отправкой
     */
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('border-red-500');
                    
                    // Удаляем класс ошибки при вводе
                    field.addEventListener('input', function() {
                        this.classList.remove('border-red-500');
                    });
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                showNotification('Пожалуйста, заполните все обязательные поля', 'error');
            }
        });
    });
    
    /**
     * Показать уведомление
     * @param {string} message - Текст сообщения
     * @param {string} type - Тип: success, error, warning, info
     */
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 notification ${getNotificationClass(type)}`;
        notification.innerHTML = `
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    ${getNotificationIcon(type)}
                </svg>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Автоматическое удаление через 5 секунд
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    }
    
    /**
     * Получить CSS класс для типа уведомления
     */
    function getNotificationClass(type) {
        const classes = {
            success: 'bg-green-50 border border-green-200 text-green-800',
            error: 'bg-red-50 border border-red-200 text-red-800',
            warning: 'bg-yellow-50 border border-yellow-200 text-yellow-800',
            info: 'bg-blue-50 border border-blue-200 text-blue-800'
        };
        return classes[type] || classes.info;
    }
    
    /**
     * Получить SVG иконку для типа уведомления
     */
    function getNotificationIcon(type) {
        const icons = {
            success: '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>',
            error: '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>',
            warning: '<path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>',
            info: '<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>'
        };
        return icons[type] || icons.info;
    }
    
    /**
     * Копирование в буфер обмена
     */
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            showNotification('Скопировано в буфер обмена', 'success');
        }).catch(() => {
            showNotification('Ошибка копирования', 'error');
        });
    }
    
    // Делаем функции доступными глобально
    window.adminPanel = {
        showNotification,
        copyToClipboard,
        performSearch
    };
    
    /**
     * Keyboard shortcuts
     * Горячие клавиши для быстрого доступа
     */
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + K - Фокус на поиск
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            if (searchInput) {
                searchInput.focus();
            }
        }
        
        // Escape - Закрыть модальные окна и результаты поиска
        if (e.key === 'Escape') {
            hideSearchResults();
        }
    });
    
    /**
     * Lazy loading для изображений
     */
    const images = document.querySelectorAll('img[data-src]');
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    imageObserver.unobserve(img);
                }
            });
        });
        
        images.forEach(img => imageObserver.observe(img));
    }
    
    /**
     * Автосохранение форм (draft mode)
     */
    const draftForms = document.querySelectorAll('[data-draft="true"]');
    draftForms.forEach(form => {
        const formId = form.id || 'draft-form';
        
        // Загрузка сохраненных данных
        const savedData = localStorage.getItem(`draft-${formId}`);
        if (savedData) {
            try {
                const data = JSON.parse(savedData);
                Object.keys(data).forEach(key => {
                    const field = form.querySelector(`[name="${key}"]`);
                    if (field) field.value = data[key];
                });
            } catch (e) {
                console.error('Error loading draft:', e);
            }
        }
        
        // Автосохранение при изменении
        form.addEventListener('input', debounce(function() {
            const formData = new FormData(form);
            const data = {};
            formData.forEach((value, key) => {
                data[key] = value;
            });
            localStorage.setItem(`draft-${formId}`, JSON.stringify(data));
        }, 1000));
        
        // Очистка при успешной отправке
        form.addEventListener('submit', function() {
            localStorage.removeItem(`draft-${formId}`);
        });
    });
    
    /**
     * Debounce функция
     */
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    console.log('Admin Panel initialized - ehosting.lv');
});
