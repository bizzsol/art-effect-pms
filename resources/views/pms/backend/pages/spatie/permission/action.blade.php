<div class="modal fade" id="permissionModal{{  $permission->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
        {!! Form::open(array('route' => ['pms.acl.permission.update',$permission->id],'class'=>'form-horizontal author_form','method'=>'PUT','files'=>'true', 'id'=>'commentForm','role'=>'form','data-parsley-validate novalidate')) !!}
            <div class="modal-header">
                <h4 class="modal-title">Permission Edit</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body p-4">
                <div class="form-group row">
                    <label for="module"><strong>Module <span class="text-danger">&nbsp;*</span></strong></label>
                    <input type="text" list="modules" name="module" id="module" class="form-control" value="{{  $permission->module }}">
                    <datalist id="modules">
                        @if(isset($modules[0]))
                        @foreach($modules as $key => $module)
                            <option value="{{ $module }}">
                        @endforeach
                        @endif
                    </datalist>
                </div>
                <div class="form-group row">
                    <label for="name"><strong>Permission <span class="text-danger">&nbsp;*</span></strong></label>
                    <input class="form-control" type="text" id="name" name="name" value="{{  $permission->name }}" />
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="submit" class="btn btn-sm btn-success">Update</button>
            </div>
        {!! Form::close(); !!}
        </div>
    </div>
</div>

{!! Form::open(array('route'=> ['pms.acl.permission.destroy', $permission->id],'method'=>'DELETE','class'=>'deleteForm','id'=>"deleteForm".$permission->id)) !!}
    {{ Form::hidden('id', $permission->id)}}
    @can('permission-edit')
    <a href="#permissionModal{{  $permission->id }}" class="btn btn-warning btn-sm" data-toggle="modal"  title="Click to Edit"><i class="la la-pencil-square"></i> Edit</a>
    @endcan
    @can('permission-delete')
    <button type="button" onclick='return deleteConfirm("deleteForm{{$permission->id}}");' class="btn btn-danger btn-sm">
    <i class="la la-trash"></i> Delete
    </button>
    @endcan
{!! Form::close() !!}