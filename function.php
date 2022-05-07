<?php

	function belajar_api_woo_get($page,$per_page){
		$per_page = $per_page;
		$total_post = wp_count_posts('shop_order' );

		$order_count      = array();
		$order_count_data = wp_count_posts( 'shop_order' );
		$jumlah_post = 0;
		foreach ( wc_get_order_statuses() as $status_slug => $status_name ) {
		$jumlah_post += (int)$order_count_data->{ $status_slug };
			$order_count[ $status_slug ] = $order_count_data->{ $status_slug };
		}

		
		$jumlah_page = ceil($jumlah_post / $per_page);
		
		$data = array();
		$loop = new WP_Query( array(
			'post_type'         => 'shop_order',
			        // "post_status" => array('wc-processing'),
			'post_status'       =>  array_keys( wc_get_order_statuses() ),
	        'paged' => $page,
	   		'posts_per_page' => $per_page
		));

		if ( $loop->have_posts() ): 
			while ( $loop->have_posts() ) : $loop->the_post();

				$order_id = $loop->post->ID;

				$order = wc_get_order($loop->post->ID);
				$obj = array();
				$obj['id'] = $order_id;
				$obj['user_id'] = $order->get_user_id();
				$order_data = $order->get_data(); // The Order data

				$obj['order_status'] = $order_data['status'];

				$obj['date_created'] = $order_data['date_created']->date('Y-m-d H:i:s');
				$obj['date_modified'] = $order_data['date_modified']->date('Y-m-d H:i:s');
				$obj['total'] = $order_data['total'];


				$obj['first_name'] = $order_data['shipping']['first_name'];
				$obj['last_name'] = $order_data['shipping']['last_name'];
				$obj['company'] = $order_data['shipping']['company'];
				$obj['address_1'] = $order_data['shipping']['address_1'];
				$obj['address_2'] = $order_data['shipping']['address_2'];
				$obj['city'] = $order_data['shipping']['city'];
				$obj['state'] = $order_data['shipping']['state'];
				$obj['postcode'] = $order_data['shipping']['postcode'];

				$obj['email'] = $order_data['billing']['email'];
				$obj['phone'] = $order_data['billing']['phone'];

				foreach ($order->get_items() as $item_key => $item_values):
					$item_data = $item_values->get_data();
					$obj['qty'] = $item_data['quantity'];
					$obj['id_produk'] = $item_data['product_id'];
				endforeach;
				array_push($data, $obj);

			endwhile;

			wp_reset_postdata();
		endif;

		$respon = [];
		$respon["status"] = "success";
		$respon["message"] = "berhasil";
		$respon["page"] = $page;
		$respon["per_page"] = $per_page;
		$respon["total"] = $jumlah_post;
		$respon["total_pages"] = $jumlah_page;
		$respon["data"] = $data;

		return $respon;
	}
	function belajar_api_woo_post($param){
		global $woocommerce;
		$respon = [];
		if ($param['id_produk'] == false) {
			$respon["status"] = "gagal";
			$respon["message"] = "produk harus ada";
		}else{
			$address = array();
			if($param['first_name'] != false){
				$address['first_name'] = $param['first_name'];
			}
			if($param['last_name'] != false){
				$address['last_name'] = $param['last_name'];
			}
			if($param['company'] != false){
				$address['company'] = $param['company'];
			}
			if($param['email'] != false){
				$address['email'] = $param['email'];
			}
			if($param['phone'] != false){
				$address['phone'] = $param['phone'];
			}
			if($param['address_1'] != false){
				$address['address_1'] = $param['address_1'];
			}
			if($param['address_2'] != false){
				$address['address_2'] = $param['address_2'];
			}
			if($param['city'] != false){
				$address['city'] = $param['city'];
			}
			if($param['state'] != false){
				$address['state'] = $param['state'];
			}
			if($param['postcode'] != false){
				$address['postcode'] = $param['postcode'];
			}

			$order = wc_create_order();
			$order->add_product( get_product($param['id_produk']), $param['qty']);
			$order->set_address( $address, 'billing' );
			$order->set_address( $address, 'shipping' );

			if($param['id_user'] != false){
				$order->set_customer_id($param['id_user']);
			}
			$order->calculate_totals();
			$order->update_status("Completed", 'Imported order', TRUE);

			$respon["status"] = "success";
			$respon["message"] = "berhasil";
			$respon["data"] = array(
				"order_id" => $order->get_id()
			);
		}
		return $respon;
	}
	function belajar_api_woo_put($param){
		$order = new WC_Order($param['id_order']);
		 
		if (!empty($order)) {
			$address = array();
			$update_adress = false;
			if($param['first_name'] != false){
				$update_adress = true;
				$address['first_name'] = $param['first_name'];
			}
			if($param['last_name'] != false){
				$update_adress = true;
				$address['last_name'] = $param['last_name'];
			}
			if($param['company'] != false){
				$update_adress = true;
				$address['company'] = $param['company'];
			}
			if($param['email'] != false){
				$update_adress = true;
				$address['email'] = $param['email'];
			}
			if($param['phone'] != false){
				$update_adress = true;
				$address['phone'] = $param['phone'];
			}
			if($param['address_1'] != false){
				$update_adress = true;
				$address['address_1'] = $param['address_1'];
			}
			if($param['address_2'] != false){
				$update_adress = true;
				$address['address_2'] = $param['address_2'];
			}
			if($param['city'] != false){
				$update_adress = true;
				$address['city'] = $param['city'];
			}
			if($param['state'] != false){
				$update_adress = true;
				$address['state'] = $param['state'];
			}
			if($param['postcode'] != false){
				$update_adress = true;
				$address['postcode'] = $param['postcode'];
			}
			if($param['status_order'] != false){
				$order->update_status( $param['status_order'] );
			}

			if ($update_adress) {
				$order->set_address( $address, 'billing' );
				$order->set_address( $address, 'shipping' );
			}
		 
		}
		
		if($param['qty'] != false){
			$order_items = $order->get_items();
			foreach ($order_items as $item_key => $item_values):
				$item_data = $item_values->get_data();
				$product_id    = $item_data['product_id']; 
				$order_item_id    = $item_data['id'];
			endforeach;
			$quantity = $param['qty'];

			$product = wc_get_product( $product_id );
			$price = $product->get_price();
			$price = ( int ) $quantity * $price;
			$order_items[ $order_item_id ]->set_quantity( $quantity );
			$order_items[ $order_item_id ]->set_subtotal( $price );
			$order_items[ $order_item_id ]->set_total( $price );

			$order->calculate_totals();
			$order->save();
		}

		$respon = [];
		$respon["tes"] = $order_item_id;
		// $respon["get_items"] = $order->get_items();
		$respon["status"] = "success";
		$respon["message"] = "berhasil";
		$respon["data"] = array("id" => $id);
		return $respon;
	}
	function belajar_api_woo_delete($id = 216){
		wp_delete_post($id);
		$respon = [];
		$respon["status"] = "success";
		$respon["message"] = "berhasil";
		$respon["data"] = array("id" => $id);
		return $respon;
	}