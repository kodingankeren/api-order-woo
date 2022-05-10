----
GET
----
# endpoint :
belajar-api-woo

# Parameter Conditional
- pages
- per_page
- start 				// Format yyyy-mm-dd
- end 					// Format yyyy-mm-dd
----
POST
----
# endpoint :
belajar-api-woo

# Parameter Required
- id_produk

# Parameter Conditional
- qty
- id_user
- first_name
- last_name
- company
- email
- phone
- address_1
- address_2
- city
- state
- postcode

----
PUT
----
# endpoint :
belajar-api-woo

# Parameter Required
- id_order

# Parameter Conditional

- qty

- id_user

- status_order (pending','failed','on-hold','completed')

- first_name
- last_name
- company
- email
- phone
- address_1
- address_2
- city
- state
- postcode


----
DELETE
----
# endpoint :
belajar-api-woo

# Parameter Required
- id_order