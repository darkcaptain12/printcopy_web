<?php 
require_once '../partials/header.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $title = clean_input($_POST['title']);
    $slug = $_POST['slug'] ?: strtolower(str_replace(' ', '-', $title));
    $content = $_POST['content'];
    $imagePath = null;
    if (!empty($_FILES['image']['name'])) {
        $imagePath = upload_image($_FILES['image']);
    }
    
    $stmt = $pdo->prepare("INSERT INTO blogs (title, slug, content, image) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$title, $slug, $content, $imagePath])) {
        set_flash('success', 'Post created');
        redirect('index.php');
    }
}
?>

<div class="mb-6"><a href="index.php" class="text-gray-600">&larr; Back</a></div>
<h1 class="text-2xl font-bold mb-6">Create Post</h1>

<div class="bg-white rounded shadow p-6">
    <form action="" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="mb-4">
            <label class="block font-bold mb-2">Title</label>
            <input type="text" name="title" class="w-full border rounded p-2" required>
        </div>
        <div class="mb-4">
            <label class="block font-bold mb-2">Slug</label>
            <input type="text" name="slug" class="w-full border rounded p-2">
        </div>
        <div class="mb-4">
            <label class="block font-bold mb-2">Image</label>
            <input type="file" name="image" class="w-full border rounded p-2">
        </div>
        <div class="mb-6">
            <label class="block font-bold mb-2">Content</label>
            <textarea name="content" rows="10" class="w-full border rounded p-2"></textarea>
        </div>
        <button class="bg-blue-600 text-white px-6 py-2 rounded">Create Post</button>
    </form>
</div>

<?php require_once '../partials/footer.php'; ?>
