<?php
// Display orders
$total_price = 0; // initialize total price outside the loop

if (isset($orders) && is_array($orders)) {
    // Initialize an array to keep track of displayed orders
    $displayed_orders = array();
    
    foreach ($orders as $order) {
        $total_price_query = "SELECT SUM(p.product_price * od.quantity) AS total_price FROM orderdetails od JOIN products p ON od.product_id = p.id WHERE od.order_id = ?";
        
        $statement = $connection->prepare($total_price_query);
        $statement->bind_param('i', $order['id']);
        $statement->execute();
        $result = $statement->get_result();
        $total_price_row = $result->fetch_assoc();
        $total_price += $total_price_row['total_price'];

        // Check if the order has already been displayed
        if (!in_array($order['id'], $displayed_orders)) {
            // Add the order ID to the displayed orders array
            $displayed_orders[] = $order['id'];
?>
            <tr>
                <td><?php echo $order['order_date']; ?></td>
                <td><?php echo $order['order_status']; ?></td>
                <td><?php echo $total_price_row['total_price']; ?></td>
                <td class='table-buttons'>
                <a href="#" class="view-details-btn" data-order-id="<?php echo $order['id']; ?>">View Details</a>
                         <?php if ($order['order_status'] == 'processing'): ?>
                          <a href='#' class='cancel-order-btn' data-order-id='<?php echo $order['id']; ?>'>Cancel Order</a>
                    <?php endif; ?>
                </td>
            </tr>
<?php
        }
    }
}
?>
