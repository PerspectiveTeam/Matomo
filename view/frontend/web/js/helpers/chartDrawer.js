define([
    'jquery',
    'chart'
], function ($) {
    'use strict';

    return function (labels, datasets) {
        if (window.chartDrawerInstance) {
            window.chartDrawerInstance.destroy();
        }
        window.chartDrawerInstance = new Chart(
            document.getElementById('myChart').getContext('2d'), {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: datasets,
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
                                boxWidth: 6,
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
                            },
                            ticks: {
                                color: '#222121',
                                font: {
                                    size: 14,
                                },
                            }
                        },
                        y: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#222121',
                                font: {
                                    size: 14,
                                },
                            }
                        }
                    }
                },
            }
        );
        return window.chartDrawerInstance;
    };
});
