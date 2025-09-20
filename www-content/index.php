<?php
require_once __DIR__ . '/data.php';
seed_data();
$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $role  = $_POST['role'] ?? 'visitante';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Informe um e-mail válido.";
    } else {
        $user = [
            'email'=>$email,
            'role'=>$role,
            'name'=>$role==='empresa' ? 'Minha Empresa' : 'Visitante locGM',
            'company_name'=>$role==='empresa' ? 'Minha Empresa' : '',
            'phone'=>'',
            'address'=>'',
            'prices_note'=>''
        ];
        set_user($user);
        header('Location: /home.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>locGM - Login</title>
<link rel="stylesheet" href="/static/style.css">
</head>
<body class="center">
  <div class="card w-420">
    <div class="logo">locGM</div>
    <h2>Entrar</h2>
    <?php if ($error): ?><div class="alert"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
    <form method="post" class="vstack gap">
      <label class="label">Modo de uso</label>
      <div class="hstack gap">
        <label class="pill"><input type="radio" name="role" value="empresa"> Empresa</label>
        <label class="pill"><input type="radio" name="role" value="visitante" checked> Visitante</label>
      </div>
      <label class="label">E-mail</label>
      <input type="email" name="email" placeholder="email@dominio.com" required>
      <button class="btn primary">Continuar</button>
    </form>
    <p class="muted small">Sem banco de dados • os dados ficam na sessão do navegador.</p>
  </div>
</body>
</html>
