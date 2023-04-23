@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header"><h3>{{ __('Crypto exchange') }} </h3></div>
                    <div class="card-body mx-auto">
                        <form action="{{ route('exchange.calculate') }}">
                            @method('GET')
                            @csrf
                            <div class="">
                                <label for="inp-pair">Crypto pair</label>
                                <select name="pair" id="inp-pair" class="form-control">
                                    <option value="">{{ __('- Select pair -') }}</option>
                                    @foreach($cryptoPairs as $pair)
                                        <option value="{{ $pair }}" @selected(old('pair') == $pair)>{{ $pair }}</option>
                                    @endforeach
                                </select>
                                @error('pair')
                                <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mt-2 ">
                                <label for="inp-action">Action</label>
                                <select name="action" id="inp-amount" class="form-control text-capitalize">
                                    <option value="">{{ __('- Select action -') }}</option>
                                    @foreach(config('crypto.exchangeActions') as $action)
                                        <option
                                            value="{{ $action }}" @selected(old('action') == $action)>{{ $action }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('action')
                            <p class="text-danger">{{ $message }}</p>
                            @enderror
                            <div class="mt-2 ">
                                <label for="inp-amount">Amount</label>
                                <input name="amount" id="inp-amount" class="form-control"
                                       value="{{ old('amount') ?? null }}">
                            </div>
                            @error('amount')
                            <p class="text-danger">{{ $message }}</p>
                            @enderror
                            <div class="mt-2 text-center">
                                <button class="btn btn-success" type="submit">Calculate</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
@endsection
