<?php $data = records_all(); ?>
<div class="container">
    <table class="table table-striped">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Artist</th>
            <th>Price</th>
            <th>Format</th>
        </tr>
        <?php if (count($data) > 0): ?>
            <?php foreach ($data as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']); ?></td>
                    <td><?= htmlspecialchars($row['title']); ?></td>
                    <td><?= htmlspecialchars($row['artist']); ?></td>
                    <td><?= htmlspecialchars($row['price']); ?></td>
                    <td><?= htmlspecialchars($row['name']); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">No records found</td>
            </tr>
        <?php endif; ?>
    </table>
</div>