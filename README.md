# 🌱 EcoLink — Plataforma Comunitária de Pontos de Descarte

O **EcoLink** é uma plataforma desenvolvida em Laravel que permite que qualquer pessoa visualize, cadastre e ajude a mapear pontos de descarte de lixo e materiais recicláveis em sua cidade.  
O objetivo é facilitar o acesso a locais adequados de descarte, promover a colaboração comunitária e contribuir significativamente para a preservação do meio ambiente.  
O projeto busca incentivar práticas sustentáveis e reduzir o impacto ambiental causado pelo descarte incorreto de resíduos.

---

## Tecnologias e Recursos Utilizados

- **Laravel 11**
- **PHP 8.2+**
- **MySQL**
- **Leaflet.js**
- **TailwindCSS**
- **Composer**
- **NPM + Vite**
- **Laravel Breeze (autenticação)**
- **Arquitetura organizada em:**
  - `App/Http/Controllers`
  - `App/Http/Repositories`
  - `App/Http/Services`
  - `App/Http/Requests`
  - `App/Http/Middleware`
  - `App/Models`
  - `App/Interfaces`
  - `App/Providers`

---

##  Instalação e Execução

### **1. Clonar o repositório**
```bash
git clone https://github.com/askuovye/EcoLink.git
cd EcoLink

### **2. Instalar dependências**
```bash
composer install
npm install
```

### **3. Configurar os arquivos**
```bash
cp .env.example .env
Database
APP_URL
Credenciais do Breeze
Outras configs necessárias
```

### **4. Gerar chave da aplicação**
```bash
php artisan key:generate
```

### **5. Rodar as migrations**
```bash
php artisan migrate
```

### **6. Iniciar o servidor**
```bash
php artisan serve
npm run dev
```

## Usuário Teste
```bash
Email: teste@ecolink.com
Senha: 12345678
```

# Documentação da API
A API segue uma estrutura REST organizada em Controllers, Services e Repositories.
Abaixo estão as rotas principais utilizadas para manipulação dos pontos de coleta.

## Rotas — Pontos de Coleta (CollectionPoint)

### **GET /collection-points**
Retorna todos os pontos de coleta cadastrados.
```bash
Exemplo de resposta:
[
  {
    "id": 1,
    "name": "Ponto Central",
    "address": "Rua A, 123",
    "latitude": -25.1234,
    "longitude": -51.1234
  }
]
```

### **GET /collection-points/{id}**
```bash
Retorna detalhes de um ponto de coleta específico.
```

### **POST /collection-points**
Cria um novo ponto.
Validações feitas via StoreCollectionPointRequest.
```bash
{
  "name": "Ponto Verde",
  "address": "Rua B",
  "latitude": -25.1122,
  "longitude": -51.7788
}
```

### **PUT /collection-points/{id}**
Atualiza um ponto de coleta.

### **DELETE /collection-points/{id}**]
Remove um ponto de coleta do sistema.

## Arquitetura Interna (Resumo)
- Controllers

Definem as rotas e retornam respostas formatadas.

- Services

Implementam regras de negócio e validações.

- Repositories

Responsáveis pela comunicação com o banco de dados.

## Consumo da API
### **Exemplo usando JavaScript (fetch)**
```bash
fetch("http://localhost:8000/collection-points")
  .then(res => res.json())
  .then(data => console.log(data));
```

### **Exemplo usando cURL**
```bash
curl -X GET http://localhost:8000/collection-points
```

### **Criar um ponto**
```bash
curl -X POST http://localhost:8000/collection-points \
  -H "Content-Type: application/json" \
  -d '{"name":"Ponto X","address":"Rua Y","latitude":-25.1,"longitude":-51.2}'
```

## Licença
Este projeto está sob a licença MIT.
