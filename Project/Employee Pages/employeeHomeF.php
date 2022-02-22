<?php
session_start();
?>

<html>

<header>
	
	<link rel="stylesheet" type="text/css" href="employeeHomeF_styles.css">

	<title></title>

</header>

<body>
	<nav class ="navbar" id = "navbar">
		<p1> Website </p1>
		<ul>

			<li> <a href="employeeHomeF.php"> Home </a> </li>
			<li> <a href="employeeAccountInfoF.php"> Account</a> </li>
		</ul>
	</nav>
    
    <div>
        <h1>Food Order</h1>
        <table>
            <thead>
                <tr class="rowhead">
                    <td id="status">Deliver Status</td>
                    <td id="time">Order Number</td>
                    <td id="time">Date/Time</td>
                    <td id="roomNo">RoomNo</td>
                    <td id="seatid">SeatID</td>
                    <td id="food">Food Items</td>
                    <td id="deliver">Edit Deliver Status</td>
                </tr>
                </thead>
                <!-- Display Food orders from the database -->
                <?php

                    //Make database connection
                    include_once '../Database/connection.php';
                    $con = getConnection();

                    //Get order_numbers of customers in the same theatre employee works in
                    //Ordered by time and prioritizes orders that are in progress
                    $stmt = $con->prepare('CALL getFoodOrders(?, ?, ?)');

                    $email = $_SESSION["employeeE-mail"];
                    $delivered = 2;

                    $stmt-> bind_param('sss', $delivered, date('Y-m-d H:i:s'), $email);

                    $stmt->execute();

                    $result = $stmt->get_result();

                    while($row = mysqli_fetch_array($result)){
                        //Displays deliver staus
                        if($row['Deliver_Status'] == 0){
                            echo "<tr class='orderPlaced'>
                            <td>Order Placed</td>";
                        }
                        else if($row['Deliver_Status'] == 1){
                            echo "<tr class='inProgress'>
                            <td>In progress</td>";
                        }

                        //Displays order number, date and time of movie start and room number to deliver the food
                        echo "<td>". $row['Order_Number']. "</td>
                        <td>". $row['DateTime']. "</td>
                        <td>". $row['Room_No']. "</td>";

                        $con->next_result();

                        //Get seatids for associated with the food order
                        $stmt2 = $con->prepare('CALL getSeatIDs(?, ?)');
                        
                        $stmt2-> bind_param('ss', $row['CustomerID'], $row['DateTime']);

                        $stmt2->execute();

                        $result2 = $stmt2->get_result();

                        echo "<td>";
                        $n = 0; //Used to put line break if more than one seat ID
                        while($row2 = mysqli_fetch_array($result2)){
                            //Displays seat ids
                            if($n != 0){
                                echo "<br>";
                            }
                            echo $row2['SeatID']. " ";
                            $n = $n + 1;
                        }
                        echo "</td>";

                        $con->next_result();

                        //Get Food items based on the order number
                        $stmt3 = $con->prepare('CALL getFoodItemsbyOrderNo(?)');
                        
                        $stmt3-> bind_param('s', $row['Order_Number']);

                        $stmt3->execute();

                        $result3 = $stmt3->get_result();

                        echo "<td>";

                        $i = 0; //used to put line break if more than one food item
                        while($row3 = mysqli_fetch_array($result3)){
                            //Displays food items and their quantity
                            if($i != 0){
								echo "<br>";
							}
							if($row3['Popcorn'] == 1){
								echo "Quantity: ". $row3['Quantity'] ."   Item: ". $row3['Description']. " Popcorn (". $row3['Size']. ")";
							}
							else if($row3['Drink'] == 1){
								echo "Quantity: ". $row3['Quantity'] ."   Item: ". $row3['Description']. " (". $row3['Size']. ")";
							}
							else if($row3['Candy'] == 1){
								echo "Quantity: ". $row3['Quantity'] ."   Item: ". $row3['Description']. " (". $row3['Size']. ")";
							}
							else if($row3['Poutine'] == 1){
								echo "Quantity: ". $row3['Quantity'] ."   Item: ". $row3['Description']. " Poutine (". $row3['Size']. ")";
							}
							else if($row3['Nacho'] == 1){
								echo "Quantity: ". $row3['Quantity'] ."   Item: ". $row3['Description']. " Nachos (". $row3['Size']. ")";
							}
							$i = $i + 1;
                        }
                        //Submission form to update the deliver status
                        echo "</td>
                        <td>
                            <form action='updateDeliverStatusB.php' method='post'>
                                <select name='deliverStatus'>
                                    <option value='inProgress'>In progress</option>
                                    <option value='delivered'>Delivered</option>
                                    <option value='orderPlaced'>Order Placed</option>
                                </select>
                                <pre> </pre>
                                <input type='submit' value='Update'>
                                <input type='hidden' name='OrderNo' value='".$row['Order_Number']."'>
                                <input type='hidden' name='CustomerID' value='".$row['CustomerID']."'>
                                <input type='hidden' name='RoomNo' value='".$row['Room_No']."'>
                                <input type='hidden' name='DateTime' value='".$row['DateTime']."'>
                            </form>
                        </td>
                        </tr>";
                    }
                    $con->next_result();
                    mysqli_close($con);
                ?>
        </table>
    </div>
</body>

</html>