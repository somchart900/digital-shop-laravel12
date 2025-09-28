<?php

use function Safe\unlink;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
function getCurrentEnvironment()
{
    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || $_SERVER['SERVER_PORT'] == 443;

    return $isHttps ? 'production' : 'local';
}
function getCurrentBaseUrl()
{
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST']; // รวม domain + port เช่น localhost:8000

    return $scheme . '://' . $host;
}


function createDatabaseFile($host, $dbname, $username, $password, $appurl, $appenv)
{
    //$envPath = __DIR__ . '/../.env';
    $envPath = __DIR__ . '/.env';
    if ($appenv == 'production') {
        $debugs = 'false';
    } else {
        $debugs  = 'true';
    }


    $env = "
APP_NAME=Laravel
APP_ENV={$appenv}
APP_KEY=base64:pyh+WcN7++56YISFvv5F9T4MQJyNvqx5nGkVUgX/IWg=
APP_DEBUG={$debugs}
APP_URL={$appurl}
APP_TIMEZONE=Asia/Bangkok

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file


PHP_CLI_SERVER_WORKERS=4

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST={$host}
DB_PORT=3306
DB_DATABASE={$dbname}
DB_USERNAME={$username}
DB_PASSWORD={$password}

SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=true
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync

CACHE_STORE=file
# CACHE_PREFIX=

#MEMCACHED_HOST=127.0.0.1

#REDIS_CLIENT=phpredis
#REDIS_HOST=127.0.0.1
#REDIS_PASSWORD=null
#REDIS_PORT=6379

#MAIL_MAILER=smtp
#MAIL_HOST=smtp.gmail.com
#MAIL_PORT=587
#MAIL_USERNAME=null
#MAIL_PASSWORD=null
#MAIL_ENCRYPTION=tls
#MAIL_FROM_ADDRESS=\${APP_NAME}@gmail.com
#MAIL_FROM_NAME=\${APP_NAME}


";



    $result = file_put_contents($envPath, $env);

    return $result !== false;
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = $_POST['host'];
    $username = $_POST['username'];
    $password = $_POST['password-db'];
    $dbname = $_POST['dbname'];
    $appurl = getCurrentBaseUrl();
    // เขียนข้อมูลลงในไฟล์ .env
    $appenv = getCurrentEnvironment();


    $conn = new mysqli($host, $username, $password);
    $conn->set_charset('utf8mb4');
    $conn->query("SET time_zone = '+07:00'"); // ตั้งค่าโซนเวลา mysql เป็น +07:00 (ประเทศไทย)
    // ตรวจสอบการเชื่อมต่อ
    if ($conn->connect_error) {
        die(' Connection failed: ' . $conn->connect_error);
        exit;
    }
    // สร้างฐานข้อมูล
    $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
    if ($conn->query($sql) === TRUE) {
    } else {
        echo " เกิดข้อผิดพลาดในการสร้างฐานข้อมูล: " . $conn->error . "<br>";
        exit;
    }
    // ใช้งานฐานข้อมูล
    $conn->select_db($dbname);

    // สร้างตาราง users
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        `password` VARCHAR(255) NOT NULL, 
        email VARCHAR(100) NOT NULL UNIQUE,
        email_verified_at DATETIME DEFAULT NULL, 
        `level` int(1) DEFAULT 1,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    if ($conn->query($sql)) {
        // echo " สร้างตาราง users สำเร็จ.<br>";
    } else {
        echo " เกิดข้อผิดพลาดในการสร้างตาราง users ";
        exit;
    }

    // สร้างตาราง otp
    $otp = "CREATE TABLE IF NOT EXISTS otps (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        otp VARCHAR(6) NOT NULL,
        otp_expired DATETIME DEFAULT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
   )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    if ($conn->query($otp) === TRUE) {
        //     echo "ตาราง 'otp' สร้างสำเร็จ.<br>";
    } else {
        echo "เกิดข้อผิดพลาดในการสร้างตาราง 'otp': " . $conn->error . "<br>";
        exit;
    }


    // สร้างตาราง credits
    $credits = "CREATE TABLE IF NOT EXISTS credits (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        amount DECIMAL(10, 2) NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    if ($conn->query($credits) === TRUE) {
        //     echo "ตาราง 'credits' สร้างสำเร็จ.<br>";
    } else {
        echo "เกิดข้อผิดพลาดในการสร้างตาราง 'credits': " . $conn->error . "<br>";
        exit;
    }
    // สร้างตาราง topups
    $topup = "CREATE TABLE IF NOT EXISTS topups (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        channel VARCHAR(100),   
        status VARCHAR(100),
        amount DECIMAL(10, 2) NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        link VARCHAR(255) NOT NULL,  
        remark VARCHAR(255),    
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    if ($conn->query($topup) === TRUE) {
        //     echo "ตาราง 'topup' สร้างสำเร็จ.<br>";
    } else {
        echo "เกิดข้อผิดพลาดในการสร้างตาราง 'topup': " . $conn->error . "<br>";
        exit;
    }

    // สร้างตาราง setting
    $setting = "CREATE TABLE IF NOT EXISTS settings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        `name` VARCHAR(100) NOT NULL,
        `value` VARCHAR(300) NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    if ($conn->query($setting) === TRUE) {
        // echo "เพิ่มตาราง '' เรียบร้อยแล้ว.<br>";
    } else {
        echo "เกิดข้อผิดพลาดในการสร้างตาราง 'setting': " . $conn->error . "<br>";
        exit;
    }

    // สร้างตาราง categories
    $categories = "CREATE TABLE IF NOT EXISTS categories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        `name` VARCHAR(255) NOT NULL UNIQUE, -- ชื่อหมวดหมู่
        img_link VARCHAR(255), -- ลิงก์ไฟล์รูปสินค้า
        `description` TEXT, -- คำอธิบายเพิ่มเติม
        is_featured TINYINT(1) DEFAULT 0, -- สินค้าแนะนำ (1 = ใช้งาน, 0 = ไม่ใช้งาน)
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP        
    )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    if ($conn->query($categories) === TRUE) {
        // echo "เพิ่มตาราง '' เรียบร้อยแล้ว.<br>";
    } else {
        echo "เกิดข้อผิดพลาดในการสร้างตาราง 'categories': " . $conn->error . "<br>";
        exit;
    }


    // สร้างตาราง products
    $products = "CREATE TABLE IF NOT EXISTS products (
        id INT AUTO_INCREMENT PRIMARY KEY,
        category_id INT, -- เชื่อมกับชนิดสินค้า
        `name` VARCHAR(255) NOT NULL, -- ชื่อสินค้า
        `description` TEXT, -- รายละเอียดสินค้า
        img_link VARCHAR(255), -- ลิงก์ไฟล์รูปสินค้า
        price DECIMAL(10,2) NOT NULL, -- ราคา
        is_featured TINYINT(1) DEFAULT 0, -- สินค้าแนะนำ (1 = ใช้งาน, 0 = ไม่ใช้งาน)
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
    )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    if ($conn->query($products) === TRUE) {
        // echo "เพิ่มตาราง '' เรียบร้อยแล้ว.<br>";
    } else {
        echo "เกิดข้อผิดพลาดในการสร้างตาราง 'products': " . $conn->error . "<br>";
        $error_message = 'มีบางอย่างผิดพลาด';
    }
    // สร้างตาราง items
    $items = "CREATE TABLE IF NOT EXISTS items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        category_id INT, -- เชื่อมกับหมวดหมู่สินค้า
        product_id INT, -- เชื่อมกับชนิดสินค้า
       `name` VARCHAR(255) NOT NULL, -- ชื่อสินค้า
       `description` TEXT, -- คำอธิบาย
        price DECIMAL(10,2) NOT NULL, -- ราคา
        code VARCHAR(255), -- ถ้ามีโค้ดดิจิตอล เช่น Key เกม
        youtube VARCHAR(255), -- ลิ้งยูทูป
        img_link VARCHAR(255), -- ลิงก์ไฟล์รูปสินค้า
        article TEXT, -- รายละเอียดสินค้าเชิงลึก
        external_link VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    if ($conn->query($items) === TRUE) {
        // echo "เพิ่มตาราง 'items' เรียบร้อยแล้ว.<br>";
    } else {
        echo "เกิดข้อผิดพลาดในการสร้างตาราง 'items': " . $conn->error . "<br>";
        $error_message = 'มีบางอย่างผิดพลาด';
    }
    //--------------------------
    //--------------------------
    // สร้างตาราง orders
    $orders = "CREATE TABLE IF NOT EXISTS orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        category_id INT, -- เชื่อมกับหมวดหมู่สินค้า
        product_id INT, -- เชื่อมกับชนิดสินค้า
        user_id INT NOT NULL, 
        username VARCHAR(255), -- ใครซื้อไป      
        `name` VARCHAR(255) NOT NULL, -- ชื่อสินค้า (สำรองไว้หากลบจาก products)
        description TEXT, -- รายละเอียดสินค้า
        price DECIMAL(10,2) NOT NULL, -- ราคาที่ซื้อ
        code VARCHAR(255), -- โค้ดดิจิตอล เช่น Key เกม
        youtube VARCHAR(255), -- ลิ้งยูทูป
        img_link VARCHAR(255), -- สำรองลิงก์ไฟล์รูป
        article TEXT, -- รายละเอียดสินค้าเชิงลึก
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- เวลาสร้างคำสั่งซื้อ  
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    if ($conn->query($orders) === TRUE) {
        // echo "เพิ่มตาราง '' เรียบร้อยแล้ว.<br>";
    } else {
        echo "เกิดข้อผิดพลาดในการสร้างตาราง 'orders': " . $conn->error . "<br>";
        exit;
    }
    //--------------------------
    // สร้างตาราง login_logs
    $login_logs = "CREATE TABLE IF NOT EXISTS loginlogs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        ip VARCHAR(45) NOT NULL,
        os TEXT NOT NULL,
        browser TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    if ($conn->query($login_logs) === TRUE) {
        // echo "เพิ่มตาราง '' เรียบร้อยแล้ว.<br>";
    } else {
        echo "เกิดข้อผิดพลาดในการสร้างตาราง 'login_logs': " . $conn->error . "<br>";
        exit;
    }
    //--------------------------
    $activity = "CREATE TABLE IF NOT EXISTS activitylogs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL, 
        `action` VARCHAR(255) NOT NULL, -- ชื่อการกระทํา
        `description` TEXT NOT NULL, 
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,       
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE    
    )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    if ($conn->query($activity) === TRUE) {
        // echo "เพิ่มตาราง activitylogs เรียบร้อยแล้ว.<br>";
    } else {
        echo "เกิดข้อผิดพลาดในการสร้างตาราง 'activitylogs': " . $conn->error . "<br>";
        exit;
    }

    //--------------------------
    $inboxs = "CREATE TABLE IF NOT EXISTS inboxs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,          -- ผู้รับข้อความ
        sender VARCHAR(255) NOT NULL DEFAULT 'System',         -- ผู้ส่ง (แอดมิน)
        message MEDIUMTEXT NOT NULL,         -- เนื้อหาข้อความ
        is_read DATETIME DEFAULT NULL, -- สถานะอ่าน (ว่าถูกอ่านแล้วหรือยัง ถ้า null คือยังไม่ถูกอ่าน)
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    if ($conn->query($inboxs) === TRUE) {
        // echo "เพิ่มตาราง '' เรียบร้อยแล้ว.<br>";
    } else {
        echo "เกิดข้อผิดพลาดในการสร้างตาราง 'inboxs': " . $conn->error . "<br>";
        exit;
    }
    //--------------------------
    //  สร้างผู้ใช้งานแอดมิน
    $admin_email = $_POST['email']; // อีเมลแอดมิน
    $admin_username = $_POST['admin']; // ชื่อผู้ใช้งานแอดมิน
    $admin_password = password_hash($_POST['password'], PASSWORD_DEFAULT); // แฮชรหัสผ่าน
    $del = $conn->prepare("DELETE FROM users WHERE username = ?");
    $del->bind_param("s", $admin_username);
    $del->execute();
    $del->close();
    $admin = "INSERT INTO users (username, password, email, level) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($admin);
    if ($stmt === false) {
        die("เตรียมคำสั่งล้มเหลว: " . $conn->error);
    }
    $level = 99;
    $stmt->bind_param("sssi", $admin_username, $admin_password, $admin_email, $level);
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            //  echo "เพิ่มผู้ใช้งานแอดมิน '$admin_username' เรียบร้อยแล้ว.<br>";
        }
    } else {
        echo "เกิดข้อผิดพลาดในการเพิ่มผู้ใช้งานแอดมิน: " . $stmt->error . "<br>";
        exit;
    }
    $stmt->close();


    $create = createDatabaseFile($host, $dbname, $username, $password, $appurl, $appenv);
    if ($create) {
        //     echo "<p> .env created successfully.</p>";
    } else {
        echo "<p>Failed to create .env</p>";
        exit;
    }
    $success_message = 'ทุกอย่างเสร็จสมบูรณ์';
    $conn->close();
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ติดตั้งฐานข้อมูล </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- รวม SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
</head>

<body class="d-flex flex-column min-vh-100">
    <!-- Dropdown Menu -->
    <nav class="navbar navbar-expand-sm navbar-dark bg-primary">
        <div class="container-fluid">
            <h1>ติดตั้งฐานข้อมูล</h1>
        </div>
    </nav>

    <main class="container my-4">
        <div class="container mt-5">
            <div class="card shadow-lg">
                <div class="card-body">
                    <h1 class="text-center text-primary mb-4">ติดตั้งฐานข้อมูล </h1>
                    <form id="myForm" action="" method="POST">
                        <div class="mb-3">
                            <label for="้host" class="form-label">host db</label>
                            <input type="text" class="form-control" id="host" name="host" required>
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">username db</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password-db" class="form-label">password db</label>
                            <input type="password" class="form-control" id="password-db" name="password-db">
                        </div>
                        <div class="mb-3">
                            <label for="dbname" class="form-label">Database Name</label>
                            <input type="text" class="form-control" id="dbname" name="dbname" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">กำหนด email ของแอดมินระบบ</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="admin" class="form-label">กำหนด username สำหรับแอดมินระบบ</label>
                            <input type="text" class="form-control" id="admin" name="admin" required>
                        </div>
                        <div class="mb-3">
                            <label for="passworda" class="form-label">กำหนด password สำหรับแอดมินระบบ</label>
                            <input type="text" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">ยืนยันการติดตั้งระบบ</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <!-- Footer -->
    <footer class="bg-light.bg-gradient text-primary text-center py-3 mt-auto">
        <div class="container">
            <p class="mb-0">© 2025 ติดตั้งฐานข้อมูล.</p>
        </div>
    </footer>
    <!-- รวม SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <script>
        <?php if (isset($success_message)) { ?>
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ!',
                text: '<?php echo $success_message; ?>',
                timer: 3000, // แจ้งเตือน 2 วินาที
                timerProgressBar: true, // แสดง progress bar
                willClose: () => {
                    window.location.href = '/'; // รีไดเรกไปยัง หน้าแรก
                }
            });
        <?php } ?>
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <script>
        document.getElementById('myForm').addEventListener('submit', function(event) {
            event.preventDefault(); // ป้องกันการ Submit ฟอร์มชั่วคราว
            Swal.fire({
                title: 'installing...',
                text: 'กำลังติดตั้งฐานข้อมูล',
                didOpen: () => {
                    Swal.showLoading(); // แสดงไอคอน Loading
                }
            });
            //  จำลองการส่งข้อมูลไปยังเซิร์ฟเวอร์ (2 วินาที)
            setTimeout(() => {
                // สามารถเรียกใช้งานการ Submit จริงเมื่อทำงานเสร็จ
                this.submit();
            }, 3000); // แก้ไขเวลาได้ตามต้องการ
        });
    </script>
</body>

</html>