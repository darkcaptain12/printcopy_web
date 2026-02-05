<?php require_once '../partials/header.php'; ?>

<h1 class="text-3xl font-bold mb-6">Pages</h1>

<div class="bg-white rounded shadow">
    <table class="w-full">
        <thead class="bg-gray-100 border-b">
            <tr>
                <th class="py-3 px-4 text-left">Key</th>
                <th class="py-3 px-4 text-left">Title</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $pdo->query("SELECT * FROM pages");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
            ?>
            <tr class="border-b hover:bg-gray-50">
                <td class="py-3 px-4 font-mono text-sm"><?= $row['slug'] ?></td> <!-- using slug/page_key interchangeably based on schema -->
                <td class="py-3 px-4"><?= htmlspecialchars($row['title']) ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php require_once '../partials/footer.php'; ?>
