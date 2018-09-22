@extends('paybox::layouts.master')
@section('content')
    <div class="uk-background-muted uk-padding-small">
        <ul class="uk-subnav uk-subnav-divider uk-flex-center uk-margin-small">

            <li class="uk-active">
                <a href="{{ route('paybox.dashboard') }}">
                    <span class="icon is-small uk-text-primary">
                        <i class="fa fa-dashboard"></i>
                    </span>
                    <span class="uk-text-primary">
                        Stats
                    </span>
                </a>
            </li>

        </ul>
    </div>


    <div class="uk-section">
        <div class="uk-container">

            <div class="uk-child-width-1-3@s uk-grid-match" uk-grid v-cloak>
                <div>
                    <div class="uk-card uk-card-default uk-card-body">
                        <h3 class="uk-card-title"> {{ $totalPlans }}</h3>
                        <p>Total Plans</p>
                    </div>
                </div>
                <div>
                    <div class="uk-card uk-card-default uk-card-body">
                        <h3 class="uk-card-title">
                            {{ $totalSubscriptions }}
                        </h3>
                        <p>Total Subscriptons</p>
                    </div>
                </div>
                <div>
                    <div class="uk-card uk-card-default uk-card-body">
                        <h3 class="uk-card-title">
                            {{ $totalErrors }}
                        </h3>
                        <p>Total Errors</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection