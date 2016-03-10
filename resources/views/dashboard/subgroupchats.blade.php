
<?php 
// echo '<pre>';print_r($subgroups);die;
?>

	@foreach($subgroups as $data)

    <?php 
        $titledata = explode(' ', $data->title);
        if(is_array($titledata)){
            $title = strtolower(implode('', $titledata));
        }
        // echo '<pre>';print_r($title);die;
    ?>
        <div class="groupcat">
        	<div>    			
    			<input class="group-radio" data-value="{{ $title }}" type="radio" name="radiobtn" id="{{ $title }}" data-id="{{ $data->title }}"></input>
    			<label for="{{ $title }}">{{ $data->title }}</label>
        	</div>
            <?php $fieldsData = DB::table('categories')->where(['parent_id' => $data->id])->where(['status' => 'Active'])->select('title', 'id')->get(); ?>
            @if($fieldsData)
            <select class="selectbox" style="display: none">
                @foreach($fieldsData as $val)
                    <option value="{{ $val->id }}">{{ $val->title }}</option>
                @endforeach
            </select>
            @endif
        </div>
    @endforeach
<br/><br/><br/>
    <a style="border:solid 1px;height:50px;width:auto;padding:5px;margin:5px;" class="enterchat" data-value="{{ $dataval }}" data-parentid="{{ $data->id }}">Enter Chat</a>

<script type="text/javascript">

$(document).on('change', '.group-radio', function(){

    if ( $('.group-radio').is(':checked')){
        var dataval = $(this).data('value');
        var preVal = $('.enterchat').data("value");
        var newval = preVal+'-'+dataval;
        $('.enterchat').attr("data-value",newval);
    }

});

/*    $(document).on('click', '.enterchat', function(){
        var atLeastOneIsChecked = false, dataval;
            $('.group-radio').each(function () {
            if ($(this).is(':checked')) {
                atLeastOneIsChecked = true;
                return false;
            }
        });
    });*/    
</script>