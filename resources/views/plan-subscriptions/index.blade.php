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
                        Showing Subscriptons on: {{ $plan->name }} plan
                    </span>
                </a>
            </li>

            <li>
                <a href="#">
                    Total: {{$total}}
                </a>
            </li>

        </ul>
    </div>


     <user-invoices
        v-if="userInvoicesModal"
    ></user-invoices>


    <div class="uk-section">
        <div class="uk-container">

            <table class="uk-table uk-table-middle uk-table-striped uk-box-shadow-large">
                <thead>
                    <tr>
                        <th >user_id</th>
                        <th class="uk-text-center">stripe_id</th>
                        <th class="uk-text-center uk-table-shrink">quantity</th>
                        <th class="uk-text-center">Created On</th>
                        <th class="uk-text-center">Ends On</th>
                        <th class="uk-text-center">trial_ends_at</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($subscriptions as $sub)
                    <tr>
                        <td>
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
                            <p class="uk-text-success">
                                {{ $sub->quantity }}
                            </p>
                        </td>

                        <td class="uk-text-center">
                            <p class="uk-text-meta uk-text-small">
                                {{ $sub->created_at->format('m/d/Y') }}
                            </p>
                        </td>

                        <td class="uk-text-center">
                            <p class="uk-text-success">
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