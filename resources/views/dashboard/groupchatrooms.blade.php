@extends('layouts.dashboard')

@section('content')

<div class="page-data dashboard-body">
        <div class="container">
            <div class="row">

            @include('panels.left')

            <div class="col-sm-6">
                <div class="shadow-box page-center-data no-margin-top">
                    <div class="page-title">
                        <i class="flaticon-people"></i>Group Chatroom
                        <div class="search-box">
                            <input type="text" placeholder="Search" class="form-control">
                            <button class="search-btn-small" type="button"><i class="glyph-icon flaticon-magnifyingglass138"></i></button>
                        </div>
                    </div>

                    <div class="container">

    <?php $count = 0; ?>
    @foreach($parent_category as $data)

        <?php 
            $titledata = explode(',', $data->title);
            if(is_array($titledata)){
                $title1 = strtolower(implode('', $titledata));

                $exp = explode(' ', $title1);
                if(is_array($exp))
                    $title = implode('', $exp);
                else
                    $title = $title1;
            }
            // echo '<pre>';print_r($title);die;
        ?>
        @if($count <= 9)
            <div style="border:solid 1px;height:100px;width:110px;float:left;margin:15px;padding:5px" class="">
                <a id="dLabel" data-target="#" href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    {{ $data->title }}
                </a>
                <?php 
                    $subCat = DB::table('categories')->where(['parent_id' => $data->id])->get(); 
                    // echo '<pre>';print_r($subCat);//die;
                    if(!empty($subCat)){ ?>
                        <br/><a style="border:solid 1px;height:50px;width:auto;padding:5px;margin-top:10px;margin-left:5px;margin-right:5px" data-parentid="{{ $data->id }}" data-value="{{ $title }}" class="groupnext" href="subgroup/{{$title}}/{{$data->id}}">Next >></a>
                <?php }else{ ?> 
                        <br/><a style="border:solid 1px;height:50px;width:auto;padding:5px;margin:5px;" class="enterchat" data-value="{{ $title }}" data-parentid="{{ $data->id }}" href="groupchat/{{$title}}">Enter Chat</a>
                <?php } ?>
            </div>
            <?php $count = $count + 1; ?>
        @endif
    @endforeach


                    </div>


           
    <div class="shadow-box bottom-ad"><img class="img-responsive" alt="" src="images/bottom-ad.jpg"></div>
            </div></div>

 @include('panels.right')
            </div>
        </div>
    </div><!--/pagedata-->
  
 
@endsection