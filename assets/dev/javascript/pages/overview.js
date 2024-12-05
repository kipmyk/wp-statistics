if (!wps_js.isset(wps_js.global, 'request_params', 'page') || wps_js.global.request_params.page === "overview-new") {

    class DateManager {
        static getDateRange(filter) {
            const today = moment().format('YYYY-MM-DD');
            const currentWeekEnd = moment().endOf('week').format('YYYY-MM-DD');
            
            const ranges = {
                'today': { start: today, end: today },
                'yesterday': { 
                    start: moment().subtract(1, 'days').format('YYYY-MM-DD'),
                    end: moment().subtract(1, 'days').format('YYYY-MM-DD')
                },
                'this_week': {
                    start: moment().startOf('week').format('YYYY-MM-DD'),
                    end: currentWeekEnd
                },
                'last_week': {
                    start: moment().subtract(1, 'week').startOf('week').format('YYYY-MM-DD'),
                    end: moment().subtract(1, 'week').endOf('week').format('YYYY-MM-DD')
                },
                'this_month': {
                    start: moment().startOf('month').format('YYYY-MM-DD'),
                    end: moment().endOf('month').format('YYYY-MM-DD')
                },
                'last_month': {
                    start: moment().subtract(1, 'month').startOf('month').format('YYYY-MM-DD'),
                    end: moment().subtract(1, 'month').endOf('month').format('YYYY-MM-DD')
                },
                '7days': {
                    start: moment().subtract(6, 'days').format('YYYY-MM-DD'),
                    end: today
                },
                '30days': {
                    start: moment().subtract(29, 'days').format('YYYY-MM-DD'),
                    end: today
                },
                '90days': {
                    start: moment().subtract(89, 'days').format('YYYY-MM-DD'),
                    end: today
                },
                '6months': {
                    start: moment().subtract(6, 'months').format('YYYY-MM-DD'),
                    end: today
                },
                'this_year': {
                    start: moment().startOf('year').format('YYYY-MM-DD'),
                    end: moment().endOf('year').format('YYYY-MM-DD')
                }
            };

            return ranges[filter] || { start: null, end: null };
        }

        static formatDateRange(startDate, endDate) {
            if (!startDate || !endDate) {
                return '';
            }

            const start = moment(startDate);
            const end = moment(endDate);

            if (start.isSame(end, 'day')) {
                return start.format('MMM D, YYYY');
            }
            return `${start.format('MMM D, YYYY')} - ${end.format('MMM D, YYYY')}`;
        }

        static getDefaultDateRange() {
            const today = moment().format('YYYY-MM-DD');
            return {
                start: moment().subtract(29, 'days').format('YYYY-MM-DD'),
                end: today
            };
        }
    }

    class DatePickerHandler {
        constructor() {
            this.initializeEventListeners();
        }

        initializeEventListeners() {
            this.initializeFilterToggles();
            this.initializeMoreFilters();
            this.initializeDatePicker();
            this.initializeCustomDatePicker();
            this.initializeDateSelection();
            this.initializeFilterClicks();
        }

        initializeFilterToggles() {
            jQuery(document).off('click', '.js-filters-toggle').on('click', '.js-filters-toggle', e => {
                const $target = jQuery(e.currentTarget);
                jQuery('.js-widget-filters').removeClass('is-active');
                jQuery('.postbox').removeClass('has-focus');
                $target.closest('.js-widget-filters').toggleClass('is-active');
                $target.closest('.postbox').toggleClass('has-focus');
                
                const targetTopPosition = $target[0].getBoundingClientRect().top;
                if (targetTopPosition < 350) {
                    $target.closest('.js-widget-filters').addClass('is-down');
                }
            });
        }

        initializeMoreFilters() {
            jQuery(document).off('click', '.js-show-more-filters').on('click', '.js-show-more-filters', e => {
                e.preventDefault();
                jQuery(e.currentTarget).closest('.c-footer__filters__list').find('.js-more-filters').addClass('is-open');
            });

            jQuery(document).off('click', '.js-close-more-filters').on('click', '.js-close-more-filters', e => {
                e.preventDefault();
                jQuery(e.currentTarget).closest('.js-more-filters').removeClass('is-open');
            });
        }

        initializeDatePicker() {
            jQuery('.js-datepicker-input').each(function() {
                if (!jQuery(this).data('daterangepicker')) {
                    jQuery(this).daterangepicker({
                        autoUpdateInput: false,
                        autoApply: true,
                        locale: {
                            cancelLabel: 'Clear',
                            format: 'YYYY-MM-DD'
                        }
                    });
                }
            });
        }

        initializeCustomDatePicker() {
            jQuery(document).off('click', 'button[data-filter="custom"]').on('click', 'button[data-filter="custom"]', e => {
                const $target = jQuery(e.currentTarget);
                const metaboxKey = $target.attr("data-metabox-key");
                const dateInput = jQuery('#' + metaboxKey + ' .inside .js-datepicker-input').first();

                this.setupDateRangePicker(dateInput, metaboxKey);
                dateInput.data('daterangepicker').show();
            });
        }

        setupDateRangePicker(dateInput, metaboxKey) {
            if (!dateInput.data('daterangepicker')) {
                dateInput.daterangepicker({
                    autoUpdateInput: false,
                    autoApply: true,
                    locale: {
                        cancelLabel: 'Clear',
                        format: 'YYYY-MM-DD'
                    }
                });

                dateInput.on('apply.daterangepicker', (ev, picker) => {
                    const dates = {
                        startDate: picker.startDate.format('YYYY-MM-DD'),
                        endDate: picker.endDate.format('YYYY-MM-DD')
                    };
                    this.handleDateSelection(metaboxKey, dates, picker);
                });
            }
        }

        initializeDateSelection() {
            jQuery('.js-datepicker-input').off('apply.daterangepicker').on('apply.daterangepicker', (ev, picker) => {
                const $metabox = jQuery(ev.currentTarget).closest('.postbox');
                const metaboxId = $metabox.attr('id');
                const dates = {
                    startDate: picker.startDate.format('YYYY-MM-DD'),
                    endDate: picker.endDate.format('YYYY-MM-DD')
                };
                this.handleDateSelection(metaboxId, dates, picker);
            });

            jQuery('.js-datepicker-input').off('hide.daterangepicker').on('hide.daterangepicker', (ev, picker) => {
                if (picker.startDate) {
                    const $metabox = jQuery(ev.currentTarget).closest('.postbox');
                    const metaboxId = $metabox.attr('id');
                    const dates = {
                        startDate: picker.startDate.format('YYYY-MM-DD'),
                        endDate: picker.endDate ? picker.endDate.format('YYYY-MM-DD') : picker.startDate.format('YYYY-MM-DD')
                    };
                    this.handleDateSelection(metaboxId, dates, picker, true);
                }
            });
        }

        handleDateSelection(metaboxId, dates, picker, isHide = false) {
            const $metabox = jQuery('#' + metaboxId);
            const dateRangeText = dates.startDate === dates.endDate ?
                picker.startDate.format('MMM D, YYYY') :
                picker.startDate.format('MMM D, YYYY') + ' - ' + picker.endDate.format('MMM D, YYYY');

            this.updateUIElements($metabox, dateRangeText, isHide);
            this.loadMetaBoxData(metaboxId, dates);
        }

        updateUIElements($metabox, dateRangeText, isHide) {
            const titleText = isHide ? 'Custom Range' : wps_js._('str_custom');
            $metabox.find('.js-filter-title').text(titleText);
            $metabox.find('.hs-filter-range').text(dateRangeText);
            $metabox.find('.js-filters-toggle').text(titleText);
            $metabox.find('.c-footer__filters').removeClass('is-active');
            jQuery('.postbox.has-focus').removeClass('has-focus');
        }

        initializeFilterClicks() {
            jQuery(document).off('click', '.c-footer__filters__list-item:not(.js-show-more-filters):not(.js-close-more-filters):not([data-filter="custom"])')
                .on('click', '.c-footer__filters__list-item:not(.js-show-more-filters):not(.js-close-more-filters):not([data-filter="custom"])', e => {
                    const $target = jQuery(e.currentTarget);
                    const filter = $target.data('filter');
                    const $metabox = $target.closest('.postbox');
                    const metaboxId = $metabox.attr('id');
                    const dates = DateManager.getDateRange(filter);

                    this.updateFilterUI($metabox, $target, dates);
                    this.loadMetaBoxData(metaboxId, dates, filter);
                });
        }

        updateFilterUI($metabox, $target, dates) {
            $metabox.find('.js-filter-title').text($target.text());
            $metabox.find('.hs-filter-range').text(DateManager.formatDateRange(dates.start, dates.end));
            $metabox.find('.js-filters-toggle').text($target.text());
            $metabox.find('.c-footer__filters').removeClass('is-active');
            $target.closest('.postbox.has-focus').removeClass('has-focus');
        }

        loadMetaBoxData(metaboxId, dates, filter = 'custom') {
            wps_js.showLoadingSkeleton(metaboxId);
            loadMetaBoxData(metaboxId, dates.startDate || dates.start, dates.endDate || dates.end, filter)
                .then(response => {
                    if (typeof wps_js[`render_${metaboxId}`] === 'function') {
                        wps_js[`render_${metaboxId}`](response, metaboxId);
                    }
                })
                .catch(error => console.error(`Error loading metabox ${metaboxId}:`, error));
        }
    }

    // Initialize DatePickerHandler
    wps_js.datePickerHandler = new DatePickerHandler();
    wps_js.initDatePickerHandlers = function() {
        wps_js.datePickerHandler.initializeEventListeners();
    };

    // Rest of your existing code...
    const meta_list = wps_js.global.meta_boxes;

    function loadMetaBoxData(metaBoxKey, startDate = null, endDate = null, date_filter = null) {
        return new Promise((resolve, reject) => {
            let data = {
                'action': `wp_statistics_${metaBoxKey}_metabox_get_data`,
                'wps_nonce': wps_js.global.rest_api_nonce
            };

            if (date_filter) {
                data.date_filter = date_filter;
            }

            if (startDate && endDate) {
                data.from = startDate;
                data.to = endDate;
            }

            const successHandler = `${metaBoxKey}_success`;
            const errorHandler = `${metaBoxKey}_error`;

            wps_js[successHandler] = function (data) {
                resolve(data);
                return data;
            };

            wps_js[errorHandler] = function (error) {
                reject(error);
                return error;
            };

            wps_js.ajaxQ(
                wps_js.global.admin_url + 'admin-ajax.php',
                data,
                successHandler,
                errorHandler,
                'GET',
                false
            );
        });
    }

    // Initialize meta boxes
    meta_list.forEach((metaBoxKey) => {
        loadMetaBoxData(metaBoxKey).then(response => {
            if (typeof wps_js[`render_${metaBoxKey}`] === 'function') {
                wps_js[`render_${metaBoxKey}`](response, metaBoxKey);
            }
        });

        jQuery(document).on('click', `#${metaBoxKey} .wps-refresh`, function () {
            wps_js.showLoadingSkeleton(metaBoxKey);
            loadMetaBoxData(metaBoxKey).then(response => {
                if (typeof wps_js[`render_${metaBoxKey}`] === 'function') {
                    wps_js[`render_${metaBoxKey}`](response, metaBoxKey);
                }
            });
        });
    });

    // Export utility functions
    wps_js.metaBoxInner = key => jQuery('#' + key + ' .inside');
    
    wps_js.showLoadingSkeleton = function(metaBoxKey) {
        let metaBoxInner = jQuery('#' + metaBoxKey + ' .inside');
        metaBoxInner.html('<div class="wps-skeleton-container"><div class="wps-skeleton-container__skeleton wps-skeleton-container__skeleton--full wps-skeleton-container__skeleton--h-150"></div></div>');
    };

    wps_js.handelReloadButton = key => {
        const selector = "#" + key + " .handle-actions button:first";
        if (!jQuery('#' + key + ' .wps-refresh').length) {
            jQuery(`<button class="handlediv wps-refresh" type="button" title="${wps_js._('reload')}"></button>`).insertBefore(selector);
        }
    };

    wps_js.handelMetaBoxFooter = function (key,response){
        let html = '<div class="c-footer"><div class="c-footer__filter js-widget-filters">';
        if (response.options && response.options.datepicker) {
            let startDateResponse;
            let endDateResponse;
            let dateFilterTitle =wps_js._(`str_30days`);
            let dateFilterType = wps_js._(`str_30days`);
            if (response?.filters && response.filters.date && response.filters.date.filter) {
                dateFilterType = response.filters.date.type === 'custom' ? response.filters.date.filter : wps_js._(`str_${response.filters.date.filter}`);
                startDateResponse = response.filters.date.from;
                endDateResponse = response.filters.date.to;
                dateFilterTitle = response.filters.date.type === 'custom' ? wps_js._('str_custom') : wps_js._(`str_${response.filters.date.filter}`)
            }

            html += `
                <button class="c-footer__filter__btn js-filters-toggle">` + dateFilterType + `</button>
                <div class="c-footer__filters">
                    <div class="c-footer__filters__current-filter">
                        <span class="c-footer__current-filter__title js-filter-title">` + dateFilterTitle + `</span>
                         <span class="c-footer__current-filter__date-range hs-filter-range">` + startDateResponse + ' _ ' + endDateResponse + `</span>
                    </div>
                    <div class="c-footer__filters__list">
                        <button data-metabox-key="${key}" data-filter="today" class="c-footer__filters__list-item">` + wps_js._('str_today') + `</button>
                        <button data-metabox-key="${key}" data-filter="yesterday" class="c-footer__filters__list-item">` + wps_js._('str_yesterday') + `</button>
                        <button data-metabox-key="${key}" data-filter="this_week" class="c-footer__filters__list-item">` + wps_js._('str_this_week') + `</button>
                        <button data-metabox-key="${key}" data-filter="last_week" class="c-footer__filters__list-item">` + wps_js._('str_last_week') + `</button>
                        <button data-metabox-key="${key}" data-filter="this_month" class="c-footer__filters__list-item">` + wps_js._('str_this_month') + `</button>
                        <button data-metabox-key="${key}" data-filter="last_month" class="c-footer__filters__list-item">` + wps_js._('str_last_month') + `</button>
                        <button class="c-footer__filters__list-item c-footer__filters__list-item--more js-show-more-filters">` + wps_js._('str_more') + `</button>
                        <div class="c-footer__filters__more-filters js-more-filters">
                            <button data-metabox-key="${key}" data-filter="7days" class="c-footer__filters__list-item">` + wps_js._('str_7days') + `</button>
                            <button data-metabox-key="${key}" data-filter="30days" class="c-footer__filters__list-item">` + wps_js._('str_30days') + `</button>
                            <button data-metabox-key="${key}" data-filter="90days" class="c-footer__filters__list-item">` + wps_js._('str_90days') + `</button>
                            <button data-metabox-key="${key}" data-filter="6months" class="c-footer__filters__list-item">` + wps_js._('str_6months') + `</button>
                            <button data-metabox-key="${key}" data-filter="this_year" class="c-footer__filters__list-item">` + wps_js._('str_this_year') + `</button>
                            <button class="c-footer__filters__close-more-filters js-close-more-filters">` + wps_js._('str_back') + `</button>
                        </div>
                        <input type="text" class="c-footer__filters__custom-date-input js-datepicker-input"/>
                        <button data-metabox-key="${key}" data-filter="custom" class="c-footer__filters__list-item c-footer__filters__list-item--custom js-custom-datepicker">` + wps_js._('str_custom') + `</button>
                    </div>
                </div> `;
        }
        html += `</div>`;
        if (response.options && response.options.button) {
            html += response.options.button;
        }
        html += `</div></div>`;

        wps_js.metaBoxInner(key).append(html);
    }
}