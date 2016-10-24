@extends('layouts.dashboard')

@include('panels.meta-data')
@section('title', 'Group Chat')
@section('content')

<div class="page-data dashboard-body">
        <div class="container">
            <div class="row">

            @include('panels.left')

            <div class="col-sm-6">
                <div class="shadow-box page-center-data no-margin-top">
                    <div class="page-title">
                        <i class="flaticon-people"></i>Group Chatroom
<!--                         <div class="search-box">
                            <input type="text" placeholder="Search" class="form-control">
                            <button class="search-btn-small" type="button"><i class="glyph-icon flaticon-magnifyingglass138"></i></button>
                        </div> -->
                    </div>

                    <div class="container">
                        {{ Form::open(array('url' => 'groupchat', 'method' => 'get')) }}

                        <input type="hidden" name="parentgroup" value="{{ $parentgroup }}"></input>
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
                                    <input class="group-radio" data-value="{{ $title }}" type="radio" name="subcategory" id="{{ $title }}" data-id="{{ $data->title }}"></input>
                                    <label for="{{ $title }}">{{ $data->title }}</label>
                                </div>
                                <?php $fieldsData = \App\Category::where(['parent_id' => $data->id])->where(['status' => 'Active'])->select('title', 'id')->get(); ?>
                                @if($fieldsData)
                                <select name="subgroup" class="selectbox" style="display: none">
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
                            </div>
                        @endforeach
                    <br/><br/><br/>

                        <button>Enter Chat</button>
                        <a style="border:solid 1px;height:50px;width:auto;padding:5px;margin:5px;" class="enterchat" data-value="" data-parentid="{{ $data->id }}" href="groupchat/{{$parentgroup}}/staticname"></a>


                        {{ Form::close() }}
                    </div>


           
    <div class="shadow-box bottom-ad"><img class="img-responsive" alt="Shop By Temperature" src="{{url('images/bottom-ad.jpg')}}"></div>
            </div></div>

 @include('panels.right')
            </div>
        </div>
    </div><!--/pagedata-->
  
 
@endsection


 
