@foreach($document->container as $container)
<div class="ui
@if($container->fluid)
fluid
@endif
grid
 container">

 @foreach($container->row as $row)
 <div class="ui row">

 	@foreach($row->column as $column)
 		<div class="ui {{ $renderer->spelledOutRelativeWidthForColumnInGrid($column) }} wide column">
 			{!! $renderer->renderContentToHtml($column) !!}
 		</div>
 	@endforeach

 </div>
 @endforeach

</div>
@endforeach