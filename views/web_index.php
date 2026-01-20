<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Number เลขศาสตร์ พลังเงา - Google Play Store</title>
    <link rel="icon" type="image/png" href="/favicon.png">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600&family=Sarabun:wght@300;400;500&display=swap"
        rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --gp-green: #01875f;
            --gp-text-black: #202124;
            --gp-text-gray: #5f6368;
            --gp-bg: #ffffff;
            --gp-border: #dadce0;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Sarabun', sans-serif;
            background-color: var(--gp-bg);
            color: var(--gp-text-black);
            line-height: 1.5;
        }

        /* --- Store Content Layout --- */
        .store-container {
            max-width: 1040px;
            margin: 0 auto;
            padding: 2rem 1.5rem;
        }

        /* --- Hero / Header Section --- */
        .app-hero {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 2.5rem;
            gap: 2rem;
        }

        .app-info {
            flex: 1;
        }

        .app-title {
            font-family: 'Kanit', sans-serif;
            font-size: 2.75rem;
            font-weight: 500;
            margin: 0 0 0.5rem 0;
            color: var(--gp-text-black);
        }

        .app-developer {
            color: var(--gp-green);
            text-decoration: none;
            font-size: 1.1rem;
            font-weight: 500;
            font-family: 'Kanit', sans-serif;
        }

        .app-developer:hover {
            text-decoration: underline;
        }

        .app-icon-container {
            width: 192px;
            height: 192px;
            border-radius: 36px;
            box-shadow: 0 1px 2px 0 rgba(60, 64, 67, .3), 0 1px 3px 1px rgba(60, 64, 67, .15);
            overflow: hidden;
            flex-shrink: 0;
            background: white;
        }

        .app-icon-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* --- Stats Row --- */
        .stats-row {
            display: flex;
            gap: 2rem;
            margin: 1.5rem 0;
            align-items: center;
        }

        .stat-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            border-right: 1px solid var(--gp-border);
            padding-right: 2rem;
        }

        .stat-item:last-child {
            border-right: none;
        }

        .stat-value {
            font-weight: 600;
            font-size: 1.1rem;
        }

        .stat-label {
            font-size: 0.8rem;
            color: var(--gp-text-gray);
            margin-top: 4px;
        }

        /* --- Action Buttons --- */
        .actions-row {
            display: flex;
            gap: 1rem;
            align-items: center;
            margin-top: 2rem;
        }

        .btn-install {
            background-color: var(--gp-green);
            color: white;
            padding: 0.6rem 2.5rem;
            border-radius: 24px;
            font-weight: 500;
            font-family: 'Kanit', sans-serif;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-install:hover {
            background-color: #037050;
        }

        .btn-share {
            background: none;
            border: none;
            color: var(--gp-green);
            font-size: 1.25rem;
            cursor: pointer;
            padding: 0.5rem;
        }

        /* --- Availability Bar --- */
        .availability-bar {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-top: 1.5rem;
            font-size: 0.9rem;
            color: var(--gp-text-gray);
        }

        .availability-bar i {
            font-size: 1.1rem;
        }

        /* --- Screenshots Section --- */
        .screenshots-section {
            margin: 4rem 0;
        }

        .screenshots-scroll {
            display: flex;
            gap: 1rem;
            overflow-x: auto;
            padding-bottom: 1.5rem;
            scrollbar-width: thin;
        }

        .screenshot-item {
            flex-shrink: 0;
            width: 200px;
            height: 355px;
            /* Aspect 9:16 approx scaled down */
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid var(--gp-border);
            box-shadow: 0 1px 2px 0 rgba(60, 64, 67, .1);
        }

        .screenshot-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* --- Content Sections --- */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.25rem;
        }

        .section-title {
            font-family: 'Kanit', sans-serif;
            font-size: 1.25rem;
            font-weight: 500;
        }

        .content-text {
            color: var(--gp-text-gray);
            font-size: 0.95rem;
            line-height: 1.6;
            white-space: pre-line;
            margin-bottom: 2rem;
        }

        /* --- Responsive --- */
        @media (max-width: 768px) {
            .app-hero {
                flex-direction: column-reverse;
                align-items: center;
                text-align: center;
            }

            .app-title {
                font-size: 2rem;
            }

            .stats-row {
                justify-content: center;
            }

            .actions-row {
                justify-content: center;
            }

            .availability-bar {
                justify-content: center;
            }
        }

        /* --- Footer --- */
        .store-footer {
            margin-top: 5rem;
            padding-top: 2rem;
            border-top: 1px solid var(--gp-border);
            text-align: center;
            padding-bottom: 3rem;
        }
    </style>
</head>

<body>

    <?php include 'web_menu.php'; ?>

    <main class="store-container">
        <!-- Hero Section -->
        <section class="app-hero">
            <div class="app-info">
                <h1 class="app-title">Number เลขศาสตร์ พลังเงา</h1>
                <a href="#" class="app-developer">numberniceic</a>

                <div class="stats-row">
                    <div class="stat-item">
                        <span class="stat-value"><?php echo $appStats['downloads'] ?? '1+'; ?></span>
                        <span class="stat-label">Downloads</span>
                    </div>
                    <?php if (!empty($appStats['rating']) && $appStats['rating'] > 0): ?>
                        <div class="stat-item">
                            <span class="stat-value"><?php echo $appStats['rating']; ?> <i class="fa-solid fa-star"
                                    style="font-size:0.8rem; color:#FFD700"></i></span>
                            <span class="stat-label">Rating</span>
                        </div>
                    <?php endif; ?>
                    <div class="stat-item">
                        <span class="stat-value" style="display:flex; align-items:center; gap:4px;">
                            3+ <i class="fa-solid fa-square-info" style="font-size:0.8rem"></i>
                        </span>
                        <span class="stat-label">Rated for 3+ <i class="fa-solid fa-circle-info"></i></span>
                    </div>
                </div>

                <div class="actions-row">
                    <a href="https://play.google.com/store/apps/details?id=com.numberniceic" target="_blank"
                        class="btn-install">Install on more devices</a>
                    <button class="btn-share"><i class="fa-solid fa-share-nodes"></i></button>
                </div>

                <div class="availability-bar">
                    <i class="fa-solid fa-mobile-screen"></i>
                    <span>This app is available for all of your devices</span>
                </div>
            </div>

            <div class="app-icon-container">
                <img src="https://play-lh.googleusercontent.com/1zRGPJdAu_e7XrS-vC0cPEdrrEF5xhZ96Wm2VTzSUAgFihw36gGpwvt_dENOHY7A6DkDSDpFB2gpuKiNKg94=s512-rw"
                    alt="App Icon">
            </div>
        </section>

        <!-- Screenshots Carousel -->
        <section class="screenshots-section">
            <div class="screenshots-scroll">
                <div class="screenshot-item"><img
                        src="https://play-lh.googleusercontent.com/UG_lPrKiwEIgsmbP8ixOvm0iVVGrRDp0V1a-0u6QlkVp7fIEHJggAMqvF8W1zbz3git5BBMUx1upl1vqHs1ynA=w1052-h592-rw"
                        alt="Screenshot 1"></div>
                <div class="screenshot-item"><img
                        src="https://play-lh.googleusercontent.com/ZMu30k6mgO3vdSneMM71mNXt4b6keurWCPONV8rBtWFcU8TUNHBkDrCxLJ5014c6Ab2ImACm8q5K9cQJfP2d=w1052-h592-rw"
                        alt="Screenshot 2"></div>
                <div class="screenshot-item"><img
                        src="https://play-lh.googleusercontent.com/hygSqXPTp7SupKIYhSJ3Aom0rW2E7W4s3dNN8T0uXxuCgWMn9EXdE_5UnGz3uNJmkZEPsYsct-9uHVJGf-8k2QA=w1052-h592-rw"
                        alt="Screenshot 3"></div>
                <div class="screenshot-item"><img
                        src="https://play-lh.googleusercontent.com/e0m8tzxuzcLRNAe7gkKejskRFab1L74WiLpjl3zkd1WyNXOEHjcL-h9lQchpMJSwvMNOgRM8xBxujx3-gaxG5iQ=w1052-h592-rw"
                        alt="Screenshot 4"></div>
                <div class="screenshot-item"><img
                        src="https://play-lh.googleusercontent.com/guG2mfzkBc_J46VnzfGrZNsRGQpOxsLwyupgfb6kcqAbMdtox9onZBa1KaYPIZ8lz489WAFPNPX75XGEaKgmNlU=w1052-h592-rw"
                        alt="Screenshot 5"></div>
                <div class="screenshot-item"><img
                        src="https://play-lh.googleusercontent.com/Yg_2fQEuo385emuQGkHICXdZ9FPF0QU5CkngffSdx8oMuBHhTB4RdVqfy11kwwJisPal2Uew2Aqoh28lry9s=w1052-h592-rw"
                        alt="Screenshot 6"></div>
            </div>
        </section>

        <!-- Summary / What's New Section -->
        <section class="content-section">
            <div class="section-header">
                <h2 class="section-title">What's new</h2>
                <i class="fa-solid fa-arrow-right" style="color:var(--gp-text-gray)"></i>
            </div>
            <div class="content-text">
                รับเปิดดวงชะตาดูดวงประจำปีเพื่อให้รู้ดีรู้ร้ายล่วงหน้าและเพื่อเตรียมการป้องกัน แก้ไข
                และเปลี่ยนแปลงเลขเปลี่ยนแปลงดวงชะตาตามวาระที่คู่ควร เจาะพื้นดวงและเจาะเลข วิเคราะห์ดวง วางเลขวางเบอร์
                วางเลขเบอร์โทรศัพท์ทะเบียนรถและบ้านเลขที่มงคลตรงตามพื้นดวงและโฉลกดวง
                ตั้งชื่อเล่นชื่อจริงนามสกุลมงคลทั้งเด็กและผู้ใหญ่ตรงตามพื้นดวงและโฉลกดวงชะตา
                เบอร์โทรศัพท์มงคลทุกเบอร์มีจริง
                ทะเบียนรถมงคลทุกป้ายมีจริงออกโดยกรมขนส่งอย่างถูกต้องตามกฎหมายและสามารถใช้ได้กับรถใหม่ป้ายแดง
                หรือรถที่มีเลขทะเบียนอยู่แล้วได้จริง
            </div>
        </section>

        <!-- Pinned Articles / News section (Styled like "Similar apps") -->
        <section class="content-section">
            <div class="section-header">
                <h2 class="section-title">บทความแนะนำ</h2>
                <a href="/articles"
                    style="color:var(--gp-green); text-decoration:none; font-weight:500; font-size:0.9rem;">ดูทั้งหมด</a>
            </div>
            <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
                <?php foreach ($pinnedArticles ?: [] as $a): ?>
                    <a href="/articles/<?php echo $a->slug; ?>"
                        style="text-decoration:none; display:flex; gap:1rem; align-items:center;">
                        <div
                            style="width:64px; height:64px; border-radius:12px; overflow:hidden; border:1px solid var(--gp-border); flex-shrink:0;">
                            <img src="<?php echo $a->image_url; ?>" style="width:100%; height:100%; object-fit:cover;">
                        </div>
                        <div style="overflow:hidden;">
                            <div
                                style="font-family:'Kanit',sans-serif; color:var(--gp-text-black); font-size:1rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                <?php echo $a->title; ?>
                            </div>
                            <div style="color:var(--gp-text-gray); font-size:0.85rem; margin-top:2px;">
                                <?php echo $a->category; ?>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>

        <footer class="store-footer">
            <p style="color:var(--gp-text-gray); font-size:0.9rem;">&copy; <?php echo date('Y'); ?> Number เลขศาสตร์
                พลังเงา. พัฒนาโดย numberniceic</p>
        </footer>
    </main>

</body>

</html>