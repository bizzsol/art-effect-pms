<div class="col-lg-12">
    <div class="iq-card">
        <div class="iq-card-header d-flex justify-content-between p-0 bg-white">
            <div class="iq-header-title">
                <h4 class="card-title text-primary border-left-heading">PURCHASE MANAGE </h4>
            </div>
        </div>
        <div class="iq-card-body p-0">
            <div class="hr-section">
                <section id="features">
                    <div class="container-fluid p-0">
                        <div class="row">
                            <div class="col-md-3 pr-0">
                                <a href="{{ url('pms/quotation/cs-analysis') }}">
                                    <div class="feature-effect-box wow fadeInUp bg-primary" data-wow-duration="0.4s">
                                          <div class="feature-i iq-bg-primary">
                                            <i class="las la-chart-bar"></i>
                                          </div>
                                        <div class="feature-icon">
                                          <h5 class="text-white">CS Analysis</h5>
                                        </div>
                                        <div class="feature-i iq-bg-primary pull-right counter mr-0" style="border-radius: 25% !important; font-weight: bold;margin-top: 2px">
                                            {{ $purchaseStats['cs-analysis'] }}
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-3 pr-0">
                                <a href="{{ url('pms/quotation/index') }}">
                                    <div class="feature-effect-box wow fadeInUp bg-success" data-wow-duration="0.4s">
                                          <div class="feature-i iq-bg-success">
                                            <i class="las la-check-double"></i>
                                          </div>
                                        <div class="feature-icon">
                                          <h5 class="text-white">App. Quotation</h5>
                                        </div>
                                        <div class="feature-i iq-bg-success pull-right counter mr-0" style="border-radius: 25% !important; font-weight: bold;margin-top: 2px">
                                            {{ $purchaseStats['approved-quotations'] }}
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-3 pr-0">
                                <a href="{{ url('pms/quotation/approval-list') }}">
                                    <div class="feature-effect-box wow fadeInUp bg-warning" data-wow-duration="0.4s">
                                          <div class="feature-i iq-bg-warning">
                                            <i class="las la-hourglass-half"></i>
                                          </div>
                                        <div class="feature-icon">
                                          <h5 class="text-white">Pen. Quotation</h5>
                                        </div>
                                        <div class="feature-i iq-bg-warning pull-right counter mr-0" style="border-radius: 25% !important; font-weight: bold;margin-top: 2px">
                                            {{ $purchaseStats['pending-quotations'] }}
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-3 pr-0">
                                <a href="{{ url('pms/quotation/generate-po-list') }}">
                                    <div class="feature-effect-box wow fadeInUp bg-info" data-wow-duration="0.4s">
                                          <div class="feature-i iq-bg-info">
                                            <i class="las la-file-invoice"></i>
                                          </div>
                                        <div class="feature-icon">
                                          <h5 class="text-white">PO Generated</h5>
                                        </div>
                                        <div class="feature-i iq-bg-info pull-right counter mr-0" style="border-radius: 25% !important; font-weight: bold;margin-top: 2px">
                                            {{ $purchaseStats['purchase-orders'] }}
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-3 pr-0 mt-3">
                                <a href="{{ url('pms/quotation/reject-list') }}">
                                    <div class="feature-effect-box wow fadeInUp bg-danger" data-wow-duration="0.4s">
                                          <div class="feature-i iq-bg-danger">
                                            <i class="las la-times-circle"></i>
                                          </div>
                                        <div class="feature-icon">
                                          <h5 class="text-white">CS Reject List</h5>
                                        </div>
                                        <div class="feature-i iq-bg-danger pull-right counter mr-0" style="border-radius: 25% !important; font-weight: bold;margin-top: 2px">
                                            {{ $purchaseStats['cs-rejected'] }}
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-3 pr-0 mt-3">
                                <a href="{{ url('pms/estimate') }}">
                                    <div class="feature-effect-box wow fadeInUp bg-dark" data-wow-duration="0.4s">
                                          <div class="feature-i iq-bg-dark">
                                            <i class="las la-shopping-cart"></i>
                                          </div>
                                        <div class="feature-icon">
                                          <h5 class="text-white">Direct Purchase</h5>
                                        </div>
                                        <div class="feature-i iq-bg-dark pull-right counter mr-0" style="border-radius: 25% !important; font-weight: bold;margin-top: 2px">
                                            {{ $purchaseStats['direct-purchase'] }}
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-3 pr-0 mt-3">
                                <a href="{{ url('pms/quotation/estimate-approval-list') }}">
                                    <div class="feature-effect-box wow fadeInUp bg-warning" data-wow-duration="0.4s">
                                          <div class="feature-i iq-bg-warning">
                                            <i class="las la-user-check"></i>
                                          </div>
                                        <div class="feature-icon">
                                          <h5 class="text-white">DP App. List</h5>
                                        </div>
                                        <div class="feature-i iq-bg-warning pull-right counter mr-0" style="border-radius: 25% !important; font-weight: bold;margin-top: 2px">
                                            {{ $purchaseStats['direct-purchase-approval'] }}
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-3 pr-0 mt-3">
                                <a href="{{ url('pms/quotation/estimate-reject-list') }}">
                                    <div class="feature-effect-box wow fadeInUp bg-danger" data-wow-duration="0.4s">
                                          <div class="feature-i iq-bg-danger">
                                            <i class="las la-user-times"></i>
                                          </div>
                                        <div class="feature-icon">
                                          <h5 class="text-white">DP Reject List</h5>
                                        </div>
                                        <div class="feature-i iq-bg-danger pull-right counter mr-0" style="border-radius: 25% !important; font-weight: bold;margin-top: 2px">
                                            {{ $purchaseStats['direct-purchase-reject'] }}
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-4 pr-0 mt-3">
                                <a href="{{ url('pms/po-cash-approval') }}?cash_status=pending">
                                    <div class="feature-effect-box wow fadeInUp bg-warning" data-wow-duration="0.4s">
                                          <div class="feature-i iq-bg-warning">
                                            <i class="las la-money-bill-wave"></i>
                                          </div>
                                        <div class="feature-icon">
                                          <h5 class="text-white">Cash Pen.</h5>
                                        </div>
                                        <div class="feature-i iq-bg-warning pull-right counter mr-0" style="border-radius: 25% !important; font-weight: bold;margin-top: 2px">
                                            {{ $purchaseStats['po-cash']['pending'] }}
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-4 pr-0 mt-3">
                                <a href="{{ url('pms/po-cash-approval') }}?cash_status=halt">
                                    <div class="feature-effect-box wow fadeInUp bg-danger" data-wow-duration="0.4s">
                                          <div class="feature-i iq-bg-danger">
                                            <i class="las la-comment-slash"></i>
                                          </div>
                                        <div class="feature-icon">
                                          <h5 class="text-white">Cash Halt</h5>
                                        </div>
                                        <div class="feature-i iq-bg-danger pull-right counter mr-0" style="border-radius: 25% !important; font-weight: bold;margin-top: 2px">
                                            {{ $purchaseStats['po-cash']['halt'] }}
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-4 pr-0 mt-3">
                                <a href="{{ url('pms/po-cash-approval') }}?cash_status=approved">
                                    <div class="feature-effect-box wow fadeInUp bg-success" data-wow-duration="0.4s">
                                          <div class="feature-i iq-bg-success">
                                            <i class="las la-money-check-alt"></i>
                                          </div>
                                        <div class="feature-icon">
                                          <h5 class="text-white">Cash App.</h5>
                                        </div>
                                        <div class="feature-i iq-bg-success pull-right counter mr-0" style="border-radius: 25% !important; font-weight: bold;margin-top: 2px">
                                            {{ $purchaseStats['po-cash']['approved'] }}
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

<!-- <div class="col-lg-6">
    <div class="iq-card">
        <div class="iq-card-header d-flex justify-content-between p-0 bg-white">
            <div class="iq-header-title">
                <h4 class="card-title text-primary border-left-heading"><a href="{{ url('pms/purchase/order-list') }}">PURCHASE STATS</a></h4>
            </div>
        </div>
        <div class="iq-card-body p-0">
            <canvas class="bar-charts" id="purchase-stat-chart" data-data="{{ $purchaseStats['cs-analysis'] }},{{ $purchaseStats['approved-quotations'] }},{{ $purchaseStats['pending-quotations'] }},{{ $purchaseStats['purchase-orders'] }}" data-labels="CS Analysis,App. Quotation,Pen. Quotation,Total PO List" data-legend-position="top" data-title-text="Count" width="200" height="110"></canvas>
        </div>
    </div>
</div>
<div class="col-lg-6">
    <div class="iq-card">
        <div class="iq-card-header d-flex justify-content-between p-0 bg-white">
            <div class="iq-header-title">
                <h4 class="card-title text-primary border-left-heading">TOP SUPPLIER </h4>
            </div>
        </div>
        <div class="iq-card-body p-0">
            <div class="hr-section">
                <section id="features">
                    <div class="container-fluid p-0">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="project-card" style="height: auto !important">
                                    <div class="project-card-header">
                                        <h5 class="mb-0">
                                            <i class="las la-users" style="transform: scale(1.5,1.5)"></i>&nbsp;&nbsp;&nbsp;Top 10 Supplier
                                        </h5>
                                    </div>
                                    <div class="project-card-body pb-0">
                                        <table class="table table-striped table-bordered miw-500 dac_table pb-0 mb-0" cellspacing="0" width="100%" id="dataTable">
                                            @php
                                                $topSuppliers = topSuppliers(10);
                                            @endphp
                                            <tbody>
                                                <tr>
                                                    <td colspan="2" class="text-center">
                                                        <h5><strong>Top</strong></h5>
                                                    </td>
                                                </tr>
                                                @if(isset($topSuppliers[0]))
                                                @foreach($topSuppliers as $key => $topSupplier)
                                                <tr>
                                                    <td style="width: 65%">
                                                        <a href="{{ url('pms/supplier/profile/'.$topSupplier->id) }}">{{ $topSupplier->name }}</a>
                                                    </td>
                                                    <td style="width: 35%" class="text-right">
                                                        {{ $topSupplier->pay_amount > 0 ? $topSupplier->pay_amount : '0.00' }}
                                                    </td>
                                                </tr>
                                                @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div> -->
