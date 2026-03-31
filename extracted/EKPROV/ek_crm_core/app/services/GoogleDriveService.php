<?php
// app/services/GoogleDriveService.php

require_once ROOT_PATH . 'vendor/autoload.php';

class GoogleDriveService
{
    private $client;
    private $service;
    private $isConnected = false;

    public function __construct()
    {
        $this->client = new Google_Client();
        $this->client->setApplicationName(APP_NAME);
        $this->client->setScopes(Google_Service_Drive::DRIVE_FILE);

        if (file_exists(GOOGLE_DRIVE_CREDENTIALS)) {
            $this->client->setAuthConfig(GOOGLE_DRIVE_CREDENTIALS);
            $this->service = new Google_Service_Drive($this->client);
            $this->isConnected = true;
        } else {
            error_log("Google Drive Credentials not found at: " . GOOGLE_DRIVE_CREDENTIALS);
        }
    }

    public function isConnected()
    {
        return $this->isConnected;
    }

    /**
     * Create a folder (or return existing one if name exists in parent)
     */
    public function createFolder($folderName, $parentId = null)
    {
        if (!$this->isConnected) {
            throw new Exception("GoogleDriveService no está conectado. Verifique credentials.json.");
        }

        if ($parentId === null) {
            $parentId = defined('GOOGLE_DRIVE_ROOT_FOLDER_ID') ? GOOGLE_DRIVE_ROOT_FOLDER_ID : 'root';
        }

        error_log("[DRIVE] Creating folder: '$folderName' in parent: $parentId");

        // Check if folder already exists
        $existingId = $this->findFileIdByName($folderName, $parentId, true);
        if ($existingId) {
            return $existingId;
        }

        $fileMetadata = new Google_Service_Drive_DriveFile([
            'name' => $folderName,
            'mimeType' => 'application/vnd.google-apps.folder',
            'parents' => [$parentId]
        ]);

        try {
            $file = $this->service->files->create($fileMetadata, [
                'fields' => 'id',
                'supportsAllDrives' => true
            ]);
            error_log("[DRIVE] Folder created SUCCESS: " . $file->id);
            return $file->id;
        } catch (Exception $e) {
            throw new Exception("Error al crear carpeta '$folderName' en Drive: " . $e->getMessage());
        }
    }

    /**
     * Upload a file to a specific folder
     */
    public function uploadFile($localFilePath, $parentId, $destName = null, $mimeType = null)
    {
        if (!$this->isConnected) {
            throw new Exception("GoogleDriveService no está conectado al intentar subir '$destName'.");
        }
        if (!file_exists($localFilePath)) {
            throw new Exception("El archivo local no existe para subir: $localFilePath");
        }

        if ($destName === null) {
            $destName = basename($localFilePath);
        }

        // Basic MIME type detection if not provided
        if ($mimeType === null) {
            $mimeType = mime_content_type($localFilePath) ?: 'application/octet-stream';
        }

        error_log("[DRIVE] Uploading: '$destName' to parent: $parentId (mime: $mimeType)");

        $fileMetadata = new Google_Service_Drive_DriveFile([
            'name' => $destName,
            'parents' => [$parentId]
        ]);

        $content = file_get_contents($localFilePath);

        try {
            $file = $this->service->files->create($fileMetadata, [
                'data' => $content,
                'mimeType' => $mimeType,
                'uploadType' => 'multipart',
                'fields' => 'id',
                'supportsAllDrives' => true
            ]);
            error_log("[DRIVE] Upload SUCCESS: '$destName' => ID: " . $file->id);
            return $file->id;
        } catch (Exception $e) {
            throw new Exception("Error al subir archivo '$destName' a Drive: " . $e->getMessage());
        }
    }

    /**
     * Find a file or folder ID by name within a parent folder
     */
    public function findFileIdByName($name, $parentId, $isFolder = false)
    {
        if (!$this->isConnected)
            return null;

        $q = "name = '$name' and '$parentId' in parents and trashed = false";
        if ($isFolder) {
            $q .= " and mimeType = 'application/vnd.google-apps.folder'";
        }

        try {
            $files = $this->service->files->listFiles([
                'q' => $q,
                'spaces' => 'drive',
                'fields' => 'files(id, name)',
                'pageSize' => 1,
                'supportsAllDrives' => true,
                'includeItemsFromAllDrives' => true
            ]);

            if (count($files->files) == 0) {
                return null;
            }

            return $files->files[0]->id;
        } catch (Exception $e) {
            error_log("Error searching file '$name': " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get file content stream (for viewing/downloading)
     */
    public function getFileContent($fileId)
    {
        if (!$this->isConnected)
            return null;

        try {
            $response = $this->service->files->get($fileId, [
                'alt' => 'media',
                'supportsAllDrives' => true
            ]);
            return $response->getBody()->getContents();
        } catch (Exception $e) {
            error_log("Error getting file content '$fileId': " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get file metadata
     */
    public function getFileMetadata($fileId)
    {
        if (!$this->isConnected)
            return null;

        try {
            return $this->service->files->get($fileId, [
                'fields' => 'id, name, mimeType, size',
                'supportsAllDrives' => true
            ]);
        } catch (Exception $e) {
            error_log("Error getting metadata '$fileId': " . $e->getMessage());
            return null;
        }
    }

    /**
     * Rename a file or folder
     */
    public function renameFile($fileId, $newName)
    {
        if (!$this->isConnected)
            return false;

        $fileMetadata = new Google_Service_Drive_DriveFile([
            'name' => $newName
        ]);

        try {
            $this->service->files->update($fileId, $fileMetadata, [
                'fields' => 'id, name',
                'supportsAllDrives' => true
            ]);
            return true;
        } catch (Exception $e) {
            error_log("Error renaming file '$fileId': " . $e->getMessage());
            return false;
        }
    }
}
