<?php

/**
 * Контроллер AdminOrderController
 * Управление заказами в админпанели
 */

class AdminOrderController extends AdminBase
{
    
    /**
     * Action для страницы "Управления заказами"
     */
    public function actionIndex()
    {
        // Проверка доступа
        self::checkAdmin();
        
        // Получаем список заказов
        $ordersList = Order::getOrderList();
        
        // Подключаем вид
        require_once(ROOT . '/views/admin_order/index.php');
        return true;
    }
    
    /**
     * Action для страницы "Редактировать заказ"
     */
    public function actionUpdate($id)
    {
        // Проверка доступа
        self::checkAdmin();
        
        // Получаем данные о конкретном заказе
        $order = Order::getOrderByid($id);
        
        // Обработка форм
        if (isset($_POST['submit'])) {
            // Если форма отправлена
            // Поучаем данные из формы
            $userName = $_POST['userName'];
            $userPhone = $_POST['userPhone'];
            $userComment = $_POST['userComment'];
            $date = $_POST['date'];
            $status = $_POST['status'];
            
            // Сохраняем изменения
            Order::updateOrderById($id, $userName, $userPhone, $userComment, $date, $status);
            
            // Перенаправляем пользователя на страницу управлениями заказами
            header("Location: /admin/order/view/$id");
        }
        
        // Подключаем вид
        require_once(ROOT . '/views/admin_order/update.php');
        return true;
    }
    
    /**
     * Action для страницы "Просмотр заказа"
     */
    public function actionView($id)
    {
        // Проверка доступа
        self::checkAdmin();
        
        // Получаем данные о конкретном заказе
        $order = Order::getOrderByid($id);
        
        // Получаем массив с идентификаторами и количеством товаров
        $productsQuantity = json_decode($order['products'], true);
        
        // Получаем массив с индефикаторами товаров
        $productsIds = array_keys($productsQuantity);
        
        // Получаем список товаров в заказе
        $products = Product::getProductsByIds($productsIds);
        
        // Подключаем вид
        require_once(ROOT . '/views/admin_order/view.php');
        return true;
        
    }


    /**
     * Action для страницы "Удалить заказ"
     */
    public function actionDelete($id)
    {
        // Проверка доступа
        self::checkAdmin();

        // Обработка формы
        if (isset($_POST['submit'])) {
            // Если форма отправлена
            // Удаляем заказ
            Order::deleteOrderById($id);

            // Перенаправляем пользователя на страницу управлениями товарами
            header("Location: /admin/order");
        }

        // Подключаем вид
        require_once(ROOT . '/views/admin_order/delete.php');
        return true;
    }
}