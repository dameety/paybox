@extends('paybox::layouts.master')
@section('content')
    <div class="uk-background-muted uk-padding-small">
        <ul class="uk-subnav uk-subnav-divider
            uk-flex-center uk-margin-small">

            <li class="uk-active">
                <a>
                    <span class="icon is-small uk-text-primary">
                        <i class="fa fa-folder"></i>
                    </span>
                    <span class="uk-text-primary">
                        {{ $total }} Total Errors
                    </span>
                </a>
            </li>

        </ul>
    </div>

    <div class="uk-section">
        <div class="uk-container">

            <table class="uk-table uk-table-middle uk-table-striped uk-box-shadow-large">

                <thead>
                    <tr>
                        <th>Exception</th>
                        <th class="uk-text-center">Caused by</th>
                        <th class="uk-text-center">Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($errors as $error)
                    <tr>
                        <td>
                            <div>
                                Type: {{$error->name}}
                                <p class="uk-text-meta uk-text-small uk-margin-remove">
                                    Message: ${{$error->message}}
                                </p>
                            </div>
                        </td>

                        <td class="uk-text-center">
                            <p class="uk-text-meta uk-text-small">
                                {{$error->user}}
                            </p>
                        </td>

                        <td class="uk-text-center">
                            <p class="uk-text-meta uk-text-small">
                                {{$error->created_at}}
                            </p>
                        </td>

                        <td>
                            <div class="uk-text-center">
                                <button class="uk-button uk-button-default
                                    uk-button-small uk-text-danger border-danger"
                                    @click.prevent="deleteError('{{ $error->slug }}')">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
@endsection