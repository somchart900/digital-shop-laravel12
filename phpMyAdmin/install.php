<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('log_errors', 1); // เปิดการบันทึกข้อผิดพลาด
ini_set('error_log', 'error.log');
function createDatabaseFile($host, $dbname, $username, $password)
{
    $content = "
DB_HOST=$host
DB_NAME=$dbname
DB_USER=$username
DB_PASS=$password
";

    // บันทึกไฟล์ database.php
    $result = file_put_contents(__DIR__ . '/page/models/database/database.env', $content);
    if ($result) {
        return true;
    } else {
        return false;
    }
    //   echo "ไฟล์ database ถูกสร้างเรียบร้อย!";
}
// ตรวจสอบว่า URL ที่เข้าถึงเป็น localhost หรือไม่
if ($_SERVER['HTTP_HOST'] !== 'localhost') {
    // ถ้าไม่ใช่ localhost ให้ redirect ไปยังหน้าอื่น เช่น example.com
    // header("Location: /");
    //  exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //รับค่าจากฟอร์ม
    if ($_POST['secret'] != '0872556208') {
        echo 'oh no!';
        $error_message = 'secret wrong !!!!';
    } else {
        $host = $_POST['host'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $dbname = $_POST['dbname'];

        // เขียนข้อมูลลงในไฟล์ database.php
        $create = createDatabaseFile($host, $dbname, $username, $password);

        if ($create) {
            //     echo "<p> database created successfully.</p>";
        } else {
            echo "<p>Failed to create database.env.</p>";
            $error_message = 'มีบางอย่างผิดพลาด';
        }

        $conn = new mysqli($host, $username, $password);
        $conn->set_charset('utf8mb4');
        $conn->query("SET time_zone = '+07:00'");
        // ตรวจสอบการเชื่อมต่อ
        if ($conn->connect_error) {
            die(' Connection failed: ' . $conn->connect_error);
            exit;
            $error_message = 'มีบางอย่างผิดพลาด';
        }
        // สร้างฐานข้อมูล
        $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
        if ($conn->query($sql) === TRUE) {
            //     echo "ฐานข้อมูล '$dbname' สร้างสำเร็จ.<br>";
        } else {
            echo " เกิดข้อผิดพลาดในการสร้างฐานข้อมูล: " . $conn->error . "<br>";
            $error_message = 'มีบางอย่างผิดพลาด';
        }

        // ใช้งานฐานข้อมูล
        $conn->select_db($dbname);

        // สร้างตาราง users
        $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL, 
        email VARCHAR(100) NOT NULL UNIQUE,
        email_verified TINYINT(1) DEFAULT 0, 
        registration_date DATETIME DEFAULT CURRENT_TIMESTAMP,
        user_level ENUM('ทั่วไป', 'วีไอพี', 'แอดมิน') DEFAULT 'ทั่วไป',
        account_balance DECIMAL(10, 2) DEFAULT 0.00,
        verification_code VARCHAR(6) DEFAULT NULL, 
        reset_expiration DATETIME DEFAULT NULL
    )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        if ($conn->query($sql) === TRUE) {
            // echo "ตาราง 'users' สร้างสำเร็จ.<br>";
            $admin_email = $_POST['email'];
            $admin_username = $_POST['usernamea'];
            // 1. ค้นหาข้อมูลที่ตรงกับ $admin_username
            $check_sql = "SELECT * FROM users WHERE username = ?";

            // เตรียมคำสั่ง
            $stmt = $conn->prepare($check_sql);
            if ($stmt === false) {
                die("เตรียมคำสั่งล้มเหลวในการค้นหาข้อมูล: " . $conn->error);
                $error_message = 'มีบางอย่างผิดพลาด';
            }

            // ผูกตัวแปรกับคำสั่ง
            $stmt->bind_param("s", $admin_username);

            // execute คำสั่ง
            $stmt->execute();

            // ตรวจสอบว่ามีข้อมูลหรือไม่
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                // ถ้ามีข้อมูลตรงกัน ให้ลบข้อมูลเก่าออกก่อน
                $delete_sql = "DELETE FROM users WHERE username = ?";

                // เตรียมคำสั่ง
                $delete_stmt = $conn->prepare($delete_sql);
                if ($delete_stmt === false) {
                    die("เตรียมคำสั่งล้มเหลวในการลบข้อมูล: " . $conn->error);
                    $error_message = 'มีบางอย่างผิดพลาด';
                }

                // ผูกตัวแปรกับคำสั่ง
                $delete_stmt->bind_param("s", $admin_username);

                // execute การลบข้อมูล
                if ($delete_stmt->execute()) {
                    //  echo "ข้อมูลผู้ใช้งาน '$admin_username' ถูกลบออกแล้ว.<br>";
                } else {
                    echo "เกิดข้อผิดพลาดในการลบข้อมูลผู้ใช้งาน: " . $delete_stmt->error . "<br>";
                    $error_message = 'มีบางอย่างผิดพลาด';
                }

                $delete_stmt->close();
            }

            // 2. เพิ่มข้อมูลใหม่

            $admin_password = password_hash($_POST['passworda'], PASSWORD_DEFAULT); // แฮชรหัสผ่าน

            $insert_sql = "INSERT INTO users (username, password, user_level, email) VALUES (?, ?, 'แอดมิน', ?)";

            $stmt = $conn->prepare($insert_sql);
            if ($stmt === false) {
                die("เตรียมคำสั่งล้มเหลว: " . $conn->error);
                $error_message = 'มีบางอย่างผิดพลาด';
            }

            $stmt->bind_param("sss", $admin_username, $admin_password, $admin_email);

            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    //  echo "เพิ่มผู้ใช้งานแอดมิน '$admin_username' เรียบร้อยแล้ว.<br>";
                }
            } else {
                echo "เกิดข้อผิดพลาดในการเพิ่มผู้ใช้งานแอดมิน: " . $stmt->error . "<br>";
                $error_message = 'มีบางอย่างผิดพลาด';
            }

            $stmt->close();
        } else {
            echo "เกิดข้อผิดพลาดในการสร้างตาราง 'users': " . $conn->error . "<br>";
            $error_message = 'มีบางอย่างผิดพลาด';
        }
        //--------------------
        $topup = "CREATE TABLE IF NOT EXISTS topup (
        id INT AUTO_INCREMENT PRIMARY KEY,
        topup_name VARCHAR(11),
        user_name VARCHAR(11),    
        topup_amount DECIMAL(10, 2) NOT NULL,
        topup_date DATETIME DEFAULT CURRENT_TIMESTAMP,
        link VARCHAR(255) NOT NULL,
        user_id INT,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        if ($conn->query($topup) === TRUE) {
            //     echo "ตาราง 'topup' สร้างสำเร็จ.<br>";
        } else {
            echo "เกิดข้อผิดพลาดในการสร้างตาราง 'topup': " . $conn->error . "<br>";
            $error_message = 'มีบางอย่างผิดพลาด';
        }

        // สร้างตาราง checkslip
        $checkslip = "CREATE TABLE IF NOT EXISTS checkslip (
        id INT AUTO_INCREMENT PRIMARY KEY,
        api_service VARCHAR(100) NOT NULL UNIQUE,
        api_key VARCHAR(100) NOT NULL,
        api_key2 VARCHAR(100) NOT NULL,
        bank_name VARCHAR(50) NOT NULL,
        bank_account_name VARCHAR(100) NOT NULL,
        bank_account_number VARCHAR(100) NOT NULL
    )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        if ($conn->query($checkslip) === TRUE) {
            // echo "เพิ่มตาราง '' เรียบร้อยแล้ว.<br>";
        } else {
            echo "เกิดข้อผิดพลาดในการสร้างตาราง 'checkslip': " . $conn->error . "<br>";
            $error_message = 'มีบางอย่างผิดพลาด';
        }
        // สร้างตาราง setting
        $setting = "CREATE TABLE IF NOT EXISTS setting (
        id INT AUTO_INCREMENT PRIMARY KEY,
        setting_key VARCHAR(100) NOT NULL,
        setting_value VARCHAR(300) NOT NULL,
        user_id INT,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        if ($conn->query($setting) === TRUE) {
            // echo "เพิ่มตาราง '' เรียบร้อยแล้ว.<br>";
        } else {
            echo "เกิดข้อผิดพลาดในการสร้างตาราง 'setting': " . $conn->error . "<br>";
            $error_message = 'มีบางอย่างผิดพลาด';
        }

        //--------------------------
        // สร้างตาราง token
        $token = "CREATE TABLE IF NOT EXISTS token (
        id INT AUTO_INCREMENT PRIMARY KEY,
        token_name VARCHAR(100) NOT NULL,
        value1 VARCHAR(300) NULL,
        value2 VARCHAR(300) NULL
    )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        if ($conn->query($token) === TRUE) {
            // echo "เพิ่มตาราง '' เรียบร้อยแล้ว.<br>";
        } else {
            echo "เกิดข้อผิดพลาดในการสร้างตาราง 'token': " . $conn->error . "<br>";
            $error_message = 'มีบางอย่างผิดพลาด';
        }

        //--------------------------
        //--------------------------
        // สร้างตาราง categories
        $categories = "CREATE TABLE IF NOT EXISTS categories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL UNIQUE, -- ชื่อหมวดหมู่
        img_link VARCHAR(255), -- ลิงก์ไฟล์รูปสินค้า
        description TEXT, -- คำอธิบายเพิ่มเติม
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        if ($conn->query($categories) === TRUE) {
            // echo "เพิ่มตาราง '' เรียบร้อยแล้ว.<br>";
        } else {
            echo "เกิดข้อผิดพลาดในการสร้างตาราง 'categories': " . $conn->error . "<br>";
            $error_message = 'มีบางอย่างผิดพลาด';
        }
        //--------------------------
        //--------------------------
        // สร้างตาราง categories
        $categories_type = "CREATE TABLE IF NOT EXISTS categories_type (
        id INT AUTO_INCREMENT PRIMARY KEY,
        category_id INT NOT NULL,
        name VARCHAR(255) NOT NULL UNIQUE, -- ชื่อชนิดสินค้า
        img_link VARCHAR(255), -- ลิงก์ไฟล์รูปสินค้า
        description TEXT, -- คำอธิบายเพิ่มเติม
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
    )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        if ($conn->query($categories_type) === TRUE) {
            // echo "เพิ่มตาราง '' เรียบร้อยแล้ว.<br>";
        } else {
            echo "เกิดข้อผิดพลาดในการสร้างตาราง 'categories_type': " . $conn->error . "<br>";
            $error_message = 'มีบางอย่างผิดพลาด';
        }
        //--------------------------
        //--------------------------
        // สร้างตาราง products
        $products = "CREATE TABLE IF NOT EXISTS products (
        id INT AUTO_INCREMENT PRIMARY KEY,
        categories_type_id INT, -- เชื่อมกับชนิดสินค้า
        name VARCHAR(255) NOT NULL, -- ชื่อสินค้า
        description TEXT, -- รายละเอียดสินค้า
        price DECIMAL(10,2) NOT NULL, -- ราคา
        code VARCHAR(255), -- ถ้ามีโค้ดดิจิตอล เช่น Key เกม
        youtube VARCHAR(255), -- ถ้ามีโค้ดดิจิตอล เช่น Key เกม
        img_link VARCHAR(255), -- ลิงก์ไฟล์รูปสินค้า
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (categories_type_id) REFERENCES categories_type(id) ON DELETE CASCADE
    )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        if ($conn->query($products) === TRUE) {
            // echo "เพิ่มตาราง '' เรียบร้อยแล้ว.<br>";
        } else {
            echo "เกิดข้อผิดพลาดในการสร้างตาราง 'products': " . $conn->error . "<br>";
            $error_message = 'มีบางอย่างผิดพลาด';
        }
        //--------------------------
        //--------------------------
        // สร้างตาราง sold_products
        $sold_products = "CREATE TABLE IF NOT EXISTS sold_products (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL, -- ใครซื้อไป      
        name VARCHAR(255) NOT NULL, -- ชื่อสินค้า (สำรองไว้หากลบจาก products)
        description TEXT, -- รายละเอียดสินค้า
        price DECIMAL(10,2) NOT NULL, -- ราคาที่ซื้อ
        code VARCHAR(255), -- ถ้ามีโค้ดดิจิตอล เช่น Key เกม
        youtube VARCHAR(255), -- ถ้ามีโค้ดดิจิตอล เช่น Key2 เกม
        img_link VARCHAR(255), -- สำรองลิงก์ไฟล์รูป
        delivered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- เวลาส่งมอบสินค้า       
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        if ($conn->query($sold_products) === TRUE) {
            // echo "เพิ่มตาราง '' เรียบร้อยแล้ว.<br>";
        } else {
            echo "เกิดข้อผิดพลาดในการสร้างตาราง 'sold_products': " . $conn->error . "<br>";
            $error_message = 'มีบางอย่างผิดพลาด';
        }
        //--------------------------
        //--------------------------
        // สร้างตาราง login_history
        $login_history = "CREATE TABLE IF NOT EXISTS login_history (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        ip_address VARCHAR(45) NOT NULL,
        user_agent TEXT NOT NULL,
        login_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        if ($conn->query($login_history) === TRUE) {
            // echo "เพิ่มตาราง '' เรียบร้อยแล้ว.<br>";
        } else {
            echo "เกิดข้อผิดพลาดในการสร้างตาราง 'login_history': " . $conn->error . "<br>";
            $error_message = 'มีบางอย่างผิดพลาด';
        }
        //--------------------------
        //--------------------------
        // สร้างตาราง ประกาศข่าว
        $announce = "CREATE TABLE IF NOT EXISTS announce (
        id INT AUTO_INCREMENT PRIMARY KEY,
        message TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        if ($conn->query($announce) === TRUE) {
            // echo "เพิ่มตาราง '' เรียบร้อยแล้ว.<br>";
        } else {
            echo "เกิดข้อผิดพลาดในการสร้างตาราง 'announce': " . $conn->error . "<br>";
            $error_message = 'มีบางอย่างผิดพลาด';
        }
        //--------------------------
        //--------------------------

        // ปิดการเชื่อมต่อ
        $conn->close();
    }
    if (!isset($error_message)) {
        $success_message = 'ทุกอย่างเสร็จสมบูรณ์';
    }
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
                            <!-- <label for="secret" class="form-label">ระหัสติดตั้ง</label> -->
                            <input type="hidden" class="form-control" id="secret" name="secret" value="0872556208">
                        </div>
                        <div class="mb-3">
                            <label for="้host" class="form-label">host db</label>
                            <input type="text" class="form-control" id="host" name="host" required>
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">username db</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">password db</label>
                            <input type="password" class="form-control" id="password" name="password">
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
                            <label for="usernamea" class="form-label">กำหนด username สำหรับแอดมินระบบ</label>
                            <input type="text" class="form-control" id="usernamea" name="usernamea" required>
                        </div>
                        <div class="mb-3">
                            <label for="passworda" class="form-label">กำหนด password สำหรับแอดมินระบบ</label>
                            <input type="text" class="form-control" id="passworda" name="passworda" required>
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
        <?php if (isset($success_message)): ?>
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ!',
                text: '<?php echo $success_message; unlink(__FILE__);?>',
                timer: 3000, // แจ้งเตือน 2 วินาที
                timerProgressBar: true, // แสดง progress bar
                willClose: () => {
                    window.location.href = '/'; // รีไดเรกไปยัง หน้าแรก
                }
            });
        <?php elseif (isset($error_message)): ?>
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด!',
                text: '<?php echo $error_message; ?>',

            });
        <?php endif; ?>
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