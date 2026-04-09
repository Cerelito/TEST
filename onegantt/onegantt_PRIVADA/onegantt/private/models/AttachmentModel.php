<?php
class AttachmentModel
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function byTask(int $tareaId): array
    {
        return $this->db->fetchAll(
            'SELECT a.*, u.nombre AS subido_por FROM attachments a
             JOIN users u ON u.id = a.usuario_id
             WHERE a.tarea_id = :t ORDER BY a.created_at DESC',
            [':t' => $tareaId]
        );
    }

    public function upload(int $tareaId, int $userId, array $file): string|false
    {
        if ($file['error'] !== UPLOAD_ERR_OK) return false;
        if ($file['size'] > MAX_UPLOAD_BYTES) return false;

        $finfo    = finfo_open(FILEINFO_MIME_TYPE);
        $mime     = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mime, ALLOWED_MIME, true)) return false;

        $ext       = pathinfo($file['name'], PATHINFO_EXTENSION);
        $diskName  = bin2hex(random_bytes(16)) . '.' . $ext;
        $dest      = UPLOAD_PATH . $diskName;

        if (!move_uploaded_file($file['tmp_name'], $dest)) return false;

        return $this->db->insert(
            'INSERT INTO attachments (tarea_id, usuario_id, nombre_orig, nombre_disco, mime_type, tamano)
             VALUES (:t, :u, :no, :nd, :m, :s)',
            [
                ':t'  => $tareaId,
                ':u'  => $userId,
                ':no' => $file['name'],
                ':nd' => $diskName,
                ':m'  => $mime,
                ':s'  => $file['size'],
            ]
        );
    }

    public function delete(int $id): bool
    {
        $att = $this->db->fetchOne('SELECT nombre_disco FROM attachments WHERE id = :id', [':id' => $id]);
        if (!$att) return false;
        @unlink(UPLOAD_PATH . $att['nombre_disco']);
        $this->db->execute('DELETE FROM attachments WHERE id = :id', [':id' => $id]);
        return true;
    }
}
