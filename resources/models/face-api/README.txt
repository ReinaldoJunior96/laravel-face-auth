# Instruções para uso dos arquivos do face-api.js no seu package Laravel

## 1. JS principal
- Copie o arquivo:
  - `face-api.js/dist/face-api.min.js`
- Para:
  - `resources/js/face-api.min.js`

## 2. Modelos necessários (para reconhecimento facial)
- Copie da pasta `face-api.js/weights/` para `resources/models/face-api/` os arquivos:
  - `tiny_face_detector_model-weights_manifest.json`
  - `tiny_face_detector_model-shard1`
  - `face_landmark_68_model-weights_manifest.json`
  - `face_landmark_68_model-shard1`
  - `face_recognition_model-weights_manifest.json`
  - `face_recognition_model-shard1`
  - `face_recognition_model-shard2`

## 3. (Opcional) Outros modelos
- Se quiser usar detecção de idade, expressão, etc, copie também:
  - `age_gender_model-*`
  - `face_expression_model-*`

## 4. Publicação dos modelos
- No ServiceProvider, publique a pasta `resources/models/face-api` para `public/vendor/faceauth/models`.
- Assim, o JS poderá carregar os modelos via `/vendor/faceauth/models`.

## 5. Após copiar, pode apagar a pasta `face-api.js` clonada.

---

**Resumo:**
- Só precisa do JS minificado e dos modelos citados acima para reconhecimento facial funcionar.
- Não publique o repositório inteiro, só os arquivos essenciais!
