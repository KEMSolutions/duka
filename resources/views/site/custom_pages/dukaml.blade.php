@foreach($document->container as $container)
<div class="ui stackable grid container">

 @foreach($container->row as $row)
 <div class="ui row" style="padding: 3rem;">

 	@foreach($row->column as $column)
 		<div class="ui {{ $renderer->spelledOutRelativeWidthForColumnInGrid($column) }} wide column">
 			{!! $renderer->renderContentToHtml($column) !!}
 		</div>
 	@endforeach

 </div>
 @endforeach

</div>
@endforeach