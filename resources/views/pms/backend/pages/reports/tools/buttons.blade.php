<div class="btn-group ml-2" style="width: 100%">
    <button type="submit" class="btn btn-md btn-success text-white"><i class="fa fa-search"></i></button>
    <a class="btn btn-md btn-primary" target="_blank" href="{{ url()->full() }}&type=print&text={{ $title }}" title="Print {{ $title }}"><i class="las la-print"></i></a>
    <a class="btn btn-md btn-secondary" href="{{ url()->full() }}&type=pdf&text={{ $title }}" title="Download PDF of {{ $title }}"><i class="las la-file-pdf"></i></a>
    <a class="btn btn-md btn-dark" href="{{ url()->full() }}&type=excel&text={{ $title }}" title="Download Excel of {{ $title }}"><i class="las la-file-excel"></i></a>
    <a class="btn btn-md btn-danger text-white" href="{{ url()->current() }}"><i class="fa fa-times"></i></a>
</div>