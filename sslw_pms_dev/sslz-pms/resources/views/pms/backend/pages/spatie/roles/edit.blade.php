@extends('pms.backend.layouts.master-layout')

@section('title', session()->get('system-information')['name']. ' | '.$title)

@section('page-css')

@endsection

@section('main-content')
    <!-- WRAPPER CONTENT ----------------------------------------------------------------------------->
    <div class="main-content">
        <div class="main-content-inner">
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <a href="#">Home</a>
                    </li>
                    <li>
                        <a href="#">PMS</a>
                    </li>
                    <li class="active">{{__($title)}} List</li>
                    <li class="top-nav-btn">
                        <a href="{{route('pms.acl.roles.index')}}" class="btn btn-sm btn-primary text-white" data-toggle="tooltip" title="Click Here to go Role List" id="addRequisitionTypeBtn"> <i class="las la-list"></i>Role List</a>
                    </li>
                </ul><!-- /.breadcrumb -->

            </div>

            <div class="page-content">
                <div class="">
                    <div class="panel panel-info">
                        <div class="panel-body">
                            {!! Form::model($role, ['method' => 'PATCH','route' => ['pms.acl.roles.update', $role->id],'class'=>'kt-form kt-form--label-right']) !!}

                            <div class="form-group row">
                                <div class="col-3">
                                    <label for="example-text-input" class="col-form-label text-right"><strong>Role Name <span class="text-danger">&nbsp;*</span></strong></label>
                                    {!! Form::text('name', $value=old('name', $role->name), array('placeholder' => 'Role Name Here','class' => 'form-control','required'=>true)) !!}

                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                            <strong class="text-danger">{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-9">
                                    <label for="example-text-input" class="col-form-label text-right">Word Restrictions</label>
                                    {!! Form::text('word_restrictions', $value=old('word_restrictions', implode(', ', array_map(function($value){
                                                return ucwords($value);
                                            }, isset(json_decode($role->word_restrictions, true)[0]) ? json_decode($role->word_restrictions, true) : []))), array('placeholder' => 'Write Words here (Separated by Comma)','class' => 'form-control','required'=>false)) !!}

                                    @if ($errors->has('word_restrictions'))
                                        <span class="help-block">
                                            <strong class="text-danger">{{ $errors->first('word_restrictions') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                @if ($errors->has('permission'))
                                    <span class="help-block">
                                        <strong class="text-danger">{{ $errors->first('permission') }}</strong>
                                    </span>
                                    <br>
                                @endif

                                <div class="col-md-12 mb-2">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="check_all" style="cursor: pointer">
                                                <input type="checkbox" name="check_all" id="check_all" style="transform: scale(1.5, 1.5)">&nbsp;&nbsp;&nbsp;<strong>Check all Permissions</strong>
                                            </label>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-0 pb-0">
                                                <input type="text" placeholder="Search Permissions here..." class="form-control search" style="margin-top: -6px">
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="mt-0 pt-0">
                                </div>

                                <div class="col-md-12">
                                    <div class="row">
                                        @php
                                            $chunk_counter = 0;
                                        @endphp
                                        @if(isset($modules[0]))
                                        @foreach($modules as $key => $module)
                                        @php
                                            $modulePermissions = collect(Spatie\Permission\Models\Permission::where('module', $module)->get())->chunk(10)
                                        @endphp
                                        @if(isset($modulePermissions[0]))
                                            @foreach($modulePermissions as $chunk)
                                            @php
                                                $chunk_counter++;
                                            @endphp
                                            <div class="col-3 mb-3">
                                                <h5 class="">
                                                    <label for="check_all_module-{{ $chunk_counter  }}" style="cursor: pointer">
                                                        <input type="checkbox" class="check-all-module" data-counter="{{ $chunk_counter  }}" id="check_all_module-{{ $chunk_counter  }}" style="transform: scale(1.25, 1.25)" {{ $chunk->count() == $chunk->whereIn('id', $rolePermissions)->count() ? 'checked' : ''  }}>&nbsp;&nbsp;&nbsp;<strong>{{ $module }}</strong>
                                                    </label>
                                                </h5>
                                                @foreach($chunk as $permission)
                                                    <label class="permission-names">
                                                        <input name="permission[]" type="checkbox" value="{{ $permission->id }}" class="name module-permissions-{{ $chunk_counter }} permissions" {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>&nbsp;
                                                        {{ $permission->name }}
                                                    </label>
                                                    <br/>
                                                @endforeach
                                            </div>
                                            @endforeach
                                        @endif
                                        @endforeach
                                        @endif
                                    </div>
                                </div>
                                    
                                <div class="col-md-12">
                                    <div class="row">
                                        @php
                                            $chunk_counter = 0;
                                        @endphp
                                        @if(isset($permissions[0]))
                                        @foreach($permissions as $chunk)
                                        @php
                                            $chunk_counter++;
                                        @endphp
                                            <div class="col-3 mb-3">
                                                <h5 class="">
                                                    <label for="check_all_permission-{{ $chunk_counter  }}" style="cursor: pointer">
                                                        <input type="checkbox" class="check-all-permission" data-counter="{{ $chunk_counter }}" id="check_all_permission-{{ $chunk_counter  }}" style="transform: scale(1.25, 1.25)" {{ $chunk->count() == $chunk->whereIn('id', $rolePermissions)->count() ? 'checked' : ''  }}>&nbsp;&nbsp;&nbsp;<strong>Allow Permissions</strong>
                                                    </label>
                                                </h5>
                                                @foreach($chunk as $permission)
                                                <label class="permission-names">
                                                    <input name="permission[]" type="checkbox" value="{{ $permission->id }}" class="name permission-permmissions-{{ $chunk_counter }} permissions" {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>&nbsp;
                                                    {{ $permission->name }}
                                                </label>
                                                <br/>
                                                @endforeach
                                            </div>
                                        @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary pull-right">Submit</button>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END WRAPPER CONTENT ------------------------------------------------------------------------->
    <!-- Modal ------------------------------------------------------------------------->
    <div class="modal fade bd-example-modal-md" id="requisitionTypeModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="requisitionAddModalLabel">Add New Requisition Type</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="requisitionForm">
                        <div class="form-row">
                            <div class="col-md-12">
                                <p class="mb-1 font-weight-bold"><label for="name">{{ __('Name') }}:</label> <span class="text-danger"></span></p>
                                <div class="input-group input-group-md mb-12 d-">
                                    <input type="text" name="name" id="name" class="form-control rounded" aria-label="Large" placeholder="{{__('Name')}}" aria-describedby="inputGroup-sizing-sm" required>

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary rounded" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="button" class="btn btn-danger rounded" id="requisitionTypeFormSubmit">{{ __('Save') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        $(document).ready(function() {
            $('#check_all').change(function () {
                if($('#check_all').is(':checked')){
                    $('.permissions').prop('checked', true);
                    $('.check-all-module').prop('checked', true);
                }else{
                    $('.permissions').prop('checked', false);
                    $('.check-all-module').prop('checked', false);
                }
            });

            $('.check-all-module').change(function(event) {
                if($(this).is(':checked')){
                    $('.module-permissions-'+$(this).attr('data-counter')).prop('checked', true);
                }else{
                    $('.module-permissions-'+$(this).attr('data-counter')).prop('checked', false);
                }
            });

            $('.check-all-permission').change(function(event) {
                if($(this).is(':checked')){
                    $('.permission-permissions-'+$(this).attr('data-counter')).prop('checked', true);
                }else{
                    $('.permission-permissions-'+$(this).attr('data-counter')).prop('checked', false);
                }
            });

            $('.search').change(function(event) {
                searchPermissions($(this).val());
            });

            $('.search').keyup(function(event) {
                searchPermissions($(this).val());
            });
        }); 

        function searchPermissions(search) {
            $.each($('.permission-names'), function(index, val) {
                if($(this).text().trim().indexOf(search) !== -1){
                    $(this).show();
                }else{
                    $(this).hide();
                }
            });
        }

        (function ($) {
            "use script";
            $('[data-toggle="tooltip"]').tooltip();
            const form = document.getElementById('requisitionForm');
            const tableContainer = document.getElementById('dataTable').querySelector('tbody');

            const showAlert = (status, error) => {
                swal({
                    icon: status,
                    text: error,
                    dangerMode: true,
                    buttons: {
                        cancel: false,
                        confirm: {
                            text: "OK",
                            value: true,
                            visible: true,
                            closeModal: true
                        },
                    },
                }).then((value) => {
                    if(value)form.reset();
            });
            };



            $('#addRequisitionTypeBtn').on('click', function () {
                $('#requisitionTypeModal').modal('show');
                form.setAttribute('data-type', 'post');
            });

        })(jQuery)
    </script>
@endsection