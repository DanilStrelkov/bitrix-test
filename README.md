# Тестовое задание — Bitrix Fullstack (PHP + Vue)

## Как запустить

### Требования
- PHP 8.1+
- Composer

### Установка и запуск

```bash
git clone <repo-url> bitrix-test
cd bitrix-test
composer install
```

### Запуск встроенного PHP-сервера

Встроенный сервер PHP позволяет проверить проект без Apache/Nginx.  
**Важно:** сервер нужно запускать из корня проекта, чтобы пути к `ajax/` и `frontend/` работали корректно.

```bash
# Запуск из корня проекта
cd bitrix-test
php -S localhost:8080
```

Затем открыть в браузере: http://localhost:8080/frontend/index.html

> **Vue.js:** в папке `frontend/` уже лежит `vue.global.js` — интернет для работы не нужен.

Остановить сервер: `Ctrl+C`

### Запуск тестов

```bash
vendor/bin/phpunit tests/
```

---

## Структура проекта

```
bitrix-test/
├── BitrixMock/
│   ├── Contracts/         # Интерфейсы (IBlockElementInterface, CatalogProductInterface, DBResultInterface)
│   ├── Mock/              # Мок-реализации: CIBlockElement, CCatalogProduct, CDBResult
│   ├── Repository/        # ProductRepository — получает список товаров через интерфейсы
│   ├── Service/           # OrderService — валидация и списание остатка
│   ├── bootstrap.php      # Подключает мок-файлы и autoload
│   └── data.php           # Начальные данные (6 товаров)
├── ajax/
│   ├── products.php       # GET — список товаров в JSON
│   └── order.php          # POST — оформление заказа
├── frontend/
│   └── index.html         # Vue 3 (CDN): таблица товаров + форма заказа
├── tests/                 # PHPUnit тесты
└── composer.json
```

---

## Перенос на реальный Битрикс

1. **Удалить `BitrixMock/`** полностью.
2. **Заменить подключение** в `ajax/products.php` и `ajax/order.php`:
   ```php
   // Было:
   require_once __DIR__ . '/../BitrixMock/bootstrap.php';
   // Стало:
   require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');
   ```
3. **Создать адаптеры** `BitrixBlockElementAdapter` и `BitrixCatalogProductAdapter`, реализующие контракты из `Contracts/` и делегирующие к реальным статическим методам:
   ```php
   class BitrixBlockElementAdapter implements IBlockElementInterface {
       public function GetList(...): DBResultInterface {
           return CIBlockElement::GetList(...);  // реальный вызов
       }
   }
   ```
4. **Передать адаптеры** в `ProductRepository` и `OrderService` вместо моков — всё остальное без изменений.
5. **Заменить сессионное хранилище** на реальные вызовы `CCatalogProduct::Update()` для списания остатка.
6. **Установить `IBLOCK_ID`** в параметре `$filter` вызова `GetList()`.
