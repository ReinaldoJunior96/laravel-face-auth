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

### 2. Publique os assets, migrations, views, models e config

O comando abaixo publica tudo de uma vez (recomendado):

```bash
php artisan vendor:publish --provider="FaceAuth\\FaceAuthServiceProvider"
```

Se quiser publicar apenas partes específicas:
- Apenas assets e modelos:
  ```bash
  php artisan vendor:publish --tag=faceauth-assets --force
  ```
- Apenas migrations:
  ```bash
  php artisan vendor:publish --tag=faceauth-migrations
  ```
- Apenas config:
  ```bash
  php artisan vendor:publish --tag=faceauth-config
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

## Como cadastrar usuários com fotos para reconhecimento facial

Para que o login facial funcione, é obrigatório que cada usuário tenha pelo menos 1 foto cadastrada (máximo 5). O upload dessas imagens deve ser feito no momento do cadastro do usuário no seu sistema.

### Exemplo de formulário de cadastro

```blade
<form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
    @csrf
    <input type="text" name="name" required placeholder="Nome">
    <input type="email" name="email" required placeholder="Email">
    <input type="password" name="password" required placeholder="Senha">
    <input type="file" name="face_image[]" accept="image/*" multiple required>
    <button type="submit">Cadastrar</button>
</form>
```

### Exemplo de código backend (Controller)

```php
public function register(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:3',
        'face_image' => 'required|array|min:1|max:5',
        'face_image.*' => 'image|max:2048',
    ]);

    DB::transaction(function () use ($request) {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        foreach ($request->file('face_image') as $file) {
            $hashName = md5(uniqid() . microtime(true)) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs("users/{$user->id}", $hashName, 'local');
            DB::table('faceauth_faces')->insert([
                'user_id' => $user->id,
                'face_image' => $path,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    });

    // ... restante do fluxo
}
```

> **Importante:**
> - O cadastro de fotos é obrigatório para o reconhecimento facial funcionar.
> - Adapte o exemplo acima conforme a estrutura da sua tabela de usuários.
> - O package não altera sua tabela de usuários, apenas utiliza a tabela `faceauth_faces` para armazenar as imagens.

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
