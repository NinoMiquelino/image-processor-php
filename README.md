## 👨‍💻 Autor

<div align="center">
  <img src="https://avatars.githubusercontent.com/ninomiquelino" width="100" height="100" style="border-radius: 50%">
  <br>
  <strong>Onivaldo Miquelino</strong>
  <br>
  <a href="https://github.com/ninomiquelino">@ninomiquelino</a>
</div>

---

# 🎨 PHP Image Processor (GD Library & AJAX Upload)

![Made with PHP](https://img.shields.io/badge/PHP-777BB4?logo=php&logoColor=white)
![Frontend JavaScript](https://img.shields.io/badge/Frontend-JavaScript-F7DF1E?logo=javascript&logoColor=black)
![TailwindCSS](https://img.shields.io/badge/TailwindCSS-38B2AC?logo=tailwindcss&logoColor=white)
![License MIT](https://img.shields.io/badge/License-MIT-green)
![Status Stable](https://img.shields.io/badge/Status-Stable-success)
![Version 1.0.0](https://img.shields.io/badge/Version-1.0.0-blue)
![GitHub stars](https://img.shields.io/github/stars/NinoMiquelino/image-processor-php?style=social)
![GitHub forks](https://img.shields.io/github/forks/NinoMiquelino/image-processor-php?style=social)
![GitHub issues](https://img.shields.io/github/issues/NinoMiquelino/image-processor-php)

Este projeto demonstra como o PHP pode ser usado para processar arquivos binários (imagens) após o upload. É um exemplo fundamental para qualquer aplicação que lide com upload de mídia, como CMSs ou e-commerce.

---

## 🖼️ Recursos Principais

* **Processamento de Imagens com GD:** O PHP utiliza a biblioteca GD (Graphics Drawing) para criar novas imagens a partir da original.
* **Múltiplas Saídas:** Uma única imagem de upload resulta em três arquivos no servidor:
    1.  Imagem Original
    2.  Miniatura Redimensionada (200px de largura)
    3.  Imagem com Marca d'Água (texto simples)
* **Pré-visualização Local:** O JavaScript usa a API `FileReader` para exibir a imagem selecionada ao usuário antes que o upload seja sequer iniciado, otimizando a experiência do usuário.
* **Upload Assíncrono:** Utiliza a API `fetch` e `FormData` para enviar a imagem ao PHP sem recarregar a página.

---

## ⚠️ Pré-requisito Crítico: Biblioteca GD

Para que este projeto funcione, a **extensão GD do PHP deve estar instalada e habilitada** no seu `php.ini`.

* **Para Linux (Debian/Ubuntu):** `sudo apt install php-gd`
* **Para XAMPP/WAMP:** Verifique se a linha `extension=gd` (ou `extension=php_gd.dll`) não está comentada no seu arquivo `php.ini`.

---

## 🧠 Tecnologias utilizadas

* **Backend:** PHP 7.4+ (GD Library, I/O de Arquivos).
* **Frontend:** HTML5, JavaScript Vanilla (`FileReader`, `fetch`, `FormData`).
* **Estilização:** Tailwind CSS (via CDN).

---

## 🧩 Estrutura do Projeto

```
image-processor-php/
├── index.html
├── api.php
├── README.md
├── .gitignore
├── LICENSE
└──  📁 uploads/
```

---

## ⚙️ Configuração e Instalação

### 1. Estrutura e Pasta de Uploads

1.  Crie a estrutura de pastas conforme o diagrama.
2.  **Crie a pasta de uploads:** `mkdir src/uploads`
3.  **Defina as permissões:** É crucial que a pasta `src/uploads` tenha permissão de escrita para o usuário do servidor web (ex: `chmod 777 src/uploads/` para testes locais).

### 2. Executar o Servidor

Utilize o servidor embutido do PHP para testes (a partir da raiz do projeto):

```bash
php -S localhost:8001
```

​- Acesse: O frontend estará disponível em http://localhost:8001/public/index.html.

3. Configurar o Endpoint da API
​Confirme que a constante API_URL no arquivo public/index.html aponte corretamente:

```javascript
// public/index.html
const API_URL = 'http://localhost:8001/src/api.php'; 
```

---

## 📝 Instruções de Uso

​Acesse a aplicação no navegador.
​Clique em "Selecionar Imagem" e escolha um arquivo JPG ou PNG.
​Observe que a "Pré-visualização Local" aparece imediatamente (graças ao FileReader do JavaScript).
​Clique em "Processar e Fazer Upload".
​O PHP fará o upload e criará os três arquivos na pasta src/uploads/.
​A seção "Imagens Processadas" será atualizada, mostrando os links para as três versões criadas.

---

## 🤝 Contribuições
Contribuições são sempre bem-vindas!  
Sinta-se à vontade para abrir uma [*issue*](https://github.com/NinoMiquelino/image-processor-php/issues) com sugestões ou enviar um [*pull request*](https://github.com/NinoMiquelino/image-processor-php/pulls) com melhorias.

---

## 💬 Contato
📧 [Entre em contato pelo LinkedIn](https://www.linkedin.com/in/onivaldomiquelino/)  
💻 Desenvolvido por **Onivaldo Miquelino**

---
