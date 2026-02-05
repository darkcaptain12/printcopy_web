<?php 
require_once '../partials/header.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = clean_input($_POST['name']);
    $slug = $_POST['slug'] ?: strtolower(str_replace(' ', '-', $name));
    $parent_id = !empty($_POST['parent_id']) ? $_POST['parent_id'] : null;
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    $stmt = $pdo->prepare("INSERT INTO categories (name, slug, parent_id, is_active) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$name, $slug, $parent_id, $is_active])) {
        set_flash('success', 'Category created');
        redirect('/admin/categories/index.php');
    } else {
        set_flash('error', 'Error creating category');
    }
}

$parents = $pdo->query("SELECT * FROM categories WHERE parent_id IS NULL")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="mb-6"><a href="index.php" class="text-gray-600">&larr; Back</a></div>
<h1 class="text-2xl font-bold mb-6">Create Category</h1>

<div class="bg-white rounded shadow p-6 max-w-xl">
    <form action="" method="POST">
        <?= csrf_field() ?>
        <div class="mb-4">
            <label class="block font-bold mb-2">Name</label>
            <input type="text" name="name" class="w-full border rounded p-2" required>
        </div>
        <div class="mb-4">
            <label class="block font-bold mb-2">Slug</label>
            <input type="text" name="slug" class="w-full border rounded p-2">
        </div>
        <div class="mb-4">
            <label class="block font-bold mb-2">Parent Category</label>
            <select name="parent_id" class="w-full border rounded p-2">
                <option value="">None</option>
                <?php foreach($parents as $p): ?>
                    <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-6">
            <label class="flex items-center">
                <input type="checkbox" name="is_active" class="mr-2" checked>
                <span>Active</span>
            </label>
        </div>
        <button class="bg-blue-600 text-white px-6 py-2 rounded">Create</button>
    </form>
</div>

<?php require_once '../partials/footer.php'; ?>
