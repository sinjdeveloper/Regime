<?php
// Partial: standardized flash / error messages for client views
// Supports flashdata keys: success, error, warning, info, errors
$session = session();

$flashTypes = [
    'success' => 'Succès',
    'error'   => 'Erreur',
    'warning' => 'Attention',
    'info'    => 'Info',
];

foreach ($flashTypes as $key => $label) {
    $msg = $session->getFlashdata($key);
    if (empty($msg)) continue;

    echo '<div class="flash-message flash-' . esc($key) . '" role="alert" style="margin-bottom:12px;padding:12px;border-radius:6px;">';
    echo '<strong>' . esc($label) . ':</strong> ';

    if (is_array($msg)) {
        // array of messages
        echo '<ul style="margin:6px 0 0 18px;">';
        foreach ($msg as $m) {
            echo '<li>' . esc((string)$m) . '</li>';
        }
        echo '</ul>';
    } else {
        echo '<span style="margin-left:6px;">' . esc((string)$msg) . '</span>';
    }

    echo '</div>';
}

// Generic 'errors' flash (validation arrays) or $errors variable
$errors = $session->getFlashdata('errors') ?? ($errors ?? null);
if (! empty($errors)) {
    echo '<div class="flash-message flash-error" role="alert" style="margin-bottom:12px;padding:12px;border-radius:6px;">';
    echo '<strong>Erreurs :</strong>';
    if (is_array($errors)) {
        echo '<ul style="margin:6px 0 0 18px;">';
        foreach ($errors as $field => $message) {
            if (is_array($message)) {
                foreach ($message as $m) {
                    echo '<li>' . esc((string)$m) . '</li>';
                }
            } else {
                echo '<li>' . esc((string)$message) . '</li>';
            }
        }
        echo '</ul>';
    } else {
        echo '<div style="margin-top:6px;">' . esc((string)$errors) . '</div>';
    }
    echo '</div>';
}

// If controllers set a structured flash with message+details, display details
$structured = $session->getFlashdata('flash');
if (! empty($structured) && is_array($structured)) {
    $main = $structured['message'] ?? null;
    $details = $structured['details'] ?? null;
    if ($main) {
        echo '<div class="flash-message flash-info" role="alert" style="margin-bottom:12px;padding:12px;border-radius:6px;">';
        echo '<strong>Message :</strong> ' . esc((string)$main);
        if (! empty($details)) {
            echo '<div style="margin-top:8px;font-size:.95em;color:#444">' . nl2br(esc((string)$details)) . '</div>';
        }
        echo '</div>';
    }
}

?>
