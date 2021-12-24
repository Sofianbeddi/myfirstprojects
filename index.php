<?php

$pdo = new PDO('mysql:host=localhost;port=3306;dbname=product_crud', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$search = $_GET['search'] ?? '';
if($search) {
  $statement = $pdo->prepare('SELECT * FROM product WHERE title LIKE :title ORDER BY create_date DESC');
  $statement->bindValue(':title',"%$search%");

}
else {
  $statement = $pdo->prepare('SELECT * FROM product ORDER BY create_date DESC');
}

$statement->execute();
$products = $statement->fetchAll(PDO::FETCH_ASSOC);



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


  <title>Products CRUD</title>
</head>

<body>
  <h1>Products CRUD</h1>

  
  <p>
    <a href="create.php" class="btn btn-success">Create Product</a>
  </p>
  <form>
    <div class="input-group mb-3">
      <input type="text" class="form-control" placeholder="Search for products" name="search" value="<?php echo $search ?>">
      <button class="btn btn-outline-secondary" type="submit">Search</button>
    </div>
  </form>
  

  <table class="table">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Image</th>
        <th scope="col">Title</th>
        <th scope="col">Price</th>
        <th scope="col">Create Date</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($products as $i => $product) : ?>
        <tr>
          <th scope="row"><?php echo $i + 1 ?></th>
          <td>
            <img src="<?php echo $product['Image'] ?>" class="image_size">
          </td>
          <td><?php echo $product['Title'] ?></td>
          <td><?php echo $product['Price'] ?></td>
          <td><?php echo $product['Create_date'] ?></td>
          <td>
            <a href="update.php?id=<?php echo $product['id']?>" class="btn btn-sm btn-outline-primary">Edit</a>
            <form style="display: inline-block" method="post" action="delete.php">
              <input type="hidden" name="id" value="<?php echo $product['id'] ?>">
              <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
            </form>

          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>

</html>