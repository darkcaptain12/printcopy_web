<?php 
require_once '../partials/header.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = clean_input($_POST['name']);
    $slug = $_POST['slug'] ?: strtolower(str_replace(' ', '-', $name));
    $category_id = $_POST['category_id'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $stock_status = $_POST['stock_status'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    $imagePath = null;
    if (!empty($_FILES['image']['name'])) {
        $imagePath = upload_image($_FILES['image']);
    }

    $stmt = $pdo->prepare("INSERT INTO products (name, slug, category_id, price, description, image, stock_status, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt->execute([$name, $slug, $category_id, $price, $description, $imagePath, $stock_status, $is_active])) {
        set_flash('success', 'Product created successfully');
        redirect('/admin/products/index.php');
    } else {
        set_flash('error', 'Error creating product');
    }
}

$categories = $pdo->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="mb-6"><a href="index.php" class="text-gray-600 hover:underline">&larr; Back</a></div>
<h1 class="text-2xl font-bold mb-6">Create Product</h1>

<div class="bg-white rounded shadow p-6">
    <form action="" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div>
                    <label class="block font-bold mb-1">Name</label>
                    <input type="text" name="name" class="w-full border rounded p-2" required>
                </div>
                <div>
                    <label class="block font-bold mb-1">Slug</label>
                    <input type="text" name="slug" class="w-full border rounded p-2">
                </div>
                <div>
                    <label class="block font-bold mb-1">Category</label>
                    <select name="category_id" class="w-full border rounded p-2" required>
                        <?php foreach($categories as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block font-bold mb-1">Price</label>
                    <input type="number" step="0.01" name="price" class="w-full border rounded p-2" required>
                </div>
            </div>
            
            <div class="space-y-4">
                <div>
                    <label class="block font-bold mb-1">Stock Status</label>
                    <select name="stock_status" class="w-full border rounded p-2">
                        <option value="in_stock">In Stock</option>
                        <option value="out_of_stock">Out of Stock</option>
                    </select>
                </div>
                <div>
                    <label class="block font-bold mb-1">Image</label>
                    <input type="file" name="image" class="w-full border rounded p-2">
                </div>
                <div class="pt-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" class="mr-2" checked>
                        <span>Active</span>
                    </label>
                </div>
            </div>
            
            <div class="col-span-2">
                <label class="block font-bold mb-1">Description</label>
                <textarea name="description" rows="5" class="w-full border rounded p-2"></textarea>
            </div>
        </div>
        
        <div class="mt-6">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Create Product</button>
        </div>
    </form>
</div>

<?php require_once '../partials/footer.php'; ?>
