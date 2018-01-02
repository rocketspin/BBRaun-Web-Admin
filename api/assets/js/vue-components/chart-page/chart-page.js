(function(){
    'use strict';

    var chartConfig = {responsive: true, maintainAspectRatio: true,
        scales: {
            yAxes: [
                {
                    ticks: {
                        min: 0,
                        max: 100,
                        stepSize: 20,
                    },
                    scaleLabel: {
                        display: true,
                        labelString: 'Percent (%)'
                    }
                },

            ],
            xAxes: [
                {
                    categoryPercentage: 0.7,
                    barPercentage: 0.4,
                    ticks: {
                        autoSkip: false,
                        fontSize: 9
                    }
                }
            ]
        }
    };

    var chartCountConfig = {responsive: true, maintainAspectRatio: true,
        scales: {
            yAxes: [
                {
                    ticks: {
                        min: 0,
                        stepSize: 200
                    },
                    scaleLabel: {
                        display: true,
                        labelString: 'Count'
                    }
                },

            ],
            xAxes: [
                {
                    categoryPercentage: 0.7,
                    barPercentage: 0.4,
                    ticks: {
                        autoSkip: false
                    }
                }
            ]
        }
    };

    var chartDataTemplate = {
        labels: [],
        dataset: [],
        datasets: [
            {
                label: 'Compliance',
                backgroundColor: '#00B582',
                data: []
            }
      ]
    };

    var tableColumns = [{
        data: 'date_registered',
        title: "Date Time"
        }, {
            data: 'username',
            title: "Auditor"
        }, {
            data: 'location_1_name',
            title: "Location 1"
        }, {
            data: 'location_2_name',
            title: "Location 2"
        }, {
            data: 'location_3_name',
            title: "Location 3"
        }, {
            data: 'location_4_name',
            title: "Location 4"
        }, {
            data: 'hcw_titlename',
            title: "Title"
        }, {
            data: 'hcw_name',
            title: "Name"
        }, {
            data: 'moment1',
            title: "Moment 1"
        }, {
            data: 'moment2',
            title: "Moment 2"
        }, {
            data: 'moment3',
            title: "Moment 3"
        }, {
            data: 'moment4',
            title: "Moment 4"
        }, {
            data: 'moment5',
            title: "Moment 5"
        }, {
            data: 'hh_compliance',
            title: "Action"
        }, {
            data: 'result',
            title: "Result"
        }, {
            data: 'hh_compliance_type',
            title: "Exposure Risk"
        }, {
            data: 'glove_compliance',
            title: "Gloves"
        }, {
            data: 'gown_compliance',
            title: "Gown"
        }, {
            data: 'mask_compliance',
            title: "Mask"
        }, {
            data: 'mask_type',
            title: "Mask Type"
        }, {
            data: 'note',
            title: "Notes"
        }];

      Vue.use(VueCharts);
      var vm = new Vue({
        el: '#app',
        data: {
            loading: false,
            chartConfig: chartConfig,
            chartCountConfig: chartCountConfig,
            companies: [],
            usersLookup: [],
            hcwLookup: [],
            loc1Lookup: [],
            loc2Lookup: [],
            loc3Lookup: [],
            loc4Lookup: [],
            users: [],
            hcw: [],
            loc1: [],
            loc2: [],
            loc3: [],
            loc4: [],
            selected: {
                company: '',
                user: '',
                hcw: '',
                loc1: '',
                loc2: '',
                loc3: '',
                loc4: '',
                startDate: moment().add(-1, 'days').format('YYYY-MM-DD'),
                endDate: moment().add(-1, 'days').format('YYYY-MM-DD'),
                complianceOptions: [
                    "loc1",
                    "loc2",
                    "loc3",
                    "loc4",
                    "hcw",
                    "cpm",
                    "cbm",
                    "loc1hcw",
                    "loc1m",
                    "loc2hcw",
                    "loc2m",
                    "loc3hcw",
                    "loc3m",
                    "loc4hcw",
                    "loc4m",
                ],
            },
            rawData: [],
            chartData: {
                loc1: _.cloneDeep(chartDataTemplate),
                loc2: _.cloneDeep(chartDataTemplate),
                loc3: _.cloneDeep(chartDataTemplate),
                loc4: _.cloneDeep(chartDataTemplate),
                hcw: _.cloneDeep(chartDataTemplate),
                cpm: _.cloneDeep(chartDataTemplate),
                cbm: _.cloneDeep(chartDataTemplate),
                loc1hcw: _.cloneDeep(chartDataTemplate),
                loc1m: _.cloneDeep(chartDataTemplate),
                loc2hcw: _.cloneDeep(chartDataTemplate),
                loc2m: _.cloneDeep(chartDataTemplate),
                loc3hcw: _.cloneDeep(chartDataTemplate),
                loc3m: _.cloneDeep(chartDataTemplate),
                loc4hcw: _.cloneDeep(chartDataTemplate),
                loc4m: _.cloneDeep(chartDataTemplate),
            },
            chunkedChartData: {}
        },

        created: function() {
            this.getCompanies();
            this.getUsers(true);
            this.getHcw();
            this.getLocations();
            this.fetchData();
        },

        mounted: function() {
            var self = this;
            $(".from_date").datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                endDate: '0'
            }).on('changeDate', function (selected) {
                var startDate = moment(selected.date.valueOf()).format("YYYY-MM-DD");
                $('.to_date').datepicker('setStartDate', startDate);
                self.selected.startDate = startDate;
            }).on('clearDate', function (selected) {
                $('.to_date').datepicker('setStartDate', null);
                self.selected.startDate = null;
            });

            $(".to_date").datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                endDate: '0'
            }).on('changeDate', function (selected) {
                var endDate = moment(selected.date.valueOf()).format("YYYY-MM-DD");
                $('.from_date').datepicker('setEndDate', endDate);
                self.selected.endDate = endDate;
            }).on('clearDate', function (selected) {
                $('.from_date').datepicker('setEndDate', null);
                self.selected.setEndDate = null;
            });
        },

        methods: {

            getCompanies: function() {
                var self = this;
                jQuery.get(chartUrlPrefix + '/chart/institutions', function(data){
                    self.companies = data;
                });
            },

            getUsers: function(bln) {
                var self = this;
                jQuery.get(chartUrlPrefix + '/chart/users', function(data){
                    self.users = self.usersLookup = data;
                });
            },

            getHcw: function() {
                var self = this;
                jQuery.get(chartUrlPrefix + '/chart/hcw', function(data){
                    self.hcw = self.hcwLookup = data;
                });
            },

            getLocations: function() {
                var self = this;
                jQuery.get(chartUrlPrefix + '/chart/locations', function(data){

                    self.loc1Lookup = self.loc1 = data.filter(function(loc){
                        return loc.category == 'location1';
                    });

                    self.loc2Lookup = self.loc2 = data.filter(function(loc){
                        return loc.category == 'location2';
                    });

                    self.loc3Lookup = self.loc3 = data.filter(function(loc){
                        return loc.category == 'location3';
                    });

                    self.loc4Lookup = self.loc4 = data.filter(function(loc){
                        return loc.category == 'location4';
                    });
                });
            },

            updateFiltersOnCompanyChange: function(event) {
                var self = this;

                if (self.selected.company == '') {
                    this.users = this.usersLookup;
                    this.hcw = this.hcwLookup;
                    this.loc1 = this.loc1Lookup;
                } else {
                    this.users = this.usersLookup.filter(function(user){
                        return user.cid == self.selected.company;
                    });

                    this.hcw = this.hcwLookup.filter(function(hcw){
                        return hcw.cid == self.selected.company;
                    });

                    this.loc1 = this.loc1Lookup.filter(function(loc){
                        return loc.cid == self.selected.company;
                    });

                    this.loc2 = this.loc2Lookup.filter(function(loc){
                        return loc.cid == self.selected.company;
                    });

                    this.loc3 = this.loc3Lookup.filter(function(loc){
                        return loc.cid == self.selected.company;
                    });

                    this.loc4 = this.loc4Lookup.filter(function(loc){
                        return loc.cid == self.selected.company;
                    });
                }
            },

            submitHandler: function(e) {
                e.preventDefault();
                this.fetchData();
                $('#filterModal').modal('toggle');
            },

            toggleModal: function() {
                $('#filterModal').modal('toggle');
            },

            fetchData: function() {
                var self = this;
                self.loading = true;
                jQuery.get(chartUrlPrefix + '/chart/getData/', this.selected)
                    .done(function(data) {
                        self.rawData = data.rawData;
                        $.each(data.chart, function(index, val){
                            if (data.chart[index] !== null || (data.chart[index] && data.chart[index].length > 1) ) {
                                self.chartData[index].datasets[0].data = data.chart[index].values;
                                self.chartData[index].labels = data.chart[index].columns;

                                self.chartData[index].dataset = data.chart[index].values;
                            }
                        });

                        self.chunkedChartData = data.chunkedChartData;

                        if ($.fn.dataTable.isDataTable( '#rawData' )) {
                            $('#rawData').DataTable().destroy();
                        }

                        $('#rawData').DataTable({
                            data: self.rawData,
                            searching: false,
                            columns: tableColumns
                        })

                        self.loading = false;
                    });
            },

            exportToPdf: function() {
                var self = this;
                var params = $.param(self.selected);
                window.open(chartUrlPrefix + '/chartExports/exportPdf?' + params);
            },

            exportToExcel: function () {
                var self = this;
                var params = $.param(self.selected);
                window.open(chartUrlPrefix + '/chartExports/exportExcel?' + params);
            },

            addComplianceOptions: function(option) {
                this.selected.complianceOptions.push(option);
            },

            removeComplianceOptions: function(option) {
                arr = arr.filter(function(item) {
                    return item !== option
                })
            },

            showChart: function(key) {
                return this.selected.complianceOptions.indexOf(key) !== -1;
            }
        },
      });

        $(document).ready(function() {
                $('#compOpt').multiselect({
                    onChange: function(element, checked) {
                        console.log(element.val(), checked)
                        if (checked === true) {
                            vm.selected.complianceOptions.push(element.val());
                        } else {
                            vm.selected.complianceOptions = vm.selected.complianceOptions.filter(function(e) {
                                 return e !== element.val()
                            });
                        }
                    }
                });
                $("#compOpt").multiselect('selectAll', false);
                $("#compOpt").multiselect('updateButtonText');
            });
    })();