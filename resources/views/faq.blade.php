@extends('_layout')

@section('meta_title')
    {{config('app.name', 'Laravel').'-'.__('t.frequently_asked_questions')}}
@endsection

@section('content')

    <div class="card">
        <h1 class="card-header h4">{{__('t.frequently_asked_questions')}}</h1>
        <div class="card-body">

            <h3 class="h5">Collapsible Group Item #1 <small class="text-muted">With faded secondary text</small></h3>
            <p>
                Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
            </p>


            @for ($i = 1; $i < 9; $i++)

                @break(!(app('translator')->has('faq.question'.$i)))

                <h3 class="h5">@lang('faq.question'.$i)</h3>
                <p>@lang('faq.answer'.$i)</p>
            @endfor



        </div>
    </div>


@endsection
