<div class="col-lg-12">
    <div class="iq-card">
        <div class="iq-card-header d-flex justify-content-between p-0 bg-white">
            <div class="iq-header-title">
                <h4 class="card-title text-primary border-left-heading">PERSONAL DATA</h4>
            </div>
        </div>
        <div class="iq-card-body p-0">
            <div class="hr-section">
                <section id="features">
                    <div class="container-fluid p-0">
                        <div class="row">
                            <div class="col-md-3 pr-0">
                                <a href="{{ url('pms/requisition/requisition') }}?status=3">
                                    <div class="feature-effect-box wow fadeInUp bg-primary" data-wow-duration="0.4s">
                                          <div class="feature-i iq-bg-primary">
                                            <i class="las la-receipt"></i>
                                          </div>
                                        <div class="feature-icon">
                                          <h5 class="text-white">Total Draft</h5>
                                        </div>
                                        <div class="feature-i iq-bg-primary pull-right counter mr-0" style="border-radius: 25% !important; font-weight: bold;margin-top: 2px">
                                            {{ $userData['requisitions']['draft'] }}
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-3 pr-0">
                                <a href="{{ url('pms/requisition/requisition') }}?status=0">
                                    <div class="feature-effect-box wow fadeInUp bg-warning" data-wow-duration="0.4s">
                                          <div class="feature-i iq-bg-warning">
                                            <i class="las la-clock"></i>
                                          </div>
                                        <div class="feature-icon">
                                          <h5 class="text-white">Total Pending</h5>
                                        </div>
                                        <div class="feature-i iq-bg-warning pull-right counter mr-0" style="border-radius: 25% !important; font-weight: bold;margin-top: 2px">
                                            {{ $userData['requisitions']['pending'] }}
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-3 pr-0">
                                <a href="{{ url('pms/requisition/requisition') }}?status=1">
                                    <div class="feature-effect-box wow fadeInUp bg-success" data-wow-duration="0.4s">
                                          <div class="feature-i iq-bg-success">
                                            <i class="las la-check-circle"></i>
                                          </div>
                                        <div class="feature-icon">
                                          <h5 class="text-white">Total Approved</h5>
                                        </div>
                                        <div class="feature-i iq-bg-success pull-right counter mr-0" style="border-radius: 25% !important; font-weight: bold;margin-top: 2px">
                                            {{ $userData['requisitions']['approved'] }}
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-3 pr-0">
                                <a href="#">
                                    <div class="feature-effect-box wow fadeInUp bg-info" data-wow-duration="0.4s">
                                          <div class="feature-i iq-bg-info">
                                            <i class="las la-sync"></i>
                                          </div>
                                        <div class="feature-icon">
                                          <h5 class="text-white">Processing</h5>
                                        </div>
                                        <div class="feature-i iq-bg-info pull-right counter mr-0" style="border-radius: 25% !important; font-weight: bold;margin-top: 2px">
                                            {{ $userData['requisitions']['processing'] }}
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-3 pr-0">
                                <a href="{{ url('pms/requisition/delivered-requistion-list') }}">
                                    <div class="feature-effect-box wow fadeInUp bg-success" data-wow-duration="0.4s">
                                          <div class="feature-i iq-bg-success">
                                            <i class="las la-truck"></i>
                                          </div>
                                        <div class="feature-icon">
                                          <h5 class="text-white">Delivered</h5>
                                        </div>
                                        <div class="feature-i iq-bg-success pull-right counter mr-0" style="border-radius: 25% !important; font-weight: bold;margin-top: 2px">
                                            {{ $userData['requisitions']['delivered'] }}
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-3 pr-0">
                                <a href="#">
                                    <div class="feature-effect-box wow fadeInUp bg-dark" data-wow-duration="0.4s">
                                          <div class="feature-i iq-bg-dark">
                                            <i class="las la-archive"></i>
                                          </div>
                                        <div class="feature-icon">
                                          <h5 class="text-white">Received</h5>
                                        </div>
                                        <div class="feature-i iq-bg-dark pull-right counter mr-0" style="border-radius: 25% !important; font-weight: bold;margin-top: 2px">
                                            {{ $userData['requisitions']['received'] }}
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-3 pr-0">
                                <a href="{{ url('pms/requisition/notification-all') }}">
                                    <div class="feature-effect-box wow fadeInUp bg-danger" data-wow-duration="0.4s">
                                          <div class="feature-i iq-bg-danger">
                                            <i class="las la-bell"></i>
                                          </div>
                                        <div class="feature-icon">
                                          <h5 class="text-white">Notification</h5>
                                        </div>
                                        <div class="feature-i iq-bg-danger pull-right counter mr-0" style="border-radius: 25% !important; font-weight: bold;margin-top: 2px">
                                            {{ $userData['notifications']['unread'] }}
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
                            @can('requisition-list')
                            <div class="col-md-{{(Auth::user()->hasPermissionTo('project-manage'))?3:4}}">
                                <div class="project-card" style="height: auto !important">
                                    <div class="project-card-header">
                                        <h5 class="mb-0">
                                            <i class="las la-receipt"></i>&nbsp;&nbsp;<a href="{{ url('pms/requisition/requisition') }}">Requisitions</a>
                                        </h5>
                                    </div>
                                    <div class="project-card-body pb-3">
                                        <canvas id="requisition-progress" class="charts" data-data="{{ implode(',', array_values($userData['requisitions'])) }}" data-labels="Draft,Pending,Approved,Processing,Delivered,Received" data-chart="pie" data-legend-position="top" data-title-text="" width="300" height="300"></canvas>
                                    </div>
                                </div>
                            </div>
                            @endcan

                           

                            @can('requisition-delivered-list')
                            <div class="col-md-{{(Auth::user()->hasPermissionTo('project-manage'))?3:4}}">
                                <div class="project-card" style="height: auto !important">
                                    <div class="project-card-header">
                                        <h5 class="mb-0">
                                            <i class="las la-truck-loading"></i>&nbsp;&nbsp;<a href="{{ url('pms/requisition/delivered-requistion-list') }}">Delivered Requisitions</a>
                                        </h5>
                                    </div>
                                    <div class="project-card-body pb-3">
                                        <canvas id="delivered-requisitions" class="charts" data-data="{{ implode(',', array_values($userData['delivered-requisitions'])) }}" data-labels="Pending,Acknowledge,Delivered" data-chart="pie" data-legend-position="top" data-title-text="" width="300" height="300"></canvas>
                                    </div>
                                </div>
                            </div>
                            @endcan
                             @can('notification-list')
                            <div class="col-md-{{(Auth::user()->hasPermissionTo('project-manage'))?3:4}}">
                                <div class="project-card" style="height: auto !important">
                                    <div class="project-card-header">
                                        <h5 class="mb-0">
                                            <i class="las la-bell"></i>&nbsp;&nbsp;<a href="{{ url('pms/requisition/notification-all') }}">Notifications</a>
                                        </h5>
                                    </div>
                                    <div class="project-card-body pb-3">
                                        <canvas id="notifications" class="charts" data-data="{{ implode(',', array_values($userData['notifications'])) }}" data-labels="Read,Unread" data-chart="pie" data-legend-position="top" data-title-text="" width="300" height="300"></canvas>
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


