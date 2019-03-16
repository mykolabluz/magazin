<?php

/**
 * Контроллер AdminProductController
 * Управление категориями в админпанели
 */
class AdminCategoryController extends AdminBase
{
    
    /**
     * Action для страницы "Управления категориями"
     */
    public function actionIndex()
    {
        // Проверка доступа
        self::checkAdmin();
        
        // Подключаем список категорий
        $categoriesList = Category::getCategoriesListAdmin();
        
        // Подключаем вид
        require_once(ROOT . '/views/admin_category/index.php');
        return true;
    }
    
    /**
     * Action для страницы "Добавть категорию"
     */
    public function actionCreate()
    {
        // Проверка доступа
        self::checkAdmin();
        
        // Обработка форм
        if (isset($_POST['submit'])) {
            // Если форма отправлена
            // Получаем данные из формы
            $options['name'] = $_POST['name'];
            $options['sort_order'] = $_POST['sort_order'];
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
                $id = Category::createCategory($options);
                
                // Перенаправляем администратора на страницу управления категориями
                header("Location: /admin/category");
            }
        }
        // Подключаем вид
        require_once(ROOT . '/views/admin_category/create.php');
        return true;
    }
    
    /**
     * Action для страницы "Редактировать категорию"
     */
    public function actionUpdate($id)
    {
        // Проверка доступа
        self::checkAdmin();
        
        // Получаем данные о конкретной категории
        $category = Category::getCategoryById($id);
        
        // Обработка форм
        if (isset($_POST['submit'])) {
            $options['name'] = $_POST['name']; 
            $options['sort_order'] = $_POST['sort_order']; 
            $options['status'] = $_POST['status'];
            
            // Сохраняем изменения
            if (Category::updateCategoryById($id, $options)) {
                
                // Если запись сохранена
                // Проверим, загружалось ли через форму изображение
            }
            
            // Перенаправляем администратора на страницу управления товарами
            header("Location: /admin/category");
        }
            // Подключаем вид
            require_once(ROOT . '/views/admin_category/update.php');
            return true;
    }
    
    /**
     * Action для страницы "Удалить категорию"
     */
    public function actionDelete($id)
    {
        // Проверка доступа
        self::checkAdmin();
        
        // Обработка форм
        if (isset($_POST['submit'])) {
            // Если форма отправлена
            // Удалить товар
            Category::deleteCategoryById($id);
            
            // Перенаправляем администратора а страницу управлениями товарами
            header("Location: /admin/category");
            
        }
        
        // Подключаем вид
        require_once(ROOT . '/views/admin_category/delete.php');
        return true;
    }
}
