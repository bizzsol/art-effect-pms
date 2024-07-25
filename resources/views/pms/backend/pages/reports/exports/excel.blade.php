<table>
   @include('pms.backend.pages.reports.exports.excel-header', [
      'columns' => $columns,
   ])

   @include('pms.backend.pages.reports.'.$blade)

   @include('pms.backend.pages.reports.exports.excel-footer', [
      'columns' => $columns,
   ])
</table>