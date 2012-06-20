After success order place, every customer (guest or registered) see the success page where they can choose: continue shopping or print just created order.
The problem with guests that they don't have enough permissions to load order to print it.
To solve this PrintOrder will have it own controller that will provide access to guest order.
To make guests order viewing more confident - on success page Print Order button will be presented with unique (!!!) and temporary (!!!) hash.
Hash are generated from guests specific params and have short period of time of availability.
Hash linked with order id by storing linking in PrintOrder table. Link table cleaning by cron

In DeveloperMode exceptions will be throwed as is


1) Create extra table to link tmp hash to order id
   Table name - guests_order_hash

     id  |    hash    | order_id |     created_at
   ----------------------------------------------------
     1   | 4s5sdfg8df |    11    | 2012-06-19 12:45:00

     id - auto increment
     hash - temporary hash string (md5) using order_id + store_secrete_key + time
     order_id - order id with foreign key to sales_order
     created_at - time when created
      -- OR --
     expired_at - time when hash expired


     Table can consist of user specific data, such as browser, IP, session etc., to increse security protection

2) Create Model to support previous table

3) Create observer to fill table, created in previous step

4) Create controller to provide ability to view order

5) Create cron specific functionality to clean old (already expired) hash items