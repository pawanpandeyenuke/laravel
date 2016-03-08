
<?php 
// echo '<pre>';print_r($subgroups);die;
?>

	<?php $count = 0; ?>
	@foreach($subgroups as $data)
		@if($count <= 9)
        	<div>    			
    			<input type="radio" name="radiobtn" id="{{ $data->title.$data->title }}" data-id="{{ $data->title }}"></input>
    			<label for="{{ $data->title.$data->title }}">{{ $data->title }}</label>
        	</div>
    		<?php $count = $count + 1; ?>
    	@endif
    @endforeach

    <a style="border:solid 1px;height:50px;width:auto;padding:5px;margin:5px;" class="enterchat" data-parentid="{{ $data->id }}">Enter Chat</a>