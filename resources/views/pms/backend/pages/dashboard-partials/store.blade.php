<div class="col-lg-12">
    <div class="row">
        <div class="col-lg-8">
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between p-0 bg-white">
                    <div class="iq-header-title">
                        <h4 class="card-title text-primary border-left-heading">STORE MANAGE</h4>
                    </div>
                </div>
                <div class="iq-card-body p-0">
                    <div class="hr-section">
                        <section id="features" class="p-3">
                            <div class="row">
                                <div class="col-md-4 pr-0">
                                    <a href="{{route('pms.store-manage.store-requisition-list')}}">
                                        <div class="feature-effect-box wow fadeInUp bg-primary" data-wow-duration="0.6s">
                                            <div class="feature-i iq-bg-primary">
                                                <i class="las la-list"></i>
                                            </div>
                                            <div class="feature-icon">
                                                <h5 class="text-white">Requisition List</h5>
                                            </div>
                                            <div class="feature-i iq-bg-primary pull-right counter mr-0" style="border-radius: 25% !important; font-weight: bold;margin-top: 2px">
                                                {{ $storeData['store-manage']['requistions'] }}
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <div class="col-md-4 pr-0">
                                    <a href="{{route('pms.store-manage.rfp.requisition.list')}}">
                                        <div class="feature-effect-box wow fadeInUp bg-success" data-wow-duration="0.6s">
                                            <div class="feature-i iq-bg-success">
                                                <i class="lab la-buffer"></i>
                                            </div>
                                            <div class="feature-icon">
                                                <h5 class="text-white">RFP Requisition</h5>
                                            </div>
                                            <div class="feature-i iq-bg-success pull-right counter mr-0" style="border-radius: 25% !important; font-weight: bold;margin-top: 2px">
                                                {{ $storeData['store-manage']['rfp-requistions'] }}
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <div class="col-md-4 pr-0">
                                    <a href="{{route('pms.store-manage.delivered-requisition')}}">
                                        <div class="feature-effect-box wow fadeInUp bg-warning" data-wow-duration="0.6s">
                                            <div class="feature-i iq-bg-warning">
                                                <i class="las la-truck-loading"></i>
                                            </div>
                                            <div class="feature-icon">
                                                <h5 class="text-white">Pen. Delivery</h5>
                                            </div>
                                            <div class="feature-i iq-bg-warning pull-right counter mr-0" style="border-radius: 25% !important; font-weight: bold;margin-top: 2px">
                                                {{ $storeData['store-manage']['pending-delivery'] }}
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <div class="col-md-4 pr-0 mt-3">
                                    <a href="{{url('pms/store-manage/delivered-requisition/delivered')}}">
                                        <div class="feature-effect-box wow fadeInUp bg-info" data-wow-duration="0.6s">
                                            <div class="feature-i iq-bg-info">
                                                <i class="las la-truck"></i>
                                            </div>
                                            <div class="feature-icon">
                                                <h5 class="text-white">Comp. Delivery</h5>
                                            </div>
                                            <div class="feature-i iq-bg-info pull-right counter mr-0" style="border-radius: 25% !important; font-weight: bold;margin-top: 2px">
                                                {{ $storeData['store-manage']['complete-delivery'] }}
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-4 pr-0 mt-3">
                                    <a href="{{url('pms/qce-list')}}">
                                        <div class="feature-effect-box wow fadeInUp bg-danger" data-wow-duration="0.6s">
                                            <div class="feature-i iq-bg-danger">
                                                <i class="las la-clipboard-check"></i>
                                            </div>
                                            <div class="feature-icon">
                                                <h5 class="text-white">QCE List</h5>
                                            </div>
                                            <div class="feature-i iq-bg-danger pull-right counter mr-0" style="border-radius: 25% !important; font-weight: bold;margin-top: 2px">
                                                {{ $storeData['grn']['qce-list'] }}
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-4 pr-0 mt-3">
                                    <a href="{{url('pms/grn-list')}}">
                                        <div class="feature-effect-box wow fadeInUp bg-dark" data-wow-duration="0.6s">
                                            <div class="feature-i iq-bg-dark">
                                                <i class="las la-file-invoice"></i>
                                            </div>
                                            <div class="feature-icon">
                                                <h5 class="text-white">GRN List</h5>
                                            </div>
                                            <div class="feature-i iq-bg-dark pull-right counter mr-0" style="border-radius: 25% !important; font-weight: bold;margin-top: 2px">
                                                {{ $storeData['grn']['grn-list'] }}
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </section>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between p-0 bg-white">
                    <div class="iq-header-title">
                        <h4 class="card-title text-primary border-left-heading">
                            <a href="{{ url('pms/inventory/inventory-logs') }}">INVENTORY STATE</a>
                        </h4>
                    </div>
                </div>
                <div class="iq-card-body p-0 d-flex align-items-center justify-content-center" style="min-height: 250px;">
                    <canvas class="bar-charts" id="inventory-state-chart" 
                        data-data="{{ $storeData['inventory']['in'] . '|' . $storeData['inventory']['out'] }}" 
                        data-labels="TOTAL VOLUME" 
                        data-title-text="IN,OUT" 
                        data-legend-position="top" 
                        width="200" height="150"></canvas>
                </div>
                <div class="iq-card-footer bg-white border-top p-2 text-center">
                    <small class="text-muted">Total In: <strong>{{ number_format($storeData['inventory']['in'], 2) }}</strong> | Total Out: <strong>{{ number_format($storeData['inventory']['out'], 2) }}</strong></small>
                </div>
            </div>
        </div>
    </div>
</div>
