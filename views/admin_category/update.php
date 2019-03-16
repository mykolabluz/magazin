<?php include ROOT . '/views/layouts/header_admin.php'; ?>

<section>
    <div class="container">
        <div class="row">
            
            <br>
            
            <div class="breadcrumbs">
                <ol class="breadcrumb">
                    <li><a href="/admmin">Админпанель</a></li>
                    <li><a href="/admin/product">Управление категориями</a></li>
                    <li class="active">Редактировать категорию</li>
                </ol>
            </div>
            
            <h4>Редактировать категорию #<?php echo $id; ?></h4>
            
            <br>
            
            <div class="col-lg-4">
                <div class="login-form">
                    <form action="#" method="post" enctype="multipart/form-data">
                        
                        <p>Название категории</p>
                        <input type="text" name="name" placeholder="" value="<?php echo $category['name']; ?>">
                        
                        <p>Порядковый номер</p>
                        <input type="text" name="sort_order" placeholder="" value="<?php echo $category['sort_order']; ?>">
                                                
                        <br><br>
                        
                        <p>Статус</p>
                        <select name="status">
                            <option value="1" <?php if ($category['status'] == 1) echo ' selected="selected"'; ?>>Отображается</option>
                            <option value="1" <?php if ($category['status'] == 0) echo ' selected="selected"'; ?>>Скрыт</option>
                        </select>
                        
                        <br><br>
                        
                        <input type="submit" name="submit" class="btm btn-default" value="Сохранить" />
                        
                        <br><br>
                        
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</section>

<?php include ROOT . '/views/layouts/footer_admin.php'; ?>



