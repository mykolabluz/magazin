<?php

/**
 * Контроллер AdminProductController
 * Управление товарами в админпанели
 */
class AdminProductController extends AdminBase
{
    
    /**
     * Action для страницы "Управление товарами"
     */
    public function actionIndex()
    {
        
        // Проверка в доступе
        self::checkAdmin();
        
        // Подключаем список товаров
        $productsList = Product::getProductsList();
        
        // Подключаем вид
        require_once(ROOT . '/views/admin_product/index.php');
        return true;
        
    }
    
    /**
     * Action для страницы "Добавить товар"
     */
    public function actionCreate()
    {
        // Проверка доступа
        self::checkAdmin();
        
        // Получение списка категорий для выпадающего списка
        $categoriesList = Category::getCategoriesListAdmin();
        
        // Обработка форм
        if (isset($_POST['submit'])) {
            // Если форма отправлена
            // Получаем данные из формы
            $options['name'] = $_POST['name'];
            $options['code'] = $_POST['code'];
            $options['price'] = $_POST['price'];
            $options['category_id'] = $_POST['category_id'];
            $options['brand'] = $_POST['brand'];
            $options['availability'] = $_POST['availability'];
            $options['description'] = $_POST['description'];
            $options['is_new'] = $_POST['is_new'];
            $options['is_recommended'] = $_POST['is_recommended'];
            $options['status'] = $_POST['status'];
            
            // Флаг оштбкт в форме
            $errors = false;
            
            // При необходимости можно валидировать значения нужным образом
            if (!isset($options['name']) || empty($options['name'])) {
                $errors[] = 'Заполните поля';
            }
            
            if ($errors == false) {
                // Если ошибок нет
                // Добавляем новый товар
                $id = Product::createProduct($options);
                
                // Если запись добавлена
                if ($id) {
                    // Проверим, загружалось ли через форму изоображение
                    if (is_uploaded_file($_FILES["image"]["tmp_name"])) {
                        // Если загружалось, переместим его в нужную папку, дадим новое название
                        move_uploaded_file($_FILES["image"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . "/upload/images/products/{$id}.jpg");
                    }
                };
                
                // Перенаправляем администратора на страницу управление товарами
                header("Location: /admin/product");
            }
        }
        
        // Подключаем вид
        require_once(ROOT . '/views/admin_product/create.php');
        return true;
    }
    
    /**
     * Action для страницы "Редактировать товар"
     */
    public function actionUpdate($id)
    {
        // Проверка доступа
        self::checkAdmin();
        
        // Поучаем список категорий для выпадающего списка
        $categoriesList = Category::getCategoriesListAdmin();
        
        // Получаем данные о конкретном товаре
        $product = Product::getProductById($id);
        
        // Обработка форм
        if (isset($_POST['submit'])) {
            // Если форма отправлена
            // Получаем данные из формы редактирования. При необходимости можно валидировать значенния
            $options['name'] = $_POST['name']; 
            $options['code'] = $_POST['code']; 
            $options['price'] = $_POST['price']; 
            $options['category_id'] = $_POST['category_id']; 
            $options['brand'] = $_POST['brand']; 
            $options['availability'] = $_POST['availability'];
            $options['description'] = $_POST['description'];
            $options['is_new'] = $_POST['is_new']; 
            $options['is_recommended'] = $_POST['is_recommended']; 
            $options['status'] = $_POST['status'];  
            
            // Сохраняем изменения
            if (Product::updateProductById($id, $options)) {
                
                // Если запись сохранена
                // Проверим, загружалось ли через форму изображение
                if (is_uploaded_file($_FILES["image"]["tmp_name"])) {
                    
                    // Если загружалось, переместим его в нужную папку, дадим новое имя
                    move_uploaded_file($_FILES["image"]["tmp_name"], $_SERVER['DOCUMENT_ROOT']. "/upload/images/products/{$id}.jpg");
                }
            };
            
            // Перенаправляем администратора на страницу управления товарами
            header("Location: /admin/product");

        }
            // Подключаем вид
            require_once(ROOT . '/views/admin_product/update.php');
            return true;
    }

    /**
     * Action для страницы "Удалить товар"
     */
    public function actionDelete($id)
    {
        // Проверка доступа
        self::checkAdmin();
        
        // Обработка форм
        if (isset($_POST['submit'])) {
            // Если форма отправлена
            // Удалить товар
            Product::deleteProductById($id);
            
            // Перенаправляем администратора а страницу управлениями товарами
            header("Location: /admin/product");
            
        }
        
        // Подключаем вид
        require_once(ROOT . '/views/admin_product/delete.php');
        return true;
    }
}