# Code review assistant
<img src ="https://img.shields.io/badge/Symfony-black?logo=symfony"> <img src="https://img.shields.io/badge/php-%23777BB4.svg?&logo=php&logoColor=white"> <img src="https://img.shields.io/badge/MySQL-4479A1?logo=mysql&logoColor=fff"> <img src="https://img.shields.io/badge/Mistral%20AI-FA520F?logo=mistral-ai&logoColor=fff">

Automate code reviews and generate fixed PHP files using Mistral AI and Symfony Console.

## 🎯 Overview

This tool analyzes your PHP/Symfony code for performance, security, readability, and best practices using Mistral AI. It provides:

- Detailed code analysis with actionable improvements.
- Auto-generated fixed code in a new PHP file.
- Interactive CLI to choose whether to include fixed code.
- Structured output with recommendations and generated file paths.

## 🛠️ Requirements
* Php 8.5
* Composer 2 ([download](https://getcomposer.org/download/))
* Mysql 8.0

## 🚀 Installation
1. Clone the Repository
2. Install Dependencies
```bash
composer install
```

3. Configure Environment
- Copy .env and add your Mistral API variables:
```bash
cp .env.dist .env.local
```

- Edit .env.local:
```dotenv
MISTRAL_API_KEY=your_mistral_api_key_here
MISTRAL_API_ENDPOINT=your_mistral_api_endpoint_here
```

## 🤖 Usage
### Run the command
Analyze a PHP file and optionally generate a fixed version:
```bash
php bin/console app:review-code src/Controller/YourController.php
```

### Answer the prompt
(Type y to generate a fixed file or n to skip.)
```text
Would you like to include the corrected code in the analysis ?
```

### Generated file
The generated file is located into `/var/generated` and named as a timestamp.

# Screenshot

![screenshot](/assets/images/screenshot.png)
![screenshot1](/assets/images/screenshot1.png)
