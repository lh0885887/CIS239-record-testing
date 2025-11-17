<div class="container">
    <form method="POST">
        <h2>Enter the ID of the record you want to update:</h2>
        <input type="number" placeholder="ID" name="record_id">

        <input type="hidden" name="action" value="create">

        <div class="col-12 mt-3">
            <button class="btn btn-success">Create</button>
            <a href="?view=list" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div>