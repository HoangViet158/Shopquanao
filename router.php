
<!-- define('ROOT_PATH', __DIR__);

// Xử lý request
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Xử lý file tĩnh (CSS, JS, ảnh...)
$static_extensions = ['css', 'js', 'png', 'jpg', 'jpeg', 'gif', 'ico', 'webp', 'svg'];
$ext = pathinfo($request_uri, PATHINFO_EXTENSION);

if (in_array($ext, $static_extensions)) {
    $static_dirs = ['/upload/products', '/public']; // Các thư mục chứa file tĩnh
    
    foreach ($static_dirs as $dir) {
        $file_path = ROOT_PATH . $dir . str_replace($dir, '', $request_uri);
        if (file_exists($file_path)) {
            $mime_types = [
                'css' => 'text/css',
                'js' => 'application/javascript',
                'png' => 'image/png',
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'gif' => 'image/gif',
                'ico' => 'image/x-icon',
                'webp' => 'image/webp',
                'svg' => 'image/svg+xml'
            ];
            
            header('Content-Type: ' . ($mime_types[$ext] ?? 'text/plain'));
            readfile($file_path);
            exit;
        }
    }
    http_response_code(404);
    exit;
}

// Xử lý API requests
if (strpos($request_uri, '/api/') === 0) {
    require ROOT_PATH . '/admin/API/index.php';
    exit;
}
if (strpos($request_uri, '/admin/') === 0) {
    require ROOT_PATH . '/admin/View/index.php';
    exit;
}
//  Route mặc định
require ROOT_PATH . '/admin/View/index.php'; -->