<?php
    $route = Route::current()->getUri();

    if( $_SERVER['REQUEST_URI'] == '/' )
    	$data = getMetaData( $_SERVER['REQUEST_URI'], false );
    elseif( stristr( $_SERVER['REQUEST_URI'], 'forums' ) ) 
    	$data = getMetaData( ltrim($_SERVER['REQUEST_URI'], '/'), true );
	else 
    	$data = getMetaData( ltrim($_SERVER['REQUEST_URI'], '/'), false );

    $meta_title = $meta_keyword = $meta_description = '';

    if( $data ){
	    $meta_title = $data->meta_title;
	    $meta_keyword = $data->meta_keyword;
	    $meta_description = $data->meta_description;
    }
?>
@if( $meta_title ) @section( 'title', $meta_title ) @endif
@if( $meta_keyword ) @section( 'keywords', $meta_keyword ) @endif
@if( $meta_description ) @section( 'description', $meta_description ) @endif