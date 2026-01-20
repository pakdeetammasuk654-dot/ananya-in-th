<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Library - Ananya</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
        }

        .main-wrapper {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            background: white;
            padding: 1.5rem 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .header h1 {
            margin: 0;
            font-size: 1.5rem;
            color: #333;
        }

        .upload-area {
            background: #e9ecef;
            border: 2px dashed #ced4da;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            margin-bottom: 2rem;
            transition: all 0.3s;
        }

        .upload-area:hover {
            border-color: #198754;
            background: #f1f8f5;
        }

        .images-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }

        .img-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s;
            position: relative;
        }

        .img-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .img-preview {
            height: 150px;
            width: 100%;
            object-fit: cover;
            background: #eee;
            border-bottom: 1px solid #eee;
        }

        .img-info {
            padding: 10px;
        }

        .img-name {
            font-size: 0.85rem;
            color: #555;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 5px;
        }

        .img-actions {
            display: flex;
            gap: 5px;
            margin-top: 5px;
        }

        .btn {
            padding: 8px 15px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 0.9rem;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: #0d6efd;
            color: white;
        }

        .btn-success {
            background: #198754;
            color: white;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-sm {
            font-size: 0.75rem;
            padding: 4px 8px;
        }

        .btn-outline {
            border: 1px solid #ddd;
            background: white;
            color: #555;
        }

        .btn-outline:hover {
            background: #f8f9fa;
            border-color: #ccc;
        }

        .copy-feedback {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #333;
            color: white;
            padding: 10px 20px;
            border-radius: 50px;
            opacity: 0;
            transition: opacity 0.3s;
            pointer-events: none;
            z-index: 999;
        }
    </style>
</head>

<body>
    <?php include 'web_menu.php'; ?>

    <div class="main-wrapper">
        <div class="header">
            <div>
                <h1>คลังรูปภาพ (Image Library)</h1>
                <p style="margin:5px 0 0 0; color:#888;">จัดการรูปภาพทั้งหมดในระบบ</p>
            </div>
            <a href="/web/admin/articles" class="btn btn-outline">&larr; กลับไปจัดการบทความ</a>
        </div>

        <!-- Upload Form -->
        <div class="upload-area">
            <h3 style="margin-top:0;">อัปโหลดรูปภาพใหม่</h3>
            <form action="/web/admin/images/upload" method="POST" enctype="multipart/form-data">
                <input type="file" name="image" accept="image/*" required id="fileInput" style="display:none;">
                <label for="fileInput" class="btn btn-success" style="padding:10px 30px; font-size:1.1rem;">
                    📁 เลือกไฟล์รูปภาพ (Choose File)
                </label>
                <div id="fileName" style="margin-top:10px; color:#666;"></div>
                <div style="margin-top:15px;">
                    <button type="submit" class="btn btn-primary">เริ่มอัปโหลด (Upload Now)</button>
                </div>
            </form>
        </div>

        <script>
            document.getElementById('fileInput').addEventListener('change', function  ( ) {
                 if (this.files && this.files.length >  0) {
                    document.getElementById('fileName').innerText = 'Selected: ' + this.files[0].name;
                }
            });
        </script>

        <!-- Image Grid -->
        <h3 style="margin-bottom:15px; color:#444;">รายการรูปภาพ (
            <?php echo count($images); ?> files)
        </h3>

        <?php if (empty($images)): ?>
            <div style="text-align:center; padding:50px; color:#999; background:white; border-radius:10px;">
                ยังไม่มีรูปภาพในระบบ
            </div>
        <?php else: ?>
            <div class="images-grid">
                <?php foreach ($images as $img): ?>
                    <div class="img-card">
                        <a href="<?php echo $img['url']; ?>" target="_blank">
                            <!-- Use /uploads/ path directly if symlink exists -->
                            <img src="<?php echo $img['url']; ?>" class="img-preview" loading="lazy"
                                alt="<?php echo $img['name']; ?>">
                        </a>
                        <div class="img-info">
                            <div class="img-name" title="<?php echo $img['name']; ?>">
                                <?php echo $img['name']; ?>
                            </div>
                            <div style="font-size:0.75rem; color:#999; margin-bottom:8px;">
                                <?php echo date('d/m/Y H:i', $img['time']); ?> •
                                <?php echo round($img['size'] / 1024, 1); ?> KB
                            </div>
                            <div class="img-actions">
                                <button onclick="copyToClipboard('<?php echo $img['url']; ?>')" class="btn btn-sm btn-outline"
                                    style="flex:1;">
                                    📋 Copy Link
                                </button>
                                <a href="/web/admin/images/confirm-delete?name=<?php echo urlencode($img['name']); ?>"
                                    class="btn btn-sm btn-danger">
                                    🗑️
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div id="toast" class="copy-feedback">Copied URL to clipboard!</div>

    <script>
        function copyToClipboard(text) {
            // Ensure path is absolute if needed, but relative /uploads/ is usually best for portability
            navigator.clipboard.writeText(text).then(func tion () {
                const toast = document.getElementById('toast');
                toast.style.opacity = '1';
                setTimeout(() => { toast.style.opacity = '0'; }, 2000);
            }, fun ction (err) {
                console.error('Async: Could not copy text: ', err);
            });
    }
    </script>
</body>

</html>