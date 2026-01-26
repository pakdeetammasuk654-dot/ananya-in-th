<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>วิธีการทำบุญ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                fontFamily: { sans: ['Sarabun', 'sans-serif'] },
                extend: {
                    colors: {
                        primary: '#ff6f00'
                    }
                }
           }
        }
    </script>
    <style>
        body {
            -webkit-font-smoothing: antialiased;
            letter-spacing: 0.01em;
        }

        .content-body p {
            margin-bottom: 1.5rem;
            text-align: justify;
        }

        .header-text {
            font-size: 1.25rem;
            font-weight: 500;
            margin-top: 2rem;
            margin-bottom: 1rem;
            color: #333;
        }

        .sub-header-text {
            font-size: 1.15rem;
            font-weight: 500;
            margin-top: 2.5rem;
            margin-bottom: 1.5rem;
            color: #333;
        }
    </style>
</head>

<body class="bg-white text-[#444]">

    <div class="max-w-3xl mx-auto min-h-screen bg-white">

        <!-- Header Green Removed as per user request -->

        <div class="p-6 pt-8">
            <!-- Title (Orange) -->
            <h1 class="text-3xl font-bold text-primary mb-3">
                <?= htmlspecialchars($item['title'] ?? '') ?>
            </h1>

            <!-- Divider -->
            <div class="h-[1px] bg-gray-200 w-full mb-8"></div>

            <!-- Content -->
            <div class="content-body text-[1.1rem] leading-[1.8] font-light">
                <?php
                $desc = $item['desc'] ?? '';

                // Format Header @@@...@@@ (Big Header)
                $desc = preg_replace('/@@@(.*?)@@@/', '<div class="sub-header-text">$1</div>', $desc);

                // Format Header @@...@@ (Regular Header)
                $desc = preg_replace('/@@(.*?)@@/', '<div class="header-text">$1</div>', $desc);

                // Format Bold **...** 
                $desc = preg_replace('/\*\*(.*?)/', '<strong class="font-bold text-black">$1</strong>', $desc);

                echo nl2br($desc);
                ?>
            </div>

            <div class="h-24"></div>
        </div>
    </div>

</body>

</html>