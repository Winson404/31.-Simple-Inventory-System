<?php 
	include '../config.php';
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	require '../vendor/PHPMailer/src/Exception.php';
	require '../vendor/PHPMailer/src/PHPMailer.php';
	require '../vendor/PHPMailer/src/SMTP.php';
	date_default_timezone_set('Asia/Manila');



	// SAVE PRODUCT - PRODUCT_MGMT.PHP
	if(isset($_POST['create_product'])) {
		$prod_name       = mysqli_real_escape_string($conn, $_POST['prod_name']);
		$prod_desc       = mysqli_real_escape_string($conn, $_POST['prod_desc']);
		$prod_type       = mysqli_real_escape_string($conn, $_POST['prod_type']);
		$prod_stock      = mysqli_real_escape_string($conn, $_POST['prod_stock']);
		$prod_price      = mysqli_real_escape_string($conn, $_POST['prod_price']);

		$check_email = mysqli_query($conn, "SELECT * FROM product WHERE prod_name='$prod_name'");
		if(mysqli_num_rows($check_email)>0) {
		      $_SESSION['message'] = "Product already exists!";
		      $_SESSION['text'] = "Please try again.";
		      $_SESSION['status'] = "error";
			  header("Location: product_mgmt.php?page=create");
		} else {

			$save = mysqli_query($conn, "INSERT INTO product (prod_name, prod_desc, prod_type, prod_stock, prod_stock_orig, prod_price) VALUES ('$prod_name', '$prod_desc', '$prod_type', '$prod_stock', '$prod_stock', '$prod_price')");

	      	  if($save) {
	          	$_SESSION['message'] = "Product record has been saved!";
	            $_SESSION['text'] = "Saved successfully!";
		        $_SESSION['status'] = "success";
				header("Location: product_mgmt.php?page=create");
	          } else {
	            $_SESSION['message'] = "Something went wrong while saving the information.";
	            $_SESSION['text'] = "Please try again.";
		        $_SESSION['status'] = "error";
				header("Location: product_mgmt.php?page=create");
	          }
			
		}
	}





	// SAVE INVENTORY - INVENTORY_MGMT.PHP
	if(isset($_POST['create_inventory'])) {
		$prod_Id     = mysqli_real_escape_string($conn, $_POST['prod_Id']);
		$qty_sold    = mysqli_real_escape_string($conn, $_POST['qty_sold']);
		$total_price = mysqli_real_escape_string($conn, $_POST['total_price']);

		$fetch = mysqli_query($conn, "SELECT * FROM product WHERE prod_Id ='$prod_Id' ");
		$row = mysqli_fetch_array($fetch);
		$stock = $row['prod_stock'];
		$price = $row['prod_price'];

		if($qty_sold > $stock) {
		  $_SESSION['message'] = "Quantity sold must be lesser than ".$stock." ";
	      $_SESSION['text'] = "Please try again.";
	      $_SESSION['status'] = "error";
		  header("Location: inventory_mgmt.php?page=create");
		} else {

			$save = mysqli_query($conn, "INSERT INTO inventory (prod_Id, qty_sold, total) VALUES ('$prod_Id', '$qty_sold', '$total_price')");
      	  if($save) {

      	  	$update = mysqli_query($conn, "UPDATE product SET prod_stock=prod_stock-'$qty_sold' WHERE prod_Id='$prod_Id' ");
      	  	if($update) {
      	  		$_SESSION['message'] = "Inventory has been saved!";
	            $_SESSION['text'] = "Saved successfully!";
		        $_SESSION['status'] = "success";
				header("Location: inventory_mgmt.php?page=create");
      	  	} else {
  	  		    $_SESSION['message'] = "Something went wrong while updating the product stock.";
	            $_SESSION['text'] = "Please try again.";
		        $_SESSION['status'] = "error";
				header("Location: inventory_mgmt.php?page=create");
      	  	}

          	
          } else {
            $_SESSION['message'] = "Something went wrong while saving the information.";
            $_SESSION['text'] = "Please try again.";
	        $_SESSION['status'] = "error";
			header("Location: inventory_mgmt.php?page=create");
          }
		}

	}




	// SAVE PRODUCT RECEIVED - PRODUCT_RECEIVED_MGMT.PHP
	if(isset($_POST['create_product_received'])) {
		$supplier_name = mysqli_real_escape_string($conn, $_POST['supplier_name']);
		$prod_Id       = mysqli_real_escape_string($conn, $_POST['prod_Id']);
		$qty_received  = mysqli_real_escape_string($conn, $_POST['qty_received']);
		$date 				 = date('Y-m-d');

		$save = mysqli_query($conn, "INSERT INTO received (prod_Id, supplier_name, qty_received, date_received) VALUES ('$prod_Id', '$supplier_name', '$qty_received', '$date')");
	  if($save) {
	  		$_SESSION['message'] = "Product received has been saved!";
        $_SESSION['text'] = "Saved successfully!";
        $_SESSION['status'] = "success";
				header("Location: product_received_mgmt.php?page=create");
      } else {
        $_SESSION['message'] = "Something went wrong while saving the information.";
        $_SESSION['text'] = "Please try again.";
      	$_SESSION['status'] = "error";
				header("Location: product_received_mgmt.php?page=create");
      }

	}










?>



