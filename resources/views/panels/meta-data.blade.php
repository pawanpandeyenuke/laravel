<?php
    $route = Route::current()->getUri();
    $data = getMetaData( $route, true );

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