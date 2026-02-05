<?php require __DIR__ . '/../partials/header.php'; ?>

<section class="bg-body py-12">
    <div class="container max-w-4xl mx-auto bg-white p-8 rounded shadow-sm border">
        <h1 class="text-3xl font-bold mb-4"><?= htmlspecialchars($page['title']) ?></h1>
        <article class="prose max-w-none">
            <?= $page['content'] ?>
        </article>
    </div>
</section>

<?php require __DIR__ . '/../partials/footer.php'; ?>
