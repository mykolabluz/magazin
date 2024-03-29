<?php


class Product 
{
    
    const SHOW_BY_DEFAULT = 6;


    //Returns an array of products
    
    public static function getLatestProducts($count = self::SHOW_BY_DEFAULT)
    {
        $count = intval($count);
        
        $db = Db::getConnection();
        
        $productsList = array();
        
        $result = $db->query('SELECT id, name, price, is_new FROM product WHERE status = "1" ORDER BY id DESC LIMIT ' . $count);
        
        $i = 0;
        while ($row = $result->fetch()) {
            $productsList[$i]['id'] = $row['id'];
            $productsList[$i]['name'] = $row['name'];
            $productsList[$i]['price'] = $row['price'];
            $productsList[$i]['is_new'] = $row['is_new'];
            $i++;
            
        }
        
        return $productsList;
    }
    
    /**
     * Возвращает список рекомендуемых товаров
     * @return array <p>Массив с товарами</p>
     */
    public static function getRecommendProducts()
    {
        
        $db = Db::getConnection();
        
        $productsRecommend = array();
        
        $result = $db->query('SELECT id, name, price, is_new FROM product WHERE status = "1" AND is_recommended = "1" ORDER BY id DESC');

        
        $a = 0;
        while ($row = $result->fetch()) {
            $productsRecommend[$a]['id'] = $row['id'];
            $productsRecommend[$a]['name'] = $row['name'];
            $productsRecommend[$a]['price'] = $row['price'];
            $productsRecommend[$a]['is_new'] = $row['is_new'];
            $a++;
        }
        
        return $productsRecommend;
        
    }


    // Returns an array of products

    public static function getProductsListByCategory($categoryId = false, $page = 1)
    {
        if ($categoryId) {
            
            $page = intval($page);
            $offset = ($page - 1) * self::SHOW_BY_DEFAULT;
            
            $db = Db::getConnection();
            $products = array();
            $result = $db->query("SELECT id, name, price, is_new FROM product "
                    . "WHERE status = '1' AND category_id =  '$categoryId' "
                        . "ORDER BY id ASC "
                    . "LIMIT ".self::SHOW_BY_DEFAULT
                    . ' OFFSET '. $offset);
            
            $i = 0;
        while ($row = $result->fetch()) {
            $products[$i]['id'] = $row['id'];
            $products[$i]['name'] = $row['name'];
            $products[$i]['price'] = $row['price'];
            $products[$i]['is_new'] = $row['is_new'];
            $i++;
            
        }
        
        return $products;
        }
    }
    
    
    /*
     * Returns product item by id
     * @param integer $id
     */
    public static function getProductById($id)
    {
        $id = intval($id);
        
        if ($id) {
            $db = DB::getConnection();
            
            $result = $db->query('SELECT * FROM product WHERE id ='. $id);
            $result->setFetchMode(PDO::FETCH_ASSOC);
            
            return $result->fetch();
        }
    }
    
   // Returns total products

   public static function getTotalProductsInCategory($categoryId)
   {
       $db = Db::getConnection();
       
       $result = $db->query('SELECT count(id) AS count FROM product '
               . 'WHERE status="1" AND category_id ="'.$categoryId.'"');
       $result->setFetchMode(PDO::FETCH_ASSOC);
       $row = $result->fetch();
       
       return $row['count'];
   }
   
   // Return products
   
   public static function getProductsByIds($idsArray)
   {
       $products = array();
       
       // Соединение с БД
       $db = Db::getConnection();
       
       // Превращаем массив в строку для формирования условия в запросе
       $idsString = implode(',', $idsArray);
       
       $sql = "SELECT * FROM product WHERE status='1' AND id IN ($idsString)";
       
       $result = $db->query($sql);
       $result->setFetchMode(PDO::FETCH_ASSOC);
       
       $i = 0;
       while ($row = $result->fetch()) {
           $products[$i]['id'] = $row['id'];
           $products[$i]['code'] = $row['code'];
           $products[$i]['name'] = $row['name'];
           $products[$i]['price'] = $row['price'];
           $i++;
       }
       
       return $products;
   }
   
   /*
    * Возвращаем список товаров
    * @return array <p>Массив с товарами</p>
    */
   public static function getProductsList()
   {
       // Соединение с БД
       $db = Db::getConnection();
       
       // Получение и возврат результатов
       $result = $db->query('SELECT id, name, price, code FROM product ORDER BY id ASC');
       $productsList = array();
       $i = 0;
       while ($row = $result->fetch()) {
           $productsList[$i]['id'] = $row['id'];
           $productsList[$i]['name'] = $row['name'];
           $productsList[$i]['code'] = $row['code'];
           $productsList[$i]['price'] = $row['price'];
           $i++;
       }
       return $productsList;
   }
   
   /**
    * Добавляем новый товар
    * @param array $options <p>Массив с информацией о товаре</p>
    * @return integer <p>id добавление в таблицу записи</p>
    */
   public static function createProduct($options)
   {
       // Соединение с БД
       $db = Db::getConnection();
       
       // Текст запроса к БД
       $sql = 'INSERT INTO product '
               . '(name, code, price, category_id, brand, availability, '
               . 'description, is_new, is_recommended, status)'
               . 'VALUES '
               . '(:name, :code, :price, :category_id, :brand, :availability, '
               . ':description, :is_new, :is_recommended, :status)';
       
       // Получение и возврат результатов. Используется подготовленный запрос
       $result = $db->prepare($sql);
       $result->bindParam(':name', $options['name'], PDO::PARAM_STR);
       $result->bindParam(':code', $options['code'], PDO::PARAM_STR);
       $result->bindParam(':price', $options['price'], PDO::PARAM_STR);
       $result->bindParam(':category_id', $options['category_id'], PDO::PARAM_INT);
       $result->bindParam(':brand', $options['brand'], PDO::PARAM_STR);
       $result->bindParam(':availability', $options['availability'], PDO::PARAM_INT);
       $result->bindParam(':description', $options['description'], PDO::PARAM_STR);
       $result->bindParam(':is_new', $options['is_new'], PDO::PARAM_INT);
       $result->bindParam(':is_recommended', $options['is_recommended'], PDO::PARAM_INT);
       $result->bindParam(':status', $options['status'], PDO::PARAM_INT);
       if ($result->execute()) {
           // Если запрос выполнен успешно, возвращаем id добавленой записи
           return $db->lastInsertId();
       }
       // Иначе возвращаем 0
       return 0;
   }
   
   /**
    * Редактировать товар с заданным id
    * @param integer $id <p>id товара</p>
    * @param array $options <p>Массив с информацией о товаре</p>
    * @return boolean <p>результат выполнения метода </p>
    */
   public static function updateProductById($id, $options)
   {
       // Соединение с БД
       $db = Db::getConnection();
       
       // Текст запроса к БД
       $sql = "UPDATE product "
               . "SET "
               . "name = :name, "
               . "code = :code, "
               . "price = :price, "
               . "category_id = :category_id, "
               . "brand = :brand, "
               . "availability = :availability, "
               . "description = :description, "
               . "is_new = :is_new, "
               . "is_recommended = :is_recommended, "
               . "status = :status "
               . "WHERE id = :id";
       
       // Поучение и возврат результатов. Используя подготовленный запрос
       $result = $db->prepare($sql);
       $result->bindParam(':id', $id, PDO::PARAM_INT);
       $result->bindparam(':name', $options['name'], PDO::PARAM_STR);
       $result->bindparam(':code', $options['code'], PDO::PARAM_STR);
       $result->bindparam(':price', $options['price'], PDO::PARAM_STR);
       $result->bindparam(':category_id', $options['category_id'], PDO::PARAM_INT);
       $result->bindparam(':brand', $options['brand'], PDO::PARAM_STR);
       $result->bindparam(':availability', $options['availability'], PDO::PARAM_INT);
       $result->bindparam(':description', $options['description'], PDO::PARAM_STR);
       $result->bindparam(':is_new', $options['is_new'], PDO::PARAM_INT);
       $result->bindparam(':is_recommended', $options['is_recommended'], PDO::PARAM_INT);
       $result->bindparam(':status', $options['status'], PDO::PARAM_INT);
       return $result->execute();
   }

   /**
    * Удаляет товар с указанным id
    * @param integer $id <p>id товара</p>
    * @return boolean <p>Результат выполнения метода</p>
    */
   public static function deleteProductById($id)
   {
       // Соединение с БД
       $db = Db::getConnection();
       
       // Текст запроса к БД
       $sql = 'DELETE FROM product WHERE id = :id';
       
       // Получение и возврат результатов. Используется подготовленный запрос
       $result = $db->prepare($sql);
       $result->bindParam(':id', $id, PDO::PARAM_INT);
       return $result->execute();
   }
   
   /**
    * Возвращаем путь к изображению
    * @param integer $id
    * @return string <p>Путь к изображению</p>
    */
   public static function getImage($id)
   {
       // Название изображения-пустышки
       $noImage = 'no-image.jpg';
       
       // Путь к папке с товарами
       $path = '/upload/images/products/';
       
       // Путь к изображению товара
       $pathToProductImage = $path . $id . '.jpg';
       
       if (file_exists($_SERVER['DOCUMENT_ROOT'].$pathToProductImage)) {
           // Если изображение для товара существует
           // Возвращаем путь изображения товара
           return $pathToProductImage;
       }
       
       // Возвращаем путь к изображения-пустышка
       return $path . $noImage;
   }
    
    
}





