@extends('paybox::layouts.master')
@section('content')
    <div class="uk-background-muted uk-padding-small">
        <ul class="uk-subnav uk-subnav-divider uk-flex-center uk-margin-small">

            <li class="uk-active">
                <a href="{{ route('plan.index') }}">
                    <span class="icon is-small">
                        <i class="fa fa-folder"></i>
                    </span>
                    All
                </a>
            </li>

            <li>
                <a href="{{ route('plan.create') }}">
                    <span class="icon is-small uk-text-primary">
                        <i class="fa fa-plus"></i>
                    </span>
                    <span class="uk-text-primary">
                        New Plan
                    </span>
                </a>
            </li>

        </ul>
    </div>

    <div class="uk-section">
        <div class="uk-container">

            @include('paybox::partials._session-messages')

            <div class="uk-width-xlarge uk-background-muted
                uk-margin uk-align-center">

                <form class="uk-padding" method="POST"
                    action="{{ route('plan.store') }}">
                    {{ csrf_field() }}

                    <div class="uk-margin">
                        <label class="uk-form-label" for="Name">
                            Name
                        </label>
                        <div class="uk-form-controls">
                            <input class="uk-input {{ $errors->has('name') ? ' uk-form-danger' : '' }}"
                                value="{{ old('name') }}" type="text"
                                name="name" required>
                            @if ($errors->has('name'))
                                <p class="text-smaller uk-text-danger
                                    uk-margin-remove">
                                    <strong>
                                        {{ $errors->first('name') }}
                                    </strong>
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="uk-margin">
                        <label class="uk-form-label" for="Identifier">
                            Identifier
                        </label>
                        <div class="uk-form-controls">
                            <input class="uk-input {{ $errors->has('identifier') ? ' uk-form-danger' : '' }}"
                                value="{{ old('identifier') }}" type="text"
                                name="identifier" required>
                            @if ($errors->has('identifier'))
                                <p class="text-smaller uk-text-danger
                                    uk-margin-remove">
                                    <strong>
                                        {{ $errors->first('identifier') }}
                                    </strong>
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="uk-margin">
                        <label class="uk-form-label" for="Amount">
                            Amount
                        </label>
                        <p class="text-smaller uk-inline
                            uk-margin-remove">
                            A positive integer, must be in cents, use 0 for a free charge
                        </p>
                        <div class="uk-form-controls">
                            <input class="uk-input {{ $errors->has('amount') ? ' uk-form-danger' : '' }}"
                                value="{{ old('amount') }}" type="text"
                                name="amount" required>
                            @if ($errors->has('amount'))
                                <p class="text-smaller uk-text-danger
                                    uk-margin-remove">
                                    {{ $errors->first('amount') }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="uk-margin">
                        <label class="uk-form-label" for="Interval">
                            Interval
                        </label>
                        <div class="uk-form-controls">
                            <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
                                <label><input class="uk-radio" type="radio" name="interval" value="day"> Day </label>
                                <label><input class="uk-radio" type="radio" name="interval" value="week"> Week </label>
                                <label><input class="uk-radio" type="radio" name="interval" value="month"> Month </label>
                                <label><input class="uk-radio" type="radio" name="interval" value="year"> Year </label>
                            </div>
                        </div>
                    </div>

                    <div class="uk-margin">
                        <label class="uk-form-label" for="Features">
                            Features
                        </label>
                        <div class="uk-form-controls">
                            <textarea class="uk-textarea {{ $errors->has('features') ? ' uk-form-danger' : '' }}" rows="3" name="features"></textarea>

                            @if ($errors->has('features'))
                                <p class="text-smaller uk-text-danger
                                    uk-margin-remove">
                                    <strong>
                                        {{ $errors->first('features') }}
                                    </strong>
                                </p>
                            @endif
                        </div>
                    </div>

                    <button type="submit" class="uk-button uk-button-primary
                        uk-width-1-1 uk-margin-small-bottom">
                        Create This Plan
                    </button>
                </form>

            </div>

        </div>
    </div>
@endsection