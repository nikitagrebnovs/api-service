@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header"><h3>{{ __('Crypto exchange') }} </h3></div>
                    <div class="card-body row">
                        <form class="col-12" id="exchange-form" action="{{ route('exchange.calculate') }}" method="get">
                            <div class="">
                                <label for="inp-pair">{{ __('Crypto pair')}}</label>
                                <select name="pair" id="inp-pair" class="form-control">
                                    <option value="">{{ __('- Select pair -') }}</option>
                                    @foreach($cryptoPairs as $pair)
                                        <option value="{{ $pair }}" @selected(old('pair') == $pair)>{{ $pair }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger error-message" id="error-pair"></span>
                            </div>
                            <div class="mt-2">
                                <label for="inp-action">{{ __('Action')}}</label>
                                <select name="action" id="inp-amount" class="form-control text-capitalize">
                                    <option value="">{{ __('- Select action -') }}</option>
                                    @foreach(config('crypto.exchangeActions') as $action)
                                        <option
                                            value="{{ $action }}" @selected(old('action') == $action)>{{ $action }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <span class="text-danger error-message" id="error-action"></span>
                            <div class="mt-2 ">
                                <label for="inp-amount">{{ __('Amount')}}</label>
                                <input name="amount" id="inp-amount" class="form-control"
                                       value="{{ old('amount') ?? null }}">
                            </div>
                            <span class="text-danger error-message" id="error-amount"></span>
                            <div class="mt-4 text-center">
                                <button class="btn btn-success" type="submit"
                                        id="btn-submit">{{ __('Calculate')}}</button>
                            </div>
                        </form>
                        <div class="col-12">
                            <div class="alert alert-success d-none mt-4" id="order-book-price-alert">
                                <span id="order-book-price"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-5">
                    <div class="card-header"><h3>{{ __('Crypto rates to EUR') }} </h3></div>
                    <div class="card-body row">
                        <form action="{{ route('exchange.rates') }}" method="get" id="exchange-rates">
                            <div class="mt-4 alert alert-secondary" id="crypto-rates"></div>
                            <div class="mt-4 text-center">
                                <button class="btn btn-success" type="submit"
                                        id="btn-exchange-rates-submit">{{ __('Refresh') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card mt-5">
                    <div class="card-header"><h3>{{ __('Register') }} </h3></div>
                    <div class="card-body row">
                        <form action="{{ url('/api/register') }}" method="post" id="register-form">
                            <div class="">
                                <label for="inp-email">{{ __('Email') }}</label>
                                <input type="text" name="email" id="inp-email" class="form-control">
                            </div>
                            <span class="text-danger error" id="error-email"></span>
                            <div class="">
                                <label for="inp-password">{{ __('Password') }}</label>
                                <input type="password" name="password" id="inp-password" class="form-control">
                            </div>
                            <span class="text-danger error" id="error-password"></span>
                            <div class="mt-4 text-center">
                                <button class="btn btn-success" type="submit"
                                        id="btn-exchange-rates-submit">{{ __('Send') }}
                                </button>
                            </div>
                            <div class="alert alert-success mt-3 d-none" id="registration-request-message">
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card mt-5">
                    <div class="card-header"><h3>{{ __('Login') }} </h3></div>
                    <div class="card-body row">
                        <form action="{{ url('/api/login') }}" method="post" id="login-form">
                            <div class="">
                                <label for="inp-email">{{ __('Email') }}</label>
                                <input type="text" name="email" id="inp-email" class="form-control">
                            </div>
                            <span class="text-danger error" id="error-login-email"></span>
                            <div class="">
                                <label for="inp-password">{{ __('Password') }}</label>
                                <input type="password" name="password" id="inp-password" class="form-control">
                            </div>
                            <span class="text-danger error" id="error-login-password"></span>
                            <div class="mt-4 text-center">
                                <button class="btn btn-success" type="submit"
                                        id="btn-exchange-rates-submit">{{ __('Send') }}
                                </button>
                            </div>
                            <div class="alert alert-danger mt-3 d-none" id="login-request-message">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endsection

        @section('scripts')
            <script type="text/javascript">
                $('#exchange-form').on('submit', function (e) {
                    e.preventDefault();
                    let self = $(this);
                    let priceShow = $('#order-book-price');
                    let priceAlertBar = $('#order-book-price-alert');

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: self.attr('method'),
                        data: self.serialize(),
                        url: self.attr('action'),
                        beforeSend: function () {
                            $('input, select').removeClass('is-invalid');
                            $('.error-message').html('');
                            priceShow.html('');
                            priceAlertBar.removeAttr('class');
                        },
                        success: function (data) {
                            priceShow.html(data['message']);
                            priceAlertBar.addClass('d-show alert alert-' + data['css-status']);
                        },
                        error: function (data) {
                            $.each(data['responseJSON']['errors'], function (key, message) {
                                let splitKey = key.split('.');

                                let inputName = key.split('.').length == 1 ? key : (splitKey[0] + '[' + splitKey.splice(1).join('][') + ']');

                                $('[name="' + inputName + '"]').addClass('is-invalid');

                                $('#error-' + inputName).html(message).show();
                            })
                        }
                    });
                });

                $('#exchange-rates').on('submit', function (e) {
                    e.preventDefault();
                    let self = $(this);
                    let priceShow = $('#crypto-rates');

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: self.attr('method'),
                        data: self.serialize(),
                        url: self.attr('action'),
                        beforeSend: function () {
                            priceShow.html('');
                        },
                        success: function (data) {
                            let prices = '';
                            $.each(data['rates'], function (key, value) {
                                prices += ("<p>" + key + ": " + value + "</p>");
                            })

                            priceShow.prepend(prices);
                        },
                        error: function (data) {
                        }
                    });
                });

                $('#register-form').on('submit', function (e) {
                    e.preventDefault();
                    let self = $(this);
                    let message = $('#registration-request-message');

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'Accept': 'application/json'
                        },
                        type: self.attr('method'),
                        data: self.serialize(),
                        url: self.attr('action'),
                        beforeSend: function () {
                            $('.error').html('');
                            $('input, select').removeClass('is-invalid');
                            message.addClass('d-none');
                        },
                        success: function (data) {
                            if (data['success'] === false) {
                                $.each(data['data'], function (key, message) {
                                    let splitKey = key.split('.');

                                    let inputName = key.split('.').length == 1 ? key : (splitKey[0] + '[' + splitKey.splice(1).join('][') + ']');

                                    self.find('[name="' + inputName + '"]').addClass('is-invalid');

                                    $('#error-' + inputName).html(message).show();
                                })
                            } else {
                                message.removeClass('d-none');
                                message.html(data['message']);
                            }
                        },
                    });
                });

                $('#login-form').on('submit', function (e) {
                    e.preventDefault();
                    let self = $(this);
                    let message = $('#login-request-message');

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'Accept': 'application/json'
                        },
                        type: self.attr('method'),
                        data: self.serialize(),
                        url: self.attr('action'),
                        beforeSend: function () {
                            $('.error').html('');
                            $('input, select').removeClass('is-invalid');
                            message.addClass('d-none');
                        },
                        success: function (data) {
                            if (data['success'] === false) {
                                $.each(data['data'], function (key, message) {
                                    let splitKey = key.split('.');
                                    let inputName = key.split('.').length == 1 ? key : (splitKey[0] + '[' + splitKey.splice(1).join('][') + ']');

                                    self.find('[name="' + inputName + '"]').addClass('is-invalid');

                                    $('#error-login-' + inputName).html(message).show();
                                })
                            } else {
                                message.removeClass('d-none');
                                message.html(data['message']);
                            }
                        },
                        error: function (data) {
                            self.find('[name="email"]').addClass('is-invalid');
                            $('#error-login-email').html(data['responseJSON']['message']).show();
                        }
                    });
                });
            </script>
@endsection
