<?php


class CartController
{
    
    public function actionAdd($id)
    {
        // Добавляем товар в корзину
        Cart::addProduct($id);
        
        // Возвращаем пользователя на страницу
        $referrer = $_SERVER['HTTP_REFERER'];
        header("Location: $referrer");
    }
    
    public function actionDelete($id)
    {   
        // Удаляем товар с корзины
        Cart::deleteProduct($id);
        
        // Возвращаем пользователя на страницу
        header("Location: /cart");
    }


    public function actionaddAjax($id)
    {
        // Добавляем товар в корзину
        echo Cart::addProduct($id);
        return true;
    }
    
    public function actionIndex()
    {
        
        $categories = array();
        $categories = Category::getCategoriesList();
        
        $productsInCart = false;
        
        // Получим данные из корзины
        $productsInCart = Cart::getProducts();
        
        if ($productsInCart) {
            // Получаем полную информацию о товарах для списка
            $productsIds = array_keys($productsInCart);
            $products = Product::getProductsByIds($productsIds);
            
            // Получаем общую стоимость товаров
            $totalPrice = Cart::getTotalPrice($products);
        }
        
        require_once(ROOT . '/views/cart/index.php');
        
        return true;
    }
    
    
    public function actionCheckout()
    {
        
        // Список категорий для левого меню
        $categories = array();
        $categories = Category::getCategoriesList();
        
        // Статус успешного оформления заказа
        $result = false;
        
        // Форма отправлена?
        if (isset($_POST['submit'])) {
            // Форма отправлена? - Да
            
            // Считываем данные формы
            $userName = $_POST['userName'];
            $userPhone = $_POST['userPhone'];
            $userComment = $_POST['userComment'];
            
            // Валидация полей
            $errors = false;
            if (!User::checkName($userName))
                $errors[] = 'Неправильное имя';
            if (!User::checkPhone($userPhone))
                $errors[] = 'Неправильный телефон';
            
            // Форма заполнена корректно?
            if ($errors == false) {
                // Форма заполнена корректно? - Да
                // Сохраняем заказ в базе данных
                
                // Собираем информацию с заказа
                $productsInCart = Cart::getProducts();
                if (User::isGuest()) {
                    $userId = false;
                } else {
                    $userId = User::checkLogged();
                }
                
                // Сохраняем заказ в БД
                $result = Order::save($userName, $userPhone, $userComment, $userId, $productsInCart);
                
                if ($result) {
                    // Оповещаем администратора о новом заказе
                    $adminEmail = 'mukolakolia@gmail.com';
                    $message = 'http://realmag/admin/orders';
                    $subject = 'Новый заказ';
                    
                    // Очищаем корзину
                    Cart::clear();
                }
            } else {
                // Форма заполнена корректно? - Нет
                
                // Итоги: общая стоимость, количество товаров
                $productsInCart = Cart::getProducts();
                $productsIds = array_keys($productsInCart);
                $products = Product::getProductsByIds($productsIds);
                $totalPrice = Cart::getTotalPrice($products);
                $totalQuantity = Cart::countItems();
            }       
            
        } else {
            // Форма отпралена? - Не
            
            // Плучаем данные из корзины
            $productsInCart = Cart::getProducts();
            
            // В корзине есть товары?
            if ($productsInCart == false) {
                // В корзине есть товары? - Не
                // Отправляем пользователя на главную искать товары
                header("Location: /");
            } else {
                // В корзине есть товары? - Да
                
                // Итоги: общая стоимость, количество товаров
                $productsIds = array_keys($productsInCart);
                $products = Product::getProductsByIds($productsIds);
                $totalPrice = Cart::getTotalPrice($products);
                $totalQuantity = Cart::countItems();
                
                
                $userName = false;
                $userPhone = false;
                $userComment = false;
                
                // Пользователь авторизован?
                if (User::isGuest()) {
                    // Нет
                    // Значение для форм пустые
                } else {
                    // Да, авторизирован
                    // Получаем информацию о пользователе из БД по id
                    $userId = User::checkLogged();
                    $user = User::getUserById($userId);
                    // Подставляем в форму
                    $userName = $user['name'];
                }
            }
        }
        
        require_once(ROOT . '/views/cart/checkout.php');
        
        return true;
        
    }
}
