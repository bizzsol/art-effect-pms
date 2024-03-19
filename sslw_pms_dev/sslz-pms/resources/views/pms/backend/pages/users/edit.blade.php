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
                <li class="active">{{__($title)}}</li>
                <li class="top-nav-btn">
                   <a href="javascript:history.back()" class="btn btn-sm btn-warning text-white" data-toggle="tooltip" title="Back" > <i class="las la-chevron-left"></i>Back</a>
               </li>
           </ul><!-- /.breadcrumb -->

       </div>

       <div class="page-content">
        <div class="">
            <div class="panel panel-info">
                <div class="panel-body">
                    {!! Form::model($user,array('route' => ['pms.admin.users.update',$user->id],'method'=>'PUT','class'=>'','files'=>true)) !!}

                    <!--begin::Form-->
                    <div class="form-group row">
                        <label for="example-text-input" class="col-3 col-form-label text-right"><strong>Associate Id <sup class="text-danger">*</sup></strong></label>
                        <div class="col-5">
                            {!! Form::text('associate_id', $value=old('associate_id'), array('placeholder' => 'Associate Id','class' => 'form-control','id' => 'associate_id','required'=>false)) !!}

                            @if ($errors->has('associate_id'))
                            <span class="help-block">
                                <strong class="text-danger">{{ $errors->first('associate_id') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="col-2">
                            <a  class="btn btn-sm btn-success checkUsers" onclick="checkUsers()"><i class="las la-check-circle" type="button"></i>Search</a>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="example-text-input" class="col-3 col-form-label text-right"><strong>Name <sup class="text-danger">*</sup></strong></label>
                        <div class="col-6">
                            {!! Form::text('name', $value=old('name',$user->name), array('placeholder' => 'Name','class' => 'form-control','required'=>true)) !!}

                            @if ($errors->has('name'))
                            <span class="help-block">
                                <strong class="text-danger">{{ $errors->first('name') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="example-text-input" class="col-3 col-form-label text-right"><strong>Phone <sup class="text-danger">*</sup></strong></label>
                        <div class="col-6">
                            {!! Form::text('phone', $value=old('phone',$user->phone), array('placeholder' => 'Phone','class' => 'form-control','required'=>true)) !!}

                            @if ($errors->has('phone'))
                            <span class="help-block">
                                <strong class="text-danger">{{ $errors->first('phone') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="example-text-input" class="col-3 col-form-label text-right"><strong>Email</strong></label>
                        <div class="col-6">
                            {!! Form::email('email', $value=old('email',$user->email), array('placeholder' => 'Email address','class' => 'form-control','required'=>false)) !!}

                            @if ($errors->has('email'))
                            <span class="help-block">
                                <strong class="text-danger">{{ $errors->first('email') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="example-text-input" class="col-3 col-form-label text-right"><strong>Profile Photo</strong></label>
                        <div class="col-6">

                            <label class="slide_upload" for="file">
                                <!--  -->
                                @if(isset($user->profile_photo_path) && file_exists($user->profile_photo_path))
                                <img id="image_load" src="{{asset($user->profile_photo_path)}}" style="width: 150px;height: 150px;cursor:pointer">
                                @else

                                <img id="image_load" src="{{asset('assets/images/user/09.jpg')}}" style="width: 150px; height: 150px;cursor:pointer;">
                                @endif

                            </label>
                            <input id="file" style="display:none" name="profile_photo_path" type="file" onchange="photoLoad(this,this.id)" accept="image/*">


                            @if ($errors->has('profile_photo_path'))
                            <span class="help-block">
                                <strong class="text-danger">{{ $errors->first('profile_photo_path') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    

                    <div class="form-group row">
                        <label for="example-text-input" class="col-3 col-form-label text-right"><strong>Unit</strong></label>
                        <div class="col-6">
                         {!! Form::text('as_unit_id', isset($user->employee->unit) ? $user->employee->unit->hr_unit_name : '', array('placeholder' => 'Unit name','class' => 'form-control','readonly'=>true,'id'=>'as_unit_id')) !!}
                     </div>
                 </div>

                 <div class="form-group row">
                    <label for="example-text-input" class="col-3 col-form-label text-right"><strong>Location</strong></label>
                    <div class="col-6">
                     {!! Form::text('as_location', isset($user->employee->location) ? $user->employee->location->hr_location_name : '', array('placeholder' => 'Location name','class' => 'form-control','readonly'=>true,'id'=>'location_id')) !!}
                 </div>
             </div>

             <div class="form-group row">
                <label for="example-text-input" class="col-3 col-form-label text-right"><strong>Department</strong></label>
                <div class="col-6">
                 {!! Form::text('as_department_id', isset($user->employee->department) ? $user->employee->department->hr_department_name : '', array('placeholder' => 'Department name','id'=>'department_id','class' => 'form-control','readonly'=>true)) !!}
             </div>
         </div>

         <div class="form-group row">
                <label for="example-text-input" class="col-3 col-form-label text-right"><strong>Section</strong></label>
                <div class="col-6">
                 {!! Form::text('as_section_id', isset($user->employee->section) ? $user->employee->section->hr_section_name : '', array('placeholder' => 'Section name','id'=>'section_id','class' => 'form-control','readonly'=>true)) !!}
             </div>
         </div>

         <div class="form-group row">
            <label for="example-text-input" class="col-3 col-form-label text-right"> <strong>Designation</strong></label>
            <div class="col-6">
                {!! Form::text('as_designation_id', isset($user->employee->designation) ? $user->employee->designation->hr_designation_name : '', array('placeholder' => 'Designation name','id'=>'designation_id','class' => 'form-control','readonly'=>true)) !!}
            </div>
        </div>

        <div class="form-group row">
            <label for="example-text-input" class="col-3 col-form-label text-right"><strong>Assign Role(s)</strong></label>
            <div class="col-6">
                {!! Form::select('roles[]', $roles,$userRole, array('id'=>'','class' => 'form-control','multiple'=>true,'required'=>true)) !!}
                @if ($errors->has('roles'))
                <span class="help-block">
                    <strong class="text-danger">{{ $errors->first('roles') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <label for="example-text-input" class="col-3 col-form-label text-right"><strong>Assign Unit(s)</strong></label>
            <div class="col-6">
                <select name="user_unit_id[]" id="user_unit_id" class="form-control select2 rounded" multiple>
                   @if(isset($units[0]))
                   @foreach($units as $key => $unit)
                   <option value="{{ $unit->hr_unit_id }}" {{ $user->priorities->where('hr_unit_id', $unit->hr_unit_id)->count() > 0 ? 'selected' : '' }}>{{ $unit->hr_unit_name }}</option>
                   @endforeach
                   @endif
                </select>

                @if ($errors->has('user_unit_id'))
                <span class="help-block">
                    <strong class="text-danger">{{ $errors->first('user_unit_id') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <label for="example-text-input" class="col-3 col-form-label text-right"><strong>Assign Depertment(s)</strong></label>
            <div class="col-6">
                <select name="user_department_id[]" id="user_department_id" class="form-control select2 rounded" multiple onchange="updateSections()">
                   @if(isset($departments[0]))
                   @foreach($departments as $key => $department)
                   <option value="{{ $department->hr_department_id }}" {{ $user->priorities->where('hr_department_id', $department->hr_department_id)->count() > 0 ? 'selected' : '' }}>{{ $department->hr_department_name }}</option>
                   @endforeach
                   @endif
                </select>

                @if ($errors->has('user_department_id'))
                <span class="help-block">
                    <strong class="text-danger">{{ $errors->first('user_department_id') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <label for="example-text-input" class="col-3 col-form-label text-right"><strong>Assign Section(s)</strong></label>
            <div class="col-6">
                <select name="user_section_id[]" id="user_section_id" class="form-control select2 rounded" multiple>
                   @if(isset($sections[0]))
                   @foreach($sections as $key => $section)
                   <option value="{{ $section->hr_section_id }}" data-department-id="{{ $section->hr_section_department_id }}" {{ $user->priorities->where('hr_section_id', $section->hr_section_id)->count() > 0 ? 'selected' : '' }}>{{ $section->department->hr_department_name }} - {{ $section->hr_section_name }}</option>
                   @endforeach
                   @endif
                </select>

                @if ($errors->has('user_section_id'))
                <span class="help-block">
                    <strong class="text-danger">{{ $errors->first('user_section_id') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <label for="example-text-input" class="col-3 col-form-label text-right"><strong>Assign Warehouse(s)</strong></label>
            <div class="col-6">
                {!! Form::select('warehouse_id[]',$warehouse,$userWarehouse,array('id'=>'','class' => 'form-control','multiple'=>true)) !!}

                @if ($errors->has('warehouse_id'))
                <span class="help-block">
                    <strong class="text-danger">{{ $errors->first('warehouse_id') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <label for="example-text-input" class="col-3 col-form-label text-right"><strong>Cost Centre</strong></label>
            <div class="col-6">
                <select name="cost_centre_id" id="cost_centre_id" class="form-control rounded">
                    @if(isset($costCentres[0]))
                    @foreach($costCentres as $key => $costCentre)
                    <option value="{{ $costCentre->id }}" {{ $user->cost_centre_id == $costCentre->id ? 'selected' : '' }}>{{ $costCentre->name }}</option>
                    @endforeach
                    @endif
                </select>

                @if ($errors->has('cost_centre_id'))
                <span class="help-block">
                    <strong class="text-danger">{{ $errors->first('cost_centre_id') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="form-group row">
            @if ($errors->has('permission'))
                <span class="help-block">
                        <strong class="text-danger">{{ $errors->first('permission') }}</strong>
                    </span>
                <br>
            @endif

            <div class="col-md-12">
                <h4 class="text-center">Allow Permissions (&nbsp;<label for="check_all" style="cursor: pointer">
                    <input type="checkbox" name="check_all" id="check_all">&nbsp;Check all Permissions
                </label>)</h4>
                <hr>
            </div>

            <div class="col-md-12">
                <div class="row">
                    @php
                        $chunk_counter = 0;
                    @endphp
                    @if(isset($modules[0]))
                    @foreach($modules as $key => $module)
                        @php
                            $modulePermissions = collect(Spatie\Permission\Models\Permission::where('module', $module)->get())->chunk(12)
                        @endphp
                        @foreach($modulePermissions as $chunk)
                        @php
                            $chunk_counter++;
                        @endphp
                        <div class="col-3">
                            <h5 class="">
                                <label for="check_all_module-{{ $chunk_counter  }}" style="cursor: pointer">
                                    <input type="checkbox" class="check-all-module" data-counter="{{ $chunk_counter  }}" id="check_all_module-{{ $chunk_counter  }}" style="transform: scale(1.25, 1.25)">&nbsp;&nbsp;&nbsp;<strong>{{ $module }}</strong>
                                </label>
                            </h5>
                            @foreach($chunk as $permission)
                            <label>
                                <input name="permission[]" type="checkbox" value="{{ $permission->id }}" class="name module-permissions-{{ $chunk_counter }} permissions" {{ in_array($permission->id, $userPermissions) ? 'checked' : '' }}>&nbsp;
                                {{ $permission->name }}
                            </label>
                            <br/>
                            @endforeach
                        </div>
                        @endforeach
                    @endforeach
                    @endif
                </div>
            </div>

            @php
                $chunk_counter = 0;
            @endphp
            @if(isset($permissions[0]))
            @foreach($permissions as $key => $chunk)
            @php
                $chunk_counter++;
            @endphp
                <div class="col-3">
                    <h5 class="">
                        <label for="check_all_permission-{{ $chunk_counter  }}" style="cursor: pointer">
                            <input type="checkbox" class="check-all-permission" data-counter="{{ $chunk_counter }}" id="check_all_permission-{{ $chunk_counter  }}" style="transform: scale(1.25, 1.25)">&nbsp;&nbsp;&nbsp;<strong>Allow Permissions</strong>
                        </label>
                    </h5>
                    @foreach($chunk as $permission)
                    <label>
                        <input name="permission[]" type="checkbox" value="{{ $permission->id }}" class="name module-permissions-{{ $chunk_counter }} permissions" {{ in_array($permission->id, $userPermissions) ? 'checked' : '' }}>&nbsp;
                        {{ $permission->name }}
                    </label>
                    <br/>
                    @endforeach
                </div>
            @endforeach
            @endif
        </div>

        <div class="row pt-3">
            <div class="col-md-6 offset-md-3">
                <button type="submit" class="btn btn-primary"><i class="la la-check"></i>&nbsp;Update User</button>
                @can('user-list')
                <a href="{{route('pms.admin.users.index')}}" class="btn btn-secondary"><i class="la la-times"></i>&nbsp;Cancel </a>
                @endcan
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

@endsection

@section('page-script')
<script>
    function photoLoad(input,image_load) {
        var target_image='#'+$('#'+image_load).prev().children().attr('id');

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $(target_image).attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

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

        updateSections();
    });

    function updateSections() {
        var departments = $("#user_department_id :selected").map(function(i, el) {
            return $(el).val();
        }).get();

        $.each($('#user_section_id').find('option'), function(index, val) {
           if($.inArray($(this).attr('data-department-id'), departments) != -1){
            $(this).removeAttr('hidden');
           }else{
            $(this).attr('hidden', 'hidden');
           }
        });
          
        $('#user_section_id').select2({
            tags: true,
            templateResult: function(option) {
              if(option.element && (option.element).hasAttribute('hidden')){
                 return null;
              }
              return option.text;
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

    })(jQuery);

    function checkUsers() {
        var associateId = $('#associate_id').val();

        if (associateId !='') {

            $.ajax({
                url: '{{url('pms/admin/check-user')}}/'+associateId,
                type: 'get',
                success: (data) => {

                    if (data.status === 200) {
                        $('#name').val(data.employee.as_name);
                        $('#as_unit_id').val(data.employee.unit.hr_unit_name);
                        $('#location_id').val(data.employee.location.hr_location_name);
                        $('#department_id').val(data.employee.department.hr_department_name);
                        $('#designation_id').val(data.employee.designation.hr_designation_name);
                    }

                    if (data.status === 400) {
                        $('#name').val();
                        $('#as_unit_id').val();
                        $('#location_id').val();
                        $('#department_id').val();
                        $('#designation_id').val();
                        $('#phone').val();
                        $('#email').val();
                        $('#roles').select2().val().trigger('change');
                        $('#warehouse_id').select2().val().trigger('change');
                        notify('No user information found with this associate id.','error');
                    }
                    if (data.exists_user !=null) {
                        $('#phone').val(data.exists_user.phone);
                        $('#email').val(data.exists_user.email);
                        $('#roles').select2().val(data.userRole).trigger('change');
                        $('#warehouse_id').select2().val(data.userWarehouse).trigger('change');
                        notify('An user already exists with this associate id.','success');
                    }
                }
            });

        }else{
            notify('Please enter associate id','error');
        }   
    }
</script>
@endsection