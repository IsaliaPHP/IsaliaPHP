<?php

class UploadController extends AdminController
{
    public function index()
    {
        $attachmentManager = new AttachmentManager();
        if (isset($_FILES['file'])) {
            try {
                $result = $attachmentManager->upload($_FILES['file']);
                if ($result) {
                    Flash::valid("Archivo $result subido correctamente");
                } else {
                    Flash::error('Error al subir el archivo');
                }
            } catch (Exception $e) {
                Flash::error($e->getMessage());
            }                
        }
    }

}
