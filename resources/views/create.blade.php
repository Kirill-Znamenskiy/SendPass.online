@php
    $app_name = config('app.name', 'Laravel');
    $app_url = url('/');
    $app_link = '<a href="'.$app_url.'">'.$app_name.'</a>';
@endphp

@extends('_layout')

@section('before-container')

    <div class="container">
        <div class="row">
            <div class="col col-lg-1"></div>
            <div class="col-12 col-lg-10">
                <h4 class="text-center text-darkblue">{{__('t.welcome_title')}}</h4>
            </div>
            <div class="col col-lg-1"></div>
        </div>
    </div>

@endsection

@section('content')

    <p class="text-secondary text-justify">@lang('t.welcome_message', ['app_name' => $app_name, 'app_url' => $app_url, 'app_link' => $app_link])</p>

    <form method="POST" action="{{ route('create') }}" autocomplete="off">
        @csrf

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <h5 class="alert-heading">@lang('t.fix_errors_and_try_again')</h5>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-group">
            @php $inm = 'sectext'; $ivl = old($inm); @endphp
            <textarea
                name="{{$inm}}" id="{{$inm}}"
                class="form-control @error($inm) is-invalid @enderror"
                autocomplete="nope"
                rows="7"
                placeholder="{{__('t.input_here_any_text')}}"
            >{{ $ivl }}</textarea>

            @error($inm)<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
        </div>

        <div class="form-group">
            @php $inm = 'secpass'; $ivl = old($inm); @endphp
{{--                    <label for="{{$inm}}" class="mb-0">Для защиты пароля можете воспользоваться специальным полем:</label>--}}
            <div class="input-group @error($inm) is-invalid @enderror">
                <input type="password"
                       name="{{$inm}}" id="{{$inm}}"
                       value="{{$ivl}}"
                       class="form-control @error($inm) is-invalid @enderror"
                       autocomplete="new-password"
                       data-lpignore="true"
                       placeholder="{{__('t.or_password_here')}}"
                />
                <div class="input-group-append">
                    <button id="shdpass" class="btn @if ($errors->has($inm)) btn-outline-danger @else btn-outline-secondary @endif px-1 py-1" type="button">@svg('eye-open')@svg('eye-slash','d-none')</button>
                </div>
            </div>

            @error($inm)<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
        </div>

{{--                <p class="text-justify">Можно защитить только текст или только пароль, а можно и то и другое.</p>--}}

        <div class="card">
            <div class="card-header py-2 px-3">
                <button class="btn btn-link p-0 text-secondary" type="button" data-toggle="collapse" data-target="#opts" aria-expanded="true" aria-controls="opts">
                    {{__('t.access_options_to_protected_data')}}
                </button>
            </div>
            <div class="card-body collapse show" id="opts">
                <div class="form-row">
                    @php $inm = 'max_show_count'; $ivl = (int)old($inm,1); @endphp
                    <label for="{{$inm}}" class="col-12 col-sm-auto col-form-label text-nowrap">@lang('validation.attributes.'.$inm)</label>
                    <div class="col-12 col-sm">
                        <select name="{{$inm}}" id="{{$inm}}" class="form-control @error($inm) is-invalid @enderror">
{{--                                    <option value="" {{ ($ivl === null) ? 'selected' : '' }}>---</option>--}}
                            @foreach($max_show_count2label AS $value => $label)
                                <option value="{{$value}}" {{ ($ivl === $value) ? 'selected' : '' }}>{{$label}}</option>
                            @endforeach
                        </select>

                        @error($inm)<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                    </div>
                </div>
                <div class="form-group form-check">
                    @php $inm = 'is_hide_show_count'; $ivl = old($inm); @endphp
                    <input type="checkbox"
                           name="{{$inm}}" id="{{$inm}}"
                           value="1" {{ ($ivl === '1') ? 'checked' : '' }}
                           class="form-check-input @error($inm) is-invalid @enderror"
                    />
                    <label class="form-check-label" for="{{$inm}}">
                        @lang('validation.attributes.'.$inm)
                    </label>
                    @error($inm)<div class="invalid-feedback" role="alert">{{ $message }}</div>@enderror
                </div>

                <div class="form-row">
                    @php $inm = 'lifetime'; $ivl = old($inm,'01h'); @endphp
                    <label for="{{$inm}}" class="col-12 col-sm-auto col-form-label text-nowrap">@lang('validation.attributes.'.$inm)</label>
                    <div class="col-12 col-sm">
                        <select name="{{$inm}}" id="{{$inm}}" class="form-control @error($inm) is-invalid @enderror">
{{--                                    <option value="" {{ ($ivl === null) ? 'selected' : '' }}>---</option>--}}
                            @foreach($lifetime_value2label AS $value => $label)
                                <option value="{{$value}}" {{ ($ivl === $value) ? 'selected' : '' }}>{{$label}}</option>
                            @endforeach
                        </select>

                        @error($inm)<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                    </div>
                </div>
                <div class="form-group form-check mb-0">
                    @php $inm = 'is_hide_lifetime'; $ivl = old($inm); @endphp
                    <input type="checkbox"
                           name="{{$inm}}" id="{{$inm}}"
                           value="1" {{ ($ivl === '1') ? 'checked' : '' }}
                           class="form-check-input @error($inm) is-invalid @enderror"
                    />
                    <label class="form-check-label" for="{{$inm}}">
                        @lang('validation.attributes.'.$inm)
                    </label>
                    @error($inm)<div class="invalid-feedback" role="alert">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <div class="form-group mt-3">
            <button class="btn btn-block btn-primary" type="submit">@lang('t.create_secret_link')</button>
        </div>
    </form>

    {{--    <p>Обратите внимание! При передаче пароля через <a href="{{url('/')}}">{{ config('app.name', 'Laravel') }}</a> пожалуйста пересылайте <b><u>только!</u></b> пароль, а прочие данные необходимые для доступа (адрес сервиса и логин) передавайте пожалуйста через другой канал связи (например непосредственно напрямую в чате)</p>--}}

    <p>@lang('t.if_this_is_your_first_visit')</p>

@endsection
