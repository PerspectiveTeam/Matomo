define([
    'jquery',
    'ko',
    'vue',
    'postbox',
    'mageUtils',
    'mage/translate',
    'domReady!'
], function ($, ko, Vue, postbox, utils) {
    'use strict';
    return function (config, element) {
        return new Vue({

            el: '#dealManagementRowsScopeId',
            data: {
                items: [],
                i18n: {
                    deal_id:  $.mage.__('Deal ID'),
                    deal_name:  $.mage.__('Deal Name'),
                    pageviews:  $.mage.__('Pageviews'),
                    uni_pageviews:  $.mage.__('Unique Pageviews'),
                    avg_time_on_page:  $.mage.__('Avg. Time on Page'),
                    cta_views:  $.mage.__('CTA-Views'),
                    outlinks:  $.mage.__('Outlinks')
                }
                // items: value[0]
            },
            methods: {},
            beforeCreate: function () {
                postbox.subscribe("dataRows", function (value) {
                    postbox.publish('dataRowsAck', true);
                    this.items = value[0]
                }, this);
                console.log('mounted()');
            }
        });
    }
});
