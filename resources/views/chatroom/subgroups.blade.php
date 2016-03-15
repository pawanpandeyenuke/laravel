@extends('layouts.dashboard')
<?php $groupnamestr = ucwords($group_name); ?>
@section('content')
	<div class="page-data dashboard-body">
	        <div class="container">
	            <div class="row">

	            @include('panels.left')

					<div class="col-sm-6">
						<div class="shadow-box page-center-data no-margin-top">

							{{ Form::open(array('url' => 'groupchat', 'method' => 'get')) }}
							<div class="page-title">
								<i class="flaticon-balloon"></i>Select Category
							</div>
							@if (Session::has('error'))
								<div class="alert alert-danger">{!! Session::get('error') !!}</div>
							@endif	
							<div class="row">
								<div class="col-md-8 col-md-offset-3">
									<h4>{{$groupnamestr}}</h4>
									<div class="radio-outer-full">
										<div class="row">
											<div class="col-sm-8 col-sm-offset-3">

												@if(!empty($subgroups))
													@foreach($subgroups as $data)
								                        <?php 
								                            $titledata = explode(' ', $data->title);
								                            if(is_array($titledata)){
								                                $title = strtolower(implode('', $titledata));
								                            }
								                            // echo '<pre>';print_r($title);die;
								                        ?>
														<div class="radio-cont radio-label-left">
															<input class="group-radio" type="radio" name="subcategory" value="{{ $title }}" id="{{ $title }}" ></input>
															<label for="{{ $title }}">{{ $data->title }}</label>

															@if($title == 'country')
																<div class="subs" style="display:none">
																{!! Form::select('country', $countries, null, array(
																	'class' => 'search-field',
																	'id' => 'country',
																	
																)); !!}
																</div>
															@elseif($title == 'country,state,city')
																<div class="subs" style="display:none">
																	{!! Form::select('country', $countries, null, array(
																		'class' => 'search-field',
																		'id' => 'subcountry',
																	)); !!}

																	{!! Form::select('state', ['State'], null, array(
																		'class' => 'search-field',
																		'id' => 'substate',
																	)); !!}

																	{!! Form::select('city', ['City'], null, array(
																		'class' => 'search-field',
																		'id' => 'subcity',
																	)); !!}
																</div>
															@endif
														</div>


						                                <?php $fieldsData = DB::table('categories')->where(['parent_id' => $data->id])->where(['status' => 'Active'])->select('title', 'id')->get(); ?>
						                                @if($fieldsData)
						                                <select name="subgroupname" class="selectbox" style="display: none">
						                                    @foreach($fieldsData as $val)
						                                        <?php 
						                                            $titledata1 = explode(' ', $val->title);
						                                            if(is_array($titledata1)){
						                                                $title2 = strtolower(implode('', $titledata1));
						                                            }else{
						                                                 $title2 = $val->title;
						                                            }
						                                            // echo '<pre>';print_r($title);die;
						                                        ?>
						                                        <option value="{{ $title2 }}">{{ $val->title }}</option>
						                                    @endforeach
						                                </select>
						                                @endif

													@endforeach
												@endif

											</div>
										</div>
									</div>
									<input type="hidden" name="parentname" value="{{$group_name}}"></input>
								</div>
							</div>
							<div class="btn-cont text-center">
								<button type="submit" class="btn btn-primary btn-lg">Enter Chat</button>
							</div>
							{{ Form::close() }}
						</div><!--/page center data-->
						<div class="shadow-box bottom-ad"><img src="/images/bottom-ad.jpg" alt="" class="img-responsive"></div>
					</div>

 				@include('panels.right')

            </div>
        </div>
    </div><!--/pagedata-->
 
 <script type="text/javascript">
 	
 $(document).on('click', '.group-radio', function(){

 	if($(this).is(':checked')){

 		// alert('asdfsa');
		$(this).closest('.radio-cont').next().find('.subs').hide();
		$(this).closest('.radio-cont').find('.subs').show();
		$(this).closest('.radio-cont').prev().find('.subs').hide();

 	}

 });

	$('#subcountry').change(function(){
		var countryId = $(this).val();
		var _token = $('#searchform input[name=_token]').val();
		$.ajax({			
			'url' : '/ajax/getstates',
			'data' : { 'countryId' : countryId, '_token' : _token },
			'type' : 'post',
			'success' : function(response){				
				$('#substate').html(response);
			}			
		});	
	});

	$('#substate').change(function(){
		var stateId = $(this).val();
		var _token = $('#searchform input[name=_token]').val();
		$.ajax({			
			'url' : '/ajax/getcities',
			'data' : { 'stateId' : stateId, '_token' : _token },
			'type' : 'post',
			'success' : function(response){
				$('#subcity').html(response);
			}			
		});	
	});
 </script>


@endsection

{!! Session::forget('error') !!}