# Print Order Confirmation as Guest

This module allows guest users to print their order confirmation on the order complete/success page in Magento.
Magento does not allow guests to print orders due to the simple nature of the print order URL out of the box, example: http://www.example.com/sales/order/print/order_id/49/  

This URL could be easily modified to view other users order information by changing the value after "/order_id/".
To prevent this from occurring Magento made visible the print order button for logged in users only and checks user-to-order relation.
 
This module creates a secure URL for each guest order and prevents cross order lookup.  Example URL from the module:  

```
http://www.example.com/guest/order/print/order_hash/d69d3401d01a8ceed4549434e5ad9f40/
```

The printable order page is managed exactly the same way as Magento native print order page for logged in users.

## Installation via Composer

Make sure you have added [Firegento  repository](http://packages.firegento.com/) to your `composer.json`.  

Add as dependency to your project using composer

```bash
composer require eltrino/printorder  
```

In case you are using Magento 1.x version you will need additional requirement using composer

```bash
composer require eltrino/compatibility:dev-master
```

## Installation via archive

Download archive from Github and extract it into Magento root folder.
 
If you are running Magento 2 nothing else is required.

In case of Magento 1.x you will need to install additional [Compatibility extension] (https://github.com/eltrino/Compatibility)