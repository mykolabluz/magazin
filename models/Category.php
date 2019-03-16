<?php


class Category
{
        /*
     * Returns category item by id
     * @param integer $id
     */
    public static function getCategoryById($id)
    {
        $id = intval($id);
        
        if ($id) {
            $db = DB::getConnection();
            
            $result = $db->query('SELECT * FROM category WHERE id ='. $id);
            $result->setFetchMode(PDO::FETCH_ASSOC);
            
            return $result->fetch();
        }
    }
    
    /*Returns an array of categories*/
    
    public static function getCategoriesList()
    {
        
        $db = Db::getConnection();
        
        $categoryList = array();
        
        $result = $db->query('SELECT id, name FROM category ORDER BY sort_order ASC');
        
        $i = 0;
        while ($row = $result->fetch()) {
            $categoryList[$i]['id'] = $row['id'];
            $categoryList[$i]['name'] = $row['name'];
            $i++;
        }
        
        return $categoryList;
           
    }
    
    /**
     * Возвращает массив категорий для списка в админпанели <br>
     * (при этом в результ попадают и включенные и выключенные категории)
     * @return array <p>Массив категорий</p>
     */
    public static function getCategoriesListAdmin()
    {
        // Соединение с БД
        $db = Db::getConnection();
        
        // Запрос к БД
        $result = $db->query('SELECT id, name, sort_order, status FROM category ORDER BY sort_order ASC');
        
        // Получение и возврат результатов
        $categoryList = array();
        $i = 0;
        while ($row = $result->fetch()) {
            $categoryList[$i]['id'] = $row['id'];
            $categoryList[$i]['name'] = $row['name'];
            $categoryList[$i]['sort_order'] = $row['sort_order'];
            $categoryList[$i]['status'] = $row['status'];
            $i++;
        }
        return $categoryList;
    }
    
    /**
     * Добавляем новую категорю
     * @param array $options <p>Массив с информацией о категории</p>
     * @return integer <p>id добавление в таблицу записи</p>
     */
    public static function createCategory($options)
    {
        // Соединение с БД
       $db = Db::getConnection();
       
       // Текст запроса к БД
       $sql = 'INSERT INTO category '
               . '(name, sort_order, status)'
               . 'VALUES '
               . '(:name, :sort_order, :status)';
       
       // Получение и возврат результатов. Используются подготовленные запросы
       $result = $db->prepare($sql);
       $result->bindParam(':name', $options['name'], PDO::PARAM_STR);
       $result->bindParam(':sort_order', $options['sort_order'], PDO::PARAM_INT);
       $result->bindParam(':status', $options['status'], PDO::PARAM_INT);
       if ($result->execute()) {
           return $db->lastInsertId();
       }
       // Иначе возвращаем 0
       return 0;
    }
    
    /**
     * Редактировать категорию с заданным id
     * @param integer $id <p>id категории</p>
     * @param array $options <p>Массив с информацией о категории</p>
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function updateCategoryById($id, $options)
    {
        // Соединение с БД
        $db = Db::getConnection();
        
        // Текст запроса
        $sql = "UPDATE category "
                . "SET "
                . "name = :name, "
                . "sort_order = :sort_order, "
                . "status = :status "
                . "WHERE id = :id";
        
        // Поучение и возврат результатов. Используя подготовленный запрос
       $result = $db->prepare($sql);
       $result->bindParam(':id', $id, PDO::PARAM_INT);
       $result->bindparam(':name', $options['name'], PDO::PARAM_STR);
       $result->bindparam(':sort_order', $options['sort_order'], PDO::PARAM_INT);
       $result->bindparam(':status', $options['status'], PDO::PARAM_INT);
       return $result->execute();
    }
    
    /**
    * Удаляет товар с указанным id
    * @param integer $id <p>id товара</p>
    * @return boolean <p>Результат выполнения метода</p>
    */
   public static function deleteCategoryById($id)
   {
       // Соединение с БД
       $db = Db::getConnection();
       
       // Текст запроса к БД
       $sql = 'DELETE FROM category WHERE id = :id';
       
       // Получение и возврат результатов. Используется подготовленный запрос
       $result = $db->prepare($sql);
       $result->bindParam(':id', $id, PDO::PARAM_INT);
       return $result->execute();
   }
    
}

 
