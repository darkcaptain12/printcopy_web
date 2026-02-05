<?php use App\Core\Session; ?>

<?php if ($msg = Session::getFlash('success')): ?>
    <div class="container mt-4">
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <span><?= $msg ?></span>
        </div>
    </div>
<?php endif; ?>

<?php if ($msg = Session::getFlash('error')): ?>
    <div class="container mt-4">
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <span><?= $msg ?></span>
        </div>
    </div>
<?php endif; ?>
