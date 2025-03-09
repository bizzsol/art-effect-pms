<div class="form-group row">
    <div class="col-md-12 mb-4">
        <h4 class="mb-2">Role: <strong>{{$role->name}}</strong></h4>
        <p>
            <strong>Word Restrictions:</strong>
            {{ implode(', ', array_map(function($value){
                return ucwords($value);
            }, isset(json_decode($role->word_restrictions, true)[0]) ? json_decode($role->word_restrictions, true) : [])) }}
        </p>
    </div>
    <div class="col-md-12">
        <h4 class="mb-3 text-center"><strong>Permissions</strong></h4>
        <hr>
        @if(!empty($rolePermissions))
            @foreach(collect($rolePermissions)->groupBy('module') as $module => $permissions)
            <div class="row mb-4">
                <div class="col-md-12">
                    <h5><strong>{{ $module }}</strong></h5>
                    <hr class="mt-1 pt-1">
                    <div class="row">
                        @foreach($permissions->chunk(5) as $key => $chunk)
                        <div class="col-md-4">
                            <ul class="pl-3">
                                @foreach($chunk as $key => $permission)
                                <li>{{ $permission->name }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        @endif
    </div>
</div>