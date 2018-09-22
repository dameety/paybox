@extends('paybox::layouts.master')
@section('content')
    <div class="uk-background-muted uk-padding-small">
        <ul class="uk-subnav uk-subnav-divider
            uk-flex-center uk-margin-small">
            <li class="uk-active">
                <a>
                    <span class="uk-text-primary">
                        {{ $total }} Total Subscriptons
                    </span>
                </a>
            </li>
        </ul>
    </div>


    <user-invoices v-if="userInvoicesModal"></user-invoices>


    <div class="uk-section">
        <div class="uk-container">

            <table class="uk-table uk-table-middle uk-table-striped uk-box-shadow-large">
                <caption class="uk-clearfix uk-margin uk-background-muted">
                    <div class="uk-float-left uk-margin-top">
                        <ul class="uk-list uk-list-bullet">
                            <li class="uk-text-meta">
                                Click a plan name to list all it's subscriptions.
                            </li>
                            <li class="uk-text-meta">
                                Click a user_id to see all payment history.
                            </li>
                        </ul>
                    </div>
                    <div class="uk-float-right uk-margin-top">
                        {{ $subscriptions->links('paybox::partials._pagination') }}
                    </div>
                </caption>

                <thead>
                    <tr>
                        <th>Plan</th>
                        <th class="uk-text-center uk-table-shrink">user_id</th>
                        <th class="uk-text-center">stripe_id</th>
                        <th class="uk-text-center uk-table-shrink">quantity</th>
                        <th class="uk-text-center">Created On</th>
                        <th class="uk-text-center">Ends At</th>
                        <th class="uk-text-center">trial_ends_at</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($subscriptions as $sub)
                    <tr>
                        <td>
                            <a class="uk-text-small uk-link-primary uk-text-capitalize"
                                href="{{ route('subscriptions.show', ['id' => $sub->id]) }}">
                                {{ $sub->stripe_plan }}
                            </a>
                        </td>

                        <td class="uk-text-center">
                            <button class="uk-button uk-button-small uk-button-primary"
                            @click.prevent="viewUserInvoices('{{ $sub->user_id }}')">
                                {{ $sub->user_id }}
                            </button>
                        </td>

                        <td class="uk-text-center">
                            <p class="uk-text-meta uk-text-small">
                                {{ $sub->stripe_id }}
                            </p>
                        </td>

                        <td class="uk-text-center">
                            <p class="uk-text-success uk-text-small">
                                {{ $sub->quantity }}
                            </p>
                        </td>

                        <td class="uk-text-center">
                            <p class="uk-text-meta uk-text-small">
                                {{ $sub->created_at->format('m/d/Y') }}
                            </p>
                        </td>

                        <td class="uk-text-center">
                            <p class="uk-text-success uk-text-small">
                                {{ $sub->ends_at }}
                            </p>
                        </td>

                        <td class="uk-text-center">
                            <p class="uk-text-success">
                                {{ $sub->trial_ends_at }}
                            </p>
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
@endsection