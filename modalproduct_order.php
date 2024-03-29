<!-- Order Details Modals -->
<?php if(isset($orders) && is_array($orders)): ?>
    <?php foreach ($orders as $order): ?>
        <!-- Order Details Modal -->
        <div class="modal fade" id="orderDetailsModal<?php echo $order['id']; ?>" tabindex="-1" aria-labelledby="orderDetailsModalLabel<?php echo $order['id']; ?>" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="orderDetailsModalLabel<?php echo $order['id']; ?>">Order Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table" style="text-align: center;">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Image</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Query to get order details related to this order
                                $query_order_details = "SELECT orderdetails.quantity, products.product_name, products.product_price, products.product_image
                                                        FROM orderdetails
                                                        INNER JOIN products ON orderdetails.product_id = products.id
                                                        WHERE orderdetails.order_id = ?";
                                $statement_order_details = $connection->prepare($query_order_details);
                                $statement_order_details->bind_param('i', $order['id']);
                                $statement_order_details->execute();
                                $result_order_details = $statement_order_details->get_result();

                                while ($detail = $result_order_details->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $detail['product_name']; ?></td>
                                        <td><?php echo $detail['quantity']; ?></td>
                                        <td><?php echo $detail['product_price']; ?></td>
                                        <td><img src="img/<?php echo $detail['product_image']; ?>" alt="<?php echo $detail['product_name']; ?>" style="max-width: 100px;"></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<!-- Cancel Confirmation Modals -->
<?php foreach ($orders as $order): ?>
    <div id="cancelConfirmationModal<?php echo $order['id']; ?>" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Cancel Order Confirmation</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to cancel this order?</p>
                </div>
                <div class="modal-footer">
                    <!-- Link to cancel the order with the appropriate order_id -->
                    <a href="?cancel_order=true&order_id=<?php echo $order['id']; ?>" class="btn btn-danger">Yes, Cancel Order</a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, Keep Order</button>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>