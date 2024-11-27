<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <!-- Main Section -->
                <div class="sb-sidenav-menu-heading">Main</div>
                <a class="nav-link" href="index.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>
                <a class="nav-link" href="order-create.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-bell"></i></div>
                    Create Order
                </a>
                <a class="nav-link" href="orders.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-list"></i></div>
                    Orders
                </a>

                <!-- Inventory -->
                <div class="sb-sidenav-menu-heading">Inventory</div>
                <?php if ($_SESSION['loggedInUser']['position'] == 1): ?>
                    <a class="nav-link" href="purchase-order-select-supplier.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-bell"></i></div>
                        Create Purchase Order
                    </a>
                    <a class="nav-link" href="purchase-orders.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-list"></i></div>
                        Purchase Management
                    </a>
                <?php endif; ?>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseInventory" aria-expanded="false" aria-controls="collapseInventory">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Inventory
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseInventory" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        
                        <!-- Ingredients Section -->
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseIngredients" aria-expanded="false" aria-controls="collapseIngredients">
                            <div class="sb-nav-link-icon"><i class="fas fa-utensils"></i></div>
                            Ingredients
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseIngredients" aria-labelledby="headingOne" data-bs-parent="#collapseInventory">
                            <nav class="sb-sidenav-menu-nested nav">
                                <?php if ($_SESSION['loggedInUser']['position'] == 1): ?>
                                <a class="nav-link" href="ingredients-add.php">Add Ingredients</a>
                                <?php endif; ?>
                                <a class="nav-link" href="ingredients-view.php">View Ingredients</a>
                                <?php if ($_SESSION['loggedInUser']['position'] == 1): ?>
                                    <a class="nav-link" href="stock-out-create.php">Stock Out</a>
                                <?php endif; ?>
                            </nav>
                        </div>

                        <!-- Products Section -->
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseProducts" aria-expanded="false" aria-controls="collapseProducts">
                            <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                            Menu Products
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseProducts" aria-labelledby="headingOne" data-bs-parent="#collapseInventory">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="products.php">View Menu Products</a>
                                <?php if ($_SESSION['loggedInUser']['position'] == 1): ?>
                                <a class="nav-link" href="products-create.php">Add Menu Product</a>
                                <?php endif; ?>
                            </nav>
                        </div>

                    </nav>
                </div>

                
                <!-- Interface Section -->
                <div class="sb-sidenav-menu-heading">Interface</div>
                <?php if ($_SESSION['loggedInUser']['position'] == 1): ?>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseCategory" aria-expanded="false" aria-controls="collapseCategory">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Categories
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseCategory" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">   
                        <a class="nav-link" href="categories-create.php">Add Category</a>
                        <a class="nav-link" href="categories.php">View Categories</a> 
                    </nav>
                </div>   
                <?php endif; ?>
                

                <a class="nav-link" href="sales.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-dollar-sign"></i></div>
                    Sales Management
                </a>

                <a class="nav-link" href="inventory-management-view.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-list"></i></div>
                    Inventory Management
                </a>

                <!-- Units of Measurement -->
                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseUnits" aria-expanded="false" aria-controls="collapseUnits">
                        <i class="fas fa-balance-scale"></i>
                        <span>Units of Measurement</span>
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseUnits" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                        <?php if ($_SESSION['loggedInUser']['position'] == 1): ?>
                            <a class="nav-link" href="units-category.php">Add Unit</a>
                            <?php endif; ?>
                            <a class="nav-link" href="units.php">View Units</a>
                        </nav>
                    </div>
                </li>

                <!-- Manage Staff Section (Only for Owners/Admins) -->
                <?php if ($_SESSION['loggedInUser']['position'] == 1): ?>
                    <div class="sb-sidenav-menu-heading">Manage Staff</div>
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseAdmins" aria-expanded="false" aria-controls="collapseAdmins">
                        <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                        Admins/Staff
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseAdmins" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="admins-create.php">Add Admin</a>
                            <a class="nav-link" href="admins.php">View Admins</a>
                        </nav>
                    </div>
                <?php endif; ?>

                <!-- Manage Customer Section -->
                <div class="sb-sidenav-menu-heading">Manage People</div>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseCustomer" aria-expanded="false" aria-controls="collapseCustomer">
                    <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                    Customers
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseCustomer" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <!-- <a class="nav-link" href="customer-create.php">Add Customer</a> -->
                        <a class="nav-link" href="customers.php">View Customer</a>
                    </nav>
                </div>

                <!-- Suppliers Section -->
                <?php if ($_SESSION['loggedInUser']['position'] == 1): ?>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseSuppliers" aria-expanded="false" aria-controls="collapseSuppliers">
                    <div class="sb-nav-link-icon"><i class="fas fa-truck"></i></div>
                    Suppliers
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                
                <div class="collapse" id="collapseSuppliers" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        
                        <a class="nav-link" href="suppliers-create.php">Add Suppliers</a>
                        
                        <a class="nav-link" href="suppliers.php">View Suppliers</a>
                    </nav>
                </div>
                <?php endif; ?>

                

            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            <?= $_SESSION['loggedInUser']['username']; ?>
        </div>
    </nav>
</div>