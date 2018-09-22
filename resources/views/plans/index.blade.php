@extends('paybox::layouts.master')
@section('content')
    <div class="uk-background-muted uk-padding-small">
        <ul class="uk-subnav uk-subnav-divider uk-flex-center uk-margin-small">

            <li class="uk-active">
                <a href="{{ route('plan.index') }}">
                    <span class="icon is-small uk-text-primary">
                        <i class="fa fa-folder"></i>
                    </span>
                    <span class="uk-text-primary">
                        All
                    </span>
                </a>
            </li>

            <li>
                <a href="{{ route('plan.create') }}">
                    <span class="icon is-small">
                        <i class="fa fa-plus"></i>
                    </span>
                    New
                </a>
            </li>

        </ul>
    </div>

    <plan-features v-if="showPlanFeaturesModal"></plan-features>

    <div class="uk-section">
        <div class="uk-container">

            <table class="uk-table uk-table-middle uk-table-striped uk-box-shadow-large">
                <caption class="uk-clearfix uk-margin uk-background-muted">
                    <div class="uk-float-left uk-margin-top">
                        <ul class="uk-list uk-list-bullet">
                            <li class="uk-text-meta">
                                Click a plan name to list all it's subscriptions.
                            </li>
                        </ul>
                    </div>
                </caption>

                <thead>
                    <tr>
                        <th></th>
                        <th class="uk-text-center">Id</th>
                        <th class="uk-text-center">Interval</th>
                        <th class="uk-text-center">Feature</th>
                        <th class="uk-text-center">Created On</th>
                        <th class="uk-text-center"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($plans as $plan)
                    <tr class="tbody-tr">
                        <td class="uk-table-link">
                            <a class="uk-link-reset" href="{{ route('plan.subscriptions', ['slug' => $plan->slug]) }}">
                                <div>
                                    Name: {{$plan->name}}
                                    <p class="uk-text-meta uk-text-small uk-margin-remove">
                                        Amount: ${{$plan->amount}}
                                    </p>
                                </div>
                            </a>
                        </td>

                        <td class="uk-text-center">
                            <p class="uk-text-meta uk-text-small">
                                {{$plan->identifier}}
                            </p>
                        </td>

                        <td class="uk-text-center">
                            <p class="uk-text-meta uk-text-small">
                                {{$plan->interval}}
                            </p>
                        </td>

                        <td>
                            <div class="uk-text-center">
                                <a class="uk-button uk-button-default
                                    uk-button-small"
                                    @click.prevent="viewPlanFeatures(
                                        {{$plan}} )">
                                    Features
                                </a>
                            </div>
                        </td>

                        <td class="uk-text-center">
                            <p class="uk-text-meta uk-text-small">
                                {{$plan->created_at}}
                            </p>
                        </td>

                        <td>
                            <div class="uk-text-center">
                                <a class="uk-button uk-button-default
                                    uk-button-small uk-text-danger border-danger"
                                    @click.prevent="deletePlan('{{ $plan->slug }}')">
                                    Delete
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
@endsection