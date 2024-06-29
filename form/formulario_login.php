<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    function clean_input($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    // Pegando dados do formulário
    $name = clean_input($_POST['name'] ?? '');
    $phone = clean_input($_POST['phone'] ?? '');
    $email = clean_input($_POST['email'] ?? '');
    $cnpj = clean_input($_POST['cnpj'] ?? '');
    $regime = clean_input($_POST['regime'] ?? '');
    $role = clean_input($_POST['role'] ?? '');

    // Validando os dados
    $errors = [];

    if (empty($name)) {
        $errors[] = "Nome completo é obrigatório";
    }
    if (empty($phone) || !preg_match("/^\(\d{2}\) \d{5}-\d{4}$/", $phone)) {
        $errors[] = "Celular é obrigatório e deve estar no formato (xx) xxxxx-xxxx";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email é obrigatório e deve ser válido";
    }
    if (empty($cnpj) || !preg_match("/^\d{2}\.\d{3}\.\d{3}\/\d{4}-\d{2}$/", $cnpj)) {
        $errors[] = "CNPJ é obrigatório e deve estar no formato xx.xxx.xxx/xxxx-xx";
    }
    if (empty($regime)) {
        $errors[] = "Regime tributário é obrigatório";
    }
    if (empty($role)) {
        $errors[] = "Cargo é obrigatório";
    }

    $log = "Formulário enviado em: " . date('Y-m-d H:i:s') . "\n";
    $log .= "Dados recebidos:\n";
    $log .= "Nome Completo: $name\n";
    $log .= "Celular: $phone\n";
    $log .= "Email: $email\n";
    $log .= "CNPJ: $cnpj\n";
    $log .= "Regime Tributário: $regime\n";
    $log .= "Cargo: $role\n";
    file_put_contents('email_debug_log.txt', $log, FILE_APPEND);

    if (empty($errors)) {
        $to = "tuany.peixoto@fngroup.com.br";
        $subject = "Novo contato do formulário";
        $message = "
            Nome Completo: $name\n
            Celular: $phone\n
            Email: $email\n
            CNPJ: $cnpj\n
            Regime Tributário: $regime\n
            Cargo: $role
        ";
        $headers = "From: contato@fncorporate.com.br\r\n";
        $headers .= "Reply-To: contato@fncorporate.com.br\r\n";
        $headers .= "Cc: luiz.moeller@fngroup.com.br\r\n"; 

        if (mail($to, $subject, $message, $headers)) {
            echo json_encode(["status" => "success", "message" => "Dados enviados com Sucesso"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Erro ao enviar email."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => $errors]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Erro: Nenhum formulário válido submetido."]);
    exit;
}
?>
