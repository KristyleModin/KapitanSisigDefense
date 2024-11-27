<?php include('includes/header.php'); ?>

<div class="container-fluid px-4 pb-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Menu Products
                <a href="recipes-add.php" class="btn btn-outline-success float-end me-2">Manage Recipe</a> 
                <a href="products-create.php" class="btn btn-outline-primary float-end me-2">Add Menu Product</a> 
            </h4>
        </div>
        <div class="card-body">
            <?php alertMessage(); ?>
            <?php 
                $products = getAll('products'); 
                if (!$products) {
                    echo '<h4>Something went wrong.</h4>';
                } elseif (mysqli_num_rows($products) > 0) { 
            ?>
            <div class="table-responsive">
                <table class="table" style="width: 100%; margin-bottom: 1rem; color: #000; border: 1px solid #dee2e6;">
                    <thead>
                        <tr style="background-color: #f8f9fa; color: #000;">
                            <th>Image</th>
                            <th>Menu Product Name</th>
                            <th>Category</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i = 0;
                        while ($productItem = mysqli_fetch_assoc($products)) : 
                            $i++;
                            $productId = $productItem['id'];
                            
                            // Fetch category name
                            $categoryId = $productItem['category_id'];
                            $categoryQuery = "SELECT name FROM categories WHERE id = '$categoryId'";
                            $categoryResult = mysqli_query($conn, $categoryQuery);
                            $categoryName = $categoryResult && mysqli_num_rows($categoryResult) > 0 
                                            ? htmlspecialchars(mysqli_fetch_assoc($categoryResult)['name']) 
                                            : "Unknown Category";
                            
                            // Fetch recipe ingredients for this product
                            $recipeIngredientsQuery = "
                                SELECT ri.ingredient_id, ri.quantity AS recipe_quantity, i.quantity AS available_quantity, i.name AS ingredient_name
                                FROM recipe_ingredients ri
                                JOIN ingredients i ON ri.ingredient_id = i.id
                                WHERE ri.recipe_id = (SELECT id FROM recipes WHERE product_id = '$productId')
                            ";
                            $recipeIngredientsResult = mysqli_query($conn, $recipeIngredientsQuery);
                            
                            $minServings = PHP_INT_MAX; // Start with the highest possible value

                            // Loop through the ingredients and calculate the maximum number of servings
                            while ($ingredient = mysqli_fetch_assoc($recipeIngredientsResult)) {
                                $requiredQuantity = $ingredient['recipe_quantity'];
                                $availableQuantity = $ingredient['available_quantity'];
                                
                                // Calculate how many servings can be made with the available quantity of this ingredient
                                $possibleServings = floor($availableQuantity / $requiredQuantity);

                                // The limiting factor is the smallest number of servings for any ingredient
                                if ($possibleServings < $minServings) {
                                    $minServings = $possibleServings;
                                }
                            }

                            // If there are no ingredients or not enough stock, it should show 0 servings
                            if ($minServings == PHP_INT_MAX) {
                                $minServings = 0;
                            }

                            // Update the products table with the calculated quantity (number of servings)
                            $updateProductQuery = "
                                UPDATE products 
                                SET quantity = '$minServings' 
                                WHERE id = '$productId'
                            ";
                            mysqli_query($conn, $updateProductQuery);

                        ?>
                        <tr style="background-color: <?= $i % 2 == 0 ? '#fff' : '#f9f9f9'; ?>; border: 1px solid #dee2e6;">
                            <td><img src="../<?= htmlspecialchars($productItem['image']); ?>" style="width:70px;height:70px;" alt="product image" /></td>
                            <td><?= htmlspecialchars($productItem['productname']) ?></td>
                            <td><?= $categoryName ?></td>
                            <td><?= $minServings ?></td> <!-- Display the computed servings -->
                            <td><?= htmlspecialchars($productItem['price']) ?></td>
                            <td>
                                <a href="products-edit.php?id=<?= $productItem['id'];?>" class="btn btn-outline-success btn-sm">Edit</a>
                                <a 
                                    href="products-delete.php?id=<?= $productItem['id'];?>" 
                                    class="btn btn-outline-danger btn-sm" 
                                    onclick="return confirm('Are you sure you want to delete this product?')">
                                    Delete
                                </a>
                                <a 
                                    href="recipe-view.php?id=<?= $productItem['id'];?>" 
                                    class="btn btn-outline-info btn-sm">
                                    View Recipe
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <?php 
                } else {
                    echo '<h4 class="mb-0">No record found.</h4>';
                } 
            ?>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>