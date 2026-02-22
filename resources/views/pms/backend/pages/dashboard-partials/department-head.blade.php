<div class="col-lg-12">
    <div class="iq-card">
        <div class="iq-card-header d-flex justify-content-between p-0 bg-white">
            <div class="iq-header-title">
                <h4 class="card-title text-primary border-left-heading">DEPARTMENT REQUISITIONS</h4>
            </div>
        </div>
        <div class="iq-card-body p-0">
            <div class="hr-section">
                <section id="features">
                    <div class="container-fluid p-0">
                        <div class="row">
                            <div class="col-md-4 pr-0">
                                <a href="{{ url('pms/requisition/list-view') }}?status=0">
                                    <div class="feature-effect-box wow fadeInUp bg-warning" data-wow-duration="0.4s">
                                          <div class="feature-i iq-bg-warning">
                                            <i class="las la-clock"></i>
                                          </div>
                                        <div class="feature-icon">
                                          <h5 class="text-white">Total Pending</h5>
                                        </div>
                                        <div class="feature-i iq-bg-warning pull-right counter mr-0" style="border-radius: 25% !important; font-weight: bold;margin-top: 2px">
                                            {{ $userData['user-requisitions']['pending'] }}
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-4 pr-0">
                                <a href="{{ url('pms/requisition/list-view') }}?status=1">
                                    <div class="feature-effect-box wow fadeInUp bg-success" data-wow-duration="0.4s">
                                          <div class="feature-i iq-bg-success">
                                            <i class="las la-check-circle"></i>
                                          </div>
                                        <div class="feature-icon">
                                          <h5 class="text-white">Total Acknowledge</h5>
                                        </div>
                                        <div class="feature-i iq-bg-success pull-right counter mr-0" style="border-radius: 25% !important; font-weight: bold;margin-top: 2px">
                                            {{ $userData['user-requisitions']['acknowledge'] }}
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-4 pr-0">
                                <a href="{{ url('pms/requisition/list-view') }}?status=2">
                                    <div class="feature-effect-box wow fadeInUp bg-danger" data-wow-duration="0.4s">
                                          <div class="feature-i iq-bg-danger">
                                            <i class="las la-pause-circle"></i>
                                          </div>
                                        <div class="feature-icon">
                                          <h5 class="text-white">Total Halt</h5>
                                        </div>
                                        <div class="feature-i iq-bg-danger pull-right counter mr-0" style="border-radius: 25% !important; font-weight: bold;margin-top: 2px">
                                            {{ $userData['user-requisitions']['halt'] }}
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>

<!-- <div class="col-lg-12">
    <div class="iq-card">
        <div class="iq-card-body p-0">
            <div class="hr-section">
                <section id="features">
                    <div class="container-fluid p-0">
                        <div class="row">
                            @can('project-manage')
                            <div class="col-md-3">
                                <div class="project-card" style="height: auto !important">
                                    <div class="project-card-header">
                                        <h5 class="mb-0">
                                            <i class="las la-truck-loading"></i>&nbsp;&nbsp;<a href="{{ url('pms/requisition/list-view') }}">USERS REQUISITIONS</a>
                                        </h5>
                                    </div>
                                    <div class="project-card-body pb-3">
                                        <canvas id="delivered-requisitions" class="charts" data-data="{{ implode(',', array_values($userData['user-requisitions'])) }}" data-labels="Pending,Acknowledge,Halt" data-chart="doughnut" data-legend-position="top" data-title-text="" width="300" height="300"></canvas>
                                    </div>
                                </div>
                            </div>
                            @endcan
                        
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div> -->
