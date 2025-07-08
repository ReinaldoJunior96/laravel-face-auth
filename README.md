# FaceAuth Laravel Package

Pacote Laravel para login facial utilizando FaceAPI.

## O que o package faz?
- Ao instalar e rodar `php artisan vendor:publish --tag=faceauth-migrations`, será criada uma migration para adicionar a tabela `faceauth_faces`, que armazena as imagens faciais dos usuários.
- A tabela `faceauth_faces` possui:
  - `user_id`: referência ao usuário
  - `face_image`: campo para armazenar a imagem facial (base64, hash, ou caminho da imagem)
  - timestamps
- Não altera a tabela `users` do projeto, mantendo o package desacoplado e seguro para qualquer aplicação.
- Futuramente, o package poderá ser expandido para lidar com o reconhecimento facial e autenticação.

## Instalação
1. Adicione o package ao seu projeto Laravel via Composer:
   ```bash
   composer require faceauth/laravel-face-auth
   ```
2. Publique a migration:
   ```bash
   php artisan vendor:publish --tag=faceauth-migrations
   ```
3. Rode as migrations:
   ```bash
   php artisan migrate
   ```

## Customização
Se sua tabela de usuários tem outro nome ou chave primária, ajuste a migration publicada conforme necessário.

## Próximos passos
- Implementar integração com FaceAPI para reconhecimento facial.
- Adicionar comandos/artisan para cadastro e autenticação facial.

---

Contribuições são bem-vindas!
