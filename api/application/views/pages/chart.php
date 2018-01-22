
<div class="row" id="app">
        <div class="col-lg-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h2 class="panel-title pull-left"><i class="fa fa-bar-chart-o fa-fw"></i> Compliance Report (Charts)</h2>
                    <div class="dropdown pull-right">
                        <button class="btn btn-warning btn-block dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-gear"></i> <span class="hidden-sm hidden-xs">Options</span>
                        <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li><a href="#" v-on:click="toggleModal"><i class="fa fa-filter"></i> Show Filters</a></li>
                            <li class="divider"></li>
                            <li><a href="#" v-on:click="exportToPdf"><i class="fa fa-file-pdf-o"></i> Export Charts to PDF</a></li>
                            <li><a href="#" v-on:click="exportToExcel"><i class="fa fa-table"></i> Export Raw Data to Excel</a></li>
                        </ul>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="panel-group" v-if="showChart('loc1')">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#collapse1">Location Level 1</a>
                                </h4>
                            </div>
                            <div id="collapse1" class="panel-collapse collapse in">
                                <div class="panel-body">
                                    <div class="center-block loader" v-if="loading"></div>

                                    <div class="center-block" v-if="(chunkedChartData.loc1 === undefined || chunkedChartData.loc1.length === 0) && !loading">No Data for Charts</div>

                                    <div v-if="showChart('loc1') && !loading" v-for="(chunked, index) in chunkedChartData.loc1">
                                        <div class="col-md-6 col-sm-12 well">
                                            <h4 class="text-center">Location Level 1 <span v-if="chunkedChartData.loc1.length > 1">({{index+1}} of {{chunkedChartData.loc1.length}})</span> </h4>
                                            <chartjs-bar :datalabel="'Compliance'"
                                                :backgroundcolor="'#00B582'"
                                                :labels="chunked.columns"
                                                :data="chunked.values"
                                                :bind="true"
                                                :option="chartConfig"
                                                :height="250"
                                                v-if="!loading"
                                                ></chartjs-bar>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel-group" v-if="showChart('loc2')">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#collapse2">Location Level 2</a>
                                </h4>
                            </div>
                            <div id="collapse2" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="center-block loader" v-if="loading"></div>

                                    <div class="center-block" v-if="(chunkedChartData.loc2 === undefined || chunkedChartData.loc2.length === 0) && !loading">No Data for Charts</div>

                                    <div v-if="!loading" v-for="(chunked, index) in chunkedChartData.loc2">
                                        <div class="col-md-6 col-sm-12 well">
                                            <h4 class="text-center">Location Level 2 <span v-if="chunkedChartData.loc2.length > 1">({{index+1}} of {{chunkedChartData.loc2.length}})</span> </h4>
                                            <chartjs-bar :datalabel="'Compliance'"
                                                :backgroundcolor="'#00B582'"
                                                :labels="chunked.columns"
                                                :data="chunked.values"
                                                :bind="true"
                                                :option="chartConfig"
                                                :height="250"
                                                v-if="!loading"
                                                ></chartjs-bar>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel-group" v-if="showChart('loc3')">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#collapse3">Location Level 3</a>
                                </h4>
                            </div>
                            <div id="collapse3" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="center-block loader" v-if="loading"></div>
                                    <div class="center-block" v-if="(chunkedChartData.loc3 === undefined || chunkedChartData.loc3.length === 0) && !loading">No Data for Charts</div>
                                    <div v-if="!loading" v-for="(chunked, index) in chunkedChartData.loc3">
                                        <div class="col-md-6 col-sm-12 well">
                                            <h4 class="text-center">Location Level 3 <span v-if="chunkedChartData.loc3.length > 1">({{index+1}} of {{chunkedChartData.loc3.length}})</span> </h4>
                                            <chartjs-bar :datalabel="'Compliance'"
                                                :backgroundcolor="'#00B582'"
                                                :labels="chunked.columns"
                                                :data="chunked.values"
                                                :bind="true"
                                                :option="chartConfig"
                                                :height="250"
                                                v-if="!loading"
                                                ></chartjs-bar>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel-group" v-if="showChart('loc4')">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#collapse4">Location Level 4</a>
                                </h4>
                            </div>
                            <div id="collapse4" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="center-block loader" v-if="loading"></div>
                                    <div class="center-block" v-if="(chunkedChartData.loc4 === undefined || chunkedChartData.loc4.length === 0) && !loading">No Data for Charts</div>
                                    <div v-if="!loading" v-for="(chunked, index) in chunkedChartData.loc4">
                                        <div class="col-md-6 col-sm-12 well">
                                            <h4 class="text-center">Location Level 4 <span v-if="chunkedChartData.loc4.length > 1">({{index+1}} of {{chunkedChartData.loc4.length}})</span></h4>
                                            <chartjs-bar :datalabel="'Compliance'"
                                                :backgroundcolor="'#00B582'"
                                                :labels="chunked.columns"
                                                :data="chunked.values"
                                                :bind="true"
                                                :option="chartConfig"
                                                :height="250"
                                                v-if="!loading"
                                                ></chartjs-bar>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel-group" v-if="showChart('hcw')">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#collapse5">HCW</a>
                                </h4>
                            </div>
                            <div id="collapse5" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="center-block loader" v-if="loading"></div>
                                    <div class="center-block" v-if="(chunkedChartData.hcw === undefined || chunkedChartData.hcw.length === 0) && !loading">No Data for Charts</div>
                                    <div v-if="!loading" v-for="(chunked, index) in chunkedChartData.hcw">
                                        <div class="col-md-6 col-sm-12 well">
                                            <h4 class="text-center">HCW <span v-if="chunkedChartData.hcw.length > 1">({{index+1}} of {{chunkedChartData.hcw.length}})</span></h4>
                                            <chartjs-bar :datalabel="'Compliance'"
                                                :backgroundcolor="'#00B582'"
                                                :labels="chunked.columns"
                                                :data="chunked.values"
                                                :bind="true"
                                                :option="chartConfig"
                                                :height="250"
                                                v-if="!loading"
                                                ></chartjs-bar>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel-group" v-if="showChart('cpm')">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#collapse6">Count Per Moment</a>
                                </h4>
                            </div>
                            <div id="collapse6" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="center-block loader" v-if="loading"></div>
                                    <div class="center-block" v-if="(chunkedChartData.cpm === undefined || chunkedChartData.cpm.length === 0) && !loading">No Data for Charts</div>
                                    <div v-if="!loading" v-for="(chunked, index) in chunkedChartData.cpm">
                                        <div class="col-md-6 col-sm-12 well">
                                            <h4 class="text-center">Count Per Moment</h4>
                                            <chartjs-bar :datalabel="'Count'"
                                                :backgroundcolor="'#00B582'"
                                                :labels="chunked.columns"
                                                :data="chunked.values"
                                                :bind="true"
                                                :option="chartCountConfig"
                                                :height="250"
                                                v-if="!loading"
                                                ></chartjs-bar>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel-group" v-if="showChart('cbm')">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#collapse7">Compliance By Moment</a>
                                </h4>
                            </div>
                            <div id="collapse7" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="center-block loader" v-if="loading"></div>
                                    <div class="center-block" v-if="(chunkedChartData.cbm === undefined || chunkedChartData.cbm.length === 0) && !loading">No Data for Charts</div>
                                    <div v-if="!loading" v-for="(chunked, index) in chunkedChartData.cbm">
                                        <div class="col-md-6 col-sm-12 well">
                                            <h4 class="text-center">Compliance By Moment</h4>
                                            <chartjs-bar :datalabel="'Compliance'"
                                                :backgroundcolor="'#00B582'"
                                                :labels="chunked.columns"
                                                :data="chunked.values"
                                                :bind="true"
                                                :option="chartConfig"
                                                :height="250"
                                                v-if="!loading"
                                                ></chartjs-bar>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel-group" v-if="showChart('loc1hcw')">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#collapse8">Location Level 1 by Health Care Worker</a>
                                </h4>
                            </div>
                            <div id="collapse8" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="center-block loader" v-if="loading"></div>
                                    <div class="center-block" v-if="(chunkedChartData.loc1hcw === undefined || chunkedChartData.loc1hcw.length === 0) && !loading">No Data for Charts</div>
                                    <div v-if="!loading" v-for="(chunked, index) in chunkedChartData.loc1hcw">
                                        <div class="col-md-6 col-sm-12 well">
                                            <h4 class="text-center">Location Level 1 by Health Care Worker <span v-if="chunkedChartData.loc1hcw.length > 1">({{index+1}} of {{chunkedChartData.loc1hcw.length}})</span></h4>
                                            <chartjs-bar :datalabel="'Compliance'"
                                                :backgroundcolor="'#00B582'"
                                                :labels="chunked.columns"
                                                :data="chunked.values"
                                                :bind="true"
                                                :option="chartConfig"
                                                :height="250"
                                                v-if="!loading"
                                                ></chartjs-bar>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel-group" v-if="showChart('loc1m')">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#collapse9">Location Level 1 by Moment</a>
                                </h4>
                            </div>
                            <div id="collapse9" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="center-block loader" v-if="loading"></div>
                                    <div class="center-block" v-if="(chunkedChartData.loc1m === undefined || chunkedChartData.loc1m.length === 0) && !loading">No Data for Charts</div>
                                    <div v-if="!loading" v-for="chunked in chunkedChartData.loc1m">
                                        <div class="col-md-6 col-sm-12 well">
                                            <h4 class="text-center">{{chunked.label}}</h4>
                                            <chartjs-bar :datalabel="'Compliance'"
                                                :backgroundcolor="'#00B582'"
                                                :labels="chunked.columns"
                                                :data="chunked.values"
                                                :bind="true"
                                                :option="chartConfig"
                                                :height="250"
                                                v-if="!loading"
                                                ></chartjs-bar>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel-group" v-if="showChart('loc2hcw')">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#collapse10">Location Level 2 by Health Care Worker</a>
                                </h4>
                            </div>
                            <div id="collapse10" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="center-block loader" v-if="loading"></div>
                                    <div class="center-block" v-if="(chunkedChartData.loc2hcw === undefined || chunkedChartData.loc2hcw.length === 0) && !loading">No Data for Charts</div>
                                    <div v-if="!loading" v-for="(chunked, index) in chunkedChartData.loc2hcw">
                                        <div class="col-md-6 col-sm-12 well">
                                            <h4 class="text-center">Location Level 2 by Health Care Worker <span v-if="chunkedChartData.loc2hcw.length > 1">({{index+1}} of {{chunkedChartData.loc2hcw.length}})</span></h4>
                                            <chartjs-bar :datalabel="'Compliance'"
                                                :backgroundcolor="'#00B582'"
                                                :labels="chunked.columns"
                                                :data="chunked.values"
                                                :bind="true"
                                                :option="chartConfig"
                                                :height="250"
                                                v-if="!loading"
                                                ></chartjs-bar>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel-group" v-if="showChart('loc2m')">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#collapse11">Location Level 2 by Moment</a>
                                </h4>
                            </div>
                            <div id="collapse11" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="center-block loader" v-if="loading"></div>
                                    <div class="center-block" v-if="(chunkedChartData.loc2m === undefined || chunkedChartData.loc2m.length === 0) && !loading">No Data for Charts</div>
                                    <div v-if="!loading" v-for="chunked in chunkedChartData.loc2m">
                                        <div class="col-md-6 col-sm-12 well">
                                            <h4 class="text-center">{{chunked.label}}</h4>
                                            <chartjs-bar :datalabel="'Compliance'"
                                                :backgroundcolor="'#00B582'"
                                                :labels="chunked.columns"
                                                :data="chunked.values"
                                                :bind="true"
                                                :option="chartConfig"
                                                :height="250"
                                                v-if="!loading"
                                                ></chartjs-bar>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel-group" v-if="showChart('loc3hcw')">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#collapse12">Location Level 3 by Health Care Worker</a>
                                </h4>
                            </div>
                            <div id="collapse12" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="center-block loader" v-if="loading"></div>
                                    <div class="center-block" v-if="(chunkedChartData.loc3hcw === undefined || chunkedChartData.loc3hcw.length === 0) && !loading">No Data for Charts</div>
                                    <div v-if="!loading" v-for="(chunked, index) in chunkedChartData.loc3hcw">
                                        <div class="col-md-6 col-sm-12 well">
                                            <h4 class="text-center">Location Level 3 by Health Care Worker <span v-if="chunkedChartData.loc3hcw.length > 1">({{index+1}} of {{chunkedChartData.loc3hcw.length}})</span></h4>
                                            <chartjs-bar :datalabel="'Compliance'"
                                                :backgroundcolor="'#00B582'"
                                                :labels="chunked.columns"
                                                :data="chunked.values"
                                                :bind="true"
                                                :option="chartConfig"
                                                :height="250"
                                                v-if="!loading"
                                                ></chartjs-bar>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel-group" v-if="showChart('loc3m')">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#collapse13">Location Level 3 by Moment</a>
                                </h4>
                            </div>
                            <div id="collapse13" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="center-block loader" v-if="loading"></div>
                                    <div class="center-block" v-if="(chunkedChartData.loc3m === undefined || chunkedChartData.loc3m.length === 0) && !loading">No Data for Charts</div>
                                    <div v-if="!loading" v-for="chunked in chunkedChartData.loc3m">
                                        <div class="col-md-6 col-sm-12 well">
                                            <h4 class="text-center">{{chunked.label}}</h4>
                                            <chartjs-bar :datalabel="'Compliance'"
                                                :backgroundcolor="'#00B582'"
                                                :labels="chunked.columns"
                                                :data="chunked.values"
                                                :bind="true"
                                                :option="chartConfig"
                                                :height="250"
                                                v-if="!loading"
                                                ></chartjs-bar>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel-group" v-if="showChart('loc4hcw')">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#collapse14">Location Level 4 by Health Care Worker</a>
                                </h4>
                            </div>
                            <div id="collapse14" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="center-block loader" v-if="loading"></div>
                                    <div class="center-block" v-if="(chunkedChartData.loc4hcw === undefined || chunkedChartData.loc4hcw.length === 0) && !loading">No Data for Charts</div>
                                    <div v-if="!loading" v-for="(chunked, index) in chunkedChartData.loc4hcw">
                                        <div class="col-md-6 col-sm-12 well">
                                            <h4 class="text-center">Location Level 4 by Health Care Worker <span v-if="chunkedChartData.loc4hcw.length > 1">({{index+1}} of {{chunkedChartData.loc4hcw.length}})</span></h4>
                                            <chartjs-bar :datalabel="'Compliance'"
                                                :backgroundcolor="'#00B582'"
                                                :labels="chunked.columns"
                                                :data="chunked.values"
                                                :bind="true"
                                                :option="chartConfig"
                                                :height="250"
                                                v-if="!loading"
                                                ></chartjs-bar>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel-group" v-if="showChart('loc4m')">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#collapse15">Location Level 4 by Moment</a>
                                </h4>
                            </div>
                            <div id="collapse15" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="center-block loader" v-if="loading"></div>
                                    <div class="center-block" v-if="(chunkedChartData.loc4m === undefined || chunkedChartData.loc4m.length === 0) && !loading">No Data for Charts</div>
                                    <div v-if="!loading" v-for="chunked in chunkedChartData.loc4m">
                                        <div class="col-md-6 col-sm-12 well">
                                            <h4 class="text-center">{{chunked.label}}</h4>
                                            <chartjs-bar :datalabel="'Compliance'"
                                                :backgroundcolor="'#00B582'"
                                                :labels="chunked.columns"
                                                :data="chunked.values"
                                                :bind="true"
                                                :option="chartConfig"
                                                :height="250"
                                                v-if="!loading"
                                                ></chartjs-bar>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.panel-body -->
            </div>
        </div>

        <div class="col-lg-12">
            <div class="panel panel-primary">

                <div class="panel-heading">

                    <h2 class="panel-title pull-left"><i class="fa fa-table"></i> Compliance Report (Raw Data)</h2>
                     <button type="button" class="btn btn-warning pull-right" v-on:click="exportToExcel">
                         <i class="fa fa-file-excel-o"></i> <span class="hidden-sm hidden-xs">Export to Excel</span>
                     </button>

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
                                            <td>Location Level 1</td>
                                            <td>Location Level 2</td>
                                            <td>Location Level 3</td>
                                            <td>Location Level 4</td>
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
                                    <option value="loc1">Location Level 1</option>
                                    <option value="loc2">Location Level 2</option>
                                    <option value="loc3">Location Level 3</option>
                                    <option value="loc4">Location Level 4</option>
                                    <option value="hcw">Healthcare Compliance</option>
                                    <option value="cpm">Count per Moment</option>
                                    <option value="cbm">Compliance by Moment</option>
                                    <option value="loc1hcw">Location Level 1 per Healthcare Worker</option>
                                    <option value="loc1m">Location Level 1 per Moment </option>
                                    <option value="loc2hcw"> Location Level 2 per Healthcare Worker </option>
                                    <option value="loc2m"> Location Level 2 per Moment </option>
                                    <option value="loc3hcw"> Location Level 3 per Healthcare Worker </option>
                                    <option value="loc3m"> Location Level 3 per Moment </option>
                                    <option value="loc4hcw"> Location Level 4 per Healthcare Worker </option>
                                    <option value="loc4m"> Location Level 4 per Moment </option>
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
    <script>
        var chartUrlPrefix = "<?php echo $data['chartUrlPrefix']; ?>"
    </script>

    <script src="<?=base_url('assets/js/vuejs/vue.js')?>"></script>
    <script src="<?=base_url('assets/js/chartjs/Chart.min.js')?>"></script>
    <script src="<?=base_url('assets/js/vuejs/vue-charts.min.js')?>"></script>
    <script src="<?=base_url('assets/vue-charts/dist/vue-charts.min.js')?>"></script>
    <script src="<?=base_url('assets/js/vue-components/chart-page/chart-page.js')?>"></script>