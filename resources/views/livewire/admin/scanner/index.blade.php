@assets
<script>
    function toggleFullscreen() {
        if(document.fullscreenElement) {
            document.exitFullscreen();
        }else{
            document.documentElement.requestFullscreen({navigationUI: "hide"}).catch(err => {
                alert("Trình duyệt của bạn không hỗ trợ tính năng toàn màn hình hoặc đã bị từ chối cấp quyền tính năng.");
                console.error("Fullscreen error:", err);
            });
        }
    }
</script>
@endassets
<div class="fullscreen-container scanner scScanner">
    <div class="header-bar">
        <div class="header-title">
            <i class="fas fa-qrcode"></i>
            Quét QR
        </div>

        <div class="header-actions">
            <div class="mode-toggle">
                <button class="mode-btn scanner active">
                    <i class="fas fa-video"></i>
                    Camera
                </button>
                <button class="mode-btn scanner">
                    <i class="fas fa-upload"></i>
                    Upload
                </button>
            </div>
        </div>

        <div class="status-badge" id="statusBadge">
            <i class="fas fa-circle"></i>
            <span id="badge">Sẵn sàng</span>
        </div>
    </div>

    <div class="instructions scanner">
        <i class="fas fa-info-circle"></i>
        <span id="instructionText">Đặt mã QR trong khung quét và giữ thiết bị ổn định</span>
    </div>

    <div class="media-container scanner">
        <video id="video" autoplay muted playsinline class="d-none d-md-block"></video>

        <img id="imageDisplay" class="image-display scanner" alt="Uploaded image">

        <div class="upload-zone" id="uploadZone" onclick="triggerFileInput()">
            <div class="upload-icon">
                <i class="fas fa-cloud-upload-alt"></i>
            </div>
            <div class="upload-text">Tải ảnh lên để quét QR</div>
            <div class="upload-hint">Kéo thả ảnh vào đây hoặc click để chọn</div>
            <button class="upload-btn">
                <i class="fas fa-folder-open"></i> Chọn ảnh
            </button>
        </div>

    </div>

    <div class="side-controls d-none d-lg-flex">
        <button class="side-control-btn" onclick="toggleFullscreen()" title="Toàn màn hình">
            <i class="fas fa-expand"></i>
        </button>
        <button class="side-control-btn" title="Đổi camera" onclick="openListCameraPopup()">
            <i class="fas fa-sync-alt"></i>
        </button>
        <button class="side-control-btn" title="Cài đặt">
            <i class="fas fa-cog"></i>
        </button>
    </div>

    <!-- Control Panel -->
    <div class="control-panel">
        <!-- Camera Selection (Camera Mode Only) -->
        <div class="control-row" id="cameraRow">
            <select class="camera-selector" id="cameraSelect">
                <option value="">Đang tải camera...</option>
            </select>
        </div>

        <!-- Main Controls -->
        <div class="control-row">
            <!-- Camera Mode Controls -->
            <div id="cameraControls">
                <button class="control-btn scanner flash" id="flashBtn" onclick="toggleFlash()" title="Đèn flash">
                    <i class="fas fa-flashlight"></i>
                </button>

                <button class="control-btn scanner primary" id="startBtn" onclick="startScanner()" title="Bắt đầu quét">
                    <i class="fas fa-play"></i>
                </button>

                <button class="control-btn scanner danger" id="stopBtn" onclick="stopScanner()" style="display: none;" title="Dừng quét">
                    <i class="fas fa-stop"></i>
                </button>

                <button class="control-btn scanner" onclick="captureImage()" title="Chụp ảnh">
                    <i class="fas fa-camera"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Hidden File Input -->
    <input type="file" id="fileInput" class="file-input" accept="image/*" onchange="handleFileSelect(event)">

    <!-- Loading Screen -->
    <div class="loading-screen show" id="loadingScreen">
        <div class="loading-spinner"></div>
        <div class="loading-text" id="loadingText">Đang khởi tạo camera...</div>
    </div>

    <!-- Result Modal -->
    <div class="result-modal" id="resultModal">
        <div class="result-header">
            <i class="fas fa-check-circle"></i>
            Quét thành công!
        </div>
        <div class="result-content" id="resultContent"></div>
        <div class="result-actions">
            <button class="result-btn" onclick="copyResult()">
                <i class="fas fa-copy"></i> Sao chép
            </button>
            <button class="result-btn success" onclick="openResult()">
                <i class="fas fa-external-link-alt"></i> Mở
            </button>
            <button class="result-btn secondary" onclick="closeResult()">
                <i class="fas fa-times"></i> Đóng
            </button>
            <button class="result-btn" onclick="continueScanning()">
                <i class="fas fa-redo"></i> Quét tiếp
            </button>
        </div>
    </div>

    <div class="camera-popup-overlay" id="cameraPopupOverlay"></div>
    <div class="camera-popup" id="cameraPopup">
        <div class="camera-popup-header">
            Chọn Camera
        </div>
        <div class="camera-list" id="cameraList">

        </div>
        <div class="camera-popup-actions">
            <button class="camera-popup-btn" id="applyCamera">
                <i class="fas fa-check"></i> Sử dụng
            </button>
            <button class="camera-popup-btn secondary" id="cancelCamera">
                <i class="fas fa-times"></i> Hủy
            </button>
        </div>
    </div>
</div>
