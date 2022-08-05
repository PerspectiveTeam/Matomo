define([
    'jquery',
    'ko',
    'Magento_Ui/js/form/element/abstract',
    'mageUtils',
    'chart',
    'Perspective_Matomo/js/helpers/moment.min',
    'mage/cookies',
    'mage/translate',
    'loader',
    'domReady!'
], function ($, ko, Abstract, utils, Chart, moment) {
    'use strict';

    return Abstract.extend({
        defaults: {
            template: 'Perspective_Matomo/form/element/deal_management_graph',
            uid: utils.uniqueid(),
            inputName: 'deal_management_graph',
            fetchBackendUrl: '',
            endDate: '',
            startDate: '',
            isRunning: false,
            changeableTermsArray: [],
            dealsDates: [],
            dealsInfoArray: [],
            dealsPageviews: [],
        },

        initialize: function (config) {
            this._super();
            this.fetchBackendUrl(config.fetchBackendUrl);
            // This is example to frontend to sepcify date range
            // by default it will be set to last 7 days
            $('.deal-management-container').trigger('processStart');
            this.setStartDate(moment().subtract('days', 6).format('YYYY-MM-DD'));
            this.setEndDate(moment().format('YYYY-MM-DD'));
            //this.setStartDate('2022-01-14');
            //this.setEndDate('2022-01-20');
            this.getGraphData(this);
            return this;
        },
        initObservable: function () {
            this._super();
            this.observe('fetchBackendUrl');
            this.observe('startDate');
            this.observe('endDate');
            this.observe('isRunning');
            this.changeableTermsArray = ko.observableArray();
            this.dealsDates = ko.observableArray();
            this.dealsInfoArray = ko.observableArray();
            return this;
        },
        setStartDate: function (startDate) {
            this.startDate(startDate); // In Y-m-d format
        },
        setEndDate: function (endDate) {
            this.endDate(endDate); // In Y-m-d format
        },
        getRange: function () {
            return this.startDate() + ',' + this.endDate();
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
            let array = context.dealsInfoArray().slice(0);
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
            context.dealsInfoArray.push({
                data: data,
                checked: currentItem?.is_checked,
                deal_id: currentItem.deal_id,
                deal_name: currentItem.name,
                pageviews: currentItem.nb_visits,
                uni_pageviews: currentItem.nb_uniq_visitors,
                avg_time_on_page: currentItem.avg_time_on_page,
                cta_views: currentItem.ctaCount,
                outlinks: currentItem.exit_nb_visits,
                ...currentItem
            });
        },
        getGraphData: function (context) {
            let main = this;
            if (context?.isRunning() === false) {
                context.isRunning(true);
                // handle response starting from here
                //for example
                context.fetchPageUrlsData(context).done(function (result) {
                    // console.log(result);
                    if (result?.success) {
                        context.dealsDates.removeAll();
                        context.dealsInfoArray.removeAll();
                        context.dealsDates = Object.keys(result?.response?.pageUrls);
                        context.dealsDates = context.dealsDates.map((e) => {
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
                                    // let data = [];
                                    // let obj = {};
                                    // obj[item[0]] = currentItem.nb_visits;
                                    // data.push(obj);
                                    let data = context.dealsDates.slice(0);
                                    let index = data.indexOf(item[0]);
                                    data[index] = currentItem.nb_visits;
                                    for(let i=0; i < data.length; i++) {
                                        if (i !== index) {
                                            data[i] = '';
                                        }
                                    }
                                    context.pushUpdatedItem(context, currentItem, data);
                                }
                            });
                        });
                    }
                    context.isRunning(false);
                    main.paintChart();
                });
            }
        },
        dynamicColors : function() {
            let r = Math.floor(Math.random() * 255);
            let g = Math.floor(Math.random() * 255);
            let b = Math.floor(Math.random() * 255);
            return "rgb(" + r + "," + g + "," + b + ")";
        },
        prepareDataForChart : function() {
            const data = this.dealsInfoArray();
            const newData = data.map(item => {
                let label = item.deal_id;
                let daysDate = item.data;
                let color = this.dynamicColors();
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
        paintChart: function ()  {
            const labels = this.dealsDates;
            $('.deal-management-container').trigger('processStop');
            const myChart = new Chart(
                document.getElementById('myChart').getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: this.prepareDataForChart(),
                    },
                    options: {
                        maintainAspectRatio: false,
                        animations: {
                            radius: {
                                duration: 400,
                                easing: 'linear',
                                loop: (context) => context.active
                            }
                        },
                        plugins: {
                            title: {
                                display: true,
                                text: 'Deal ID',
                                padding: {
                                    top: 10,
                                    bottom: 15
                                },
                                align: 'end',
                            },
                            legend: {
                                labels: {
                                    usePointStyle: true,
                                    boxWidth: 6
                                },
                                align: 'end',
                                fullSize: true
                            }
                        },
                        hoverRadius: 6,
                        hoverBackgroundColor: '#E32690',
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                }
                            },
                            y: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    },
                }
            );
        }
    });
});
