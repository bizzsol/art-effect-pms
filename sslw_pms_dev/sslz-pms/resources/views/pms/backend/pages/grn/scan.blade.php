@extends('pms.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
<style type="text/css">
    .list-unstyled .ratings {
        display: none;
    }
</style>
@endsection
@section('main-content')

<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="{{  route('pms.dashboard') }}">{{ __('Home') }}</a>
                </li>
                <li>
                    <a href="#">PMS</a>
                </li>
                <li class="active">{{__($title)}}</li>
            </ul><!-- /.breadcrumb -->
        </div>

        <div class="page-content">
            <div class="">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h4 class="text-success"><strong><i class="las la-sign-in-alt"></i>&nbsp;Gate In</strong></h4>
                    </div>
                    <div class="panel-body">
                        <form action="{{ url('pms/grn/po-list-scan') }}" method="post" accept-charset="utf-8">
                        @csrf
                            <input type="hidden" name="type" value="in">
                            <div class="row pl-4 pt-3">
                                <div class="col-md-5 offset-md-2">
                                    <div class="input-group input-group-md mb-3 d-">
                                        <input type="text" name="po_reference" id="po_reference" class="form-control rounded" aria-describedby="inputGroup-sizing-sm" required placeholder="Write PO Reference or Scan from Barcode Scanner">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="input-group input-group-md">
                                        <button type="submit" class="btn btn-success btn-block rounded"><i class="las la-sign-in-alt"></i>Proceed to Gate In</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h4 class="text-danger"><strong><i class="las la-sign-out-alt"></i>&nbsp;Gate out</strong></h4>
                    </div>
                    <div class="panel-body">
                        <form action="{{ url('pms/grn/po-list-scan') }}" method="post" accept-charset="utf-8">
                        @csrf
                            <input type="hidden" name="type" value="out">
                            <div class="row pl-4 pt-3">
                                <div class="col-md-5 offset-md-2">
                                    <div class="input-group input-group-md mb-3 d-">
                                        <input type="text" name="po_reference" id="po_reference" class="form-control rounded" aria-describedby="inputGroup-sizing-sm" required placeholder="Write Return/Replace Code or Scan from Barcode Scanner">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="input-group input-group-md">
                                        <button type="submit" class="btn btn-danger btn-block rounded"><i class="las la-sign-out-alt"></i>Proceed to Gate Out</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>
@endsection
@section('page-script')
<script type="text/javascript">
    $(document).ready(function() {
        $('#po_reference').focus();
    });
</script>
@endsection