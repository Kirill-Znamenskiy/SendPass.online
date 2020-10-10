@php
    $app_name = config('app.name', 'Laravel');
    $app_url = url('/');
    $app_link = '<a href="'.$app_url.'">'.$app_name.'</a>';
@endphp

@extends('_layout')

@section('meta_title')
    {{$app_name.' - '.__('about.about_project', ['app_name' => $app_name, 'app_url' => $app_url, 'app_link' => $app_link])}}
@endsection


@section('before-container')

    <div class="container">
        <div class="row">
            <div class="col col-md-1"></div>
            <div class="col-12 col-md-10">

                <div class="card faq">
                    <h1 class="card-header h4">{{__('about.about_project', ['app_name' => $app_name, 'app_url' => $app_url, 'app_link' => $app_link])}}</h1>
                    <div class="card-body">

                        {{__('about.main_content', ['app_name' => $app_name, 'app_url' => $app_url, 'app_link' => $app_link])}}


                    </div>
                </div>

            </div>
            <div class="col col-md-1"></div>
        </div>
    </div>

@endsection

