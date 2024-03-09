@extends('pms.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('main-content')
<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
              <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="{{  route('pms.dashboard') }}">{{ __('Home') }}</a>
              </li>
              <li><a href="#">PMS</a></li>
              <li class="active">Price Graph</li>
            </ul>
        </div>

        <div class="page-content">
            <div class="">
                <div class="panel panel-info mt-2 p-2">
                    <div class="panel-body">
                        <form action="{{ url('pms/price-graph') }}" method="get" accept-charset="utf-8">
                            <div class="row">
                                <div class="col-md-2">
                                    <p class="mb-1 font-weight-bold"><label for="product_type"><strong>Product Type</strong></p>
                                    <div class="select-search-group input-group input-group-md mb-3 d-" onchange="getSubCategories()">
                                        <select name="product_type" id="product_type" class="form-control parent-category">
                                            <option value="{{ null }}">All Types</option>
                                            <option value="products" {{ request()->get('product_type') == 'products' ? 'selected' : '' }}>Product</option>
                                            <option value="fixed_asset" {{ request()->get('product_type') == 'fixed_asset' ? 'selected' : '' }}>Fixed Asset</option>
                                            <option value="cwip" {{ request()->get('product_type') == 'cwip' ? 'selected' : '' }}>CWIP</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <p class="mb-1 font-weight-bold"><label for="category_id"><strong>Sub Category</strong></label></p>
                                    <div class="select-search-group input-group input-group-md mb-3 d-">
                                        <select name="category_id" id="category_id" class="form-control" onchange="getProducts()">
                                            {!! categoryOptions([]) !!}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <p class="mb-1 font-weight-bold"><label for="product_id"><strong>Products</strong></label></p>
                                    <div class="select-search-group input-group input-group-md mb-3 d-">
                                        <select name="product_id" id="product_id" class="form-control">
                                            
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <p class="mb-1 font-weight-bold"><label for="hr_unit_id"><strong>Unit</strong></p>
                                    <div class="select-search-group input-group input-group-md mb-3 d-">
                                        <select name="hr_unit_id" id="hr_unit_id" class="form-control parent-category">
                                            <option value="{{ null }}">All Units</option>
                                            @if(isset($units[0]))
                                            @foreach($units as $unit)
                                            <option value="{{ $unit->hr_unit_id }}" {{ request()->get('hr_unit_id') == $unit->hr_unit_id ? 'selected' : '' }}>{{ $unit->hr_unit_name }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <p class="mb-1 font-weight-bold"><label for="hr_department_id"><strong>Department</strong></p>
                                    <div class="select-search-group input-group input-group-md mb-3 d-">
                                        <select name="hr_department_id" id="hr_department_id" class="form-control parent-category">
                                            <option value="{{ null }}">All Departments</option>
                                            @if(isset($departments[0]))
                                            @foreach($departments as $department)
                                            <option value="{{ $department->hr_department_id }}" {{ request()->get('hr_department_id') == $department->hr_department_id ? 'selected' : '' }}>{{ $department->hr_department_name }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <p class="mb-1 font-weight-bold"><label for="warehouse_id"><strong>Warehouse</strong></p>
                                    <div class="select-search-group input-group input-group-md mb-3 d-">
                                        <select name="warehouse_id" id="warehouse_id" class="form-control parent-category">
                                            <option value="{{ null }}">All Warehouses</option>
                                            @if(isset($warehouses[0]))
                                            @foreach($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}" {{ request()->get('warehouse_id') == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <p class="mb-1 font-weight-bold"><label for="from"><strong>From</strong></p>
                                    <div class="select-search-group input-group input-group-md mb-3 d-">
                                        <input type="date" name="from" id="from" value="{{ request()->has('from') ? request()->get('from') : date('Y-m-01') }}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <p class="mb-1 font-weight-bold"><label for="to"><strong>To</strong></p>
                                    <div class="select-search-group input-group input-group-md mb-3 d-">
                                        <input type="date" name="to" id="to" value="{{ request()->has('to') ? request()->get('to') : date('Y-m-d') }}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-2 pt-4">
                                    <button type="submit" class="btn btn-success btn-md btn-block mt-2"><i class="la la-search"></i>&nbsp;Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="amchart" id="chartdiv"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script type="text/javascript">
    am5.ready(function() {
        var root = am5.Root.new("chartdiv");
        const myTheme = am5.Theme.new(root);

        myTheme.rule("AxisLabel", ["minor"]).setAll({
          dy: 1
        });

        myTheme.rule("Grid", ["minor"]).setAll({
          strokeOpacity: 0.08
        });

        root.setThemes([
          am5themes_Animated.new(root),
          myTheme
        ]);


        var chart = root.container.children.push(am5xy.XYChart.new(root, {
          panX: false,
          panY: false,
          wheelX: "panX",
          wheelY: "zoomX",
          paddingLeft: 0
        }));

        var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {
          behavior: "zoomX"
        }));
        cursor.lineY.set("visible", false);


        var xAxis = chart.xAxes.push(am5xy.DateAxis.new(root, {
          maxDeviation: 0,
          baseInterval: {
            timeUnit: "day",
            count: 1
          },
          renderer: am5xy.AxisRendererX.new(root, {
            minorGridEnabled: true,
            minGridDistance: 200,    
            minorLabelsEnabled: true
          }),
          tooltip: am5.Tooltip.new(root, {})
        }));

        xAxis.set("minorDateFormats", {
          day: "dd",
          month: "MM"
        });

        var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
          renderer: am5xy.AxisRendererY.new(root, {})
        }));

        var series = chart.series.push(am5xy.LineSeries.new(root, {
          name: "Series",
          xAxis: xAxis,
          yAxis: yAxis,
          valueYField: "qty",
          valueXField: "date",
          tooltip: am5.Tooltip.new(root, {
            labelText: "{valueY}"
          })
        }));

        series.bullets.push(function () {
          var bulletCircle = am5.Circle.new(root, {
            radius: 5,
            fill: series.get("fill")
          });
          return am5.Bullet.new(root, {
            sprite: bulletCircle
          })
        })

        chart.set("scrollbarX", am5.Scrollbar.new(root, {
          orientation: "horizontal"
        }));

        var data = <?php echo json_encode($data); ?>;
        series.data.setAll(data);

        series.appear(1000);
        chart.appear(1000, 100);

        var title = "{{ $title }}";
        var exporting = am5plugins_exporting.Exporting.new(root, {
          menu: am5plugins_exporting.ExportingMenu.new(root, {}),
          filePrefix: title,
          dataSource: data,
          pdfOptions: {
            pageSize: "A4",
            pageOrientation: "landscape",
          }
        });

        exporting.events.on("pdfdocready", function(event) {
          // Add title to the beginning
          event.doc.content.unshift({
            text: title,
            margin: [0, 10],
            style: {
              fontSize: 14,
              bold: true,
            }
          });

          // Add a two-column intro
          event.doc.content.push({
            alignment: 'justify',
            columns: [{
              text: $('#chart-caption').text()
            }],
            columnGap: 0,
            margin: [0, 10]
          });
        });

        $('.am5exporting-icon').html('<i class="las la-file-export"></i>&nbsp;&nbsp;Export');
    });

    getSubCategories();
    function getSubCategories(){
        $('#category_id').html('<option selected disabled value="">Please wait...</option>');
        $.ajax({
            url: "{{ url('pms/price-graph') }}?get-sub-categories&"+$('#product_type').val()+"&chosen={{ request()->get('category_id') }}",
            type: 'GET',
            data: {},
        })
        .done(function(response) {
            $('#category_id').html(response).change();
        });
    }

    function getProducts(){
        $('#product_id').html('<option selected disabled value="">Please wait...</option>');
        $.ajax({
            url: "{{ url('pms/price-graph') }}?get-products&category_id="+$('#category_id').val()+"&chosen={{ request()->get('product_id') }}",
            type: 'GET',
            data: {},
        })
        .done(function(response) {
            $('#product_id').html(response).change();
        });
    }
</script>
@endsection
