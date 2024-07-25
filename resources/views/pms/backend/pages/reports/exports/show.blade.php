<h5 class="mb-2"><strong>{{ $title }} {{ $timeline['timeline'] }}</strong></h5>
<table class="table table-bordered table-striped" style="width: 100%">
    @include('pms.backend.pages.reports.'.$folder.'.report')
</table>