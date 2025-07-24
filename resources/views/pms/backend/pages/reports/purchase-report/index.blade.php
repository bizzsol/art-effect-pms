@extends('pms.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
@endsection
@section('main-content')
    <div class="main-content">
        <div class="main-content-inner">
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <a href="{{  route('pms.dashboard') }}">Home</a>
                    </li>
                    <li>
                        <a href="#">PMS Reports</a>
                    </li>
                    <li class="active">{{ $title }}</li>
                </ul>
            </div>

            <div class="page-content">
                <div class="panel panel-info">
                    <div class="panel-body">
                        <form action="{{ url('pms/purchase-report') }}" method="get" accept-charset="utf-8">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="hr_unit_id"><strong>Unit</strong></label>
                                        <select name="hr_unit_id" id="hr_unit_id" class="form-control">
                                            @if(isset($units[0]))
                                                @foreach($units as $unit)
                                                    <option value="{{ $unit->hr_unit_id }}"
                                                            {{request()->has('hr_unit_id')?(request()->get('hr_unit_id')==$unit->hr_unit_id?'selected':''):''}}>
                                                        {{ $unit->hr_unit_name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    @include('pms.backend.pages.reports.tools.timeline')
                                </div>
                                <div class="col-md-3 pt-4">
                                    <div class="mt-2">
                                        @include('pms.backend.pages.reports.tools.buttons')
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                @if(request()->has('timeline'))
                    <div class="panel panel-info">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12 table-responsive">
                                    @include('pms.backend.pages.reports.exports.show', [
                                        'folder' => 'purchase-report'
                                    ])
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
@section('page-script')
    <script>

    </script>
    @include('pms.backend.pages.reports.tools.js')
@endsection
