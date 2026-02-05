<?php require_once '../partials/header.php'; ?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold">Blog Posts</h1>
    <a href="create.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Add Post</a>
</div>

<div class="bg-white rounded shadow">
    <table class="w-full">
        <thead class="bg-gray-100 border-b">
            <tr>
                <th class="py-3 px-4 text-left">Title</th>
                <th class="py-3 px-4 text-left">Slug</th>
                <th class="py-3 px-4 text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $pdo->query("SELECT * FROM blogs ORDER BY id DESC");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
            ?>
            <tr class="border-b hover:bg-gray-50">
                <td class="py-3 px-4 font-bold"><?= htmlspecialchars($row['title']) ?></td>
                <td class="py-3 px-4 text-gray-500"><?= $row['slug'] ?></td>
                <td class="py-3 px-4 text-right">
                    <a href="edit.php?id=<?= $row['id'] ?>" class="text-blue-600 hover:underline mr-3">Edit</a>
                    <a href="delete.php?id=<?= $row['id'] ?>" class="text-red-600 hover:underline" onclick="return confirm('Delete?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php require_once '../partials/footer.php'; ?>
