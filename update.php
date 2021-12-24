<?php

$pdo = new PDO('mysql:host=localhost;port=3306;dbname=product_crud', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$id = $_GET['id'] ?? null; 

if(!$id) {
    header('Location: index.php');
    exit;
}

$statement = $pdo->prepare('SELECT * FROM product WHERE id = :id');
$statement->bindValue(':id', $id); 
$statement->execute();
$product = $statement->fetch(PDO::FETCH_ASSOC);


$errors = [];

$title = $product['Title'];
$price = $product['Price'];
$description = $product['Description'];

if ($_SERVER['REQUEST_METHOD']==='POST') {

    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];


    if(!$title) {
    $errors[]="Product title is required";
    }

    if(!$price) {
    $errors[]="Product price is required";
    }

    if (!is_dir('image')){
    mkdir('image');
    }

    if(empty($errors)) {
    $image = $_FILES['image'] ?? null;
    $imagePath = $product['Image'];

        if ($image && $image['tmp_name']) {

            if ($product['Image']) {
                unlink($product['Image']);
            }

            $imagePath = 'images/'.randomString(8).'/'.$image['name'];
            mkdir(dirname($imagePath));

            move_uploaded_file($image['tmp_name'], $imagePath);
        }


        $statement = $pdo-> prepare("UPDATE product SET Title = :title, Image = :image, Description = :description, Price = :price WHERE id = :id");

        $statement->bindValue(':title', $title);
        $statement->bindValue(':image', $imagePath);
        $statement->bindValue(':description', $description);
        $statement->bindValue(':price', $price);
        $statement->bindValue(':id', $id);
        $statement->execute();
        header('Location: index.php');
    }
}


function randomString($n) {
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $str = '';
  for ($i = 0; $i < $n; $i++) {
      $index = rand(0, strlen($characters)-1);
      $str .= $characters[$index];
  }
  return $str;
}

?>


<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">


    <title>Update Product</title>
  </head>
  <body>

    <p>
        <a href="index.php" class="btn btn-secondary">Go Back To Porducts</a>
    </p>
    <h1>Update Product : <b><?php echo $product['Title']?></b></h1>
  
    <?php if (!empty($errors)):?>
      <div class="alert alert-danger">
          <?php foreach ($errors as $error): ?>
              <div><?php echo $error?></div>
          <?php endforeach; ?>
      </div>
    <?php endif; ?>
    <form action="" method="post" enctype="multipart/form-data">
        
        <?php if($product['Image']) :?>
            <img src="<?php echo $product['Image']?>" class="update-image">
        <?php endif; ?>
        
        <div class="mb-3">
            <label >Product Image</label>
            <input type="file" class="form-control" name="image" >
        </div>
        <div class="mb-3">
            <label >Product Title</label>
            <input type="text" class="form-control" name="title" value="<?php echo $title?>">
        </div>
        <div class="mb-3">
            <label >Product Description</label>
            <textarea class="form-control" name="description" value="<?php echo $description?>"></textarea>
        </div>
        <div class="mb-3">
            <label >Product Price</label>
            <input type="number" step=".01" class="form-control" name="price" value="<?php echo $price?>">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    

  </body>
</html>
