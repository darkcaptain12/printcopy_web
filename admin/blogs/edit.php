<?php 
require_once '../partials/header.php'; 

$id = $_GET['id'] ?? null;
if (!$id) redirect('index.php');
$stmt = $pdo->prepare("SELECT * FROM blogs WHERE id = ?");
$stmt->execute([$id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $title = clean_input($_POST['title']);
    $slug = $_POST['slug'] ?: strtolower(str_replace(' ', '-', $title));
    $content = $_POST['content'];
    
    $imagePath = $post['image'];
    if (!empty($_FILES['image']['name'])) {
        $uploaded = upload_image($_FILES['image']);
        if ($uploaded) $imagePath = $uploaded;
    }

    $stmt = $pdo->prepare("UPDATE blogs SET title=?, slug=?, content=?, image=? WHERE id=?");
    if ($stmt->execute([$title, $slug, $content, $imagePath, $id])) {
        set_flash('success', 'Post updated');
        redirect('index.php');
    }
}
?>

<div class="mb-6"><a href="index.php" class="text-gray-600">&larr; Back</a></div>
<h1 class="text-2xl font-bold mb-6">Edit Post</h1>

<div class="bg-white rounded shadow p-6">
    <form action="" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="mb-4">
            <label class="block font-bold mb-2">Title</label>
            <input type="text" name="title" value="<?= htmlspecialchars($post['title']) ?>" class="w-full border rounded p-2" required>
        </div>
        <div class="mb-4">
            <label class="block font-bold mb-2">Slug</label>
            <input type="text" name="slug" value="<?= htmlspecialchars($post['slug']) ?>" class="w-full border rounded p-2">
        </div>
        <div class="mb-4">
            <label class="block font-bold mb-2">Image</label>
            <?php if($post['image']): ?>
                <div class="mb-2"><img src="/storage/<?= $post['image'] ?>" class="h-20 w-auto rounded border"></div>
            <?php endif; ?>
            <input type="file" name="image" class="w-full border rounded p-2">
        </div>
        <div class="mb-6">
            <label class="block font-bold mb-2">Content</label>
            <textarea name="content" rows="10" class="w-full border rounded p-2"><?= htmlspecialchars($post['content']) ?></textarea>
        </div>
        <button class="bg-blue-600 text-white px-6 py-2 rounded">Update Post</button>
    </form>
</div>

<?php require_once '../partials/footer.php'; ?>
