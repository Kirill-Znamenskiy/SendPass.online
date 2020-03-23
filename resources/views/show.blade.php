@php
    /** @var \App\Models\Secret|null $secret */
@endphp

@extends('_layout')

@section('content')

<div class="container">

    <div class="row">
        <div class="col col-sm-1 col-md-2"></div>
        <div class="col-12 col-sm-10 col-md-8">

            @isset($secret)

                <div class="card">
                    <div class="card-header py-2 px-3">
                        <button class="btn btn-link p-0 text-secondary" type="button" data-toggle="collapse" data-target="#ddd" aria-expanded="true" aria-controls="ddd">
                            Защищенные данные
                        </button>
                    </div>
                    <div class="card-body collapse show" id="ddd">

                        <div class="form-group">
                            @php $inm = 'sectext'; $ivl = data_get($secret,$inm); @endphp
                            <label for="{{$inm}}">@lang('validation.attributes.'.$inm)</label>
                            <textarea name="{{$inm}}" id="{{$inm}}" class="form-control" disabled="disabled" rows="7">{{ $ivl }}</textarea>
                        </div>

                        <div class="form-group mb-4">
                            @php $inm = 'secpass'; $ivl = data_get($secret,$inm); @endphp
                            <label for="{{$inm}}">@lang('validation.attributes.'.$inm)</label>
                            <div class="input-group">
                                <input type="password"
                                       name="{{$inm}}" id="{{$inm}}"
                                       value="{{$ivl}}"
                                       class="form-control"
                                       disabled="disabled"
                                />
                                <div class="input-group-append">
                                    <button id="shdpass" class="btn btn-outline-secondary px-1 py-1" type="button">@svg('eye-open')@svg('eye-slash','d-none')</button>
                                </div>
                            </div>
                        </div>

                        @unless ($secret->is_hide_show_count)

                            <div class="form-group row mx-0">
                                @php $inm = 'max_show_count'; $ivl = data_get($secret,$inm); @endphp
                                <div class="col-12 col-sm-auto col-form-label text-nowrap pl-0">@lang('validation.attributes.'.$inm)</div>
                                <div class="col-12 col-sm form-control">{{$maxShowCount2Label[$ivl]}}</div>
                            </div>

                            <div class="form-group row mx-0">
                                @php $inm = 'crr_show_count'; $ivl = $secret->crr_show_count; @endphp
                                <div class="col-12 col-sm-auto col-form-label text-nowrap pl-0">@lang('validation.attributes.'.$inm)</div>
                                <div class="col-12 col-sm form-control">{{$maxShowCount2Label[$ivl]}}</div>
                            </div>

                            <div class="form-group row mx-0">
                                @php $inm = 'rst_show_count'; $ivl = ($secret->max_show_count-$secret->crr_show_count); @endphp
                                <div class="col-12 col-sm-auto col-form-label text-nowrap pl-0">@lang('validation.attributes.'.$inm)</div>
                                <div class="col-12 col-sm form-control">@if (empty($ivl)) 0 (это последний раз!) @else {{$maxShowCount2Label[$ivl]}} @endif</div>
                            </div>

                        @endunless


                        @unless ($secret->is_hide_lifetime)

                            <div>Защищенные данные доступны до <span class="mmnt-date-time">{{$secret->expired_at->toIso8601String()}}</span></div>
                            <div>Истекает <span class="mmnt-to-date-time">{{$secret->expired_at->toIso8601String()}}</span></div>

                        @endunless

                    </div>
                </div>

            @else

                <form method="POST" action="{{ route('show',['secuuid' => $secuuid]) }}" autocomplete="off">
                    @csrf

                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <h5 class="alert-heading">Что-то пошло не так:</h5>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @break
                                @endforeach
                            </ul>
                        </div>
                    @else

{{--                        <p>Нажмите для доступа к защищенным данным:</p>--}}
                        <button class="btn btn-block btn-primary mt-3" type="submit">Нажмите сюда, чтобы посмотреть защищенные данные</button>
                        <div class="text-dark text-center mt-1 font-weight-bold">Будьте внимательны! Количество показов защищенных данных ограничено!</div>

                    @endif

                </form>

            @endisset

        </div>
        <div class="col col-sm-1 col-md-2"></div>
    </div>
</div>
@endsection
