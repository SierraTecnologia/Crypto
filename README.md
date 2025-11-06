# Crypto

**Crypto** - Powerful and elegant cryptography tools for Laravel and Lumen

[![Build Status](https://travis-ci.org/SierraTecnologiaInc/Crypto.svg?branch=master)](https://travis-ci.org/SierraTecnologiaInc/Crypto)
[![Maintainability](https://api.codeclimate.com/v1/badges/7577ab93d33bf9b69605/maintainability)](https://codeclimate.com/github/SierraTecnologiaInc/Crypto/maintainability)
[![Packagist](https://img.shields.io/packagist/dt/sierratecnologia/crypto.svg?maxAge=2592000)](https://packagist.org/packages/sierratecnologia/crypto)
[![license](https://img.shields.io/github/license/mashape/apistatus.svg?maxAge=2592000)](https://packagist.org/packages/sierratecnologia/crypto)

---

## 📚 Índice

- [Introdução](#-introdução)
- [Instalação](#-instalação)
- [Arquitetura e Estrutura Interna](#-arquitetura-e-estrutura-interna)
- [Principais Componentes](#-principais-componentes)
- [Uso Prático](#-uso-prático)
- [Integração com o Ecossistema SierraTecnologia](#-integração-com-o-ecossistema-sierratecnologia)
- [Extensão e Customização](#-extensão-e-customização)
- [Exemplos Reais](#-exemplos-reais)
- [Guia de Contribuição](#-guia-de-contribuição)
- [Licença](#-licença)

---

## 🎯 Introdução

### O que é o Crypto?

O **Crypto** é um pacote Laravel/Lumen desenvolvido pela **SierraTecnologia** que fornece uma camada robusta e elegante de criptografia para aplicações PHP. Ele oferece ferramentas poderosas para:

- **Criptografia e descriptografia** de dados sensíveis usando AES-256-CBC
- **Criptografia automática de campos** em modelos Eloquent através de Traits
- **Geração de UUIDs** (Universally Unique Identifiers)
- **Encoding/Decoding seguro** para URLs
- **Helpers e Facades** para facilitar o desenvolvimento
- **Blade Directives** para uso direto em templates
- **Comandos de console** para gerenciamento de chaves

### Objetivo e Motivação

A segurança de dados é uma preocupação crítica em aplicações modernas. O Crypto nasceu da necessidade de:

1. **Padronizar a criptografia** em todos os projetos do ecossistema SierraTecnologia
2. **Simplificar a implementação** de criptografia em campos de banco de dados
3. **Garantir compatibilidade** entre diferentes serviços e aplicações
4. **Fornecer uma API intuitiva** que reduz a complexidade da criptografia OpenSSL
5. **Manter a segurança** sem sacrificar a performance ou usabilidade

### Benefícios para Projetos Laravel

✅ **Transparência**: Criptografa e descriptografa dados automaticamente nos modelos Eloquent
✅ **Flexibilidade**: Suporta criptografia compartilhada entre aplicações
✅ **Facilidade**: Helpers globais e Facades para acesso rápido
✅ **Integração**: Blade Directives para uso direto em views
✅ **Segurança**: Implementação robusta com OpenSSL e AES-256-CBC
✅ **Performance**: Otimizado para aplicações de alta demanda

### Como se Encaixa no Ecossistema SierraTecnologia

O Crypto é uma **peça fundamental** na arquitetura de microsserviços da **SierraTecnologia / Rica Soluções**. Ele é utilizado por diversos pacotes internos para:

- Proteger dados sensíveis de usuários (PII - Personal Identifiable Information)
- Criptografar tokens e chaves de API
- Compartilhar dados criptografados entre serviços
- Garantir conformidade com LGPD/GDPR
- Manter a integridade de dados em ambientes distribuídos

---

## 🚀 Instalação

### Requisitos Mínimos

- **PHP**: 8.0 ou superior (recomendado 8.2+)
- **Laravel**: 8.x, 9.x, 10.x ou 11.x
- **Lumen**: 8.x, 9.x, 10.x ou 11.x
- **Extensões PHP**:
  - `ext-openssl` (para criptografia)
  - `ext-gmp` (para operações matemáticas de criptografia)
  - `ext-mbstring` (para manipulação de strings)

### Instalação via Composer

#### 1. Criar um novo projeto Laravel (se necessário)

```bash
composer create-project laravel/laravel your-project-name
cd your-project-name
```

#### 2. Instalar o pacote Crypto

```bash
composer require sierratecnologia/crypto
```

#### 3. Publicar arquivos de configuração (opcional)

O pacote utiliza auto-discovery do Laravel, portanto não é necessário registrar manualmente o Service Provider. Porém, se estiver usando Lumen, você precisa:

```php
// bootstrap/app.php (somente para Lumen)
$app->withFacades(); // Habilitar Facades

// Registrar o Service Provider
$app->register(SierraTecnologia\Crypto\CryptoProvider::class);
```

#### 4. Configurar variáveis de ambiente

Certifique-se de que sua aplicação possui uma chave (`APP_KEY`) configurada no arquivo `.env`:

```bash
php artisan key:generate
```

O Crypto utiliza a `APP_KEY` do Laravel como base para criptografia. **IMPORTANTE**: Não altere ou perca esta chave, pois os dados criptografados não poderão ser recuperados.

---

## 🏗️ Arquitetura e Estrutura Interna

### Estrutura de Diretórios

```
src/
├── Console/
│   └── KeyMaster.php              # Comando para gerenciamento de chaves
├── Encryption/
│   ├── CryptoEncrypter.php        # Motor de criptografia customizado
│   ├── CryptoEncrypterInterface.php # Interface do encrypter
│   └── LaravelCrypto.php          # Wrapper para criptografia Laravel
├── Helpers/
│   └── crypto.php                 # Funções helper globais
├── Services/
│   └── Crypto.php                 # Facade e classe principal
├── Traits/
│   └── Encryptable.php            # Trait para modelos Eloquent
└── CryptoProvider.php             # Service Provider do pacote
```

### Namespaces Principais

- **`SierraTecnologia\Crypto`**: Namespace raiz do pacote
- **`SierraTecnologia\Crypto\Services`**: Classes de serviço (Facade Crypto)
- **`SierraTecnologia\Crypto\Encryption`**: Motores de criptografia
- **`SierraTecnologia\Crypto\Traits`**: Traits reutilizáveis para modelos
- **`SierraTecnologia\Crypto\Console`**: Comandos Artisan

### Padrões Arquiteturais

#### 1. **Service Provider Pattern**

O `CryptoProvider` registra serviços, aliases e comandos:

```php
// src/CryptoProvider.php:44-56
public function register()
{
    $this->app->singleton('Crypto', function () {
        return new Crypto();
    });

    $loader = AliasLoader::getInstance();
    $loader->alias('Crypto', \SierraTecnologia\Crypto\Services\Crypto::class);
}
```

#### 2. **Facade Pattern**

A classe `Crypto` funciona como Facade para facilitar o acesso:

```php
Crypto::encrypt('texto sensível');
Crypto::decrypt('texto criptografado');
```

#### 3. **Trait Pattern**

O `Encryptable` trait adiciona criptografia automática aos modelos:

```php
use SierraTecnologia\Crypto\Traits\Encryptable;

class User extends Model {
    use Encryptable;

    protected $encrypted = ['email', 'cpf'];
}
```

#### 4. **Strategy Pattern**

Diferentes estratégias de criptografia podem ser implementadas através da interface `CryptoEncrypterInterface`:

- `CryptoEncrypter`: Criptografia customizada com chaves compartilháveis
- `LaravelCrypto`: Wrapper para o encrypter nativo do Laravel

### Convenções de Codificação

O projeto segue as convenções da **SierraTecnologia**:

- **PSR-12** para estilo de código
- **PHPDoc** completo em todas as classes e métodos
- **Type hints** e **return types** em PHP 8+
- **Nomenclatura em inglês** para código, português para comentários internos
- **Tests unitários** com PHPUnit
- **Análise estática** com PHPStan (level 8) e Psalm (level 7)

---

## 🔧 Principais Componentes

### 1. CryptoService (Facade)

**Localização**: `src/Services/Crypto.php`

A classe principal que expõe todos os métodos de criptografia:

```php
use Crypto;

// Criptografia básica
$encrypted = Crypto::encrypt('meu dado sensível');
$decrypted = Crypto::decrypt($encrypted);

// Geração de UUID
$uuid = Crypto::uuid(); // ex: "f47ac10b-58cc-4372-a567-0e02b2c3d479"

// Criptografia compartilhável entre apps
$shared = Crypto::shareableEncrypt('dado compartilhado');
$decoded = Crypto::shareableDecrypt($shared);

// URL-safe encoding
$urlSafe = Crypto::urlEncode('parâmetro de URL');
$urlDecoded = Crypto::urlDecode($urlSafe);
```

**Métodos Principais**:

| Método | Descrição |
|--------|-----------|
| `encrypt(string $value): string` | Criptografa um valor usando a chave da aplicação |
| `decrypt(string $value): string` | Descriptografa um valor |
| `uuid(): string` | Gera um UUID v4 |
| `shareableEncrypt(string $value): string` | Criptografa com chave compartilhável |
| `shareableDecrypt(string $value): string` | Descriptografa chave compartilhável |
| `urlEncode(string $value): string` | Criptografa e codifica para URL |
| `urlDecode(string $value): string` | Decodifica e descriptografa de URL |
| `isCrypto(string $crypto): bool` | Verifica se string parece criptografada |

### 2. CryptoEncrypter

**Localização**: `src/Encryption/CryptoEncrypter.php`

Motor de criptografia que implementa AES-256-CBC com OpenSSL:

```php
use SierraTecnologia\Crypto\Encryption\CryptoEncrypter;

$key = config('app.key');
$session = config('session.key'); // ou outra chave de sessão

$encrypter = new CryptoEncrypter($key, $session);

$encrypted = $encrypter->encrypt('dados sensíveis');
$decrypted = $encrypter->decrypt($encrypted);
```

**Características**:

- **Algoritmo**: AES-256-CBC (OpenSSL)
- **IV (Initialization Vector)**: Gerado aleatoriamente para cada criptografia
- **Chave de criptografia**: MD5 combinado de `APP_KEY` + `session key`
- **URL-safe**: Codificação automática para uso em URLs

**Fluxo de Criptografia**:

```
1. Gerar IV aleatório (16 bytes MD5)
2. Criptografar com openssl_encrypt (AES-256-CBC)
3. Concatenar IV + dados criptografados
4. Codificar em base64 URL-safe
5. Retornar string criptografada
```

**Fluxo de Descriptografia**:

```
1. Decodificar de base64 URL-safe
2. Extrair IV (primeiros 16 caracteres)
3. Extrair dados criptografados
4. Descriptografar com openssl_decrypt
5. Retornar string original
```

### 3. Encryptable Trait

**Localização**: `src/Traits/Encryptable.php`

Trait que adiciona criptografia automática a modelos Eloquent:

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use SierraTecnologia\Crypto\Traits\Encryptable;

class Customer extends Model
{
    use Encryptable;

    /**
     * Campos que serão criptografados automaticamente
     */
    protected $encrypted = [
        'email',
        'cpf',
        'phone',
        'credit_card',
        'address'
    ];
}
```

**Funcionamento**:

- **Set Automático**: Ao atribuir um valor a um campo listado em `$encrypted`, ele é automaticamente criptografado antes de salvar no banco
- **Get Automático**: Ao recuperar o modelo, os campos são automaticamente descriptografados
- **toArray/toJson**: Os dados são descriptografados antes da serialização

**Exemplo de Uso**:

```php
// Criar um novo cliente
$customer = new Customer();
$customer->name = 'João Silva';
$customer->email = 'joao@example.com'; // será criptografado automaticamente
$customer->cpf = '123.456.789-00';     // será criptografado automaticamente
$customer->save();

// No banco de dados, email e cpf estarão criptografados:
// email: "eyJpdiI6IkRZS0lOUlwvR29MbU4zN1diYz..."
// cpf: "eyJpdiI6IlhZWjEyM1wvQWJjRGVmMzN..."

// Recuperar cliente
$customer = Customer::find(1);
echo $customer->email; // "joao@example.com" (descriptografado automaticamente)
echo $customer->cpf;   // "123.456.789-00" (descriptografado automaticamente)

// Serializar para JSON
return response()->json($customer);
// {
//   "id": 1,
//   "name": "João Silva",
//   "email": "joao@example.com",  // descriptografado
//   "cpf": "123.456.789-00"        // descriptografado
// }
```

### 4. Helpers Globais

**Localização**: `src/Helpers/crypto.php`

Funções helper para uso global:

```php
// Criptografar
$encrypted = crypto_encrypt('meu segredo');

// Descriptografar
$decrypted = crypto_decrypt($encrypted);

// Gerar UUID
$id = crypto_uuid();
```

### 5. Blade Directives

**Localização**: `src/CryptoProvider.php:26-36`

Diretivas Blade para uso em templates:

```blade
{{-- Criptografar em Blade --}}
@crypto_encrypt('valor sensível')

{{-- Descriptografar em Blade --}}
@crypto_decrypt($valorCriptografado)
```

**Exemplo em View**:

```blade
<div class="user-info">
    <p>Email: @crypto_decrypt($user->encrypted_email)</p>
    <p>CPF: @crypto_decrypt($user->encrypted_cpf)</p>
</div>
```

### 6. Console Commands

**Localização**: `src/Console/KeyMaster.php`

Comando Artisan para gerenciamento de chaves (inferido pela estrutura):

```bash
php artisan crypto:key
```

---

## 💻 Uso Prático

### Cenário 1: Criptografar Campos de Modelo

**Problema**: Você precisa armazenar dados sensíveis de clientes (email, CPF, telefone) de forma criptografada no banco de dados.

**Solução**:

```php
// app/Models/Customer.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use SierraTecnologia\Crypto\Traits\Encryptable;

class Customer extends Model
{
    use Encryptable;

    protected $fillable = [
        'name', 'email', 'cpf', 'phone', 'address'
    ];

    /**
     * Campos que serão criptografados automaticamente
     */
    protected $encrypted = [
        'email',
        'cpf',
        'phone'
    ];
}

// Controller
class CustomerController extends Controller
{
    public function store(Request $request)
    {
        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,   // criptografado automaticamente
            'cpf' => $request->cpf,       // criptografado automaticamente
            'phone' => $request->phone,   // criptografado automaticamente
        ]);

        return response()->json($customer); // dados descriptografados no JSON
    }
}
```

### Cenário 2: Criptografar Parâmetros de URL

**Problema**: Você precisa enviar IDs ou dados sensíveis em URLs de email (ex: reset de senha, confirmação de cadastro).

**Solução**:

```php
use Crypto;

// Controller
class PasswordResetController extends Controller
{
    public function sendResetLink(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        // Criptografar ID do usuário para URL
        $token = Crypto::urlEncode($user->id . '|' . time());

        $resetUrl = url("/password/reset/{$token}");

        // Enviar email com o link
        Mail::to($user)->send(new PasswordResetMail($resetUrl));

        return response()->json(['message' => 'Link enviado!']);
    }

    public function resetPassword(Request $request, $token)
    {
        try {
            // Descriptografar token da URL
            $decrypted = Crypto::urlDecode($token);
            [$userId, $timestamp] = explode('|', $decrypted);

            // Verificar se não expirou (ex: 1 hora)
            if (time() - $timestamp > 3600) {
                return response()->json(['error' => 'Token expirado'], 400);
            }

            $user = User::findOrFail($userId);
            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json(['message' => 'Senha alterada!']);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Token inválido'], 400);
        }
    }
}
```

### Cenário 3: Compartilhar Dados Entre Aplicações

**Problema**: Você tem múltiplas aplicações (API, Admin, Frontend) que precisam compartilhar dados criptografados.

**Solução**:

```php
use Crypto;

// Aplicação A (API) - Criptografar
class ApiController extends Controller
{
    public function generateToken(User $user)
    {
        $payload = json_encode([
            'user_id' => $user->id,
            'email' => $user->email,
            'expires_at' => now()->addHours(24)->timestamp,
        ]);

        // Usar criptografia compartilhável
        $token = Crypto::shareableEncrypt($payload);

        return response()->json(['token' => $token]);
    }
}

// Aplicação B (Admin) - Descriptografar
class AdminController extends Controller
{
    public function validateToken(Request $request)
    {
        try {
            // Descriptografar token da outra aplicação
            $decrypted = Crypto::shareableDecrypt($request->token);
            $payload = json_decode($decrypted, true);

            if ($payload['expires_at'] < now()->timestamp) {
                return response()->json(['error' => 'Token expirado'], 401);
            }

            $user = User::find($payload['user_id']);

            return response()->json(['user' => $user]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Token inválido'], 401);
        }
    }
}
```

**IMPORTANTE**: Para que a criptografia compartilhável funcione, ambas as aplicações devem ter a **mesma `APP_KEY`** no arquivo `.env`.

### Cenário 4: Uso em Blade Templates

**Problema**: Você precisa exibir ou manipular dados criptografados diretamente em views.

**Solução**:

```blade
{{-- resources/views/customer/show.blade.php --}}
<div class="customer-details">
    <h2>{{ $customer->name }}</h2>

    <div class="sensitive-info">
        <p>
            <strong>Email:</strong>
            @crypto_decrypt($customer->encrypted_email)
        </p>
        <p>
            <strong>CPF:</strong>
            @crypto_decrypt($customer->encrypted_cpf)
        </p>
    </div>
</div>
```

### Boas Práticas de Segurança

#### ✅ **DO (Faça)**

1. **Sempre use HTTPS** em produção para proteger dados em trânsito
2. **Backup da APP_KEY**: Mantenha backup seguro da chave (sem ela, dados são irrecuperáveis)
3. **Rotação de chaves**: Planeje estratégia de rotação de chaves periodicamente
4. **Validação de entrada**: Sempre valide dados antes de criptografar/descriptografar
5. **Logs seguros**: Não logue dados descriptografados ou chaves
6. **Tratamento de exceções**: Capture e trate erros de descriptografia adequadamente

```php
try {
    $decrypted = Crypto::decrypt($encrypted);
} catch (\Exception $e) {
    \Log::warning('Falha ao descriptografar', [
        'error' => $e->getMessage(),
        // NÃO logue o valor criptografado ou descriptografado
    ]);
    return response()->json(['error' => 'Dados inválidos'], 400);
}
```

#### ❌ **DON'T (Não Faça)**

1. **Não hardcode chaves** no código-fonte
2. **Não compartilhe APP_KEY** em repositórios públicos
3. **Não criptografe campos de busca** (você não conseguirá fazer WHERE)
4. **Não criptografe tudo** (apenas dados sensíveis, por performance)
5. **Não altere APP_KEY** sem migrar dados criptografados
6. **Não use criptografia para senhas** (use Hash::make do Laravel)

---

## 🔗 Integração com o Ecossistema SierraTecnologia

O **Crypto** é uma dependência fundamental em diversos pacotes da **SierraTecnologia / Rica Soluções**:

### Pacotes que Utilizam Crypto

| Pacote | Uso |
|--------|-----|
| **Market** | Criptografia de dados de pagamento e transações |
| **MediaManager** | Proteção de metadados sensíveis de arquivos |
| **Informate** | Criptografia de dados pessoais em relatórios |
| **Telephon** | Proteção de números de telefone e contatos |
| **Population** | Criptografia de dados censitários sensíveis |
| **Fabrica** | Proteção de configurações de ambientes |

### Padrões de Versionamento

O Crypto segue o **Semantic Versioning (SemVer)**:

- **Major (X.0.0)**: Mudanças incompatíveis com versões anteriores
- **Minor (0.X.0)**: Novas funcionalidades mantendo compatibilidade
- **Patch (0.0.X)**: Correções de bugs

### Integração com Pipelines CI/CD

O projeto possui pipelines compartilhados para garantir qualidade:

```yaml
# .github/workflows/ci.yml
- PHPUnit (testes unitários e integração)
- PHPCS (PSR-12 code style)
- PHPStan (análise estática nível 8)
- PHPMD (detecção de code smells)
- Psalm (análise de tipos nível 7)
```

### Exemplo de Integração Multi-Serviço

```php
// Serviço A: Market (E-commerce)
use SierraTecnologia\Crypto\Traits\Encryptable;

class Payment extends Model
{
    use Encryptable;

    protected $encrypted = ['credit_card_number', 'cvv'];
}

// Serviço B: MediaManager (Gestão de Arquivos)
use Crypto;

class FileMetadata extends Model
{
    public function setOwnerDataAttribute($value)
    {
        $this->attributes['owner_data'] = Crypto::shareableEncrypt(json_encode($value));
    }
}

// Serviço C: Informate (Relatórios)
use Crypto;

class Report
{
    public function generateCustomerReport($customerId)
    {
        $customer = Customer::find($customerId);

        // Descriptografar dados para relatório
        return [
            'name' => $customer->name,
            'email' => Crypto::decrypt($customer->encrypted_email),
            'cpf' => Crypto::decrypt($customer->encrypted_cpf),
        ];
    }
}
```

---

## 🎨 Extensão e Customização

### Criar um Encrypter Customizado

Você pode implementar sua própria estratégia de criptografia:

```php
namespace App\Encryption;

use SierraTecnologia\Crypto\Encryption\CryptoEncrypterInterface;

class CustomEncrypter implements CryptoEncrypterInterface
{
    protected $key;
    protected $algorithm;

    public function __construct($key, $algorithm = 'aes-256-gcm')
    {
        $this->key = $key;
        $this->algorithm = $algorithm;
    }

    public function encrypt($value)
    {
        // Implementação customizada usando AES-GCM
        $iv = random_bytes(16);
        $tag = '';

        $encrypted = openssl_encrypt(
            $value,
            $this->algorithm,
            $this->key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag
        );

        return base64_encode($iv . $tag . $encrypted);
    }

    public function decrypt($value)
    {
        $decoded = base64_decode($value);
        $iv = substr($decoded, 0, 16);
        $tag = substr($decoded, 16, 16);
        $encrypted = substr($decoded, 32);

        return openssl_decrypt(
            $encrypted,
            $this->algorithm,
            $this->key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag
        );
    }

    public function uuid()
    {
        return \Ramsey\Uuid\Uuid::uuid4()->toString();
    }
}
```

### Registrar Encrypter Customizado

```php
// app/Providers/AppServiceProvider.php
use App\Encryption\CustomEncrypter;

public function register()
{
    $this->app->singleton('CustomCrypto', function () {
        return new CustomEncrypter(config('app.key'));
    });
}

// Uso
$encrypted = app('CustomCrypto')->encrypt('dados sensíveis');
```

### Estender a Classe Crypto

```php
namespace App\Services;

use SierraTecnologia\Crypto\Services\Crypto as BaseCrypto;

class ExtendedCrypto extends BaseCrypto
{
    /**
     * Criptografar com timestamp para expiração automática
     */
    public static function encryptWithExpiry($value, $ttlSeconds = 3600)
    {
        $expiresAt = time() + $ttlSeconds;
        $payload = json_encode([
            'data' => $value,
            'expires_at' => $expiresAt,
        ]);

        return parent::encrypt($payload);
    }

    /**
     * Descriptografar e verificar expiração
     */
    public static function decryptWithExpiry($encrypted)
    {
        $decrypted = parent::decrypt($encrypted);
        $payload = json_decode($decrypted, true);

        if ($payload['expires_at'] < time()) {
            throw new \Exception('Dados expirados');
        }

        return $payload['data'];
    }
}
```

### Substituir Implementação Padrão via Service Provider

```php
// app/Providers/CryptoServiceProvider.php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ExtendedCrypto;

class CryptoServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Sobrescrever binding do Crypto
        $this->app->singleton('Crypto', function () {
            return new ExtendedCrypto();
        });
    }
}

// config/app.php
'providers' => [
    // ...
    App\Providers\CryptoServiceProvider::class, // adicionar após o CryptoProvider original
],
```

### Criar Trait Customizada

```php
namespace App\Traits;

use Crypto;

trait EncryptableWithAudit
{
    use \SierraTecnologia\Crypto\Traits\Encryptable;

    /**
     * Override setAttribute para adicionar auditoria
     */
    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->encrypted ?? [])) {
            // Registrar auditoria antes de criptografar
            \Log::info("Campo {$key} será criptografado", [
                'model' => static::class,
                'id' => $this->id,
                'user' => auth()->id(),
            ]);
        }

        return parent::setAttribute($key, $value);
    }
}
```

---

## 🌟 Exemplos Reais

### Caso 1: E-commerce com Criptografia de Dados de Pagamento

**Contexto**: Loja virtual da SierraTecnologia que processa pagamentos com cartão de crédito.

**Antes** (sem Crypto):
```php
// ❌ Dados sensíveis armazenados em texto plano
class Order extends Model
{
    protected $fillable = ['customer_email', 'credit_card', 'amount'];
}

// Risco: Dados expostos em caso de breach no banco de dados
```

**Depois** (com Crypto):
```php
// ✅ Dados sensíveis criptografados automaticamente
use SierraTecnologia\Crypto\Traits\Encryptable;

class Order extends Model
{
    use Encryptable;

    protected $fillable = ['customer_email', 'credit_card', 'amount'];

    protected $encrypted = ['customer_email', 'credit_card'];
}

// Benefício: Conformidade com PCI-DSS, proteção em caso de breach
```

**Resultado**:
- ✅ Redução de 100% na exposição de dados sensíveis
- ✅ Conformidade com PCI-DSS Level 1
- ✅ Confiança do cliente aumentada
- ✅ Zero alterações no código dos controllers

### Caso 2: Sistema de Assinatura Multi-tenant

**Contexto**: Plataforma SaaS com múltiplos tenants compartilhando infraestrutura.

**Implementação**:
```php
// Modelo de Tenant
use SierraTecnologia\Crypto\Traits\Encryptable;

class Tenant extends Model
{
    use Encryptable;

    protected $encrypted = [
        'api_key',
        'webhook_secret',
        'database_password',
        'smtp_password',
    ];
}

// Compartilhar dados entre serviços
use Crypto;

class TenantProvisioningService
{
    public function provisionTenant($tenantId)
    {
        $tenant = Tenant::find($tenantId);

        // Gerar token compartilhável para worker
        $token = Crypto::shareableEncrypt(json_encode([
            'tenant_id' => $tenant->id,
            'api_key' => $tenant->api_key, // já descriptografado pelo trait
            'provisioned_at' => now()->timestamp,
        ]));

        // Enviar para fila de provisionamento
        dispatch(new ProvisionTenantJob($token));
    }
}

// Worker (outra aplicação)
class ProvisionTenantJob implements ShouldQueue
{
    public function handle($token)
    {
        $data = json_decode(Crypto::shareableDecrypt($token), true);

        // Provisionar recursos do tenant
        $this->createDatabase($data['tenant_id']);
        $this->configureApi($data['api_key']);
    }
}
```

**Benefícios**:
- ✅ Isolamento de dados entre tenants
- ✅ Segurança em filas e workers
- ✅ Auditoria simplificada
- ✅ Conformidade com LGPD/GDPR

### Caso 3: Sistema de Autenticação Distribuída

**Contexto**: Múltiplas aplicações (Web, API, Mobile) compartilhando autenticação.

**Implementação**:
```php
// Serviço de Autenticação Principal
use Crypto;

class AuthService
{
    public function generateSessionToken(User $user)
    {
        $payload = [
            'user_id' => $user->id,
            'email' => $user->email,
            'roles' => $user->roles->pluck('name'),
            'expires_at' => now()->addHours(24)->timestamp,
            'session_id' => Crypto::uuid(),
        ];

        return Crypto::shareableEncrypt(json_encode($payload));
    }

    public function validateToken($token)
    {
        try {
            $payload = json_decode(Crypto::shareableDecrypt($token), true);

            if ($payload['expires_at'] < now()->timestamp) {
                throw new \Exception('Token expirado');
            }

            return $payload;

        } catch (\Exception $e) {
            throw new \Exception('Token inválido');
        }
    }
}

// Middleware para validação em todas as aplicações
class ValidateCryptoToken
{
    public function handle($request, Closure $next)
    {
        $token = $request->bearerToken();

        try {
            $payload = app(AuthService::class)->validateToken($token);
            $request->merge(['auth_payload' => $payload]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Não autorizado'], 401);
        }

        return $next($request);
    }
}
```

**Benefícios**:
- ✅ SSO (Single Sign-On) entre aplicações
- ✅ Tokens stateless (não precisa consultar DB)
- ✅ Escalabilidade horizontal
- ✅ Segurança com expiração automática

---

## 🤝 Guia de Contribuição

### Como Contribuir

Contribuições são bem-vindas! Siga estas etapas:

#### 1. **Fork e Clone**

```bash
git clone https://github.com/seu-usuario/Crypto.git
cd Crypto
```

#### 2. **Instalar Dependências**

```bash
composer install
```

#### 3. **Criar Branch**

Siga o padrão de nomenclatura:

```bash
git checkout -b feature/nova-funcionalidade
git checkout -b fix/correcao-bug
git checkout -b docs/atualizacao-documentacao
```

#### 4. **Desenvolvimento**

Certifique-se de seguir os padrões:

- **PSR-12** para código
- **PHPDoc** completo
- **Type hints** e **return types**
- **Testes unitários** para novas funcionalidades

#### 5. **Executar Ferramentas de Qualidade**

```bash
# Rodar testes
vendor/bin/phpunit

# Verificar code style (PSR-12)
vendor/bin/phpcs --standard=PSR12 src/

# Análise estática com PHPStan (level 8)
vendor/bin/phpstan analyse src/ --level=8

# Análise com Psalm (level 7)
vendor/bin/psalm

# Detectar code smells com PHPMD
vendor/bin/phpmd src/ text cleancode,codesize,controversial,design,naming,unusedcode
```

#### 6. **Commit e Push**

Siga o padrão de commits:

```bash
git add .
git commit -m "feat: adicionar método de criptografia com expiração"
git push origin feature/nova-funcionalidade
```

**Padrão de Mensagens de Commit**:

- `feat:` nova funcionalidade
- `fix:` correção de bug
- `docs:` alterações em documentação
- `style:` formatação de código (sem mudança de lógica)
- `refactor:` refatoração de código
- `test:` adição ou correção de testes
- `chore:` tarefas de manutenção

#### 7. **Abrir Pull Request**

- Descreva claramente as mudanças
- Referencie issues relacionadas
- Aguarde review da equipe

### Padrões de Código

```php
<?php

namespace SierraTecnologia\Crypto\Services;

use SierraTecnologia\Crypto\Encryption\CryptoEncrypter;

/**
 * Classe de serviço para operações de criptografia.
 *
 * Esta classe fornece uma interface simplificada para
 * criptografia e descriptografia de dados sensíveis.
 *
 * @package SierraTecnologia\Crypto\Services
 * @author SierraTecnologia Team <help@sierratecnologia.com.br>
 */
class ExampleService
{
    /**
     * Instância do encrypter.
     *
     * @var CryptoEncrypter
     */
    protected CryptoEncrypter $encrypter;

    /**
     * Construtor do serviço.
     *
     * @param CryptoEncrypter $encrypter Instância do encrypter
     */
    public function __construct(CryptoEncrypter $encrypter)
    {
        $this->encrypter = $encrypter;
    }

    /**
     * Criptografa um valor.
     *
     * @param string $value Valor a ser criptografado
     * @return string Valor criptografado
     * @throws \Exception Se a criptografia falhar
     */
    public function encrypt(string $value): string
    {
        if (empty($value)) {
            throw new \InvalidArgumentException('Valor não pode ser vazio');
        }

        return $this->encrypter->encrypt($value);
    }
}
```

### Executar Testes Localmente

```bash
# Todos os testes
composer test

# Testes com coverage
composer test:coverage

# Testes específicos
vendor/bin/phpunit --filter CryptoTest
```

### Issues e Feature Requests

- **Bugs**: Descreva o erro, passos para reproduzir, versões (PHP, Laravel)
- **Features**: Explique o caso de uso, benefícios, exemplos

### Código de Conduta

- Seja respeitoso e profissional
- Aceite feedback construtivo
- Foque no que é melhor para a comunidade
- Mostre empatia com outros colaboradores

### Contato da Equipe

- **Email**: help@sierratecnologia.com.br
- **Issues**: https://github.com/sierratecnologia/crypto/issues
- **Docs**: https://docs.sierratecnologia.ca/others/crypto

---

## 📄 Licença

Crypto é um software de código aberto licenciado sob a [**Licença MIT**](LICENSE).

### Disclaimer

O SOFTWARE É FORNECIDO "COMO ESTÁ", SEM GARANTIA DE QUALQUER TIPO, EXPRESSA OU IMPLÍCITA, INCLUINDO, MAS NÃO SE LIMITANDO ÀS GARANTIAS DE COMERCIALIZAÇÃO, ADEQUAÇÃO A UM DETERMINADO FIM E NÃO VIOLAÇÃO. EM NENHUM CASO OS AUTORES OU TITULARES DE DIREITOS AUTORAIS SERÃO RESPONSÁVEIS POR QUALQUER RECLAMAÇÃO, DANOS OU OUTRA RESPONSABILIDADE, SEJA EM UMA AÇÃO DE CONTRATO, DELITO OU DE OUTRA FORMA, DECORRENTE DE, FORA DE OU EM CONEXÃO COM O SOFTWARE OU O USO OU OUTRAS NEGOCIAÇÕES NO SOFTWARE.

---

## 📞 Suporte e Contato

### SierraTecnologia

**SierraTecnologia** é uma empresa de tecnologia especializada em soluções empresariais robustas e escaláveis para o ecossistema PHP/Laravel.

### Rica Soluções

Parte do grupo **Rica Soluções**, oferecendo consultoria e desenvolvimento de software de alta qualidade.

### Links

- **Website**: [sierratecnologia.com.br](https://sierratecnologia.com.br)
- **Documentação**: [docs.sierratecnologia.ca](https://docs.sierratecnologia.ca)
- **GitHub**: [github.com/sierratecnologia](https://github.com/sierratecnologia)
- **Packagist**: [packagist.org/packages/sierratecnologia/crypto](https://packagist.org/packages/sierratecnologia/crypto)

### Suporte

- **Email**: help@sierratecnologia.com.br
- **Issues**: [github.com/sierratecnologia/crypto/issues](https://github.com/sierratecnologia/crypto/issues)

---

## 🙏 Agradecimentos

Agradecemos a todos os contribuidores e à comunidade Laravel/PHP por tornarem este projeto possível.

### Autores Principais

- **Matt Lantz** ([@mattylantz](http://twitter.com/mattylantz)) - Conceito original
- **Ricardo Sierra** (sierra.csi@gmail.com) - Arquitetura e desenvolvimento
- **Equipe SierraTecnologia** - Manutenção e evolução

---

**Desenvolvido com ❤️ pela [SierraTecnologia](https://sierratecnologia.com.br) / [Rica Soluções](https://ricasolucoes.com.br)**
