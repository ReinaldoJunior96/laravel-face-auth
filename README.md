# Login Fácil Package

Pacote Laravel para login facial utilizando FaceAPI.

## O que o package faz?
- Ao instalar e rodar `php artisan vendor:publish --tag=login-facil-migrations`, será criada uma migration para adicionar o campo `face_image` (foto do usuário) na tabela `users`.
- O campo é do tipo `text` (pode armazenar base64, hash, ou caminho da imagem, conforme sua estratégia).
- Futuramente, o package poderá ser expandido para lidar com o reconhecimento facial e autenticação.

## Instalação
1. Adicione o package ao seu projeto Laravel via Composer:
   ```bash
   composer require reinaldojunior/login-facil-package
   ```
2. Publique a migration:
   ```bash
   php artisan vendor:publish --tag=login-facil-migrations
   ```
3. Rode as migrations:
   ```bash
   php artisan migrate
   ```

## Customização
Se sua tabela de usuários tem outro nome, basta copiar a migration publicada, ajustar o nome da tabela e rodar novamente.

## Próximos passos
- Implementar integração com FaceAPI para reconhecimento facial.
- Adicionar comandos/artisan para cadastro e autenticação facial.

---

Contribuições são bem-vindas!
