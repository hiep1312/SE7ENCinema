import QrScanner from "qr-scanner";
import Swal from "sweetalert2";

let currentCameraId = null;
let scannerQR = null;
let successSound = true;
let fps = 25;
let resetScanner = false;
let currentFileUpload = null;

function updateInstructionText(text) {
    document.getElementById('instructionText').textContent = text;
}

function setStatusBadge(status, message, icon = ''){
    const statusBadge = document.getElementById('statusBadge');
    statusBadge.className = `status-badge ${status}`;
    statusBadge.querySelector('i').className = icon || 'fas fa-circle';
    statusBadge.querySelector('#badge').textContent = message;
}

async function initScannerQR(){
    const handleQRData = (scanResult) => {
        setStatusBadge('', 'Thành công', 'fa-solid fa-check');
        successSound && playSuccessSound();

        Livewire.dispatch('dataQR', scanResult);
    }

    const options = {
        onDecodeError: (error) => {
            console.error("QR Scanner Error:", error);
        },
        preferredCamera: "evnvironment",
        maxScansPerSecond: fps,
        calculateScanRegion: (videoEle) => Object({
            x: (videoEle.videoWidth - 300) / 2,
            y: (videoEle.videoHeight - 300) / 2,
            width: 300,
            height: 300,
            downScaledWidth: 300,
            downScaledHeight: 300,
        }),
        highlightScanRegion: true,
        highlightCodeOutline: true,
        overlay: undefined,
        returnDetailedScanResult: true,
    };

    const videoEle = document.getElementById('video');
    scannerQR = new QrScanner(videoEle, handleQRData, options);

    await scannerQR.start();
}

async function getListCameras(){
    if(!(await QrScanner.hasCamera())){
        Swal.fire("Không có camera", "Không tìm thấy camera nào trên thiết bị của bạn. Vui lòng kết nối một camera và thử lại.", "error");
        return;
    }

    const listCameras = await QrScanner.listCameras(true);
    currentCameraId ?? (currentCameraId = listCameras[0].id);
    return listCameras;
}

async function createHTMLCamerasList(listCameras) {
    listCameras ?? (listCameras = await getListCameras());
    return listCameras.map(({ id, label }) => {
        return `
            <div class="camera-item ${currentCameraId === id ? 'active' : ''}" data-camera-id="${id}">
                <div class="camera-icon">
                    <i class="fas fa-video"></i>
                </div>
                <div class="camera-info">
                    <div class="camera-name">${label}</div>
                </div>
                ${currentCameraId === id ? '<div class="camera-check"><i class="fas fa-check"></i></div>' : ''}
            </div>
        `;
    }).join("\n");
}

async function toggleBtnScanner(stopScanner = true) {
    const btnScanner = document.getElementById('btnScanner');
    btnScanner.classList.toggle('danger', !stopScanner);
    btnScanner.classList.toggle('primary', stopScanner);
    btnScanner.innerHTML = stopScanner ? '<i class="fas fa-play"></i>' : '<i class="fas fa-stop"></i>';
    btnScanner.title = stopScanner ? "Bắt đầu quét" : "Dừng quét";
    btnScanner.onclick = stopScanner ? startScannerQR : stopScannerQR;
    setStatusBadge(stopScanner ? '' : 'scanning', stopScanner ? 'Sẵn sàng' : 'Đang quét', stopScanner ? 'fa-solid fa-play' : 'fa-solid fa-circle');
    resetScanner && (scannerQR = null);
    stopScanner ? scannerQR?.stop() : (((scannerQR instanceof QrScanner) || await initScannerQR()) && await scannerQR.start());
}

async function toggleFlash() {
    if(!(scannerQR instanceof QrScanner)){
        console.error('Unable to toggle flash because the QR scanner was not found.');
        Swal.fire("Lỗi quét QR", "Không thể sử dụng đèn flash vì không tìm thấy quét QR. Vui lòng tải lại trang hoặc kiểm tra cấu hình camera của bạn.", "error");
        return;
    }

    if(!(await scannerQR.hasFlash())){
        Swal.fire("Không hỗ trợ đèn flash", "Camera hiện tại không hỗ trợ chức năng đèn flash.", "error");
        return;
    }

    const isFlashOn = scannerQR.isFlashOn();
    const btnFlash = document.getElementById('btnFlash');
    btnFlash.classList.toggle('active', !isFlashOn);
    await scannerQR.toggleFlash();
}

async function scanImageFile(file) {
    try {
        setStatusBadge('scanning', 'Đang quét', 'fa-solid fa-circle');

        const options = {
            scanRegion: undefined,
            qrEngine: undefined,
            canvas: undefined,
            disallowCanvasResizing: true,
            alsoTryWithoutScanRegion: true,
            returnDetailedScanResult: true,
        };
        const scanResult = await QrScanner.scanImage(file, options);

        setStatusBadge('', 'Thành công', 'fa-solid fa-check');
        successSound && playSuccessSound();

        await Livewire.dispatch('dataQR', scanResult);
    } catch (error) {
        console.error("Image scan error:", error);
        setStatusBadge('error', 'Lỗi quét', 'fa-solid fa-circle-exclamation');

        Swal.fire({
            title: "Không tìm thấy QR code",
            text: "Không thể tìm thấy mã QR trong ảnh này. Vui lòng thử với ảnh khác.",
            icon: "warning",
            confirmButtonText: "Thử lại",
            showCancelButton: true,
            cancelButtonText: "Đóng",
            showCloseButton: true
        }).then(result => {
            result.isConfirmed && scanImageFile(currentFileUpload);
        });

        return null;
    }
}

function openResultPopup(typeQR, data, options = {}) {
    const resultModal = document.getElementById('resultModal');
    const icon = resultModal.querySelector('.result-header i');
    const title = resultModal.querySelector('#resultTitle');
    const content = resultModal.querySelector('#resultContent');
    const [btnCopy, btnOpen, btnClose] = Array.from(resultModal.querySelectorAll('.result-btn'));
    let success = true;

    const toggleModalResult = function(force, type, titleText) {
        resultModal.classList.toggle('show', force);
        if(force) {
            const switchIcon = {
                success: 'fas fa-check-circle',
                warning: 'fas fa-exclamation-triangle',
                error: 'fas fa-times-circle'
            };

            type && (icon.className = switchIcon[type]) && resultModal.classList.add(type);
            titleText && (title.textContent = titleText);
            content.textContent = data;
        }else{
            resultModal.innerHTML = `
                <div class="result-header">
                    <i class="fas fa-check-circle"></i>
                    <span id="resultTitle">Quét thành công!</span>
                </div>
                <div class="result-content" id="resultContent"></div>
                <div class="result-actions">
                    <button class="result-btn">
                        <i class="fas fa-copy"></i> Sao chép
                    </button>
                    <a class="result-btn success" href="javascript:void(0)" style="text-decoration: none;">
                        <i class="fas fa-external-link-alt"></i> Mở
                    </a>
                    <button class="result-btn secondary">
                        <i class="fas fa-times"></i> Đóng
                    </button>
                </div>
            `;
        }
    }
    toggleModalResult(true);

    if(typeQR === "bookings"){
        const { urlPrint, taken } = options;

        if(taken) toggleModalResult(true, 'warning', 'Đơn hàng đã lấy vé!') || (success = false);
        else toggleModalResult(true, 'success', 'Quét đơn hàng thành công!');

        btnCopy.onclick = () => {
            window.open(`${urlPrint}#print`, '_blank', 'width=1000,height=600,left=250,top=100,popup');
        };

        btnCopy.innerHTML = '<i class="fas fa-print"></i> In vé';
    }else if(typeQR === "tickets"){
        const { used, expired } = options;

        if(used, expired) toggleModalResult(true, 'warning', 'Vé đã sử dụng!') || (success = false);
        else toggleModalResult(true, 'success', 'Quét vé thành công!');
    }

    typeQR !== "bookings" && (btnCopy.onclick = () => {
        navigator.clipboard.writeText(data).catch(err => {
            console.error('Failed to copy text: ', err);
            Swal.fire("Lỗi sao chép", "Không thể sao chép dữ liệu vào clipboard.", "error");
        });
    });
    btnOpen.href = typeQR !== "other" ? options.urlDetail : (URL.canParse(data) ? data : 'javascript:void(0)');
    btnClose.onclick = toggleModalResult.bind(null, false);
    success && setTimeout(toggleModalResult.bind(null, false), 6000);
}

function playSuccessSound() {
    const audio = new Audio(`${location.origin}/storage/successSound.mp3`);
    audio.play().catch(e => console.error('Audio play failed: ', e));
}

function toggleControlPanel(ofUpload = true){
    document.getElementById('controlPanel').classList.toggle('upload', ofUpload);
}

function switchMode(mode, element) {
    const video = document.getElementById('video');
    const imageDisplay = document.getElementById('imageDisplay');
    const uploadZone = document.getElementById('uploadZone');
    const modeBtns = document.querySelectorAll('.mode-btn');
    const sideControlCamera = document.getElementById('sideControlCamera');

    modeBtns.forEach(btn => btn.classList.toggle('active', btn === element));
    imageDisplay.classList.remove('show');

    if (mode === 'camera') {
        video.style.display = 'block';
        sideControlCamera.style.removeProperty('display');
        uploadZone.classList.remove('show');

        if(scannerQR instanceof QrScanner) scannerQR.start();
        else initScannerQR();

        setStatusBadge('scanning', 'Đang quét', 'fa-solid fa-circle');
        updateInstructionText('Đặt mã QR trong khung quét và giữ thiết bị ổn định');
    } else {
        setupDragAndDrop();
        document.getElementById('fileInput').addEventListener('change', handleFileSelect);

        video.style.display = 'none';
        sideControlCamera.style.setProperty('display', 'none', 'important');
        uploadZone.classList.add('show');

        if(scannerQR instanceof QrScanner) scannerQR.stop();

        setStatusBadge('', 'Sẵn sàng', 'fa-solid fa-arrow-up-from-bracket');
        updateInstructionText('Tải ảnh có mã QR');
    }

    toggleControlPanel(mode !== 'camera');
}

function validateSelectedFile(file) {
    if(!(file instanceof Blob)) return void 0;

    if (!file.type.startsWith('image/')) {
        Swal.fire({
            title: "File không hợp lệ",
            text: "Vui lòng chọn file ảnh (JPG, PNG, GIF, etc.)",
            icon: "error"
        });
        return false;
    }else if (file.size > (20 * 1024 * 1024)) {
        Swal.fire({
            title: "File quá lớn",
            text: "Vui lòng chọn file nhỏ hơn 20MB",
            icon: "error"
        });
        return false;
    }

    scanImageFile(file);

    return true;
}

function previewImage(fileImage){
    const imageDisplay = document.getElementById('imageDisplay');

    currentFileUpload = fileImage;
    imageDisplay.classList.add('show');
    imageDisplay.src = URL.createObjectURL(currentFileUpload);
    imageDisplay.onload = () => URL.revokeObjectURL(imageDisplay.src);
}

function setupDragAndDrop() {
    const uploadZone = document.getElementById('uploadZone');
    const mediaContainer = document.querySelector('.media-container');

    const switchStatusDrag = function (dragEnter, event){
        event.preventDefault();
        event.target.classList.toggle('dragover', dragEnter);
    }

    uploadZone.addEventListener('dragover', switchStatusDrag.bind(null, true));
    uploadZone.addEventListener('dragleave', switchStatusDrag.bind(null, false));
    uploadZone.addEventListener('drop', (e) => {
        switchStatusDrag(false, e);

        const file = e.dataTransfer.files.item(0);
        if(validateSelectedFile(file)) previewImage(file) || uploadZone.classList.remove('show');
    });

    const handleUploadZoneDragToggle = function(isVisible, e){
        e.preventDefault();
        (!uploadZone.classList.contains('show') || currentFileUpload) && uploadZone.classList.toggle('show', isVisible);
    }

    mediaContainer.addEventListener('dragover', handleUploadZoneDragToggle.bind(null, true));
    mediaContainer.addEventListener('drop', handleUploadZoneDragToggle.bind(null, false));
}

function handleFileSelect(event) {
    const uploadZone = document.getElementById('uploadZone');
    const file = event.target.files.item(0);
    if(validateSelectedFile(file)) previewImage(file) || uploadZone.classList.remove('show');
}

function downloadUploadedFile(){
    if(!(currentFileUpload instanceof Blob)) {
        Swal.fire({
            title: "Không tìm thấy file nào",
            text: "Chưa có file nào được tải lên.",
            icon: "warning"
        });

        return;
    }

    const a = document.createElement('a');
    const fileTemp = a.href = URL.createObjectURL(currentFileUpload);
    a.download = currentFileUpload.name;
    a.click();

    URL.revokeObjectURL(fileTemp);
    a.remove();

    Swal.fire({
        title: "Tải xuống thành công",
        text: "File đã được tải về máy của bạn.",
        icon: "success"
    });
}

function clearImage(){
    document.getElementById('uploadZone').classList.add('show');
    document.getElementById('imageDisplay').classList.remove('show');
    currentFileUpload && (currentFileUpload = null);
}

document.addEventListener('livewire:initialized', async () => {
    try{
        await initScannerQR();

        window.openListCameraPopup = async function(){
            const cameraPopup = document.getElementById('cameraPopup');
            const cameraPopupOverlay = document.getElementById('cameraPopupOverlay');
            const HTMLCameras = await createHTMLCamerasList();

            const toggleCameraPopup = function (show = false, cameraListHTML) {
                cameraPopup.classList.toggle('show', show);
                cameraPopupOverlay.classList.toggle('show', show);
                cameraPopup.querySelector('#cameraList').innerHTML = show ? cameraListHTML : '';
            }
            toggleCameraPopup(true, HTMLCameras);

            let cameraSelectedId = currentCameraId;
            const cameraItems = cameraPopup.querySelectorAll('.camera-item');
            let activeCameraItem = cameraPopup.querySelector('.camera-item.active');
            cameraItems.forEach(cameraElement => {
                cameraElement.addEventListener('click', () => {
                    activeCameraItem.classList.remove('active') || activeCameraItem.querySelector('.camera-check')?.remove();

                    cameraElement.classList.add('active');
                    cameraElement.insertAdjacentHTML('beforeend', '<div class="camera-check"><i class="fas fa-check"></i></div>');
                    cameraSelectedId = cameraElement.getAttribute('data-camera-id');
                    activeCameraItem = cameraElement;
                });
            });

            document.getElementById('applyCamera').onclick = function() {
                if(scannerQR instanceof QrScanner) scannerQR.setCamera(currentCameraId = cameraSelectedId);
                else console.error('Unable to apply the selected camera because the QR scanner was not found.') ||
                    Swal.fire("Lỗi quét QR", "Không thể sử dụng camera đã chọn vì không tìm thấy quét QR. Vui lòng tải lại trang hoặc kiểm tra cấu hình của bạn.", "error");

                toggleCameraPopup();
            };

            document.querySelectorAll('#cancelCamera, #cameraPopupOverlay').forEach(popupCloser => popupCloser.onclick = toggleCameraPopup.bind(null, false));
        }

        window.stopScannerQR = toggleBtnScanner.bind(null, true);
        window.startScannerQR = toggleBtnScanner.bind(null, false);
        window.toggleFlash = toggleFlash;

        window.openSettingsPopup = async function() {
            const settingsPopup = document.getElementById('settingsPopup');
            const cameraList = settingsPopup.querySelector('#cameraList');
            const fpsSlider = settingsPopup.querySelector('#fpsSlider');
            const fullscreenSwitch = settingsPopup.querySelector('#fullscreenSwitch');
            const soundToggle = settingsPopup.querySelector('#soundToggle');

            const toggleSettingsPopup = async function(show = false) {
                settingsPopup.classList.toggle('show', show);
                cameraList.innerHTML = show ? await createHTMLCamerasList() : '';
                fpsSlider.value = show ? fps : 0;
                show ? (settingsPopup.querySelector('#fpsValue').textContent = fps) : void 0;
                fullscreenSwitch.classList.toggle('active', show && Boolean(document.fullscreenElement));
                soundToggle.classList.toggle('active', show && successSound);
            }
            await toggleSettingsPopup(true);

            const configChanges = {};
            const registerSettingsEvents = () => {
                const cameraItems = cameraList.querySelectorAll('.camera-item');
                let activeCameraItem = cameraList.querySelector('.camera-item.active');
                cameraItems.forEach(cameraElement => {
                    cameraElement.addEventListener('click', () => {
                        activeCameraItem.classList.remove('active') || activeCameraItem.querySelector('.camera-check')?.remove();

                        cameraElement.classList.add('active');
                        cameraElement.insertAdjacentHTML('beforeend', '<div class="camera-check"><i class="fas fa-check"></i></div>');
                        configChanges.cameraSelectedId = cameraElement.getAttribute('data-camera-id');
                        activeCameraItem = cameraElement;
                    });
                });

                fullscreenSwitch.onclick = () => {
                    fullscreenSwitch.classList.toggle('active');
                    configChanges.fullscreenMode = fullscreenSwitch.classList.contains('active');
                };

                soundToggle.onclick = () => {
                    soundToggle.classList.toggle('active');
                    configChanges.successSoundMode = soundToggle.classList.contains('active');
                }

                fpsSlider.oninput = () => {
                    configChanges.fpsConfig = fpsSlider.valueAsNumber;
                    settingsPopup.querySelector('#fpsValue').textContent = configChanges.fpsConfig;
                }
            }
            registerSettingsEvents();

            settingsPopup.querySelector('#applySettings').onclick = async function() {
                if(scannerQR instanceof QrScanner){
                    configChanges.fullscreenMode !== undefined && toggleFullscreen(configChanges.fullscreenMode);
                    configChanges.successSoundMode !== undefined && (successSound = configChanges.successSoundMode);
                    configChanges.fpsConfig !== undefined && (fps = configChanges.fpsConfig) && ((scannerQR._active && !scannerQR._paused) || ((resetScanner = true) && false)) && await initScannerQR();
                    configChanges.cameraSelectedId && scannerQR.setCamera(currentCameraId = configChanges.cameraSelectedId);
                }else {
                    console.error('Unable to apply settings because the QR scanner was not found.');
                    Swal.fire("Lỗi quét QR", "Không thể áp dụng cài đặt vì không tìm thấy quét QR. Vui lòng tải lại trang hoặc kiểm tra cấu hình của bạn.", "error");
                }

                toggleSettingsPopup();
            }

            settingsPopup.querySelectorAll('.settings-dismiss').forEach(btnClose => btnClose.onclick = toggleSettingsPopup.bind(null, false));
        }
    }catch (error) {
        setStatusBadge('error', 'Lỗi camera', 'fa-solid fa-circle-exclamation');
        toggleBtnScanner(true);
        console.error("QR Scanner Initialization Error:", error);

        /* Đặt giá trị rỗng */
        window.startScannerQR = Function();
        window.stopScannerQR = Function();
        window.openListCameraPopup = Function();
        window.toggleFlash = Function();
        window.openSettingsPopup = Function();
    }finally {
        window.switchMode = switchMode;
        window.downloadUploadedFile = downloadUploadedFile;
        window.clearImage = clearImage;
        window.openResultPopup = openResultPopup;
    }
});
