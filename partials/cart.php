<div class="container mt-3">
<h2>Your Cart</h2>

<?php $records = $records_in_cart ?? []; ?>

<?php if (empty($records)): ?>
    <p>Your cart is empty.</p>
<?php else: ?>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Artist</th>
                <th>Price</th>
                <th>Format</th>

            </tr>
        </thead>
        <tbody>
            <?php foreach ($records as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']); ?></td>
                    <td><?= htmlspecialchars($row['title']); ?></td>
                    <td><?= htmlspecialchars($row['artist']); ?></td>
                    <td><?= htmlspecialchars($row['price']); ?></td>
                    <td><?= htmlspecialchars($row['name']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <form method="post">
        <input type="hidden" name="action" value="checkout">
        <button class="btn btn-success">Complete Purchase</button>
    </form>

<?php endif; ?>
</div>