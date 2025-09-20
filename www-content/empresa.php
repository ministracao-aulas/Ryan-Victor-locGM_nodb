<?php include __DIR__ . '/partials/header.php'; if ($user['role']!=='empresa'){ echo '<p class="alert">Somente empresas.</p>'; include __DIR__.'/partials/footer.php'; exit; } ?>
<h2>Painel da Empresa</h2>
<div class="grid two">
  <section class="card">
    <div class="card-title">Meus locais</div>
    <?php
    if (isset($_POST['action']) && $_POST['action']==='save_place') {
        $data = [
            'id'     => intval($_POST['id'] ?? 0),
            'name'   => trim($_POST['name'] ?? ''),
            'type'   => trim($_POST['type'] ?? 'restaurante'),
            'lat'    => floatval($_POST['lat'] ?? 0),
            'lng'    => floatval($_POST['lng'] ?? 0),
            'rating' => floatval($_POST['rating'] ?? 4.0),
            'address'=> trim($_POST['address'] ?? '')
        ];
        save_place($data, $user['email']);
        echo '<div class="alert">Local salvo!</div>';
    }
    $placesMine = my_places($user['email']);
    ?>
    <div class="vstack gap">
      <?php foreach ($placesMine as $pl): ?>
        <div class="card">
          <b><?php echo htmlspecialchars($pl['name']); ?></b>
          <div class="muted small"><?php echo htmlspecialchars($pl['type']); ?> • Nota <?php echo htmlspecialchars($pl['rating']); ?></div>
          <details>
            <summary>Editar</summary>
            <form method="post" class="grid two">
              <input type="hidden" name="action" value="save_place">
              <input type="hidden" name="id" value="<?php echo $pl['id']; ?>">
              <label>Nome<input name="name" value="<?php echo htmlspecialchars($pl['name']); ?>"></label>
              <label>Tipo<select name="type">
                <?php foreach (['restaurante','mercado','pousada','farmacia','turismo'] as $t): ?>
                  <option value="<?php echo $t; ?>" <?php echo $pl['type']===$t?'selected':''; ?>><?php echo $t; ?></option>
                <?php endforeach; ?>
              </select></label>
              <label>Latitude<input name="lat" value="<?php echo $pl['lat']; ?>"></label>
              <label>Longitude<input name="lng" value="<?php echo $pl['lng']; ?>"></label>
              <label>Nota (0-5)<input name="rating" value="<?php echo $pl['rating']; ?>"></label>
              <label>Endereço<input name="address" value="<?php echo htmlspecialchars($pl['address']); ?>"></label>
              <div class="full"><button class="btn">Salvar</button></div>
            </form>
          </details>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="card">
    <div class="card-title">Adicionar novo local</div>
    <form method="post" class="grid two">
      <input type="hidden" name="action" value="save_place">
      <label>Nome<input name="name" required></label>
      <label>Tipo<select name="type">
        <option>restaurante</option><option>mercado</option><option>pousada</option><option>farmacia</option><option>turismo</option>
      </select></label>
      <label>Latitude<input name="lat" value="-10.783"></label>
      <label>Longitude<input name="lng" value="-65.338"></label>
      <label>Nota (0-5)<input name="rating" value="4.0"></label>
      <label>Endereço<input name="address"></label>
      <div class="full"><button class="btn primary">Adicionar</button></div>
    </form>
  </section>
</div>
<?php include __DIR__ . '/partials/footer.php'; ?>
