<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="bg-body py-12">
    <div class="container">
        <div class="text-center mb-12">
            <h1 class="text-3xl font-bold mb-4">Blog & Haberler</h1>
            <p class="text-muted">Sektörel gelişmeler ve teknik ipuçları.</p>
        </div>

        <div class="grid grid-1 md:grid-3 gap-8" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
            <?php foreach($posts as $post): ?>
                <div class="card h-full flex flex-col group hover:shadow-lg transition">
                    <a href="#" class="block overflow-hidden h-48">
                        <img src="<?= $post['image'] ?>" alt="<?= htmlspecialchars($post['title']) ?>" 
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    </a>
                    <div class="card-body flex-1 flex flex-col">
                        <div class="text-xs text-muted mb-2">
                            <i class="far fa-calendar-alt mr-1"></i> <?= $post['date'] ?>
                        </div>
                        <h2 class="text-xl font-bold mb-3 leading-tight">
                            <a href="#" class="group-hover:text-accent transition">
                                <?= htmlspecialchars($post['title']) ?>
                            </a>
                        </h2>
                        <p class="text-secondary text-sm mb-4 flex-1">
                            <?= htmlspecialchars($post['excerpt']) ?>
                        </p>
                        <a href="#" class="text-accent font-bold text-sm uppercase tracking-wide hover:underline">
                            Devamını Oku &rarr;
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
