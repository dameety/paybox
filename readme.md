## About 

Paybox is a package developed to help you jumpstart subscription billing in your laravel application. This package comes with an admin panel where you can manage all your subscription operations. The paybox package is powered on the frontend using the uikit css framework and vuejs javascript framework, laravel cashier on the backend and Stripe as the payment processor.

## Introduction
After going through the installation steps, login to your application and visit <span class="uk-text-bold">www.your-application.com/paybox</span> to get to the admin panel. Navigate to the plans page and create some plans. Then, take a look at the paybox.php config file to set your stripe public key. View the paybox frontend by pasting the snippet below on the page where your application's users manage subscription.
```
<pb-interface></pb-interface>
```

Remember to include the stripe v3 js script on your page
```
<script src="https://js.stripe.com/v3/"></script>

```

Also you should have installed the npm dependencies by running <span class="uk-text-bold">npm install</span> and <span class="uk-text-bold">npm run dev or production</span> to compile the assets. View the page in the browser, you should see the pb-interface component loaded on the page.


## Frontend Components
All the components mentioned here can be found in your resources/assets/vendor/paybox folder.


**Paybox interface component**

On your app billing page or any page you want your users to manage their subscription, just place the snippet below and it will render a simple ui where your application's users can manage subscriptions.

```
<pb-interface></pb-interface>
```


This component is designed to provide a full subscription experience (create subscription, swap subscription, resume subscription, cancel subscriptioin, update payment information, and also view invoices) It is a kind of "mother" component that wraps up other components in the list below:
- pb-subscription
- pb-cancel-subscription
- pb-payment-information
- pb-invoices

You can break up the pb-interface component and use all the components seperately. All you need is a little creativity. Find explanation about these components below.

**Paybox subscription component**

It can be used by placing the snippet below on a page, you are required to pass in a prop that is the name of a plan. it will render a button on the page. 
                     
```
<pb-subscription
    plan="name of plan"
></pb-subscription>
```
 On page load or on getting an event, this component fires an ajax request to get the subscriiption status of the currently logged in user this is used to determine the kind of action the user can perform.
 
  Using this component
- an unsubscribed user can subscribe to a plan
- a subscribed user can cancel the subscription, swap subscription and update payment information.
- a user with subscripiton cancelled can resume the subscirptoin if the on grace period is still on.


**Paybox cancel subscription component**

It can be used by placing the snippet below on a page, it will render a simple button on the page.                      
```
<pb-cancel-subscription></pb-cancel-subscription>
```
On page load this component fires and ajax request to to get the current authenticated user's subscription status. If the user is not subscribed to any plan or he has cancelled his subscription, this component will not show on the page, it will only show if the user has a subscription. When the user cancels the current subscription he will be  placed on grace period till the current subscription date ends.


**Paybox payment information component**

It can be used by placing the snippet below on a page. This component will display the users card brand and last four along with a button that displays a checkout form.
```
<pb-payment-information></pb-payment-information>
```
This component will only show if the user has a subscription, the subscribed user will be albe to update the payment information using this component.


**Paybox invoices component**

It can be used by placing the snippet below on a page, it will render a simple table that shows all invoices of the current authenticated user.

```
<pb-invoices></pb-invoices>
```


## Customizing
All the blade views and vue components used in paybox can be customized to fit your need.           To customize the blade view files, publish it using the command below in the terminal
           
           
```
php artisan vendor:publish --provider="Dameety\Paybox\PayboxServiceProvider" --tag="views"
```
           
To customize any of the vue components, you already published it during installation so just go into your <span class="uk-text-bold">resources/assets/vendor/paybox</span> folder and you can find all the vue components in there. Customize as much as you want.



## Events
Emitting and listening to events is the way the vue components in paybox communicate with each other. So if created other vue components you can listen to any of these events too if you need to or just remove it if you dont need to. The list below shows all the events and the actions that cause them:

- paybox-subscription-created -- when a user subscribes to a plan
-  paybox-subscription-swapped -- when a subscribed user changes the plan 
- paybox-subscription-cancelled -- when a subscribed user cancels their subscription
- paybox-card-information-updated -- when a subscribed user updates their payment information
- paybox-subscription-resumed-and-swapped -- when a user resumes and swaps their subscription.

The following events will be fired when the user of your application performs certain actions. You can listen to this events so you can perform actions you need. Maybe send a mail or add a privilege to the user or notify a user when his subscription is deleted when attempts to renew the subscription fails.

- Dameety\Paybox\Events\SubscriptionCreated -- when a user subscribes to a plan, it passes along the user and the planId of the plan subscribed to.
- Dameety\Paybox\Events\SubscriptionChanged -- when a user swaps his/her subscription. it passes along the $user and the $plan swapped to.
- Dameety\Paybox\Events\SubscriptionDeleted -- when a user's subscription is deleted, this happens when the stripe's attempt to renew the users subscription fails. It passes along the customer data from stripe.
- Dameety\Paybox\Events\SubscriptionResumed -- when a user resumes his/her subscription, it passes along the $user.
- Dameety\Paybox\Events\SubscriptionCardUpdated -- when a user updates his/her payment information, it passes along the user.
- Dameety\Paybox\Events\SubscriptionCancelled -- when a user cancels his/her subscription, it passes along the user
- Dameety\Paybox\Events\InvoicePaymentSucceeded -- this event is fired when the subscription attempt is renewed succesfully, it passes along the user

## Config
This is the default content of the paybox.php config file:
```
/**
* Base route to access the paybox admin dashboard
*/
'uri' => 'paybox',

/**
* Stripe public key
*/
'stripePublicKey' => env('STRIPE_PUBLIC'),

/**
* currency code and symbol
*/
'currency' => [
    'code' => 'usd',
    'symbol' => '$'
],

/**
* Number of days to offer trial with card upfront
* It should be a number greater than 0 if you offer trial
* Leave it at 0 if you offer no trial period
*/
'offerTrial' => 0,

/**
* Namespace to your user model
*/
'userModel' => App\User::class

```

## Webhooks
Paybox comes with two webhooks registered that listen to events on your stripe account. When your application gets any of these events from stripe an event is also fired in your application to notify you. More details below: The stripe webhook in paybox listen to two events from stripe namely, CustomerSubscriptionDeleted and InvoicePaymentSucceeded.

- CustomerSubscriptionDeleted: When paybox gets this event from stripe, it in turns fires an event to notify you. Your application received this event from stripe when attempts to renew the subscription of a user fails.

**Dameety\Paybox\Events\SubscriptionDeleted** is fired and it passes along the customer data from stripe.

- InvoicePaymentSucceeded: When paybox gets this event from stripe, it in turns fires an event to notify you. Your application received this event from stripe when a user's subscription is renewed successfully.

**Dameety\Paybox\Events\InvoicePaymentSucceeded** is fired and it passes along the customer data from stripe.


By the way, you need to add the line below to your routes file for webhooks to work. It's already part of the installation process so this is just a reminder if you skipped it.

```
Route::post('stripe/webhook', '\Dameety\Paybox\Http\Controllers\StripeWebhookController@handleWebhook');
```