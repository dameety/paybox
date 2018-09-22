    <div id="nav">
        <ul class="uk-subnav uk-subnav-divider uk-flex-center uk-margin-remove">

            <li class="{{ ActiveMenu::areActiveRoutes(['paybox.dashboard']) }}">
                <a class="header-text" href="{{ route('paybox.dashboard') }}">
                    Dashboard
                </a>
            </li>

            <li class="{{ ActiveMenu::areActiveRoutes(['plan.index',
                'plan.create', 'plan.store']) }}">
                <a class="header-text" href="{{ route('plan.index') }}">
                    Plans
                </a>
            </li>

            <li class="{{ ActiveMenu::areActiveRoutes(['subscriptions.index',
                'subscriptions.show', 'plan.subscriptions']) }}">
                <a class="header-text" href="{{ route('subscriptions.index') }}">
                    Subscriptions
                </a>
            </li>

            <li class="{{ ActiveMenu::areActiveRoutes(['errors.index']) }}">
                <a class="header-text" href="{{ route('errors.index') }}">
                    Errors
                </a>
            </li>

        </ul>
    </div>