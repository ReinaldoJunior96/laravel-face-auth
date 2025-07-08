<button id="faceauth-login-btn" type="button" style="background:#2d3748;color:#fff;padding:10px 20px;border:none;border-radius:4px;cursor:pointer;">
    Login com Reconhecimento Facial
</button>

<!-- Modal -->
<div id="faceauth-modal" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.5);align-items:center;justify-content:center;z-index:9999;">
    <div style="background:#fff;padding:24px;border-radius:8px;max-width:400px;width:100%;text-align:center;position:relative;">
        <h3>Reconhecimento Facial</h3>
        <video id="faceauth-video" width="320" height="240" autoplay style="border-radius:8px;position:relative;z-index:1;"></video>
        <canvas id="faceauth-overlay" width="320" height="240" style="position:absolute;top:0;left:0;z-index:2;pointer-events:none;"></canvas>
        <br>
        <button id="faceauth-capture-btn" style="margin-top:12px;">Capturar e Validar</button>
        <button id="faceauth-cancel-btn" style="margin-top:12px;background:#eee;color:#333;">Cancelar</button>
        <div id="faceauth-status" style="margin-top:10px;color:#e53e3e;"></div>
    </div>
</div>

@once
    <script src="{{ asset('vendor/faceauth/face-api.min.js') }}"></script>
@endonce
<script>
const btn = document.getElementById('faceauth-login-btn');
const modal = document.getElementById('faceauth-modal');
const video = document.getElementById('faceauth-video');
const captureBtn = document.getElementById('faceauth-capture-btn');
const cancelBtn = document.getElementById('faceauth-cancel-btn');
const statusDiv = document.getElementById('faceauth-status');
const overlay = document.getElementById('faceauth-overlay');

let labeledFaceDescriptors = [];
let faceMatcher = null;
let recognizing = false;

async function fetchFaceImages() {
    const res = await fetch('/faceauth/faces');
    return await res.json(); // [{user_id, name, url}]
}

async function loadLabeledImages() {
    const faces = await fetchFaceImages();
    const labels = {};
    for (const face of faces) {
        if (!labels[face.name]) labels[face.name] = [];
        labels[face.name].push(face.url);
    }
    return Promise.all(Object.entries(labels).map(async ([name, urls]) => {
        const descriptors = [];
        for (const url of urls) {
            const img = await faceapi.fetchImage(url);
            const detection = await faceapi.detectSingleFace(img, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks().withFaceDescriptor();
            if (detection) descriptors.push(detection.descriptor);
        }
        return new faceapi.LabeledFaceDescriptors(name, descriptors);
    }));
}

btn.onclick = async () => {
    modal.style.display = 'flex';
    statusDiv.innerText = 'Carregando modelos...';
    navigator.mediaDevices.getUserMedia({ video: true })
        .then(stream => { video.srcObject = stream; })
        .catch(() => { statusDiv.innerText = 'Não foi possível acessar a câmera.'; });
    // Carrega modelos do face-api.js
    await faceapi.nets.tinyFaceDetector.loadFromUri('/vendor/faceauth/models');
    await faceapi.nets.faceLandmark68Net.loadFromUri('/vendor/faceauth/models');
    await faceapi.nets.faceRecognitionNet.loadFromUri('/vendor/faceauth/models');
    statusDiv.innerText = 'Carregando fotos dos usuários...';
    labeledFaceDescriptors = await loadLabeledImages();
    faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors, 0.6);
    statusDiv.innerText = '';
    recognizing = true;
    recognizeLoop();
};

cancelBtn.onclick = () => {
    modal.style.display = 'none';
    recognizing = false;
    if (video.srcObject) video.srcObject.getTracks().forEach(t => t.stop());
};

async function recognizeLoop() {
    if (!recognizing) return;
    if (video.readyState === 4) {
        const overlayCtx = overlay.getContext('2d');
        overlayCtx.clearRect(0, 0, overlay.width, overlay.height);
        const detection = await faceapi.detectSingleFace(video, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks().withFaceDescriptor();
        if (detection) {
            const dims = faceapi.matchDimensions(overlay, video, true);
            const resizedDet = faceapi.resizeResults(detection, dims);
            const bestMatch = faceMatcher.findBestMatch(detection.descriptor);

            // Desenha caixa, landmarks, score e legenda
            const ctx = overlay.getContext('2d');
            ctx.strokeStyle = '#00FF00';
            ctx.lineWidth = 2;
            const box = resizedDet.detection.box;
            ctx.strokeRect(box.x, box.y, box.width, box.height);
            ctx.font = '14px Arial';
            ctx.fillStyle = '#00FF00';
            const score = (resizedDet.detection.score * 100).toFixed(2) + '%';
            ctx.fillText(`Score: ${score}`, box.x, box.y - 25);
            faceapi.draw.drawFaceLandmarks(overlay, resizedDet);

            if (bestMatch.label !== 'unknown') {
                ctx.font = '16px Arial';
                ctx.fillStyle = '#00FF00';
                ctx.fillText(bestMatch.label, box.x, box.y - 10);
                statusDiv.innerText = `Usuário identificado: ${bestMatch.label}`;
                // Extrai o ID do usuário do label (ex: "Usuário 1" -> 1)
                const userId = bestMatch.label.match(/\d+/)?.[0];
                if (userId) {
                    fetch('/faceauth/login-by-id', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ user_id: userId })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.redirect) {
                            recognizing = false;
                            window.location.href = data.redirect;
                        }
                    });
                }
            } else {
                statusDiv.innerText = 'Usuário não reconhecido.';
            }
        } else {
            statusDiv.innerText = 'Nenhum rosto detectado.';
        }
    }
    setTimeout(recognizeLoop, 500); // repete a cada 500ms
}
</script>
