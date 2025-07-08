<button id="faceauth-login-btn" type="button" style="background:#2d3748;color:#fff;padding:10px 20px;border:none;border-radius:4px;cursor:pointer;">
    Login com Reconhecimento Facial
</button>

<!-- Modal -->
<div id="faceauth-modal" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.5);align-items:center;justify-content:center;z-index:9999;">
    <div style="background:#fff;padding:24px;border-radius:8px;max-width:400px;width:100%;text-align:center;position:relative;">
        <h3>Reconhecimento Facial</h3>
        <video id="faceauth-video" width="320" height="240" autoplay style="border-radius:8px;"></video>
        <br>
        <button id="faceauth-capture-btn" style="margin-top:12px;">Capturar e Validar</button>
        <button id="faceauth-cancel-btn" style="margin-top:12px;background:#eee;color:#333;">Cancelar</button>
        <div id="faceauth-status" style="margin-top:10px;color:#e53e3e;"></div>
    </div>
</div>

<script src="/vendor/faceauth/face-api.min.js"></script>
<script>
// Exemplo básico de integração JS
const btn = document.getElementById('faceauth-login-btn');
const modal = document.getElementById('faceauth-modal');
const video = document.getElementById('faceauth-video');
const captureBtn = document.getElementById('faceauth-capture-btn');
const cancelBtn = document.getElementById('faceauth-cancel-btn');
const statusDiv = document.getElementById('faceauth-status');

btn.onclick = () => {
    modal.style.display = 'flex';
    statusDiv.innerText = '';
    navigator.mediaDevices.getUserMedia({ video: true })
        .then(stream => { video.srcObject = stream; })
        .catch(() => { statusDiv.innerText = 'Não foi possível acessar a câmera.'; });
};

cancelBtn.onclick = () => {
    modal.style.display = 'none';
    if (video.srcObject) video.srcObject.getTracks().forEach(t => t.stop());
};

captureBtn.onclick = async () => {
    statusDiv.innerText = 'Validando...';
    // Aqui você pode capturar a imagem e enviar para o backend
    // Exemplo: capturar frame
    const canvas = document.createElement('canvas');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video, 0, 0);
    const imageData = canvas.toDataURL('image/png');
    // TODO: Enviar imageData para rota de validação facial
    statusDiv.innerText = 'Reconhecimento facial não implementado (exemplo de UI).';
};
</script>
