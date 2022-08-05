define([
    'jquery',
    'ko',
    'Magento_Ui/js/form/element/abstract',
    'mageUtils',
    'Perspective_Matomo/js/helpers/moment.min',
    'Perspective_Matomo/js/helpers/chartDrawer',
    'postbox',
    'mage/translate',
    'Perspective_Matomo/js/page/rows/row',
    'vue',
    'Perspective_Matomo/js/helpers/daterangepicker',
    'Perspective_Matomo/js/helpers/multiple-select',
    'Perspective_Matomo/js/helpers/jquery.mCustomScrollbar.min',
    'Perspective_Matomo/js/helpers/jquery.mousewheel.min',
    'mage/cookies',
    'loader',
    'domReady!'
], function ($, ko, Abstract, utils, moment, chartDrawer, postbox, $t) {
    'use strict';
    var self;
    let pageMissing = $t('Product page is missing');
    return Abstract.extend({
        defaults: {
            template: 'Perspective_Matomo/form/element/statistics',
            uid: utils.uniqueid(),
            inputName: 'statistics',
            chart: false,
            fetchBackendUrl: '',
            endDate: '',
            startDate: '',
            isRunning: false,
            firstRun: true,
            select: '',
            pageViews: '',
            highestPerformance: [],
            dealsDates: [],
            changeableTermsArray: [],
            dealsInfoArray: [],
            dealsIdInfoArray: [],
            checkedArray: [],
            availableDealID: [],
            percentBar: [],
            bannerData: [],
            tempArray: [],
        },

        initialize: function (config) {
            self = this;
            this._super();
            this.fetchBackendUrl(config.fetchBackendUrl);
            // This is example to frontend to sepcify date range
            // by default it will be set to last 30 days
            $('.deal-management-container').trigger('processStart');
            this.setStartDate( moment().subtract('days', 29).format('YYYY-MM-DD'));
            this.setEndDate(moment().format('YYYY-MM-DD'));
            this.tableListener();
            this.getGraphData(this);
            return this;
        },
        initObservable: function () {
            this._super();
            this.observe('fetchBackendUrl');
            this.observe('startDate');
            this.observe('endDate');
            this.observe('isRunning');
            this.observe('firstRun');
            this.observe('pageViews');
            this.highestPerformance = ko.observableArray();
            this.dealsDates = ko.observableArray();
            this.changeableTermsArray = ko.observableArray();
            this.dealsInfoArray = ko.observableArray();
            this.dealsIdInfoArray = ko.observableArray();
            this.checkedArray = ko.observableArray();
            this.availableDealID = ko.observableArray();
            this.percentBar = ko.observableArray();
            this.bannerData = ko.observableArray();
            return this;
        },
        setStartDate: function (startDate) {
            return this.startDate(startDate); // In Y-m-d format
        },
        setEndDate: function (endDate) {
            return this.endDate(endDate); // In Y-m-d format
        },
        getRange: function () {
            return this.startDate() + ',' + this.endDate();
        },
        tableListener: function () {
            $(document).on('click', '.matomo-data input', function () {
                let id = $(this).attr('id');
                if (!this.checked) {
                    window.chartDrawerInstance.data.datasets = jQuery.grep(window.chartDrawerInstance.data.datasets, function(item) {
                        if (item.label == id) {
                            self.tempArray.push(item);
                        }
                        return item.label != id;
                    });
                    window.chartDrawerInstance.update();
                } else {
                    let data = self.tempArray;
                    const index = data.findIndex(object => {
                        return object.label == id;
                    });
                    window.chartDrawerInstance.data.datasets.push(data[index]);
                    window.chartDrawerInstance.update();
                }

            });
        },
        dateRangeInit: function () {
            $('.date-range').daterangepicker({
                maxYear: moment().year(Number),
                locale: {
                    format: 'DD.MM.YYYY'
                },
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
                    'Last 7 Days': [moment().subtract('days', 6), moment()],
                    'Last 30 Days': [moment().subtract('days', 29), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
                },
                alwaysShowCalendars: true,
                startDate: moment().subtract('days', 29),
                endDate: moment(),
                opens: "center"
            }, function(start, end, label) {
                self.changeTerms(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
                $('.deal-management-container').trigger('processStart');
            });

            $('.ranges .ms-choice').on('click', function() {
                $(this).next().toggle();
            })
            $('.ranges li').on('click', function() {
                $('.ranges .placeholder').text($(this).text());
                $('.ranges .ms-drop').toggle();
            })
        },
        multiSelectInit: function () {
            let placeholderText = $t('Select deal');
            self.select = $('.multiple-select')
            self.select.multipleSelect({
                placeholder: placeholderText
            });
            $('.multiple-select .ms-drop').mCustomScrollbar({
                theme:"minimal-dark"
            });
        },
        modifyChangeableTerms: function (indexNew, valueNew, context) {
            $.each(this.changeableTermsArray(), function (index, value) {
                if (value && value['index'] === indexNew) {
                    context.changeableTermsArray(context.changeableTermsArray().splice(index, 1));
                }
            });
            this.changeableTermsArray().push({
                index: indexNew,
                value: valueNew
            });
        },
        processDateRange: function () {
            let today = new Date();
            if (!this.startDate() && !this.endDate()) {
                let lastweek = new Date(today.getFullYear(), today.getMonth(), today.getDate() - 7, 0, 0, 0, 0)
                    .toISOString()
                    .split('T')[0];
                this.modifyChangeableTerms(
                    'range',
                    lastweek + ',' + today.toISOString().split('T')[0],
                    this);
            } else {
                this.modifyChangeableTerms('range', this.getRange(), this);
            }
            return this.changeableTermsArray();
        },
        fetchPageUrlsData: function (context) {
            let formData = new FormData();
            this.processDateRange();
            $.each(this.changeableTermsArray(), function (index, value) {
                formData.append(value['index'], value['value']);
            });
            formData.append('form_key', $.mage.cookies.get('form_key'));
            return $.ajax({
                url: this.fetchBackendUrl(),
                method: 'POST',
                data: formData,
                dataType: "JSON",
                contentType: false,
                enctype: 'multipart/form-data',
                processData: false
            });
        },
        changeTerms: function (start, end) {
            self.setStartDate(start); // In Y-m-d format
            self.setEndDate(end); // In Y-m-d format
            self.getGraphData(self, true);
        },
        //little hack to persist the checkbox state (virtual element) after ajax refresh call
        onChange: function (ev, target) {
            // let context = ko.contextFor(target.target).$parent;
            // let index = context.checkedArray().indexOf(ev.deal_id);
            // if (~index) {
            //     context.checkedArray().splice(index, 1);
            // } else {
            //     context.checkedArray().push(ev.deal_id);
            // }
        },
        getDealIdFromUrl: function (url) {
            let regex = /.*\/id\/(.*?)\//gm;
            let m;
            let result;
            while ((m = regex.exec(url)) !== null) {
                // This is necessary to avoid infinite loops with zero-width matches
                if (m.index === regex.lastIndex) {
                    regex.lastIndex++;
                }

                // The result can be accessed through the `m`-variable.
                m.forEach((match, groupIndex) => {
                    if (groupIndex === 1) {
                        result = match;
                    }
                });
            }
            return result;
        },
        pushUpdatedItem: function (context, currentItem, data) {
            let backgroundColor = this.dynamicColors();
            let array = context.dealsInfoArray();
            let index = array.findIndex(obj => obj.deal_id === currentItem.deal_id)
            if (array.some(obj => obj.deal_id === currentItem.deal_id)) {
                let newArray = array[index].data.map((e, i) => {
                    if (e === '' && data[i] === '') {
                        return ''
                    }
                    if (data[i] === '') {
                        return parseInt(e)
                    }
                    if (e === '') {
                        return parseInt(data[i])
                    }
                    return parseInt(e) + parseInt(data[i]);
                });
                let updatedElement = {
                    data: newArray,
                    pageviews: array[index].pageviews + currentItem.nb_visits,
                    uni_pageviews: array[index].uni_pageviews + currentItem.nb_uniq_visitors,
                    avg_time_on_page: array[index].avg_time_on_page + currentItem.avg_time_on_page,
                    cta_views: parseInt(array[index].cta_views) + parseInt(currentItem.ctaCount),
                    outlinks: parseInt(array[index].outlinks) + parseInt(currentItem.exit_nb_visits),
                    color: array[index].color,
                    deal_name: array[index].deal_name,
                    ...currentItem
                }
                context.dealsInfoArray.replace(context.dealsInfoArray()[index], updatedElement);
                return
            }
            context.availableDealID.push(currentItem.deal_id);
            context.dealsInfoArray.push({
                data: data,
                checked: currentItem?.is_checked,
                deal_id: currentItem.deal_id,
                deal_name: currentItem.name !== undefined ? currentItem.name : pageMissing,
                pageviews: currentItem.nb_visits,
                uni_pageviews: currentItem.nb_uniq_visitors,
                avg_time_on_page: currentItem.avg_time_on_page,
                cta_views: currentItem.ctaCount,
                outlinks: currentItem.exit_nb_visits,
                color: backgroundColor,
                ...currentItem
            });
        },
        getDealId: function (context, currentItem, data) {
            let backgroundColor = this.dynamicColors();
            let array = context.dealsIdInfoArray();
            let index = array.findIndex(obj => obj.deal_id === currentItem.deal_id)
            if (array.some(obj => obj.deal_id === currentItem.deal_id)) {
                let newArray = array[index].data.map((e, i) => {
                    if (e === '' && data[i] === '') {
                        return ''
                    }
                    if (data[i] === '') {
                        return parseInt(e)
                    }
                    if (e === '') {
                        return parseInt(data[i])
                    }
                    return parseInt(e) + parseInt(data[i]);
                });
                let updatedElement = {
                    data: newArray,
                    pageviews: array[index].pageviews + currentItem.nb_visits,
                    uni_pageviews: array[index].uni_pageviews + currentItem.nb_uniq_visitors,
                    avg_time_on_page: array[index].avg_time_on_page + currentItem.avg_time_on_page,
                    cta_views: parseInt(array[index].cta_views) + parseInt(currentItem.ctaCount),
                    outlinks: parseInt(array[index].outlinks) + parseInt(currentItem.exit_nb_visits),
                    color: array[index].color,
                    deal_name: array[index].deal_name,
                    ...currentItem
                }
                context.dealsIdInfoArray.replace(context.dealsIdInfoArray()[index], updatedElement);
                return
            }
            context.availableDealID.push(currentItem.deal_id);
            context.dealsIdInfoArray.push({
                data: data,
                checked: currentItem?.is_checked,
                deal_id: currentItem.deal_id,
                deal_name: currentItem.name !== undefined ? currentItem.name : pageMissing,
                pageviews: currentItem.nb_visits,
                uni_pageviews: currentItem.nb_uniq_visitors,
                avg_time_on_page: currentItem.avg_time_on_page,
                cta_views: currentItem.ctaCount,
                outlinks: parseInt(currentItem?.exit_nb_visits) ? parseInt(currentItem?.exit_nb_visits) : 0,
                color: backgroundColor,
                ...currentItem
            });
        },
        processSuccess: function (context, result, onlyDealID) {
            if (!onlyDealID) {
                context.highestPerformance.removeAll();
                context.dealsInfoArray.removeAll();
                context.percentBar.removeAll();
                context.bannerData.removeAll();
            }
            context.dealsIdInfoArray.removeAll();
            context.availableDealID.removeAll();
            self.dealsDates = Object.keys(result?.response?.pageUrls);
            self.dealsDates = self.dealsDates.map((e) => {
                let date = e.split('-')
                return  date[2] + '.' + date[1] + '.' + date[0];
            })
            Object.entries(result?.response?.pageUrls).map(item => {
                // item[1] - is a specific day
                // inside item2[1] - data for specific url
                Object.entries(item[1]).map(item2 => {
                    let currentItem = item2[1];
                    currentItem.deal_id = context.getDealIdFromUrl(currentItem.label);
                    if (currentItem.deal_id) {
                        // if (context.checkedArray().indexOf(currentItem.deal_id) !== -1) {
                        //     currentItem.is_checked = true;
                        // } else {
                        //     currentItem.is_checked = false;
                        // }
                        currentItem.is_checked = true;
                        currentItem.outlinks = parseInt(currentItem?.exit_nb_visits) ? parseInt(currentItem?.exit_nb_visits) : 0;
                        let params = new URLSearchParams(window.location.search)
                        if (context.firstRun() && params.get('entity_id')) {
                            let productIdArray = params.get('entity_id').split(',');
                            if (productIdArray.includes(currentItem.deal_id)){
                                currentItem.is_checked = true;
                                // context.checkedArray().push(currentItem.deal_id)
                            }
                        }
                        let data = context.dealsDates.slice(0);
                        let index = data.indexOf(item[0]);
                        data[index] = currentItem.nb_visits;
                        for(let i=0; i < data.length; i++) {
                            if (i !== index) {
                                data[i] = '';
                            }
                        }
                        if (onlyDealID) {
                            context.getDealId(context, currentItem, data);
                        } else {
                            context.pushUpdatedItem(context, currentItem, data);
                        }

                    }
                });
            });
            let ack = false;
            postbox.subscribe("dataRowsAck", function (value) {
                ack = true;
            }, this);
            var vueInterval = setInterval(function () {
                postbox.publish('dataRows', [context.dealsInfoArray(), []]);
                jQuery('body').trigger('contentUpdated');
                if (ack) {
                    clearInterval(vueInterval);
                }
            }, 10);
            self.select.multipleSelect('refresh');
            $('.multiple-select .ms-drop').mCustomScrollbar({
                theme:"minimal-dark"
            });
            $('.deal-management-container').trigger('processStop');
            console.log(self.dealsInfoArray());
            context.highestPerformance.push(self.highestPerformanceFn());
            if (context.dealsInfoArray().length > 0) {
                self.horizontalBar();
                self.calculateBannerData();
            }
            if (!onlyDealID) {
                self.paintChart();
            }
        },
        getGraphData: function (context, onlyDealID) {
            // handle response starting from here
            context.fetchPageUrlsData(context).done(function (result) {
                if (result.success && !onlyDealID) {
                    context.processSuccess(context, result);
                } else {
                    context.processSuccess(context, result, onlyDealID);
                }
                context.isRunning(false);
                context.firstRun(false);
            });
        },
        dynamicColors : function() {
            let r = Math.floor(Math.random() * 255);
            let g = Math.floor(Math.random() * 255);
            let b = Math.floor(Math.random() * 255);
            return "rgb(" + r + "," + g + "," + b + ")";
        },
        highestPerformanceFn: function() {
            self.highestPerformance.removeAll();
            const data = this.dealsInfoArray().slice(0);
            if (data.length > 0) {
                return data.reduce((prev, current) => {
                    return prev.pageviews > current.pageviews ? prev : current;
                });
            } else {
                return {
                    deal_id: 0,
                    deal_name: 0,
                    pageviews: 0,
                    uni_pageviews: 0,
                    avg_time_on_page: 0,
                    cta_views: 0,
                    outlinks: 0,
                }
            }
        },
        filterDataBySelect: function (arrayFromSelect) {
            let data = self.dealsIdInfoArray().slice(0);
            if (data.length < 1) {
                data = self.dealsInfoArray().slice(0);
            }
            let selectArr;
            if (arrayFromSelect !== null && arrayFromSelect.length > 0) {
                selectArr = arrayFromSelect;
                self.dealsInfoArray.removeAll();
                let filteredArray = data.filter(function(item){
                    item.is_checked = true;
                    return selectArr.indexOf(item.deal_id) > -1;
                });
                filteredArray.forEach(element => self.dealsInfoArray.push(element))
                self.paintChart();
            } else {
                self.dealsInfoArray.removeAll();
                // this.getGraphData(this);
                data.forEach(element => self.dealsInfoArray.push(element))
                self.paintChart();
            }
            self.percentBar.removeAll();
            self.bannerData.removeAll();
            self.horizontalBar();
            self.calculateBannerData();
            console.log(self.dealsInfoArray());
            console.log(self.highestPerformanceFn());
            self.highestPerformance.push(self.highestPerformanceFn());
        },
        exportByFilter: function () {
            let arr = $('.multiple-select').val();
            self.filterDataBySelect(arr);
        },
        horizontalBar: function () {
            const data = self.dealsInfoArray().slice(0);
            let sumPageViews = data.map(obj => obj.pageviews).reduce((a, c) => { return a + c });
            data.map(item => {
                let label = item.deal_id;
                let width = (item.pageviews/(sumPageViews/100)).toFixed(1);
                self.percentBar.push({
                    label,
                    backgroundColor:  item.color,
                    cellWidth: width,
                });
            });
        },
        calculateBannerData: function () {
            const data = self.dealsInfoArray().slice(0);
            let sumPageViews = data.map(obj => obj.pageviews).reduce((a, c) => { return a + c });
            self.pageViews(sumPageViews);
            let dealsCount = data.length;
            let platform = 0;
            let SN = 0;
            let SAN = 0;
            let banner = 0;
            data.map(item => {
                if (!item.label.includes('?')) {
                    platform += 1;
                    return
                }
                if (item.label.includes('?NL=1')) {
                    SN += 1;
                    return;
                }
                if (item.label.includes('?SAL-NL=1')) {
                    SAN += 1;
                    return;
                }
                banner += 1;
            });
            const arrOfBanners = [
                {
                    name: 'Platform',
                    percent: (platform/(dealsCount/100)).toFixed(1)
                },
                {
                    name: 'Sammel Newsletter',
                    percent: (SN/(dealsCount/100)).toFixed(1)
                },
                {
                    name: 'Stand Alone Newsletter',
                    percent: (SAN/(dealsCount/100)).toFixed(1)
                },
                {
                    name: 'Banner',
                    percent: (banner/(dealsCount/100)).toFixed(1)
                }
            ]

            arrOfBanners.map(item => {
                self.bannerData.push(item);
            });

        },
        prepareDataForChart : function() {
            const data = self.dealsInfoArray();
            const newData = data.map(item => {
                let label = item.deal_id;
                let daysDate = item.data;
                let color = item.color;
                return {
                    label,
                    data: daysDate,
                    fill: false,
                    backgroundColor:  color,
                    borderColor: color,
                    pointStyle: 'circle',
                    pointRadius: 3,
                }
            });
            return newData
        },
        paintChart: function () {
            const labels = this.dealsDates;
            const datasets = this.prepareDataForChart();
            self.tempArray = [];
            if (this.chart) {
                this.chart.destroy();
            }
            $('.deal-management-container').trigger('processStop');
            this.chart = chartDrawer(labels, datasets);
        }
    });
});
