# ZRU PHP


# Overview

ZRU PHP provides integration access to the ZRU API.

# Installation

You can install using either

[composer](https://getcomposer.org):
```bash
    composer require zru/zru-php
```
or source code:
```bash
    git clone https://github.com/zrupay/zru-php zru
```
# Quick Start Example

Set this line as the first on your file:

If using [composer](https://getcomposer.org):
```php
    require_once __DIR__ . '/vendor/autoload.php';
```
Compiling from source code:
```php
    require_once('zru/src/ZRU/ZRUClient.php');


    use ZRU\ZRUClient;

    $zru = new ZRUClient('KEY', 'SECRET_KEY');

    # Create transaction
    $transaction = $zru->Transaction(
        array(
            "currency" => "EUR",
            "products" => array(
                array(
                    "amount" => 1,
                    "product_id" => "PRODUCT-ID"
                )
            )
        )
    );
    # or with product details
    $transaction = $zru->Transaction(
        array(
            "currency" => "EUR",
            "products" => array(
                array(
                    "amount" => 1,
                    "product" => array(
                        "name" => "Product",
                        "price" => 5
                    )
                )
            )
        )
    );
    $transaction->save();
    $transaction->getPayUrl() # Send user to this url to pay
    $transaction->getIframeUrl() # Use this url to show an iframe in your site

    # Get plans
    $plansPaginator = $zru->plan->itemList(null);
    $count = $plansPaginator->count;
    $results = $plansPaginator->results; # Application's plans
    $nextList = $plansPaginator->getNextList();

    # Get product, change and save
    $product = $zru->Product(
        array(
            "id" => "PRODUCT-ID"
        )
    );
    $product->retrieve();
    $product->price = 10;
    $product->save();

    # Create and delete tax
    $tax = $zru->Tax(
        array(
            "name" => "Tax",
            "percent" => 5
        )
    );
    $tax->save();
    $tax->delete();

    # Check if transaction was paid
    $transaction = $zru->Transaction(
        array(
            "id" => "c8325bb3-c24e-4c0c-b0ff-14fe89bf9f1f"
        )
    );
    $transaction->retrieve();
    $transaction->status == 'D' # Paid

    # Create subscription
    $subscription = $zru->Subscription(
        array(
            "currency" => "EUR",
            "plan_id" => "PLAN-ID",
            "note" => "Note example"
        )
    )
    # or with plan details
    $subscription = $zru->Subscription(
        array(
            "currency" => "EUR",
            "plan" => array(
                "name" => "Plan",
                "price" => 5,
                "duration" => 1,
                "unit" => "M",
                "recurring" => True
            ),
            "note" => "Note example"
        )
    );
    $subscription->save()
    $subscription->getPayUrl() # Send user to this url to pay
    $subscription->getIframeUrl() # Use this url to show an iframe in your site

    # Receive a notification
    $notificationData = $zru->NotificationData(JSON_DICT_RECEIVED_FROM_MYCHOICE2PAY, $zru);
    $notificationData->status == 'D'; # Paid
    $notificationData->transaction; # Get paid transaction
    $notificationData->sale; # Get generated sale

    # Exceptions

    require_once __DIR__ . '/vendor/autoload.php'; // Autoload files using Composer autoload

    use ZRU\ZRUClient;
    
    # Incorrect data
    $shipping = $zru->Shipping(
        array(
            "name" => "Normal shipping",
            "price" => "text" # Price must be a number
        )
    );

    try {
        $shipping->save();
    } catch (ZRU\InvalidRequestZRUError $e) {
        $e->getMessage(); # Status code
    }
```