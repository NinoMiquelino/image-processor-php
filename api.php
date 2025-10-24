<?php
// Configurações básicas e headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-Requested-With');

// ATENÇÃO: Verifique se a extensão GD está habilitada no seu php.ini!
// (Geralmente vem como 'extension=gd' ou 'extension=php_gd.dll')

const UPLOADS_DIR = __DIR__ . '/uploads/';
const THUMBNAIL_WIDTH = 200; // Largura da miniatura

/**
 * Cria a pasta de uploads se não existir.
 */
function checkUploadsDir() {
    if (!is_dir(UPLOADS_DIR)) {
        if (!mkdir(UPLOADS_DIR, 0777, true)) {
            throw new Exception("Falha ao criar o diretório de uploads. Verifique as permissões!");
        }
    }
}

/**
 * Carrega a imagem do disco e cria um recurso de imagem GD.
 * @param string $filePath Caminho para o arquivo.
 * @param string $mimeType Tipo MIME do arquivo.
 * @return resource Recurso de imagem GD.
 */
function createImageResource($filePath, $mimeType) {
    if ($mimeType === 'image/jpeg' || $mimeType === 'image/jpg') {
        return imagecreatefromjpeg($filePath);
    } elseif ($mimeType === 'image/png') {
        return imagecreatefrompng($filePath);
    } else {
        throw new Exception("Tipo de arquivo ($mimeType) não suportado para manipulação GD.");
    }
}

/**
 * Salva o recurso de imagem GD no disco no formato original.
 * @param resource $image Recurso de imagem GD.
 * @param string $filePath Caminho para salvar.
 * @param string $mimeType Tipo MIME original.
 */
function saveImageResource($image, $filePath, $mimeType) {
    if ($mimeType === 'image/jpeg' || $mimeType === 'image/jpg') {
        imagejpeg($image, $filePath, 90); // Qualidade 90
    } elseif ($mimeType === 'image/png') {
        imagepng($image, $filePath, 9); // Nível de compressão 9
    }
}

// --- Funções de Processamento ---

/**
 * Redimensiona a imagem para criar uma miniatura.
 */
function createThumbnail($srcPath, $destPath, $mimeType) {
    $srcImage = createImageResource($srcPath, $mimeType);
    $originalWidth = imagesx($srcImage);
    $originalHeight = imagesy($srcImage);
    
    // Calcula a nova altura mantendo a proporção
    $newWidth = THUMBNAIL_WIDTH;
    $newHeight = floor(($originalHeight / $originalWidth) * $newWidth);

    // Cria uma nova imagem em branco para o redimensionamento
    $newImage = imagecreatetruecolor($newWidth, $newHeight);
    
    // Resampling para redimensionar com qualidade
    imagecopyresampled($newImage, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);
    
    // Salva a miniatura
    saveImageResource($newImage, $destPath, $mimeType);

    // Libera a memória
    imagedestroy($srcImage);
    imagedestroy($newImage);
}

/**
 * Adiciona uma marca d'água de texto.
 */
function applyWatermark($srcPath, $destPath, $mimeType, $text = 'PHP PROCESSADO') {
    // Cria uma cópia da imagem original para trabalhar (pois a original já foi salva)
    copy($srcPath, $destPath);
    
    $image = createImageResource($destPath, $mimeType);
    $width = imagesx($image);
    $height = imagesy($image);
    
    // Define a cor e o tamanho da fonte (usando uma fonte GD interna)
    $textColor = imagecolorallocate($image, 255, 255, 255); // Branco
    $fontSize = 5; // Tamanho 5 é o maior built-in
    $x = $width - 150; // Posição X (canto inferior direito)
    $y = $height - 15; // Posição Y (canto inferior direito)

    // Adiciona o texto
    imagestring($image, $fontSize, $x, $y, $text, $textColor);
    
    // Salva a imagem com marca d'água
    saveImageResource($image, $destPath, $mimeType);

    // Libera a memória
    imagedestroy($image);
}


// --- Lógica Principal da API ---

$method = $_SERVER['REQUEST_METHOD'] ?? '';

if ($method === 'POST') {
    $result = ['success' => false, 'paths' => [], 'error' => ''];
    checkUploadsDir();

    try {
        if (empty($_FILES['image_file'])) {
            throw new Exception("Nenhum arquivo foi enviado.");
        }
        
        $file = $_FILES['image_file'];
        
        // 1. Verificações básicas
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Erro de upload: Código " . $file['error']);
        }
        $mimeType = mime_content_type($file['tmp_name']);
        if (!in_array($mimeType, ['image/jpeg', 'image/png', 'image/jpg'])) {
             throw new Exception("Tipo de arquivo não permitido. Apenas JPG/PNG são aceitos.");
        }
        
        // Gera um nome único para o arquivo base
        $fileExt = pathinfo($file['name'], PATHINFO_EXTENSION);
        $baseName = uniqid('img_', true);
        
        $originalFileName = $baseName . '_orig.' . $fileExt;
        $thumbFileName = $baseName . '_thumb.' . $fileExt;
        $watermarkFileName = $baseName . '_wm.' . $fileExt;

        $originalPath = UPLOADS_DIR . $originalFileName;
        
        // 2. Salva a imagem original
        if (!move_uploaded_file($file['tmp_name'], $originalPath)) {
            throw new Exception("Falha ao mover o arquivo de upload. (Verifique as permissões!)");
        }

        // 3. Cria o Thumbnail
        createThumbnail($originalPath, UPLOADS_DIR . $thumbFileName, $mimeType);

        // 4. Aplica a Marca d'Água
        applyWatermark($originalPath, UPLOADS_DIR . $watermarkFileName, $mimeType);

        // 5. Retorna os caminhos das imagens processadas
        $result['success'] = true;
        /*
        $result['paths'] = [
            'original' => 'src/uploads/' . $originalFileName,
            'thumbnail' => 'src/uploads/' . $thumbFileName,
            'watermark' => 'src/uploads/' . $watermarkFileName,
        ];
        */
        $result['paths'] = [
            'original' => 'uploads/' . $originalFileName,
            'thumbnail' => 'uploads/' . $thumbFileName,
            'watermark' => 'uploads/' . $watermarkFileName,
        ];             
    } catch (Exception $e) {
        http_response_code(400);
        $result['error'] = $e->getMessage();
    }

    echo json_encode($result);
    
} elseif ($method === 'OPTIONS') {
    http_response_code(200);
    exit();
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Método não permitido.']);
}
