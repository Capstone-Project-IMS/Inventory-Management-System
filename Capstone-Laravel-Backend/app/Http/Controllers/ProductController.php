<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    private $product;

    public function __construct() { 
        $this->product = new Product();
    }

    /**
     * Get all products
     */
    public function getAllProducts() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $products = $this->product->getAllProducts();
            if ($products) {
                // Products found
                echo json_encode($products);
            } else {
                // No products found
                echo json_encode(array("message" => "No products found"));
            }
        }
    }

    /**
     * Create a new product
     */
    public function createProduct() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name']) && isset($_POST['description']) && isset($_POST['category']) && isset($_POST['display_image'])) {
            $vendor_id = $_POST['vendor_id']; // Assuming you handle vendor ID separately
            $name = $_POST['name'];
            $description = $_POST['description'];
            $category = $_POST['category'];
            $display_image = $_POST['display_image'];

            $result = $this->product->createProduct($vendor_id, $name, $description, $category, $display_image);

            if ($result) {
                // Product created successfully
                echo json_encode(array("message" => "Product created successfully"));
            } else {
                // Failed to create product
                echo json_encode(array("message" => "Failed to create product"));
            }
        }
    }

    /**
     * Get information about specific product
     */
    public function getProduct(string $id) {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $product = $this->product->getProduct($id);
            if ($product) {
                // Product found
                echo json_encode($product);
            } else {
                // Product not found
                echo json_encode(array("message" => "Product not found"));
            }
        }
    }

    /**
     * Update a product
     */
    public function updateProduct(string $id) {
        if ($_SERVER['REQUEST_METHOD'] === 'PUT' && isset($_GET['id'])) {
            parse_str(file_get_contents("php://input"), $_PUT);
            $name = isset($_PUT['name']) ? $_PUT['name'] : null;
            $description = isset($_PUT['description']) ? $_PUT['description'] : null;
            $category = isset($_PUT['category']) ? $_PUT['category'] : null;
            $display_image = isset($_PUT['display_image']) ? $_PUT['display_image'] : null;

            $result = $this->product->updateProduct($id, $name, $description, $category, $display_image);

            if ($result) {
                // Product updated successfully
                echo json_encode(array("message" => "Product updated successfully"));
            } else {
                // Failed to update product
                echo json_encode(array("message" => "Failed to update product"));
            }
        }
    }

    /** 
     */
    public function deleteProduct(string $id) {
        if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET['id'])) {
            $result = $this->product->deleteProduct($id);

            if ($result) {
                // Product deleted successfully
                echo json_encode(array("message" => "Product deleted successfully"));
            } else {
                // Failed to delete product
                echo json_encode(array("message" => "Failed to delete product"));
            }
        }
    }
}

// Instantiate ProductController
$productController = new ProductController();

// Call the appropriate method based on the HTTP request method
switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $productController->createProduct();
        break;
    case 'GET':
        if(isset($_GET['id'])){
            $productController->getProduct($_GET['id']);
        }else{
            $productController->getAllProducts();
        }
        break;
    case 'PUT':
        if(isset($_GET['id'])){
            $productController->updateProduct($_GET['id']);
        }else{
            // Invalid request
            echo json_encode(array("message" => "Invalid request"));
        }
        break;
    case 'DELETE':
        if(isset($_GET['id'])){
            $productController->deleteProduct($_GET['id']);
        }else{
            // Invalid request
            echo json_encode(array("message" => "Invalid request"));
        }
        break;
    default:
        // Invalid request method
        http_response_code(405); // Method Not Allowed
        echo json_encode(array("message" => "Invalid request method"));
        break;
}

?>