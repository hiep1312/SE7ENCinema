import QrScanner from "qr-scanner";
import Swal from "sweetalert2";

let currentCameraId = null;
let scannerQR = null;

window.openListCameraPopup = async function(){
    const listCameras = await QrScanner.listCameras(true);
    currentCameraId ?? (currentCameraId = listCameras[0].id);
    listCameras.push({ id: 'environment', label: 'Back Camera' }, { id: 'user', label: 'Front Camera' });

    const cameraPopup = document.getElementById('cameraPopup');
    const cameraPopupOverlay = document.getElementById('cameraPopupOverlay');

    const iconActiveHTML = '<div class="camera-check"><i class="fas fa-check"></i></div>';
    const HTMLCameras = listCameras.map(({ id, label }) => {
        return `
            <div class="camera-item ${currentCameraId === id ? 'active' : ''}" data-camera-id="${id}">
                <div class="camera-icon">
                    <i class="fas fa-video"></i>
                </div>
                <div class="camera-info">
                    <div class="camera-name">${label}</div>
                </div>
                ${currentCameraId === id ? iconActiveHTML : ''}
            </div>
        `;
    }).join("\n");

    const toggleCameraPopup = function (show = false, cameraListHTML) {
        cameraPopup.classList.toggle('show', show);
        cameraPopupOverlay.classList.toggle('show', show);
        cameraPopup.querySelector('#cameraList').innerHTML = show ? cameraListHTML : '';
    }
    toggleCameraPopup(true, HTMLCameras);

    let cameraSelectedId = currentCameraId;
    const cameraItems = cameraPopup.querySelectorAll('.camera-item');
    cameraItems.forEach(item => {
        item.addEventListener('click', () => {
            cameraItems.forEach(item => item.classList.remove('active') || item.querySelector('.camera-check')?.remove());
            item.classList.add('active');
            item.insertAdjacentHTML('beforeend', iconActiveHTML);
            cameraSelectedId = item.getAttribute('data-camera-id');
        });
    });

    document.getElementById('applyCamera').addEventListener('click', (e) => {
        e.stopImmediatePropagation
        if(scannerQR instanceof QrScanner || true){
            currentCameraId = cameraSelectedId;
            scannerQR.setCamera(currentCameraId);
            console.log(currentCameraId = cameraSelectedId);
        }else{
            console.error('Unable to apply the selected camera because the QR scanner was not found.');
            Livewire.dispatch('_scAlert', [{
                title: "Lỗi quét QR",
                html: "Không thể sử dụng camera đã chọn vì không tìm thấy quét QR. Vui lòng tải lại trang hoặc kiểm tra cấu hình của bạn.",
                icon: "error"
            }, '']);
        }

        toggleCameraPopup();
    });

    document.querySelectorAll('#cancelCamera, #cameraPopupOverlay').forEach(item => item.addEventListener('click', toggleCameraPopup));
}

document.addEventListener('livewire:initialized', async () => {
    document.querySelector('#loadingScreen').classList.add('show');
    const handleQRData = (scanResult) => {
        Livewire.dispatch('dataQR', scanResult);
    }

    const options = {
        onDecodeError: (error) => {
            console.error("QR Scanner Error:", error);
        },
        preferredCamera: "environment",
        maxScansPerSecond: 25,
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
    document.querySelector('#loadingScreen').classList.remove('show');
});
