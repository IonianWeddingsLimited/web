SELECT		clients.iwcuid,
			customer_invoices.order_id,
			clients.title,
			clients.firstname,
			clients.lastname,
			from_unixtime(clients.wedding_date),
			customer_invoices.status,
			CONCAT('http://www.ionianweddings.co.uk/oos/manage-client.php?a=history&id=', clients.id) as link
FROM		customer_invoices
INNER JOIN	order_details
ON			customer_invoices.order_id				=			order_details.id
INNER JOIN	clients
ON			order_details.client_id					=			clients.id
WHERE		customer_invoices.status				not like	'%Paid%'
and			customer_invoices.type					like		'%order%'
and			customer_invoices.updated_timestamp		>			2014/01/01
and			clients.deleted = 'no'
ORDER BY	customer_invoices.order_id desc

SELECT		clients.iwcuid,
			customer_invoices.order_id,
			clients.title,
			clients.firstname,
			clients.lastname,
			from_unixtime(clients.wedding_date),
			customer_invoices.status,
			CONCAT('http://www.ionianweddings.co.uk/oos/manage-client.php?a=history&id=', clients.id) as link
FROM		customer_invoices
INNER JOIN	order_details
ON			customer_invoices.order_id				=			order_details.id
INNER JOIN	clients
ON			order_details.client_id					=			clients.id
WHERE		customer_invoices.status				like		'%Paid%'
and			customer_invoices.updated_timestamp		>			2014/01/01
and			clients.deleted = 'no'
ORDER BY	customer_invoices.order_id desc

SELECT		DISTINCT
			clients.iwcuid,
			customer_invoices.order_id,
			clients.title,
			clients.firstname,
			clients.lastname,
			from_unixtime(clients.wedding_date),
			customer_invoices.status,
			clients_options.option_value,
			CONCAT('http://www.ionianweddings.co.uk/oos/manage-client.php?a=history&id=', clients.id) as link
FROM		customer_invoices
INNER JOIN	order_details
ON			customer_invoices.order_id				=			order_details.id
INNER JOIN	clients
ON			order_details.client_id					=			clients.id
INNER JOIN	clients_options
ON			customer_invoices.id					=			clients_options.client_id
WHERE		customer_invoices.updated_timestamp		>			2014/01/01
and			clients.deleted							=			'no'
and			clients_options.client_option			like		'%continental%'
and			clients_options.option_option			=			'yes'
ORDER BY	customer_invoices.order_id desc