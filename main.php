<?php
session_start();

function fatorial($num) {
    if ($num <= 1) {
        return 1;
    } else {
        return $num * fatorial($num - 1);
    }
}

function potencia($base, $expoente) {
    return pow($base, $expoente);
}

$numero1 = isset($_POST['numero1']) ? $_POST['numero1'] : '';
$numero2 = isset($_POST['numero2']) ? $_POST['numero2'] : '';
$operacao = isset($_POST['operacao']) ? $_POST['operacao'] : '';
$resultado = '';
$historico = isset($_SESSION['historico']) ? $_SESSION['historico'] : [];

if ($numero1 != '' && $numero2 != '' && $operacao != '') {
    switch ($operacao) {
        case '+':
            $resultado = $numero1 + $numero2;
            break;
        case '-':
            $resultado = $numero1 - $numero2;
            break;
        case '*':
            $resultado = $numero1 * $numero2;
            break;
        case '/':
            if ($numero2 != 0) {
                $resultado = $numero1 / $numero2;
            } else {
                $resultado = "Erro: divisão por zero";
            }
            break;
        case '!':
            $resultado = fatorial($numero1);
            break;
        case '^':
            $resultado = potencia($numero1, $numero2);
            break;
    }

    $historico[] = array(
        'numero1' => $numero1,
        'numero2' => $numero2,
        'operacao' => $operacao,
        'resultado' => $resultado
    );

    $_SESSION['historico'] = $historico;
}

if (isset($_POST['salvar_memoria'])) {
    $_SESSION['memoria'] = array(
        'numero1' => $numero1,
        'numero2' => $numero2,
        'operacao' => $operacao
    );
}

if (isset($_POST['recuperar_memoria']) && isset($_SESSION['memoria'])) {
    $numero1 = $_SESSION['memoria']['numero1'];
    $numero2 = $_SESSION['memoria']['numero2'];
    $operacao = $_SESSION['memoria']['operacao'];
}

if (isset($_POST['limpar_historico'])) {
    $_SESSION['historico'] = [];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculadora</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</head>
<body>
    <div class="calculator">
        <form method="post">
            <input type="text" name="numero1" value="<?php echo $numero1; ?>" required>
            <select name="operacao" required>
                <option value="+" <?php echo ($operacao == '+') ? 'selected' : ''; ?>>+</option>
                <option value="-" <?php echo ($operacao == '-') ? 'selected' : ''; ?>>-</option>
                <option value="*" <?php echo ($operacao == '*') ? 'selected' : ''; ?>>*</option>
                <option value="/" <?php echo ($operacao == '/') ? 'selected' : ''; ?>>/</option>
                <option value="!" <?php echo ($operacao == '!') ? 'selected' : ''; ?>>!</option>
                <option value="^" <?php echo ($operacao == '^') ? 'selected' : ''; ?>>^</option>
            </select>
            <input type="text" name="numero2" value="<?php echo $numero2; ?>" required>
            <button type="submit">Calcular</button>
            <button type="submit" name="salvar_memoria">M (Salvar)</button>
            <button type="submit" name="recuperar_memoria">M (Recuperar)</button>
        </form>

        <?php if ($resultado !== ''): ?>
            <p>Resultado: <?php echo $resultado; ?></p>
        <?php endif; ?>
    </div>

    <div class="history">
        <h3>Histórico de Operações</h3>
        <form method="post">
            <button type="submit" name="limpar_historico">Limpar Histórico</button>
        </form>
        <ul>
            <?php foreach ($historico as $operação): ?>
                <li><?php echo "{$operação['numero1']} {$operação['operacao']} {$operação['numero2']} = {$operação['resultado']}"; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
