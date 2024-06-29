<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    function clean_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $name = clean_input($_POST['your-name']);
    $phone = clean_input($_POST['your-phone']);
    $email = clean_input($_POST['your-email']);
    $cnpj = clean_input($_POST['your-cnpj']);
    $regime = clean_input($_POST['your-regime']);
    $role = clean_input($_POST['your-role']);

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

    file_put_contents('email_debug_log.txt', "Iniciando processo de envio\n", FILE_APPEND);

    if (empty($errors)) {
        $to = "tuany.peixoto@fngroup.com.br";
        $subject = "Novo contato do formulário";
        $message = "
            Nome Completo: $name\n
            Celular: $phone\n
            E-mail: $email\n
            CNPJ: $cnpj\n
            Regime Tributário: $regime\n
            Cargo: $role
        ";
        $headers = "From: contato@fncorporate.com.br\r\n";
        $headers .= "Reply-To: contato@fncorporate.com.br\r\n";
        $headers .= "Cc: luiz.moeller@fngroup.com.br\r\n"; 

        file_put_contents('email_debug_log.txt', "Tentando enviar email para: $to\n", FILE_APPEND);

        if (mail($to, $subject, $message, $headers)) {
            file_put_contents('email_debug_log.txt', "Email enviado com sucesso para: $to\n", FILE_APPEND);
            echo "Dados enviado com Sucesso";
        } else {
            file_put_contents('email_debug_log.txt', "Falha ao enviar email para: $to\n", FILE_APPEND);
            echo "Erro ao enviar email.";
        }

        // Enviar cópia
        $emailCopia = "informacoes@fncorporate.com.br";
        $assuntoCopia = "Cópia do formulário de contato do FN Corporate";
        file_put_contents('email_debug_log.txt', "Tentando enviar cópia de email para: $emailCopia\n", FILE_APPEND);

        if (mail($emailCopia, $assuntoCopia, $message, $headers)) {
            file_put_contents('email_debug_log.txt', "Cópia de email enviada com sucesso para: $emailCopia\n", FILE_APPEND);
        } else {
            file_put_contents('email_debug_log.txt', "Falha ao enviar cópia de email para: $emailCopia\n", FILE_APPEND);
        }
    } else {
        foreach ($errors as $error) {
            echo $error . "\n";
            file_put_contents('email_debug_log.txt', $error . "\n", FILE_APPEND);
        }
    }
} else {
    echo "Erro: Nenhum formulário válido submetido.";
    file_put_contents('email_debug_log.txt', "Erro de submissão: Nenhum formulário válido submetido.\n", FILE_APPEND);
    exit;
}

?>
