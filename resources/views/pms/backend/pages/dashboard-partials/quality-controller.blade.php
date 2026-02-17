<div class="col-lg-12">
    <div class="iq-card">
        <div class="iq-card-header d-flex justify-content-between p-0 bg-white">
            <div class="iq-header-title">
                <h4 class="card-title text-primary border-left-heading">QUALITY ASSURANCE STATS</h4>
            </div>
        </div>
        <div class="iq-card-body p-0">
            <div class="hr-section">
                <section id="features" class="p-3">
                    <div class="row">
                        <div class="col-md-3 pr-0">
                            <a href="{{ url('pms/grn/grn-process') }}">
                                <div class="feature-effect-box wow fadeInUp" data-wow-duration="0.6s">
                                    <div class="feature-i iq-bg-primary">
                                        <i class="las la-truck-loading"></i>
                                    </div>
                                    <div class="feature-icon">
                                        <h5>Gate-In ({{ $gateQualityControllerData['gate-in'] }})</h5>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-3 pr-0">
                            <a href="{{ url('pms/quality-ensure/approved-list') }}">
                                <div class="feature-effect-box wow fadeInUp" data-wow-duration="0.6s">
                                    <div class="feature-i iq-bg-success">
                                        <i class="las la-check-circle"></i>
                                    </div>
                                    <div class="feature-icon">
                                        <h5>Approved ({{ $gateQualityControllerData['approved'] }})</h5>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-3 pr-0">
                            <a href="{{ url('pms/quality-ensure/return-list') }}">
                                <div class="feature-effect-box wow fadeInUp" data-wow-duration="0.6s">
                                    <div class="feature-i iq-bg-danger">
                                        <i class="las la-undo-alt"></i>
                                    </div>
                                    <div class="feature-icon">
                                        <h5>Returned ({{ $gateQualityControllerData['returned'] }})</h5>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-3 pr-0">
                            <a href="{{ url('pms/quality-ensure/return-change-list') }}">
                                <div class="feature-effect-box wow fadeInUp" data-wow-duration="0.6s">
                                    <div class="feature-i iq-bg-warning">
                                        <i class="las la-exchange-alt"></i>
                                    </div>
                                    <div class="feature-icon">
                                        <h5>Return Replace ({{ $gateQualityControllerData['return-changed'] }})</h5>
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
