@assets
<script>
    function toggleFullscreen(force = undefined) {
        if(document.fullscreenElement || force === false) {
            document.exitFullscreen();
        }else{
            document.documentElement.requestFullscreen({navigationUI: "hide"}).catch(err => {
                Livewire.dispatch('_scAlert', [
                    {
                        title: "Lỗi trình duyệt",
                        html: "Trình duyệt của bạn không hỗ trợ tính năng toàn màn hình hoặc đã bị từ chối cấp quyền tính năng.",
                        icon: 'error'
                    }, ''
                ]);
                console.error("Fullscreen error:", err);
            });
        }
    }
</script>
@endassets
<div class="fullscreen-container scanner" wire:poll wire:ignore>
    <div class="header-bar">
        <div class="header-title">
            <i class="fas fa-qrcode"></i>
            Quét QR
        </div>

        <div class="header-actions">
            <div class="mode-toggle">
                <button class="mode-btn scanner active" onclick="switchMode('camera', this)">
                    <i class="fas fa-video"></i>
                    Camera
                </button>
                <button class="mode-btn scanner" onclick="switchMode('upload', this)">
                    <i class="fas fa-upload"></i>
                    Upload
                </button>
            </div>
        </div>

        <div class="status-badge scanning" id="statusBadge">
            <i class="fas fa-circle"></i>
            <span id="badge">Đang quét</span>
        </div>
    </div>

    <div class="instructions scanner">
        <i class="fas fa-info-circle"></i>
        <span id="instructionText">Đặt mã QR trong khung quét và giữ thiết bị ổn định</span>
    </div>

    <div class="media-container scanner">
        <video id="video" autoplay muted playsinline></video>

        <img id="imageDisplay" class="image-display scanner" alt="Ảnh tải lên">

        <div class="upload-zone" id="uploadZone">
            <div class="upload-icon">
                <i class="fas fa-cloud-upload-alt"></i>
            </div>
            <div class="upload-text">Tải ảnh lên để quét QR</div>
            <div class="upload-hint">Kéo thả ảnh vào đây hoặc click để chọn</div>
            <button class="upload-btn" onclick="document.getElementById('fileInput').click()">
                <i class="fas fa-folder-open"></i> Chọn ảnh
            </button>
        </div>
    </div>

    <div class="side-controls d-none d-lg-flex" id="sideControlCamera">
        <button class="side-control-btn" title="Toàn màn hình" onclick="toggleFullscreen()">
            <i class="fas fa-expand"></i>
        </button>
        <button class="side-control-btn" title="Đổi camera" onclick="openListCameraPopup()">
            <i class="fas fa-sync-alt"></i>
        </button>
        <button class="side-control-btn" title="Cài đặt" onclick="openSettingsPopup()">
            <i class="fas fa-cog"></i>
        </button>
    </div>

    <div class="control-panel" id="controlPanel">
        <div class="control-row">
            <button class="control-btn scanner flash" id="btnFlash" onclick="toggleFlash()" title="Đèn flash">
                <i class="fas fa-flashlight"></i>
            </button>

            <button class="control-btn scanner danger" id="btnScanner" onclick="stopScannerQR()" title="Dừng quét">
                <i class="fas fa-stop"></i>
            </button>

            <button class="control-btn scanner" onclick="openSettingsPopup()" title="Cài đặt">
                <i class="fas fa-cog"></i>
            </button>
        </div>

        <div class="control-row">
            <button class="control-btn scanner" onclick="clearImage()" title="Xóa ảnh" style="background: var(--danger-color-scanner); border-color: var(--danger-color-scanner); box-shadow: 0 0 20px rgba(220, 53, 69, 0.5);">
                <i class="fas fa-trash"></i>
            </button>

            <button class="control-btn scanner primary" onclick="document.getElementById('fileInput').click()" title="Chọn ảnh">
                <i class="fas fa-folder-open"></i>
            </button>

            <button class="control-btn scanner" onclick="downloadUploadedFile()" title="Tải ảnh">
                <i class="fas fa-download"></i>
            </button>
        </div>
    </div>

    <input type="file" id="fileInput" class="file-input" accept="image/*">

    <div class="result-modal" id="resultModal">
        <div class="result-header">
            <i class="fas fa-check-circle"></i>
            <span id="resultTitle">Quét thành công!</span>
        </div>
        <div class="result-content" id="resultContent"></div>
        <div class="result-actions">
            <button class="result-btn">
                <i class="fas fa-copy"></i> Sao chép
            </button>
            <a class="result-btn success" href="javascript:void(0)" target="_blank" style="text-decoration: none;">
                <i class="fas fa-external-link-alt"></i> Mở
            </a>
            <button class="result-btn secondary">
                <i class="fas fa-times"></i> Đóng
            </button>
        </div>
    </div>

    <div class="camera-popup-overlay" id="cameraPopupOverlay"></div>
    <div class="camera-popup" id="cameraPopup">
        <div class="camera-popup-header">
            Chọn Camera
        </div>
        <div class="camera-list" id="cameraList"></div>
        <div class="camera-popup-actions">
            <button class="camera-popup-btn" id="applyCamera">
                <i class="fas fa-check"></i> Sử dụng
            </button>
            <button class="camera-popup-btn secondary" id="cancelCamera">
                <i class="fas fa-times"></i> Hủy
            </button>
        </div>
    </div>

    <div class="settings-popup" id="settingsPopup">
        <div class="settings-container">
            <div class="settings-header">
                <div class="settings-title">
                    <i class="fas fa-cog"></i>
                    Cài đặt
                </div>
                <button class="settings-close settings-dismiss">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="settings-content">
                <div class="settings-section">
                    <div class="section-title">
                        <i class="fa-solid fa-camera"></i>
                        Camera
                    </div>
                    <div class="camera-list" id="cameraList">

                    </div>
                </div>

                <div class="settings-section">
                    <div class="section-title">
                        <i class="fas fa-film"></i>
                        Chất lượng Video
                    </div>
                    <div class="setting-item">
                        <div class="setting-label">
                            FPS (Khung hình/giây)
                            <span class="range-value" id="fpsValue">30</span>
                        </div>
                        <div class="setting-description">Điều chỉnh tốc độ khung hình (FPS càng cao càng mượt)</div>
                        <div class="setting-control">
                            <input type="range" class="range-slider" id="fpsSlider" min="16" max="120" step="8">
                        </div>
                    </div>
                </div>

                <!-- Display Settings -->
                <div class="settings-section">
                    <div class="section-title">
                        <i class="fas fa-desktop"></i>
                        Hiển thị
                    </div>

                    <div class="setting-item">
                        <div class="setting-label">
                            Chế độ toàn màn hình
                            <div class="toggle-switch" id="fullscreenSwitch">
                                <div class="toggle-slider"></div>
                            </div>
                        </div>
                        <div class="setting-description">Tự động vào chế độ toàn màn hình khi bật</div>
                    </div>
                </div>

                <!-- Scan Settings -->
                <div class="settings-section">
                    <div class="section-title">
                        <i class="fas fa-qrcode"></i>
                        Quét QR
                    </div>

                    <div class="setting-item">
                        <div class="setting-label">
                            Âm thanh thông báo
                            <div class="toggle-switch active" id="soundToggle">
                                <div class="toggle-slider"></div>
                            </div>
                        </div>
                        <div class="setting-description">Phát âm thanh khi quét thành công</div>
                    </div>

                </div>
            </div>

            <div class="settings-footer">
                <button class="settings-action-btn secondary settings-dismiss">
                    <i class="fas fa-times"></i> Hủy
                </button>
                <button class="settings-action-btn" id="applySettings">
                    <i class="fas fa-save"></i> Lưu cài đặt
                </button>
            </div>
        </div>
    </div>
</div>
