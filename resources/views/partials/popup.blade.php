<div id="newsPopup" class="news-popup">
    <div class="news-popup-container">
        <div class="news-popup-header">
            <div class="news-icon">📢</div>
            <h3>ประกาศสำคัญ</h3>
            <span id="closeNewsBtn" class="close-news-btn">&times;</span>
        </div>
        
        <div class="news-popup-content">
            <div class="news-item">
                <div class="news-badge new">NEW</div>
                <p class="news-title">{{ $popup->value ?? 'ประกาศใหม่' }}</p>
                <p class="news-date">{{ $popup->updated_at->format('d/m/Y - H:i') ?? 'วันนี้' }}</p>
            </div>
            
            <div class="news-item">
                <div class="news-badge update">UPDATE</div>
                <p class="news-title">{{ $popup2->value ?? '' }}</p>
                <p class="news-date">{{ $popup2?->updated_at?->format('d/m/Y - H:i') ?? '' }}</p>
            </div>
            
        </div>
        
        <div class="news-popup-actions">
            <button id="dontShowAgain" class="secondary-btn">
                <i class="far fa-bell-slash"></i> ปิดการแจ้งเตือน 7 วัน
            </button>
            <button id="understandBtn" class="primary-btn">
                <i class="far fa-check-circle"></i> เข้าใจแล้ว
            </button>
        </div>
    </div>
</div>

<style>
    /* Base Styles */
    .news-popup {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.8);
        z-index: 1000;
        justify-content: center;
        align-items: center;
        font-family: 'Kanit', 'Segoe UI', sans-serif;
    }
    
    .news-popup-container {
        width: 450px;
        max-height: 80vh;
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.25);
        animation: popupSlideIn 0.4s cubic-bezier(0.22, 1, 0.36, 1);
        display: flex;
        flex-direction: column;
    }
    
    /* Header */
    .news-popup-header {
        background: linear-gradient(135deg, #272c29, #1b201d);
        color: white;
        padding: 18px 25px;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .news-icon {
        font-size: 24px;
        margin-right: 5px;
    }
    
    .news-popup-header h3 {
        margin: 0;
        font-size: 20px;
        font-weight: 500;
        flex-grow: 1;
    }
    
    .close-news-btn {
        font-size: 28px;
        cursor: pointer;
        transition: transform 0.2s;
        line-height: 1;
    }
    
    .close-news-btn:hover {
        transform: rotate(90deg);
    }
    
    /* Content */
    .news-popup-content {
        padding: 20px;
        overflow-y: auto;
        flex-grow: 1;
    }
    
    .news-item {
        background: #f9f9f9;
        border-left: 4px solid #010c06;
        padding: 15px;
        margin-bottom: 15px;
        border-radius: 0 8px 8px 0;
        position: relative;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .news-item:hover {
        transform: translateX(5px);
        box-shadow: 3px 3px 10px rgba(0,0,0,0.1);
    }
    
    .news-badge {
        position: absolute;
        top: -10px;
        right: 15px;
        padding: 3px 10px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: bold;
        color: white;
    }
    
    .new { background: #e74c3c; }
    .update { background: #3498db; }
    .event { background: #f39c12; }
    
    .news-title {
        margin: 0 0 5px 0;
        font-size: 16px;
        font-weight: 500;
        color: #2c3e50;
    }
    
    .news-date {
        margin: 0;
        font-size: 13px;
        color: #7f8c8d;
    }
    
    /* Footer */
    .news-popup-actions {
        display: flex;
        padding: 15px 20px;
        background: #f5f5f5;
        border-top: 1px solid #eee;
        gap: 10px;
    }
    
    .primary-btn, .secondary-btn {
        border: none;
        padding: 12px 20px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 500;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s;
        flex: 1;
    }
    
    .primary-btn {
        background: #cc2e5d;
        color: white;
    }
    
    .primary-btn:hover {
        background: #ae2785;
        transform: translateY(-2px);
    }
    
    .secondary-btn {
        background: #ecf0f1;
        color: #34495e;
    }
    
    .secondary-btn:hover {
        background: #dfe6e9;
    }
    
    /* Animation */
    @keyframes popupSlideIn {
        from {
            opacity: 0;
            transform: translateY(50px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    
    /* Responsive */
    @media (max-width: 500px) {
        .news-popup-container {
            width: 90%;
            max-height: 85vh;
        }
        
        .news-popup-actions {
            flex-direction: column;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const popup = document.getElementById('newsPopup');
        const closeBtn = document.getElementById('closeNewsBtn');
        const dontShowAgainBtn = document.getElementById('dontShowAgain');
        const understandBtn = document.getElementById('understandBtn');
        
        // ระบบเวอร์ชั่นข่าวสาร
        const CURRENT_NEWS_VERSION = "{{ $popup?->updated_at?->timestamp ?? 0 }}{{ $popup2?->updated_at?->timestamp ?? 0 }}"; // เปลี่ยนค่านี้เมื่อมีข่าวใหม่
        const STORAGE_KEY = "newsPopupSettings";
        
        // ตรวจสอบการแสดงป็อปอัพ
        function shouldShowPopup() {
            const savedData = localStorage.getItem(STORAGE_KEY);
            
            // ถ้าไม่มีข้อมูลที่บันทึกไว้ แสดงป็อปอัพ
            if (!savedData) return true;
            
            const { version, hideUntil } = JSON.parse(savedData);
            
            // ถ้าเป็นข่าวเวอร์ชั่นใหม่กว่า ให้แสดงทันที
            if (version !== CURRENT_NEWS_VERSION) return true;
            
            // ตรวจสอบเวลา
            return new Date().getTime() > hideUntil;
        }
        
        // แสดงป็อปอัพ
        function showPopup() {
            setTimeout(() => {
                popup.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }, 2000);
        }
        
        // ซ่อนป็อปอัพ
        function hidePopup() {
            popup.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        
        // บันทึกการตั้งค่า
        function saveSettings(days) {
            const settings = {
                version: CURRENT_NEWS_VERSION,
                hideUntil: new Date().getTime() + (days * 24 * 60 * 60 * 1000)
            };
            localStorage.setItem(STORAGE_KEY, JSON.stringify(settings));
            hidePopup();
        }
        
        // Event Listeners
        if (shouldShowPopup()) {
            showPopup();
        }
        
        closeBtn.addEventListener('click', hidePopup);
        
        dontShowAgainBtn.addEventListener('click', () => {
            saveSettings(7); // ไม่แสดงอีก 7 วัน
        });
        
        understandBtn.addEventListener('click', () => {
            saveSettings(1); // ไม่แสดงอีก 1 วัน
        });
        
        // คลิกนอกป็อปอัพเพื่อปิด
        popup.addEventListener('click', (e) => {
            if (e.target === popup) {
                hidePopup();
            }
        });
    });
</script>