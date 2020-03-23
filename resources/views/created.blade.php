
@php
    /**
     * @var \App\Models\Secret|null $succCreatedSecret
     * @var string|null $succCreatedSecretLifetime
     */
@endphp


@extends('_layout')

@section('content')


    <div class="alert alert-success" role="alert">
        <h5 class="alert-heading">Ваши данные успешно сохранены и защищены!</h5>
        <p class="m-0">Теперь у вас есть <a target="_blank" href="{{$showSecretUrl}}">безопасная ссылка</a> для доступа к защищенным данным.<br/>Вы можете спокойно отправлять ее через мессенджеры и/или эл.почту.</p>
    </div>

    <div class="form-group mb-2">
        <div class="input-group">
            <input id="securl" type="text" value="{{ $showSecretUrl }}" class="form-control" readonly="readonly" autocomplete="off"/>
            <div class="input-group-append">
                <button id="execopy" class="btn btn-outline-success px-2 py-1" type="button" >@svg('copy')</button>
            </div>
        </div>
    </div>

    <div class="form-group">
        <button id="execopy" class="btn btn-block btn-success">@svg('copy') <span class="align-bottom">Скопировать ссылку для доступа к защищенным данным</span></button>
        <div id="succopied" class="alert alert-success p-2 text-center mt-1 d-none">@svg('bs/checkmark') <span class="align-bottom">Скопировано!</span></div>
    </div>

    <div class="form-group">
        <button id="share-sec-link" class="btn btn-block btn-light d-none"><span class="align-bottom">Поделиться безопасной ссылкой</span> @svg('bs/export')</button>
    </div>

{{--            <div class="form-group">--}}
{{--                <div>created_at: {{$secret->created_at->toIso8601String()}}</div>--}}
{{--                <div>updated_at: {{$secret->updated_at->toIso8601String()}}</div>--}}
{{--                <div>expired_at: {{$secret->expired_at->toIso8601String()}}</div>--}}
{{--            </div>--}}

{{--            <div class="form-group">--}}
{{--                <div>created_at: {{$secret->created_at->tz(1)->toIso8601String()}}</div>--}}
{{--                <div>updated_at: {{$secret->updated_at->tz(2)->toIso8601String()}}</div>--}}
{{--                <div>expired_at: {{$secret->expired_at->tz(3)->toIso8601String()}}</div>--}}
{{--            </div>--}}




    @if (isset($succCreatedSecret))
        <div class="card">
            <div class="card-header py-2 px-3">
                <button class="btn btn-link p-0 text-secondary text-decoration-underline" type="button" data-toggle="collapse" data-target="#ddd" aria-expanded="true" aria-controls="ddd">
                    Перепроверить защищенные данные (доступно только 1 раз)
                </button>
            </div>
            <ul class="list-group list-group-flush collapse" id="ddd">
                <li class="list-group-item">
                    <small>
                        Перепровить защищенные данные можно только один раз, сразу же после того как Вы их сохранили.
                        <br/>
                        Чтобы поделиться защищенными данными с кем-либо воспользуйтесь специальной безопасной ссылкой (см.выше).
                        <br/>
                        После перезагрузки этой страницы возможности перепровить защищенные данные у Вас больше не будет.
                    </small>
                </li>
            </ul>
            <div class="card-body collapse" id="ddd">

                <div class="form-group">
                    @php $inm = 'sectext'; $ivl = data_get($succCreatedSecret,$inm); @endphp
                    <label for="{{$inm}}">@lang('validation.attributes.'.$inm)</label>
                    <textarea name="{{$inm}}" id="{{$inm}}" class="form-control" disabled="disabled" rows="7">{{ $ivl }}</textarea>

                    @error($inm)
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group mb-4">
                    @php $inm = 'secpass'; $ivl = data_get($succCreatedSecret,$inm); @endphp
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

                <div class="form-group row mb-0 mx-0">
                    @php $inm = 'max_show_count'; $ivl = data_get($succCreatedSecret,$inm); @endphp
                    <div class="col-12 col-sm-auto col-form-label text-nowrap pl-0">@lang('validation.attributes.'.$inm)</div>
                    <div class="col-12 col-sm form-control">{{$maxShowCount2Label[$ivl]}}</div>
                </div>
                <div class="form-group form-check">
                    @php $inm = 'is_hide_show_count'; $ivl = data_get($succCreatedSecret,$inm); @endphp
                    <input type="checkbox"
                           name="{{$inm}}" id="{{$inm}}"
                           value="1" {{ ($ivl === true) ? 'checked' : '' }}
                           class="form-check-input"
                           disabled="disabled"
                    />
                    <label class="form-check-label text-body" for="{{$inm}}">
                        @lang('validation.attributes.'.$inm)
                    </label>
                </div>

                <div class="form-group row mb-0 mx-0">
                    @php $inm = 'lifetime'; $ivl = $succCreatedSecretLifetime; @endphp
                    <div class="col-12 col-sm-auto col-form-label text-nowrap pl-0">@lang('validation.attributes.'.$inm)</div>
                    <div class="col-12 col-sm form-control">{{$lifetimeValue2Label[$ivl]}}</div>
                </div>
                <div class="form-group form-check">
                    @php $inm = 'is_hide_lifetime'; $ivl = data_get($succCreatedSecret,$inm); @endphp
                    <input type="checkbox"
                           name="{{$inm}}" id="{{$inm}}"
                           value="1" {{ ($ivl === true) ? 'checked' : '' }}
                           class="form-check-input"
                           disabled="disabled"
                    />
                    <label class="form-check-label text-body" for="{{$inm}}">
                        @lang('validation.attributes.'.$inm)
                    </label>
                </div>

            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body">
                <p>Перепровить защищенные данные можно только один раз, сразу же после того как Вы их сохранили.</p>
                <p>Чтобы поделиться защищенными данными с кем-либо воспользуйтесь специальной защищенной ссылкой (см.выше).</p>
            </div>
        </div>
    @endif


@endsection
