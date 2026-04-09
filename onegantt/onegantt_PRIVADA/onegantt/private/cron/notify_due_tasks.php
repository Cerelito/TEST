<?php
/**
 * OneGantt — Cron job: alertas de tareas próximas a vencer
 * Configurar en cPanel Cron Jobs:
 *   0 8 * * * php /home1/erickedu/Controladores-Apotema/onegantt/cron/notify_due_tasks.php
 */

define('ROOT_PATH', '/home1/erickedu/Controladores-Apotema/onegantt/');

require_once ROOT_PATH . 'config/app.php';
require_once ROOT_PATH . 'core/Database.php';
require_once ROOT_PATH . 'core/Mailer.php';
require_once ROOT_PATH . 'models/TaskModel.php';
require_once ROOT_PATH . 'models/ProjectModel.php';

$tasks  = new TaskModel();
$mailer = new Mailer();

$due = $tasks->dueSoon(3); // vencen en los próximos 3 días

$sent = 0;
foreach ($due as $t) {
    $user = ['nombre' => $t['asignado_nombre'], 'email' => $t['asignado_email']];
    $task = [
        'id'       => $t['id'],
        'titulo'   => $t['titulo'],
        'fecha_fin'=> $t['fecha_fin'],
        'proyecto' => $t['proyecto'],
    ];
    if ($mailer->sendTaskReminder($user, $task)) {
        $sent++;
        echo "[OK] Enviado a {$t['asignado_email']} — {$t['titulo']}\n";
    } else {
        echo "[FAIL] {$t['asignado_email']} — {$t['titulo']}\n";
    }
}

echo "\nTotal enviados: {$sent} de " . count($due) . "\n";
