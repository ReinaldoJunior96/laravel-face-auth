# Laravel FaceAuth Package

Reconhecimento facial plug-and-play para autenticação automática de usuários em projetos Laravel.

## Recursos
- Login automático via reconhecimento facial (face-api.js)
- Modal de autenticação facial em tempo real
- Suporte a múltiplas imagens por usuário (1 a 5)
- Publicação automática de assets, modelos, views e config
- Rotas, controllers e assets prontos para uso
- Segurança: rate limiting, nomes de arquivos aleatórios, dados sensíveis ocultos

---

## Instalação

### 1. Instale o package via Composer

```bash
composer require faceauth/laravel-face-auth
```

### 2. Publique os assets, migrations, views e config

```bash
php artisan vendor:publish --provider="FaceAuth\\FaceAuthServiceProvider"
```

### 3. Execute as migrations

```bash
php artisan migrate
```

### 4. Configure o caminho das imagens e prefixo das rotas

No seu `.env`:

```env
FACEAUTH_USERS_IMAGE_PATH=storage/app/private
FACEAUTH_ROUTE_PREFIX=faceauth # (opcional, para customizar o prefixo das rotas)
```

Se necessário, ajuste também em `config/faceauth.php`.

### 5. Permissões de pasta

Garanta que o diretório definido em `FACEAUTH_USERS_IMAGE_PATH` seja gravável pelo PHP.

### 6. Adicione o botão de login facial na view desejada

Inclua o blade onde quiser exibir o botão/modal:

```blade
@include('faceauth::face-login-button')
```

### 7. Garanta o meta CSRF no seu layout principal

No `<head>` do seu layout principal (ex: `resources/views/layouts/app.blade.php`):

```blade
<meta name="csrf-token" content="{{ csrf_token() }}">
```

### 8. Ajuste o formulário de cadastro para múltiplas imagens

No seu formulário de cadastro:

```html
<input type="file" name="face_image[]" multiple required>
```

No controller, aceite de 1 a 5 imagens (veja exemplo no código do package). Cada imagem será salva com nome aleatório/hash.

---

## Fluxo de uso
1. Usuário acessa o botão/modal de login facial
2. O sistema reconhece o rosto em tempo real
3. Se reconhecido, faz login automático e redireciona para o dashboard

---

## Segurança e Boas Práticas
- As rotas do package já usam middleware `web` e rate limiting (10 req/min).
- Os endpoints não expõem dados sensíveis, apenas identificadores genéricos.
- Os arquivos de imagem são salvos com nomes aleatórios/hash.
- Não exponha o diretório de imagens diretamente via webserver.
- Se desejar, proteja a rota de imagens para exigir autenticação.
- Para debug, veja os logs em `storage/logs/laravel.log`.

---

## Customização
- O prefixo das rotas pode ser alterado via `.env` ou `config/faceauth.php`.
- O caminho das imagens pode ser alterado via `.env` ou `config/faceauth.php`.

---

## Contribuição
Pull requests são bem-vindos! Abra uma issue para sugestões ou bugs.

---

## Licença
MIT

---

**Desenvolvido com ❤️ para facilitar o login facial no Laravel!**
