import QrScanner from "qr-scanner";

let currentCameraId = null;
let scannerQR = null;

window.openListCameraPopup = async function(){
    const listCameras = await QrScanner.listCameras(true);
    currentCameraId ?? (currentCameraId = listCameras[0].id);

    listCameras.push({ id: 'abc123', label: 'Back Camera' }, { id: 'xyz456', label: 'Front Camera' });

    const cameraPopup = document.getElementById('cameraPopup');
    const cameraPopupOverlay = document.getElementById('cameraPopupOverlay');

    const iconActive = '<div class="camera-check"><i class="fas fa-check"></i></div>';
    const generateDOMCameras = listCameras.map(({ id, label }) => {
        return `
            <div class="camera-item ${currentCameraId === id ? 'active' : ''}" data-camera-id="${id}">
                <div class="camera-icon">
                    <i class="fas fa-video"></i>
                </div>
                <div class="camera-info">
                    <div class="camera-name">${label}</div>
                </div>
                ${currentCameraId === id ? iconActive : ''}
            </div>
        `;
    }).join("\n");

    cameraPopupOverlay.classList.add('show');
    cameraPopup.classList.add('show');
    cameraPopup.querySelector('#cameraList').innerHTML = generateDOMCameras;

    let cameraSelectedId = null;
    const cameraItems = cameraPopup.querySelectorAll('.camera-item');
    cameraItems.forEach(item => {
        item.addEventListener('click', () => {
            cameraSelectedId = item.dataset.cameraId;

        });
    });
}

document.addEventListener('livewire:initialized', () => {
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

    scannerQR.start();
});
