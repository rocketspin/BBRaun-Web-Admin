    <div class="row" id="app">
        <div class="col-lg-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h2 class="panel-title pull-left"><i class="fa fa-bar-chart-o fa-fw"></i> Compliance Report (Charts)</h2>
                    <button type="button" class="btn btn-warning pull-right" data-toggle="modal" data-target="#filterModal">
                        <i class="fa fa-filter"></i> Show Filters
                    </button>
                    <div class="clearfix"></div>
                </div>

                <!-- /.panel-heading -->
                <div class="panel-body">

                    <div class="col-md-6" v-if="showChart('loc1')">
                        <div class="well">
                            <h4 class="text-center">Location 1</h4>
                            <chartjs-bar :datalabel="'Compliance'"
                                :backgroundcolor="'#00B582'"
                                :labels="chartData.loc1.labels"
                                :data="chartData.loc1.datasets[0].data"
                                :bind="true"
                                :option="chartConfig"
                                ></chartjs-bar>
                        </div>
                    </div>

                    <div class="col-md-6" v-if="showChart('loc2')">
                        <div class="well">
                            <h4 class="text-center">Location 2</h4>
                            <chartjs-bar :datalabel="'Compliance'"
                                :backgroundcolor="'#00B582'"
                                :labels="chartData.loc2.labels"
                                :data="chartData.loc2.datasets[0].data"
                                :bind="true"
                                :option="chartConfig"
                                ></chartjs-bar>
                        </div>
                    </div>

                    <div class="col-md-6" v-if="showChart('loc3')">
                        <div class="well">
                            <h4 class="text-center">Location 3</h4>
                            <chartjs-bar :datalabel="'Compliance'"
                                :backgroundcolor="'#00B582'"
                                :labels="chartData.loc3.labels"
                                :data="chartData.loc3.datasets[0].data"
                                :bind="true"
                                :option="chartConfig"
                                ></chartjs-bar>
                        </div>
                    </div>

                    <div class="col-md-6" v-if="showChart('loc4')">
                        <div class="well">
                            <h4 class="text-center">Location 4</h4>
                            <chartjs-bar :datalabel="'Compliance'"
                                :backgroundcolor="'#00B582'"
                                :labels="chartData.loc4.labels"
                                :data="chartData.loc4.datasets[0].data"
                                :bind="true"
                                :option="chartConfig"
                                ></chartjs-bar>
                        </div>
                    </div>

                    <div class="col-md-6" v-if="showChart('hcw')">
                        <div class="well">
                            <h4 class="text-center">Healthcare Compliance</h4>
                            <chartjs-bar :datalabel="'Compliance'"
                                :backgroundcolor="'#00B582'"
                                :labels="chartData.hcw.labels"
                                :data="chartData.hcw.datasets[0].data"
                                :bind="true"
                                :option="chartConfig"
                                ></chartjs-bar>
                        </div>
                    </div>

                    <div class="col-md-6" v-if="showChart('cpm')">
                        <div class="well">
                            <h4 class="text-center">Count by Moment</h4>
                            <chartjs-bar :datalabel="'Compliance'"
                                :backgroundcolor="'#00B582'"
                                :labels="chartData.cpm.labels"
                                :data="chartData.cpm.datasets[0].data"
                                :bind="true"
                                :option="chartCountConfig"
                                ></chartjs-bar>
                        </div>
                    </div>

                    <div class="col-md-6" v-if="showChart('cbm')">
                        <div class="well">
                            <h4 class="text-center">Compliance By Moment</h4>
                            <chartjs-bar :datalabel="'Compliance'"
                                :backgroundcolor="'#00B582'"
                                :labels="chartData.cbm.labels"
                                :data="chartData.cbm.datasets[0].data"
                                :bind="true"
                                :option="chartConfig"
                                ></chartjs-bar>
                        </div>
                    </div>

                    <div class="col-md-6" v-if="showChart('loc1hcw')">
                        <div class="well">
                            <h4 class="text-center">Location 1 By Health Care Worker</h4>
                            <chartjs-bar :datalabel="'Compliance'"
                                :backgroundcolor="'#00B582'"
                                :labels="chartData.loc1hcw.labels"
                                :data="chartData.loc1hcw.datasets[0].data"
                                :bind="true"
                                :option="chartConfig"
                                ></chartjs-bar>
                        </div>
                    </div>

                    <div class="col-md-6" v-if="showChart('loc1m')">
                        <div class="well">
                            <h4 class="text-center">Location 1 By Moment</h4>
                            <chartjs-bar :datalabel="'Compliance'"
                                :backgroundcolor="'#00B582'"
                                :labels="chartData.loc1m.labels"
                                :data="chartData.loc1m.datasets[0].data"
                                :bind="true"
                                :option="chartConfig"
                                ></chartjs-bar>
                        </div>
                    </div>

                    <div class="col-md-6" v-if="showChart('loc2hcw')">
                        <div class="well">
                            <h4 class="text-center">Location 2 By Health Care Worker</h4>
                            <chartjs-bar :datalabel="'Compliance'"
                                :backgroundcolor="'#00B582'"
                                :labels="chartData.loc2hcw.labels"
                                :data="chartData.loc2hcw.datasets[0].data"
                                :bind="true"
                                :option="chartConfig"
                                ></chartjs-bar>
                        </div>
                    </div>

                    <div class="col-md-6" v-if="showChart('loc2m')">
                        <div class="well">
                            <h4 class="text-center">Location 2 By Moment</h4>
                            <chartjs-bar :datalabel="'Compliance'"
                                :backgroundcolor="'#00B582'"
                                :labels="chartData.loc2m.labels"
                                :data="chartData.loc2m.datasets[0].data"
                                :bind="true"
                                :option="chartConfig"
                                ></chartjs-bar>
                        </div>
                    </div>

                    <div class="col-md-6" v-if="showChart('loc3hcw')">
                        <div class="well">
                            <h4 class="text-center">Location 3 By Health Care Worker</h4>
                            <chartjs-bar :datalabel="'Compliance'"
                                :backgroundcolor="'#00B582'"
                                :labels="chartData.loc3hcw.labels"
                                :data="chartData.loc3hcw.datasets[0].data"
                                :bind="true"
                                :option="chartConfig"
                                ></chartjs-bar>
                        </div>
                    </div>

                    <div class="col-md-6" v-if="showChart('loc3m')">
                        <div class="well">
                            <h4 class="text-center">Location 3 By Moment</h4>
                            <chartjs-bar :datalabel="'Compliance'"
                                :backgroundcolor="'#00B582'"
                                :labels="chartData.loc3m.labels"
                                :data="chartData.loc3m.datasets[0].data"
                                :bind="true"
                                :option="chartConfig"
                                ></chartjs-bar>
                        </div>
                    </div>

                    <div class="col-md-6" v-if="showChart('loc4hcw')">
                        <div class="well">
                            <h4 class="text-center">Location 4 By Health Care Worker</h4>
                            <chartjs-bar :datalabel="'Compliance'"
                                :backgroundcolor="'#00B582'"
                                :labels="chartData.loc4hcw.labels"
                                :data="chartData.loc4hcw.datasets[0].data"
                                :bind="true"
                                :option="chartConfig"
                                ></chartjs-bar>
                        </div>
                    </div>

                    <div class="col-md-6" v-if="showChart('loc4m')">
                        <div class="well">
                            <h4 class="text-center">Location 4 By Moment</h4>
                            <chartjs-bar :datalabel="'Compliance'"
                                :backgroundcolor="'#00B582'"
                                :labels="chartData.loc4m.labels"
                                :data="chartData.loc4m.datasets[0].data"
                                :bind="true"
                                :option="chartConfig"
                                ></chartjs-bar>
                        </div>
                    </div>

                </div>
                <!-- /.panel-body -->
            </div>
        </div>

        <div class="col-lg-12">
            <div class="panel panel-primary">

                <div class="panel-heading">
                    <h2 class="panel-title pull-left"><i class="fa fa-table fa-fw"></i> Compliance Report (Drilldown)</h2>
                    <div class="clearfix"></div>
                </div>

                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table id="rawData" class="display table table-bordered" width="100%">
                                    <thead class="bg-primary">
                                        <tr>
                                            <td>Date & Time</td>
                                            <td>Auditor</td>
                                            <td>Location 1</td>
                                            <td>Location 2</td>
                                            <td>Location 3</td>
                                            <td>Location 4</td>
                                            <td>Title</td>
                                            <td>Name</td>
                                            <td>Moment 1</td>
                                            <td>Moment 2</td>
                                            <td>Moment 3</td>
                                            <td>Moment 4</td>
                                            <td>Moment 5</td>
                                            <td>Action</td>
                                            <td>Result</td>
                                            <td>Exposure Risk</td>
                                            <td>Gloves</td>
                                            <td>Gown</td>
                                            <td>Mask</td>
                                            <td>Mask Type</td>
                                            <td>Notes</td>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="filterModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Compliance Report | Filters</h4>
                    </div>
                    <div class="modal-body">
                        <form role="form" v-on:submit="submitHandler">
                            <div class="form-group">
                                <label>Start Date</label>
                                <div class="input-group date from_date">
                                    <input type="text" class="form-control" v-model="selected.startDate">
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-th"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>End Date</label>
                                <div class="input-group date to_date">
                                    <input type="text" class="form-control" v-model="selected.endDate">
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-th"></span>
                                    </div>
                                </div>
                            </div>
                            <?php if($this->ion_auth->is_admin()):?>
                            <div class="form-group">
                                <label>Institutions</label>
                                <select class="form-control"
                                    id="companies"
                                    v-model="selected.company"
                                    v-on:change="updateFiltersOnCompanyChange"
                                    >
                                    <option value=""> -- All -- </option>
                                    <option v-for="(option, index) in companies" v-bind:value="option.id">
                                        {{ option.name }}
                                    </option>
                                </select>
                            </div>
                            <?php endif;?>
                            <div class="form-group">
                                <label>Users</label>
                                <select class="form-control" id="users" v-model="selected.user">
                                    <option value=""> -- All -- </option>
                                    <option v-for="(option, index) in users" v-bind:value="option.id">
                                        {{ option.name }}
                                    </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Health Care Worker</label>
                                <select class="form-control" id="hcw" v-model="selected.hcw">
                                    <option value=""> -- All -- </option>
                                    <option v-for="(option, index) in hcw" v-bind:value="option.id">
                                        {{ option.name }}
                                    </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Location Level 1</label>
                                <select class="form-control" id="loc1" v-model="selected.loc1">
                                    <option value=""> -- All -- </option>
                                    <option v-for="(option, index) in loc1" v-bind:value="option.id">
                                        {{ option.name }}
                                    </option>
                                </select>
                            </div>
                           <div class="form-group">
                                <label>Location Level 2</label>
                                <select class="form-control" id="loc2" v-model="selected.loc2">
                                    <option value=""> -- All -- </option>
                                    <option v-for="(option, index) in loc2" v-bind:value="option.id">
                                        {{ option.name }}
                                    </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Location Level 3</label>
                                <select class="form-control" id="loc3" v-model="selected.loc3">
                                    <option value=""> -- All -- </option>
                                    <option v-for="(option, index) in loc3" v-bind:value="option.id">
                                        {{ option.name }}
                                    </option>
                                </select>
                            </div>
                           <div class="form-group">
                                <label>Location Level 4</label>
                                <select class="form-control" id="loc4" v-model="selected.loc4">
                                    <option value=""> -- All -- </option>
                                    <option v-for="(option, index) in loc4" v-bind:value="option.id">
                                        {{ option.name }}
                                    </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Compliance Options</label><br>
                                <select class="form-control" id="compOpt" v-model="selected.complianceOptions" multiple="multiple">
                                    <option value="loc1">Location 1</option>
                                    <option value="loc2">Location 2</option>
                                    <option value="loc3">Location 3</option>
                                    <option value="loc4">Location 4</option>
                                    <option value="hcw">Healthcare Compliance</option>
                                    <option value="cpm">Count per Moment</option>
                                    <option value="cbm">Compliance by Moment</option>
                                    <option value="loc1hcw">Location 1 per Healthcare Worker</option>
                                    <option value="loc1m">Location 1 per Moment </option>
                                    <option value="loc2hcw"> Location 2 per Healthcare Worker </option>
                                    <option value="loc2m"> Location 2 per Moment </option>
                                    <option value="loc3hcw"> Location 3 per Healthcare Worker </option>
                                    <option value="loc3m"> Location 3 per Moment </option>
                                    <option value="loc4hcw"> Location 4 per Healthcare Worker </option>
                                    <option value="loc4m"> Location 4 per Moment </option>
                                </select>
                            </div>
                            <div class="form-group text-right">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Generate</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="<?=base_url('assets/js/vuejs/vue.js')?>"></script>
    <script src="<?=base_url('assets/js/chartjs/Chart.min.js')?>"></script>
    <script src="<?=base_url('assets/js/vuejs/vue-charts.min.js')?>"></script>
    <script src="<?=base_url('assets/vue-charts/dist/vue-charts.min.js')?>"></script>
    <script src="<?=base_url('assets/js/vue-components/chart-page/chart-page.js')?>"></script>