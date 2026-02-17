

<div class="col-lg-12">
    <div class="iq-card">
        <div class="iq-card-header d-flex justify-content-between p-0 bg-white">
            <div class="iq-header-title">
                <h4 class="card-title text-primary border-left-heading">BILLING MANAGEMENT STATS</h4>
            </div>
        </div>
        <div class="iq-card-body p-0">
            <div class="hr-section">
                <!-- Billing Attachments Section -->
                <section id="billing-attachment" class="p-3 border-bottom">
                    <h6 class="mb-3 text-secondary font-weight-bold">
                        <a href="{{ url('pms/billing-audit/billing-po-attachment-list') }}">
                            <i class="las la-file-invoice"></i> BILLING ATTACHMENTS
                        </a>
                    </h6>
                    <div class="row">
                        <div class="col-md-4 pr-0">
                            <a href="{{ url('pms/billing-audit/billing-po-attachment-list?status=pending') }}">
                                <div class="feature-effect-box wow fadeInUp" data-wow-duration="0.6s">
                                    <div class="feature-i iq-bg-primary">
                                        <i class="las la-clock"></i>
                                    </div>
                                    <div class="feature-icon">
                                        <h5>Pending ({{ $billingAuditStats['billing-attachment']['pending'] }})</h5>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4 pr-0">
                            <a href="{{ url('pms/billing-audit/billing-po-attachment-list?status=approved') }}">
                                <div class="feature-effect-box wow fadeInUp" data-wow-duration="0.6s">
                                    <div class="feature-i iq-bg-success">
                                        <i class="las la-check-circle"></i>
                                    </div>
                                    <div class="feature-icon">
                                        <h5>Approved ({{ $billingAuditStats['billing-attachment']['approved'] }})</h5>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4 pr-0">
                            <a href="{{ url('pms/billing-audit/billing-po-attachment-list?status=halt') }}">
                                <div class="feature-effect-box wow fadeInUp" data-wow-duration="0.6s">
                                    <div class="feature-i iq-bg-danger">
                                        <i class="las la-pause-circle"></i>
                                    </div>
                                    <div class="feature-icon">
                                        <h5>Halt ({{ $billingAuditStats['billing-attachment']['halt'] }})</h5>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </section>

                <!-- PO Advance Section -->
                <section id="po-advance" class="p-3 border-bottom">
                    <h6 class="mb-3 text-secondary font-weight-bold">
                        <a href="{{ url('pms/billing-audit/po-advance') }}">
                            <i class="las la-money-check-alt"></i> PO ADVANCE
                        </a>
                    </h6>
                    <div class="row">
                        <div class="col-md-4 pr-0">
                            <a href="{{ url('pms/billing-audit/po-advance?status=pending') }}">
                                <div class="feature-effect-box wow fadeInUp" data-wow-duration="0.6s">
                                    <div class="feature-i iq-bg-primary">
                                        <i class="las la-hourglass-half"></i>
                                    </div>
                                    <div class="feature-icon">
                                        <h5>Pending ({{ $billingAuditStats['po-advance']['pending'] }})</h5>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4 pr-0">
                            <a href="{{ url('pms/billing-audit/po-advance?status=audited') }}">
                                <div class="feature-effect-box wow fadeInUp" data-wow-duration="0.6s">
                                    <div class="feature-i iq-bg-success">
                                        <i class="las la-stamp"></i>
                                    </div>
                                    <div class="feature-icon">
                                        <h5>Audited ({{ $billingAuditStats['po-advance']['approved'] }})</h5>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4 pr-0">
                            <a href="{{ url('pms/billing-audit/po-advance?status=canceled') }}">
                                <div class="feature-effect-box wow fadeInUp" data-wow-duration="0.6s">
                                    <div class="feature-i iq-bg-danger">
                                        <i class="las la-times-circle"></i>
                                    </div>
                                    <div class="feature-icon">
                                        <h5>Canceled ({{ $billingAuditStats['po-advance']['halt'] }})</h5>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </section>

                <!-- Spot Purchase Section -->
                <section id="spot-purchase" class="p-3">
                    <h6 class="mb-3 text-secondary font-weight-bold">
                        <a href="{{ url('pms/billing-audit/spot-purchase-price-approval') }}">
                            <i class="las la-shopping-cart"></i> SPOT PURCHASE APPROVAL (DIRECT)
                        </a>
                    </h6>
                    <div class="row">
                        <div class="col-md-4 pr-0">
                            <a href="{{ url('pms/billing-audit/spot-purchase-price-approval') }}">
                                <div class="feature-effect-box wow fadeInUp" data-wow-duration="0.6s">
                                    <div class="feature-i iq-bg-primary">
                                        <i class="las la-user-clock"></i>
                                    </div>
                                    <div class="feature-icon">
                                        <h5>Wait/Pending ({{ $billingAuditStats['spot-purchase']['pending'] }})</h5>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4 pr-0">
                            <a href="{{ url('pms/billing-audit/spot-purchase-price-approval') }}">
                                <div class="feature-effect-box wow fadeInUp" data-wow-duration="0.6s">
                                    <div class="feature-i iq-bg-success">
                                        <i class="las la-check-double"></i>
                                    </div>
                                    <div class="feature-icon">
                                        <h5>Approved ({{ $billingAuditStats['spot-purchase']['approved'] }})</h5>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4 pr-0">
                            <a href="{{ url('pms/billing-audit/spot-purchase-price-approval') }}">
                                <div class="feature-effect-box wow fadeInUp" data-wow-duration="0.6s">
                                    <div class="feature-i iq-bg-danger">
                                        <i class="las la-hand-paper"></i>
                                    </div>
                                    <div class="feature-icon">
                                        <h5>Halt ({{ $billingAuditStats['spot-purchase']['halt'] }})</h5>
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
