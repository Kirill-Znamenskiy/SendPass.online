@php
    $app_name = config('app.name', 'Laravel');
    $app_url = url('/');
    $app_link = '<a href="'.$app_url.'">'.$app_name.'</a>';
@endphp

@extends('_layout')

@section('meta_title')
    {{$app_name.' - '.__('about.about_project')}}
@endsection


@section('before-container')

    <div class="container">
        <div class="row">
            <div class="col col-md-1"></div>
            <div class="col-12 col-md-10">

                <div class="card faq">
                    <h1 class="card-header h4">{{__('about.about_project')}}</h1>
                    <div class="card-body">

                        {{--            <h3 class="h5">Collapsible Group Item #1 <small class="text-muted">With faded secondary text</small></h3>--}}
                        {{--            <p>--}}
                        {{--                Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.--}}
                        {{--            </p>--}}


                        @for ($i = 1; $i < 9; $i++)

                            @break(!(app('translator')->has('faq.question'.$i)))

                            @if ($i > 1) <hr/> @endif

                            <h3 class="h5">@lang('faq.question'.$i, ['app_name' => $app_name, 'app_url' => $app_url, 'app_link' => $app_link])</h3>
                            <p>@lang('faq.answer'.$i, ['app_name' => $app_name, 'app_url' => $app_url, 'app_link' => $app_link])</p>
                        @endfor



                    </div>
                </div>

            </div>
            <div class="col col-md-1"></div>
        </div>
    </div>

@endsection

