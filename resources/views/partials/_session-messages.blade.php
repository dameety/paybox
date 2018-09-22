@if (session('cardError'))
     <p class="uk-text-center text-smaller uk-text-danger uk-margin-remove">
        {{ session('cardError') }}
    </p>
@endif

@if (session('invalidRequest'))
    <p class="uk-text-center text-smaller uk-text-danger uk-margin-remove">
        {{ session('invalidRequest') }}
    </p>
@endif

@if (session('paymentError'))
    <p class="uk-text-center text-smaller uk-text-danger uk-margin-remove">
        {{ session('paymentError') }}
    </p>
@endif

@if (session('apiConnectionError'))
    <p class="uk-text-center text-smaller uk-text-danger uk-margin-remove">
        {{ session('apiConnectionError') }}
    </p>
@endif

@if (session('generalError'))
    <p class="uk-text-center text-smaller uk-text-danger uk-margin-remove">
        {{ session('generalError') }}
    </p>
@endif

@if (session('stripeError'))
    <div class="uk-width-xlarge uk-align-center uk-text-center uk-padding uk-padding-remove-top">
        <p class="uk-text-danger text-smaller uk-margin-remove">
            {{ session('stripeError') }}
        </p>
    </div>
@endif

@if (session('plan-created'))
    <div class="uk-width-xlarge uk-align-center uk-text-center bg-success uk-padding">
        <p class="uk-margin-remove">
            {{ session('plan-created') }}
        </p>
    </div>
@endif