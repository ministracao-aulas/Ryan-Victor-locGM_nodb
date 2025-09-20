<?php include __DIR__ . '/partials/header.php'; ?>
<h2>Social • Promoções e Novidades</h2>
<?php
if ($user['role']==='empresa' && isset($_POST['content'])) {
    $c = trim($_POST['content']);
    if ($c!=='') { add_post($user['email'], $user['company_name'] ?: $user['name'], $c); echo '<div class="alert">Post publicado!</div>'; }
}
?>
<?php if ($user['role']==='empresa'): ?>
<form method="post" class="card vstack gap">
  <label class="label">Nova postagem</label>
  <textarea name="content" rows="3" placeholder="Escreva sua promoção..."></textarea>
  <button class="btn primary">Publicar</button>
</form>
<?php endif; ?>
<div class="vstack gap">
<?php foreach (array_reverse(posts()) as $p): ?>
  <article class="card">
    <div class="card-title"><?php echo htmlspecialchars($p['company_name']); ?></div>
    <p><?php echo nl2br(htmlspecialchars($p['content'])); ?></p>
    <div class="muted small"><?php echo htmlspecialchars($p['created_at']); ?></div>
  </article>
<?php endforeach; ?>
</div>
<?php include __DIR__ . '/partials/footer.php'; ?>
